<?php

use App\Http\Controllers\SuperAdmin\AuthController as SuperAdminAuth;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\SubscriptionPlanController as SuperAdminPlans;
use App\Http\Controllers\SuperAdmin\TenantController as SuperAdminTenants;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central / Landlord Web Routes
|--------------------------------------------------------------------------
|
| These routes are loaded on central domains (e.g. localhost, admin.fieldma.com)
| for managing tenants, subscriptions, and platform administration.
|
*/

$centralDomains = config('tenancy.central_domains') ?: ['localhost', '127.0.0.1', 'manojshrestha239.com.np'];

foreach ($centralDomains as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', function () {
            if (Auth::guard('super_admin')->check()) {
                return redirect()->route('super-admin.dashboard');
            }
            return redirect()->route('super-admin.login');
        });
    });
}

// Super Admin Guest Routes
Route::prefix('super-admin')->group(function () {
    Route::get('/login', [SuperAdminAuth::class, 'showLogin'])->name('super-admin.login');
    Route::post('/login', [SuperAdminAuth::class, 'login'])->name('super-admin.login.submit');
    Route::post('/logout', [SuperAdminAuth::class, 'logout'])->name('super-admin.logout');

    // Super Admin Protected Routes
    Route::middleware(['auth:super_admin'])->group(function () {
        Route::get('/', [SuperAdminDashboard::class, 'index'])->name('super-admin.dashboard');
        Route::get('/dashboard', [SuperAdminDashboard::class, 'index']);

        // Tenants Management
        Route::resource('tenants', SuperAdminTenants::class, [
            'as' => 'super-admin'
        ]);
        Route::post('tenants/{tenant}/toggle-status', [SuperAdminTenants::class, 'toggleStatus'])->name('super-admin.tenants.toggle-status');
        Route::post('tenants/{tenant}/migrate', [SuperAdminTenants::class, 'migrate'])->name('super-admin.tenants.migrate');
        Route::post('tenants/{tenant}/rollback', [SuperAdminTenants::class, 'rollback'])->name('super-admin.tenants.rollback');
        Route::post('tenants/{tenant}/storage-link', [SuperAdminTenants::class, 'storageLink'])->name('super-admin.tenants.storage-link');
        Route::post('tenants/{tenant}/optimize-clear', [SuperAdminTenants::class, 'optimizeClear'])->name('super-admin.tenants.optimize-clear');

        // Subscription Plans Management
        Route::resource('plans', SuperAdminPlans::class, [
            'as' => 'super-admin'
        ]);
    });
});
