<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MobileUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\SubareaController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\DenominationController;
use App\Http\Controllers\FinalProductController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PurposeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ExcelExportController;
use App\Http\Controllers\ExcelInventoryController;
use App\Http\Controllers\ExcelProductManufactureController;
use App\Http\Controllers\ExcelRawProductController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ProductDealerController;
use App\Http\Controllers\ProductInventoryController;
use App\Http\Controllers\ProductInventorymanufController;
use App\Http\Controllers\SubdealerInventoryController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Working for cache.
Route::get('optimize', function () {
    Artisan::call('migrate --seed');
    Artisan::call('storage:link');
    Artisan::call('optimize:clear');
    Artisan::call('config:cache');
    return response()->json('Migrations run, Storage linked, Cache cleared and Config cached successfully.');
});

Route::get('/', [DashboardController::class, 'index']);

Auth::routes();

//Forgetting Password Or Resetting Password
Route::get('aforget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('aforget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('areset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('areset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::group(['middleware' => ['auth', 'user.status']], function () {
    Route::get('dashboard', [LocationController::class, 'index'])->middleware('permission:home');
    Route::get('activeuser', [LocationController::class, 'indexReplace']);
    Route::get('userposition/{id}/{date}', [LocationController::class, 'individual'])->name('userposition');
    Route::get('userposition/{id}', [LocationController::class, 'individual'])->name('testm');

    Route::get('artisan', [AccountController::class, 'artisan']);

    Route::get('/admin/{id}/pass', [UserController::class, 'change']);
    Route::PATCH('/adminpass/{id}', [UserController::class, 'changepass']);
    Route::resource('admin', UserController::class);

    Route::get('/user/reset/{id}', [MobileUserController::class, 'reset']);
    Route::get('/userPassword/{id}/edit', [MobileUserController::class, 'passwordView']);
    Route::patch('/user/changePassword/{id}', [MobileUserController::class, 'changePassword']);

    //Notice Routes
    require __DIR__ . '/tenants/notices.php';

    //Order Routes
    require __DIR__ . '/tenants/orders.php';

    //Collection Routes
    require __DIR__ . '/tenants/collections.php';

    // Schedule Visit Routes
    require __DIR__ . '/tenants/schedule_visits.php';

    Route::group(['middleware' => ['permission:chat', 'feature:chats']], function () {
        // For Chat
        Route::resource('chat', MessageController::class);
        Route::get('/chatuser/search', [MessageController::class, 'search']);
    });

    // Complaint Routes
    Route::resource('complaint', ComplaintController::class)
        ->middleware(['permission:complaint', 'feature:complaints']);

    //Reports Routes
    require __DIR__ . '/tenants/reports/core.php';
    require __DIR__ . '/tenants/reports/daily_reports.php';

    Route::group(['middleware' => ['permission:user']], function () {
        Route::resource('role-permission', PermissionController::class);
        Route::resource('user', MobileUserController::class);
    });

    Route::group(['middleware' => ['permission:dealer']], function () {
        // For Dealer
        Route::POST('/dealers/excel', [DealerController::class, 'excelUpload']);
        Route::get('/dealers-map', [DealerController::class, 'dealermap']);
        Route::get('/mapdata', [DealerController::class, 'mapdata']);
        Route::resource('dealer', DealerController::class);
        Route::get('dealerExport', [DealerController::class, 'dealerExport']);
    });

    Route::group(['middleware' => ['permission:area']], function () {
        //for Area CRUD
        Route::resource('area', AreaController::class);
    });

    // For Product Inventory
    require __DIR__ . '/tenants/product_inventory.php';

    Route::group(['middleware' => ['permission:productinventory', 'feature:inventory']], function () {
        ///begin for product inventory///
        Route::resource('productmanufac', ProductInventorymanufController::class);
        Route::get('/get-size/{product_id}', [ProductInventorymanufController::class, 'getSize']);

        Route::resource('producinventory', ProductInventoryController::class);

        Route::resource('product-dealer', ProductDealerController::class);
        Route::get('/get-size/{product_id}', [ProductDealerController::class, 'getSize']);

        Route::resource('subdealer-inventory', SubdealerInventoryController::class)->except(['edit', 'update', 'destroy']);
        Route::get('subdealer-inventory-reduce/create', [SubdealerInventoryController::class, 'reduceCreate'])->name('subdealer-inventory-reduce.create');
        Route::post('subdealer-inventory-reduce', [SubdealerInventoryController::class, 'reduceStore'])->name('subdealer-inventory-reduce.store');
    });

    Route::group(['middleware' => ['permission:product']], function () {
        // For Category
        Route::resource('category', CategoryController::class);

        // For Category
        Route::resource('size', SizeController::class);

        // For Products
        Route::get('/product/images/{id}', [ProductController::class, 'viewImage']);
        Route::POST('/product/images', [ProductController::class, 'imageSave']);
        Route::get('/product/images/{id}/all', [ProductController::class, 'imgView']);
        Route::DELETE('/image/delete/{id}', [ProductController::class, 'unlink']);
        Route::resource('product', ProductController::class);

        // For Final Product
        Route::resource('final-product', FinalProductController::class);

        //Final Product Export
        Route::get('/exportproduct', [ExcelExportController::class, 'export']);

        //Raw Materials Inventory
        Route::get('/exportinventory', [ExcelInventoryController::class, 'export']);

        //Raw Product Inventory
        Route::get('/exportrawproduct', [ExcelRawProductController::class, 'export']);
        Route::get('/exportproductmanufacture', [ExcelProductManufactureController::class, 'export']);
        Route::post('/products/excel', [ProductController::class, 'excelProduct']);
    });

    Route::post('/bank/excel', [BankController::class, 'bankexcel'])->middleware('permission:bank');
    Route::resource('bank', BankController::class)->middleware('permission:bank');

    //For Upload ledger file
    Route::resource('uploadfile', AccountController::class)->only(['index', 'store', 'create'])->middleware('permission:upload-due');

    // Distance Setting
    Route::resource('distance', SettingController::class)->only(['index', 'store'])->middleware('permission:distance');

    // Auto Checkout Time Setting
    Route::get('checkout-time', [SettingController::class, 'checkoutIndex'])->name('checkout-time.index')->middleware('permission:auto-checkout');
    Route::post('checkout-time', [SettingController::class, 'checkoutStore'])->name('checkout-time.store')->middleware('permission:auto-checkout');
    // For Denomination
    Route::resource('denomination', DenominationController::class)->middleware('permission:denomination');

    // For Purpose
    Route::resource('purpose', PurposeController::class)->middleware('permission:purpose');

    // For Role
    Route::resource('role', RoleController::class)->middleware('permission:role');

    Route::get('/privacy-policy/status/{privacypolicy_id}', [PrivacyPolicyController::class, 'status'])->middleware('permission:privacy-policy');
    Route::resource('/privacy-policy', PrivacyPolicyController::class)->middleware('permission:privacy-policy');

    Route::get('/getdealer', [DealerController::class, 'getdealer']);
    Route::get('/getsubarea', [SubareaController::class, 'getsubarea']);
    Route::resource('subarea', SubareaController::class)->only(['store', 'show', 'update', 'edit']);

    //User Location View
    Route::post('/user-location/{id}', [LocationController::class, 'userLocation'])->name('user.location');
    Route::post('/origin/{id}', [OrderController::class, 'getOrderLocationDetails'])->name('origin');
    Route::post('visit/origin/{id}', [VisitController::class, 'getVisitLocationDetails'])->name('visit.origin');


    // Site Registration
    require __DIR__ . '/tenants/site_registration.php';
});

// Route::get('notification/reminder/3jR4GUzghGFTrpy2', [AlertReminderController::class, 'index']);
Route::get('privacy_policy', [PrivacyPolicyController::class, 'privacypolicy']); //privacy-policy page

Route::middleware(['auth'])->group(function () {
    Route::get('/tenant/settings/branding', [App\Http\Controllers\Tenant\TenantSettingController::class, 'edit'])->name('tenant.settings.branding');
    Route::put('/tenant/settings/branding', [App\Http\Controllers\Tenant\TenantSettingController::class, 'update'])->name('tenant.settings.branding.update');
});
