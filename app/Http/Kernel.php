<?php

namespace App\Http;

use App\Http\Middleware\AccountAdminMiddleware;
use App\Http\Middleware\AuthCheck;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AuthenticateToken;
use App\Http\Middleware\CheckTenantStatus;
use App\Http\Middleware\ComboAdminMiddleware;
use App\Http\Middleware\DispatchAdminMiddleware;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\EnsureFeatureEnabled;
use App\Http\Middleware\InitializeTenancyForApi;
use App\Http\Middleware\marketingAdminMiddleware;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\UserManagement;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        TrustProxies::class,
        // \Fruitcake\Cors\HandleCors::class,
        PreventRequestsDuringMaintenance::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'cache.headers' => SetCacheHeaders::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'password.confirm' => RequirePassword::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequests::class,
        'verified' => EnsureEmailIsVerified::class,
        'super' => SuperAdminMiddleware::class,
        'dispatch' => DispatchAdminMiddleware::class,
        'account' => AccountAdminMiddleware::class,
        'marketing' => marketingAdminMiddleware::class,
        'combo' => ComboAdminMiddleware::class,
        'usermgnt' => UserManagement::class,
        'custom_auth' => AuthCheck::class,
        'role' => RoleMiddleware::class,
        'permission' => PermissionMiddleware::class,
        'role_or_permission' => RoleOrPermissionMiddleware::class,
        'token.api' => AuthenticateToken::class,
        'tenant.status' => CheckTenantStatus::class,
        'tenant.feature' => EnsureFeatureEnabled::class,
        'feature' => EnsureFeatureEnabled::class,
        'tenant.api' => InitializeTenancyForApi::class,
    ];

    /**
     * The application's middleware aliases (Laravel 10+ / 11+ / 12+ / 13+ standard).
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'cache.headers' => SetCacheHeaders::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'password.confirm' => RequirePassword::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequests::class,
        'verified' => EnsureEmailIsVerified::class,
        'super' => SuperAdminMiddleware::class,
        'dispatch' => DispatchAdminMiddleware::class,
        'account' => AccountAdminMiddleware::class,
        'marketing' => marketingAdminMiddleware::class,
        'combo' => ComboAdminMiddleware::class,
        'usermgnt' => UserManagement::class,
        'custom_auth' => AuthCheck::class,
        'role' => RoleMiddleware::class,
        'permission' => PermissionMiddleware::class,
        'role_or_permission' => RoleOrPermissionMiddleware::class,
        'token.api' => AuthenticateToken::class,
        'tenant.status' => CheckTenantStatus::class,
        'tenant.feature' => EnsureFeatureEnabled::class,
        'feature' => EnsureFeatureEnabled::class,
        'tenant.api' => InitializeTenancyForApi::class,
    ];
}
