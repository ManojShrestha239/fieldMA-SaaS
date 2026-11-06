<?php

declare(strict_types=1);

use App\Http\Controllers\AreaVisitController;
use App\Http\Controllers\AreaVisitExportController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\AttendanceSummaryController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\DealerVisitController;
use App\Http\Controllers\DealerVisitExportController;
use App\Http\Controllers\NegotiationController;
use App\Http\Controllers\UnlistedExportController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\VisitExportController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:reports', 'feature:visits']], function () {
    // Visit Report Route
    Route::get('history', [VisitController::class, 'filter']);
    Route::resource('visithistory', VisitController::class);
    Route::get('/visit/areas', [AreaVisitController::class, 'filter']);
    Route::resource('visitarea', AreaVisitController::class);
    Route::get('/visithistory-dealer', [DealerVisitController::class, 'filter']);
    Route::resource('visitdealer', DealerVisitController::class);
    Route::get('/negotiation-report', [NegotiationController::class, 'filter']);
    Route::get('/negotiation/map/{id}', [NegotiationController::class, 'map']);
    Route::resource('negotiation', NegotiationController::class);

    // Attendance
    Route::get('/attendance/filter', [AttendanceController::class, 'filter']);
    Route::get('attendance', [AttendanceController::class, 'index']);
    Route::get('/attendance/{id}', [AttendanceController::class, 'view']);
    Route::get('/attendance/view/{id}', [AttendanceController::class, 'show']);

    //Attendance Image
    Route::get('attendanceimage/{id}', [AttendanceController::class, 'attendanceimageshow'])->name('attendanceimage');

    // UpdateKM for Attendance
    Route::get('attendance/updatekm/{id}', [AttendanceController::class, 'updatekmformshow']);
    Route::patch('attendance/updatekm/{id}', [AttendanceController::class, 'updatekmformpost'])->name('attendanceupdatekm');
    Route::resource('attendanceReport', AttendanceReportController::class);
    Route::get('/report/attendance', [AttendanceReportController::class, 'search']);
    Route::get('/report/attendance/download', [AttendanceReportController::class, 'download']);
    Route::get('/report/attendance/summary', [AttendanceReportController::class, 'summary']);
    Route::resource('monthlySummary', AttendanceSummaryController::class);

    // user-wise visit report export
    Route::post('/visit-export', [VisitExportController::class, 'visit_Report']);
    Route::post('/visit-detailed-export', [VisitExportController::class, 'visit_Detailed_Report']);
    Route::post('/mis-Report', [VisitExportController::class, 'MIS_Report']);
    // area-wise visit report export
    Route::post('/area-visit-export', [AreaVisitExportController::class, 'area_visit_Report']);
    Route::post('/area-visit-detailed-export', [AreaVisitExportController::class, 'Area_visit_Detailed_Report']);
    // Dealer-wise visit report export
    Route::post('/dealer-visit-export', [DealerVisitExportController::class, 'dealer_visit_report']);
    Route::post('/dealer-visit-detailed-export', [DealerVisitExportController::class, 'dealer_visit_detailed_report']);
    // Unlisted Dealer visit report export
    Route::post('/unlisted-visit-export', [UnlistedExportController::class, 'unlisted_visit_Report']);
    Route::post('/unlisted-visit-detailed-export', [UnlistedExportController::class, 'unlisted_visit_Detailed_Report']);

    //Dealer ot Retailer Route
    Route::get('/dealerOrRetailer', [DealerController::class, 'dealerOrRetailer']);
});
