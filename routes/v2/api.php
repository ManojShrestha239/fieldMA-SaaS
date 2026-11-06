<?php

use App\Http\Controllers\API\v2\AttendanceController;
use App\Http\Controllers\API\v2\MobileuserController;
use App\Http\Controllers\API\v2\NoticeController;
use App\Http\Controllers\API\v2\PaymentController;
use App\Http\Controllers\API\v2\ProductController;
use App\Http\Controllers\API\v2\SiteRegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (v2)
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// [INACTIVE] Flutter uses /api/v3/login (Ref: Api_Audit.md #27)
// Route::post('/login', [\App\Http\Controllers\API\v3\MobileuserController::class, 'authenticate']);

Route::group(['middleware' => ['auth:api', 'token.api', 'user.status']], function () {
    // [INACTIVE] Flutter uses /api/countAmountandVisit (v1) (Ref: Api_Audit.md #28)
    // Route::get('/countAmountandVisit', [PaymentController::class, 'countAmountandVisit']);

    // [ACTIVE] Payment History (Ref: Api_Audit.md #30)
    Route::get('/collectionHistory', [PaymentController::class, 'collectionHistory'])->middleware(['feature:collections']);

    // [ACTIVE] Assigned Visit Plan (Ref: Api_Audit.md #32)
    Route::get('/visitSchedule', [MobileuserController::class, 'visitSchedule']);

    // [INACTIVE] Flutter uses /api/visit & /api/rangehistory (v1) (Ref: Api_Audit.md #29)
    // Route::get('/today', [MobileuserController::class, 'visitScheduleToday']);
    // Route::get('/visit', [MobileuserController::class, 'schedule']);
    // Route::get('/visit/{date}', [MobileuserController::class, 'visitHistory']);

    // [ACTIVE] Product Catalog (Ref: Api_Audit.md #29)
    Route::get('/products', [ProductController::class, 'index'])->middleware(['feature:dealer_orders']);

    // [INACTIVE] Flutter uses /api/v3/payment (Ref: Api_Audit.md #30)
    // Route::post('/payment', [PaymentController::class, 'index']);
    // Route::get('/payment/filter', [PaymentController::class, 'filter']);

    // // [ACTIVE] Broadcast Notices (Ref: Api_Audit.md #27, #28)
    // Route::get('/notice', [NoticeController::class, 'index']);
    // Route::get('/notice/{id}', [NoticeController::class, 'individual']);
    require __DIR__.'/../tenants/api/notice.php';

    // [INACTIVE] Flutter uses /api/v3/orderDetails (Ref: Api_Audit.md #31)
    // Route::get('/orderDetails/{id}', [NoticeController::class, 'orderDetails']);

    // [INACTIVE] Flutter uses /api/collectionDetails (v1) (Ref: Api_Audit.md #32)
    // Route::get('/collectionDetails/{id}', [NoticeController::class, 'collectionDetails']);

    // [ACTIVE] Payment Status (Ref: Api_Audit.md #31)
    Route::patch('/updatestatus/{id}', [PaymentController::class, 'updatestatus']);

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #33)
    // Route::get('/all', [NoticeController::class, 'all']);

    // [ACTIVE] Attendance Clock-in & History (Ref: Api_Audit.md #25, #26)
    Route::post('/attendance', [AttendanceController::class, 'store']);
    Route::get('/attendance', [AttendanceController::class, 'index']);

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #34)
    // Route::post('/attendance/offline', [AttendanceController::class, 'offline']);

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #35)
    // Route::get('/profile', [MobileuserController::class, 'profile']);

    // [ACTIVE] Site Registration V2 APIs (Ref: Api_Audit.md #33, #34)
    Route::get('/site-registration', [SiteRegistrationController::class, 'index']);
    Route::post('/site-registration', [SiteRegistrationController::class, 'store']);
    Route::get('/site-registration/{site}', [SiteRegistrationController::class, 'show']);
    Route::patch('/site-registration/{site}/updateSite', [SiteRegistrationController::class, 'updateSite']);
    Route::patch('/site-registration/{followUp}', [SiteRegistrationController::class, 'update']);
    Route::post('/site-registration/{site}/add-contact', [SiteRegistrationController::class, 'addContact']);
    Route::delete('/site-registration/{contact}/delete-contact', [SiteRegistrationController::class, 'deleteContact']);

    // [ACTIVE] Site Products Brand Tracking (Ref: Api_Audit.md #35)
    Route::get('site-products', [SiteRegistrationController::class, 'getSiteProducts']);
    Route::patch('site-products/{site}/{siteProduct}', [SiteRegistrationController::class, 'toggleProduct']);
});
