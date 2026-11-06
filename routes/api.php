<?php

use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\DealerController;
use App\Http\Controllers\API\DeviceLogController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\MobileuserController;
use App\Http\Controllers\API\MyTeamController;
use App\Http\Controllers\API\NoticeController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\SessionController;
use App\Http\Controllers\API\TadaReportController;
use App\Http\Controllers\API\v2\SiteRegistrationController;
use App\Http\Controllers\API\v3\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// [INACTIVE] Superseded by /api/v3/login (Ref: Api_Audit.md #1)
// Route::post('/login', [\App\Http\Controllers\API\v3\MobileuserController::class, 'authenticate']);

Route::group(['middleware' => ['auth:api', 'token.api', 'user.status']], function () {
    // [ACTIVE] Field Staff Dashboard (Ref: Api_Audit.md #1)
    Route::get('/home', [MobileuserController::class, 'home']);

    // [ACTIVE] Geofenced Dealers (Ref: Api_Audit.md #7)
    Route::get('/dealerWithboundry', [DealerController::class, 'dealerWithboundry']);

    // [ACTIVE] Daily Metrics (Ref: Api_Audit.md #3)
    Route::get('/countAmountandVisit', [PaymentController::class, 'index']);

    // [INACTIVE] Superseded by /api/v3/orderHistory (Ref: Api_Audit.md #2)
    // Route::get('/orderHistory', [OrderController::class, 'orderHistory']);

    // [INACTIVE] Superseded by /api/v2/collectionHistory (Ref: Api_Audit.md #3)
    // Route::get('/collectionHistory', [PaymentController::class, 'collectionHistory']);

    // [INACTIVE] Superseded by /api/v2/visitSchedule (Ref: Api_Audit.md #4)
    // Route::get('/visitSchedule', [MobileuserController::class, 'visitSchedule']);

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #5)
    // Route::get('/today', [MobileuserController::class, 'visitScheduleToday']);

    // [ACTIVE] Visit Schedules (Ref: Api_Audit.md #9)
    Route::get('/visit', [MobileuserController::class, 'schedule']);

    // [INACTIVE] Flutter uses /api/visit & /api/rangehistory (Ref: Api_Audit.md #6)
    // Route::get('/visit/{date}', [MobileuserController::class, 'visitHistory']);

    // [ACTIVE] Historical Visits Range (Ref: Api_Audit.md #10)
    Route::get('/rangehistory', [MobileuserController::class, 'rangehistory']);

    // [INACTIVE] Superseded by /api/v3/message (Ref: Api_Audit.md #7)
    // Route::get('/message', [MessageController::class, 'index']);
    // Route::post('/send', [MessageController::class, 'store']);

    // [INACTIVE] Superseded by /api/v2/notice (Ref: Api_Audit.md #8)
    // Route::get('/notice', [NoticeController::class, 'index']);
    // Route::get('/notice/{id}', [NoticeController::class, 'show']);

    // [INACTIVE] Superseded by /api/v3/orderDetails (Ref: Api_Audit.md #9)
    // Route::get('/orderDetails/{id}', [NoticeController::class, 'orderDetails']);

    // [ACTIVE] Payment Collections (Ref: Api_Audit.md #4)
    Route::get('/collectionDetails/{id}', [NoticeController::class, 'collectionDetails']);

    // [ACTIVE] Dealer Directory (Ref: Api_Audit.md #2)
    Route::get('/dealers', [DealerController::class, 'index']);

    // [ACTIVE] Dealer Repositioning (Ref: Api_Audit.md #6)
    Route::get('/dealers/reposition', [DealerController::class, 'reposition']);

    // [ACTIVE] Dealer Geolocation (Ref: Api_Audit.md #5)
    Route::get('/dealers/position', [DealerController::class, 'position']);

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #10)
    // Route::get('/dealer/{code}', [DealerController::class, 'show']);

    // [ACTIVE] Field Staff Dashboard (Ref: Api_Audit.md #19)
    Route::get('/dealer/{code}/update', [DealerController::class, 'update']);

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #11)
    // Route::post('/dealerlog', [DeviceLogController::class, 'store']);

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #12)
    // Route::get('/{id}/history/{date}', [SessionController::class, 'index']);

    // [ACTIVE] Visit Sessions (Ref: Api_Audit.md #8)
    Route::post('/session', [SessionController::class, 'store']);

    // [INACTIVE] Superseded by /api/v4/negotiation & /api/v5/negotiation (Ref: Api_Audit.md #13)
    // Route::post('/negotiation', [SessionController::class, 'new']);

    // [ACTIVE] Unlisted Dealer Visit (Ref: Api_Audit.md #17)
    Route::post('v5/negotiation', [SessionController::class, 'v5negotiation']);

    // [INACTIVE] Superseded by /api/v3/locations (Ref: Api_Audit.md #14)
    // Route::post('/locations', [SessionController::class, 'insert']);
    // Route::get('/locationinfo', [SessionController::class, 'index']);

    // [INACTIVE] Superseded by /api/v2/products (Ref: Api_Audit.md #15)
    // Route::get('/products', [SessionController::class, 'index']);

    // [INACTIVE] Superseded by /api/v3/order (Ref: Api_Audit.md #16)
    // Route::post('/order', [OrderController::class, 'store']);

    // [INACTIVE] Superseded by /api/v3/payment (Ref: Api_Audit.md #17)
    // Route::post('/payment', [PaymentController::class, 'store']);

    // [INACTIVE] Superseded by /api/v2/updatestatus (Ref: Api_Audit.md #18)
    // Route::patch('/updatestatus/{id}', [PaymentController::class, 'updatestatus']);

    // [INACTIVE] Development / test endpoints (Ref: Api_Audit.md #26)
    // Route::get('/test', [SessionController::class, 'test']);
    // Route::get('/m', [MessageController::class, 'm']);
    // Route::post('/attendance/offline', [AttendanceController::class, 'offline']);
    // Route::get('/all', [NoticeController::class, 'all']);
    // Route::get('/repeateschedule', [SessionController::class, 'index']);
    // Route::post('/attendance', [AttendanceController::class, 'store']);
    // Route::post('/logout', [MobileuserController::class, 'logout']);

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #20)
    // Route::post('/device-log', [DeviceLogController::class, 'store']);

    // [ACTIVE] Contractor Directory (Ref: Api_Audit.md #11, #12)
    Route::get('contractors', [SiteRegistrationController::class, 'indexContractor']);
    Route::post('contractors', [SiteRegistrationController::class, 'storeContractor']);

    // [INACTIVE] Superseded by /api/v2/site-registration (Ref: Api_Audit.md #21)
    // Route::resource('site-registrations', SiteRegistrationController::class)->only(['index', 'store']);

    // [ACTIVE] Team Hierarchy & Activity (Ref: Api_Audit.md #13, #14)
    Route::get('my-teams', [MyTeamController::class, 'index']);
    Route::get('my-team/{user}/{date}', [MyTeamController::class, 'show']);

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #22)
    // Route::get('unlisted-dealers', [DealerController::class, 'unlistedDealers']);

    // [ACTIVE] TADA Expense Claims (Ref: Api_Audit.md Section 1.2 #18-#24, #15-#16)
    Route::post('/tada/upload-image', [TadaReportController::class, 'uploadImage']);
    Route::delete('/tada-expenses/images/{image_id}', [TadaReportController::class, 'deleteExpenseImage']);

    // [INACTIVE] Redundant with DELETE /api/tada-expenses/images/{id} (Ref: Api_Audit.md #23)
    // Route::post('/tada-expenses/images/delete', [\App\Http\Controllers\API\TadaReportController::class, 'deleteExpenseImage']);

    Route::get('/tada/authorized-users', [TadaReportController::class, 'authorizedUsers']);
    Route::get('/tada/expense-authorized-users', [TadaReportController::class, 'expenseAuthorizedUsers']);
    Route::get('/tada', [TadaReportController::class, 'index']);
    Route::post('/tada', [TadaReportController::class, 'store']);
    Route::get('/tada/{id}', [TadaReportController::class, 'show']);
    Route::put('/tada/{id}', [TadaReportController::class, 'update']);
    Route::delete('/tada/{id}', [TadaReportController::class, 'destroy']);
});

// [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #24, #25)
// Route::get('due/{area_code}/{dealer_code}', [SessionController::class, 'getdue']);
// Route::get('ageing/{area_code}/{dealer_code}', [SessionController::class, 'getdata']);
