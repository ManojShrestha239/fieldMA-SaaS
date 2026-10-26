<?php

declare(strict_types=1);

use App\Http\Middleware\CheckTenantStatus;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Web Routes
|--------------------------------------------------------------------------
|
| These routes are loaded when a tenant domain / subdomain is visited.
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    CheckTenantStatus::class,
])->group(function () {
    require base_path('routes/tenant_web.php');
});
