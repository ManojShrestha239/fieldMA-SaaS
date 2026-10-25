<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Routing\UrlGenerator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Define global tenant helpers
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Blade::if('super', function () {
            return auth()->check() && (auth()->user()->user_type == "super" || Auth::user()->user_type == 'ASM');
        });
        Blade::if('tenantFeature', function (string $feature) {
            return \App\Utilities\Helpers::hasFeature($feature);
        });
        Blade::if('account', function () {
            return auth()->check() && auth()->user()->user_type == "account";
        });
        Blade::if('marketing', function () {
            return auth()->check() && auth()->user()->user_type == "marketing";
        });
        Blade::if('dispatch', function () {
            return auth()->check() && auth()->user()->user_type == "dispatch";
        });
        Blade::if('super_market', function () {
            return auth()->check() && (auth()->user()->user_type == "super" || auth()->user()->user_type == "marketing" || Auth::user()->user_type == 'ASM');
        });
        Blade::if('super_market_dispatch', function () {
            return auth()->check() && (auth()->user()->user_type == "super" || auth()->user()->user_type == "marketing" || auth()->user()->user_type == "dispatch" || Auth::user()->user_type == 'ASM');
        });
        Blade::if('super_market_account', function () {
            return auth()->check() && (auth()->user()->user_type == "super" || auth()->user()->user_type == "marketing" || auth()->user()->user_type == "account" || Auth::user()->user_type == 'ASM');
        });

        Schema::defaultStringLength(191);

        // Always take assets from the main domain
        if (! UrlGenerator::hasMacro('setAssetRoot')) {
            UrlGenerator::macro('setAssetRoot', function ($root) {
                $this->assetRoot = $root;
                return $this;
            });
        }

        $applyAssetRoot = function ($request = null) {
            $assetUrl = config('app.asset_url') ?: config('app.url');
            if (! $assetUrl) {
                return;
            }

            $isHttps = false;
            if ($request instanceof \Illuminate\Http\Request) {
                $isHttps = $request->isSecure() || strtolower((string) $request->header('x-forwarded-proto')) === 'https';
            } elseif (! $this->app->runningInConsole()) {
                $isHttps = request()->isSecure() || strtolower((string) request()->header('x-forwarded-proto')) === 'https';
            }

            if ($isHttps && str_starts_with($assetUrl, 'http://')) {
                $assetUrl = preg_replace('/^http:/i', 'https:', $assetUrl);
            }

            $this->app['url']->setAssetRoot($assetUrl);
        };

        $applyAssetRoot();

        $this->app->rebinding('request', function ($app, $request) use ($applyAssetRoot) {
            $applyAssetRoot($request);
        });
    }
}
