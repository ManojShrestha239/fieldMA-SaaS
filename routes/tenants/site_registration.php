<?php

declare(strict_types=1);

use App\Http\Controllers\FollowUpControllerV2;
use App\Http\Controllers\SiteRegistrationControllerV2;
use Illuminate\Support\Facades\Route;

// Site Registration
Route::middleware(['feature:site_registration'])->group(function () {
    Route::get('/export-site-registrations-v2', [SiteRegistrationControllerV2::class, 'export'])->name('export.site.registrations.v2');
    Route::resource('site-registration-v2', SiteRegistrationControllerV2::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    Route::get('/site-registration/{site}/activitylog', [SiteRegistrationControllerV2::class, 'getActivityLogs']);
    Route::get('site-registration-v2-map', [SiteRegistrationControllerV2::class, 'siteMaps'])->name('site.registration.v2.map');
    Route::resource('followUp', FollowUpControllerV2::class)->only(['index', 'create']);
});
