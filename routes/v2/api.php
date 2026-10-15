<?php

use App\Http\Controllers\API\v2\AttendanceController;
use App\Http\Controllers\API\v2\MobileuserController;
use App\Http\Controllers\API\v2\PaymentController;
use App\Http\Controllers\API\v2\ProductController;
use App\Http\Controllers\API\v2\NoticeController;
use App\Http\Controllers\API\v2\SiteRegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [MobileuserController::class, 'authenticate']);
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => ['auth:api', 'token.api', 'user.status']], function () {
    // nearby dealers
    // Route::get('/dealerWithboundry', [DealerController::class, 'dealerWithboundry']);

    // count Amount and Visit of the day
    Route::get('/countAmountandVisit', [PaymentController::class, 'countAmountandVisit']);

    // today's visit
    // Route::get('/visit', [NegotiationController::class, 'showNegotiation']);

    //collection History
    Route::get('/collectionHistory', [PaymentController::class, 'collectionHistory']);

    // visit schedule
    Route::get('/visitSchedule', [MobileuserController::class, 'visitSchedule']);
    Route::get('/today', [MobileuserController::class, 'visitScheduleToday']);
    Route::get('/visit', [MobileuserController::class, 'schedule']);
    Route::get('/visit/{date}', [MobileuserController::class, 'visitHistory']);

    // message
    // Route::get('/message', [MessageController::class, 'receive']);
    // Route::post('/send', [MessageController::class, 'send']);

    // Route::get('/banks', [BankController::class, 'index']);
    // Route::get('/purpose', [PurposeController::class, 'index']);

    // Route::get('/profile/{id}', [MobileuserController::class, 'show']);
    // Route::get('/profile/{id}/edit', [MobileuserController::class, 'update']);
    // Route::get('/mobileusers', [MobileuserController::class, 'index']);
    // Route::put('/profile/{id}/pass', [MobileuserController::class, 'change']);

    // Route::get('/sites', [SiteController::class, 'index']);
    // Route::post('/site', [SiteController::class, 'store']);
    // Route::get('/site/{id}', [SiteController::class, 'show']);
    // Route::put('/site/{id}', [SiteController::class, 'update']);

    // Route::get('/dealers/reposition', [DealerController::class, 'reposition']);
    // Route::get('/dealers/position', [DealerController::class, 'position']);
    // Route::get('/dealer/{code}', [DealerController::class, 'show']);
    // Route::get('/dealer/{code}/update', [DealerController::class, 'update']);

    // Route::post('/dealerlog', [DealerlogController::class, 'store']);

    // Route::get('/areas', [AreaController::class, 'index']);
    // Route::get('/area/{areacodes}/reposition', [AreaController::class, 'reposition']);
    // Route::get('/area/{areacodes}/position', [AreaController::class, 'position']);
    // Route::get('/area/{areacodes}/pending', [AreaController::class, 'pending']);
    // Route::post('/area/{areacodes}/dist', [AreaController::class, 'visit']);

    // Route::get('/{id}/history/{date}', [SessionController::class, 'index']);
    // Route::post('/session', [SessionController::class, 'store']);
    // Route::post('/negotiation', [SessionController::class, 'new']);

    // Route::post('/locations', [LocationController::class, 'insert']);
    // Route::get('/locationinfo', [LocationController::class, 'index']);

    Route::get('/products', [ProductController::class, 'index']);

    // Route::post('/order', [OrderController::class, 'place']);
    // Route::get('/order/filter', [OrderController::class, 'filter']);

    Route::post('/payment', [PaymentController::class, 'index']);
    Route::get('/payment/filter', [PaymentController::class, 'filter']);

    Route::get('/notice', [NoticeController::class, 'index']);
    Route::get('/notice/{id}', [NoticeController::class, 'individual']);
    Route::get('/orderDetails/{id}', [NoticeController::class, 'orderDetails']);
    Route::get('/collectionDetails/{id}', [NoticeController::class, 'collectionDetails']);

    Route::patch('/updatestatus/{id}', [PaymentController::class, 'updatestatus']);

    // test
    // Route::get('/test', [SessionController::class, 'test']);
    // Route::get('/m', [MessageController::class, 'm']);
    Route::get('/all', [NoticeController::class, 'all']);
    // Route::get('/repeateschedule', [RepeatescheduleController::class, 'index']);
    // Route::post('/attendance/offline', [AttandenceController::class, 'offline']);
    // Route::post('/attendance', [AttandenceController::class, 'store']);
    Route::post('/attendance', [AttendanceController::class, 'store']);
    Route::post('/attendance/offline', [AttendanceController::class, 'offline']);
    Route::get('/attendance', [AttendanceController::class, 'index']);


    // Route::PATCH('/cancel/{id}', [OrderController::class, 'editOrder']);

    // Route::PATCH('/singleCancel/{id}', [OrderController::class, 'cancelOrderItem']);

    // Route::get('/denomination', [DenominationController::class, 'index']);

    Route::get('/profile', [MobileuserController::class, 'profile']);

    //Site Registration V2 APIs
    Route::get('/site-registration', [SiteRegistrationController::class, 'index']);
    Route::post('/site-registration', [SiteRegistrationController::class, 'store']);
    Route::get('/site-registration/{site}', [SiteRegistrationController::class, 'show']);
    Route::patch('/site-registration/{site}/updateSite', [SiteRegistrationController::class, 'updateSite']);
    Route::patch('/site-registration/{followUp}', [SiteRegistrationController::class, 'update']);
    Route::post('/site-registration/{site}/add-contact', [SiteRegistrationController::class, 'addContact']);
    Route::delete('/site-registration/{contact}/delete-contact', [SiteRegistrationController::class, 'deleteContact']);
    //Site Products
    Route::get('site-products', [SiteRegistrationController::class, 'getSiteProducts']);
    Route::patch('site-products/{site}/{siteProduct}', [SiteRegistrationController::class, 'toggleProduct']);
});
