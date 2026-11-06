<?php

use App\Http\Controllers\API\v3\AttendanceController;
use App\Http\Controllers\API\v3\LocationController;
use App\Http\Controllers\API\v3\MessageController;
use App\Http\Controllers\API\v3\MobileuserController;
use App\Http\Controllers\API\v3\OrderController;
use App\Http\Controllers\API\v3\PaymentController;
use App\Http\Controllers\API\v3\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (v3)
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// [ACTIVE] User Authentication (Ref: Api_Audit.md #36)
Route::post('/login', [MobileuserController::class, 'authenticate']);

Route::group(['middleware' => ['auth:api', 'token.api', 'user.status']], function () {
    // // [ACTIVE] Order Booking & History (Ref: Api_Audit.md #38, #39, #40)
    // Route::post('/order', [OrderController::class, 'store']);
    // Route::get('/orderHistory', [OrderController::class, 'index']);
    // Route::get('/orderDetails/{id}', [OrderController::class, 'show']);
    require __DIR__.'/../tenants/api/dealer_orders.php';

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #36)
    // Route::get('/order/filter', [OrderController::class, 'filter']);

    // [ACTIVE] Real-time GPS Tracking (Ref: Api_Audit.md #37)
    Route::post('/locations', [LocationController::class, 'store']);

    // [INACTIVE] Flutter uses /api/v4/negotiation & /api/v5/negotiation (Ref: Api_Audit.md #37)
    // Route::post('/negotiation', [SessionController::class, 'store']);

    // [ACTIVE] Chat User Directory & In-app Messaging (Ref: Api_Audit.md #42, #43)
    Route::get('/userlist', [MessageController::class, 'userlist']);
    Route::apiResource('message', MessageController::class)->only(['index', 'store']);

    // [ACTIVE] Payment Submission & Details (Ref: Api_Audit.md #41)
    Route::apiResource('payment', PaymentController::class)->only(['store', 'show']);

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #38)
    // Route::post('/offline', [AttendanceController::class, 'offline']);

    // [INACTIVE] Flutter uses /api/v4/attendancewithkm & /api/v2/attendance (Ref: Api_Audit.md #39)
    // Route::apiResource('attendance', AttendanceController::class)->only(['store']);
});
