<?php

declare(strict_types=1);

use App\Http\Controllers\TadaReportController;
use Illuminate\Support\Facades\Route;
// TADA Report
Route::group(['middleware' => ['permission:reports', 'feature:daily_reports']], function () {
    // Note: dashboard-based creation is disabled. Keep other resource routes but exclude create/store.
    Route::resource('tada-report', TadaReportController::class)->except(['create', 'store']);
    Route::get('tada-report/{id}/download-pdf', [TadaReportController::class, 'downloadPdf'])->name('tada-report.pdf');
    // Export filtered TADA list to Excel
    Route::post('/tada-export', [TadaReportController::class, 'export'])->name('tada-report.export');
    // Admin Daily Report export
    Route::post('/tada-admin-export', [TadaReportController::class, 'adminExport'])->name('tada-report.admin-export');
    Route::get('/expense-report', [TadaReportController::class, 'expenseReport'])->name('expense-report.index');
    Route::get('/expense-report/export', [TadaReportController::class, 'exportExpenseReport'])->name('expense-report.export');
});
