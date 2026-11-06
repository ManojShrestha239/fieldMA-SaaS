<?php

use App\Http\Controllers\API\v4\AlertReminderController;
use App\Http\Controllers\API\v4\AttendanceController;
use App\Http\Controllers\API\v4\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (v4)
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::group(['middleware' => ['auth:api', 'token.api', 'user.status']], function () {
    // [ACTIVE] Unlisted Dealer Visit (Ref: Api_Audit.md #45)
    Route::post('/negotiation', [SessionController::class, 'new']);

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #40)
    // Route::post('/offline/negotiation', [SessionController::class, 'store']);

    // [ACTIVE] Field Reminders & Alerts (Ref: Api_Audit.md #46)
    Route::apiResource('reminder', AlertReminderController::class);

    // // [ACTIVE] Field Complaints & Issue Types (Ref: Api_Audit.md #47, #48)
    // Route::apiResource('complaint', ComplaintController::class, ['as' => 'api']);
    // Route::get('issue_type', [ComplaintController::class, 'issue_type'], ['as' => 'api']);
    require __DIR__ . '/../tenants/api/complaints.php';

    // [INACTIVE] Flutter uses /api/v3/locations (Ref: Api_Audit.md #41)
    // Route::post('/location', [SessionController::class, 'loctaion'], ["as" => "api"]);

    // [ACTIVE] GPS Attendance + KM (Ref: Api_Audit.md #44)
    Route::post('attendancewithkm', [AttendanceController::class, 'onlinewithkm'], ['as' => 'api']);

    // [INACTIVE] Flutter uses /api/v4/attendancewithkm (Ref: Api_Audit.md #42)
    // Route::post('attendance', [AttendanceController::class, 'online'], ["as" => "api"]);

    // [INACTIVE] Not used in Flutter (Ref: Api_Audit.md #43)
    // Route::post('/offline', [AttendanceController::class, 'offline'], ["as" => "api"]);

    // [INACTIVE] Flutter uses /api/v2/attendance (Ref: Api_Audit.md #44)
    // Route::get('/attendance', [AttendanceController::class, 'getAttendance']);
});
