<?php

declare(strict_types=1);

use App\Http\Controllers\RepeatescheduleController;
use App\Http\Controllers\ScheduleVisitController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:schedule', 'feature:schedules']], function () {
    // For Schedules
    Route::get('/schedule/dealer/{id}', [ScheduleVisitController::class, 'dealerValue']);
    Route::post('/schedule/search', [ScheduleVisitController::class, 'search']);
    Route::get('schedule/area/{id}', [ScheduleVisitController::class, 'getarea']);
    Route::resource('schedule', ScheduleVisitController::class);

    // For Repeated Schedule
    Route::get('repeated/search', [RepeatescheduleController::class, 'search']);
    Route::resource('repeated', RepeatescheduleController::class);
});
