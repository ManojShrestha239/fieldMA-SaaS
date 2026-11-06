<?php

use App\Utilities\Helpers;

if (! function_exists('tenant_has_feature')) {
    /**
     * Check if current tenant has a feature flag enabled.
     */
    function tenant_has_feature(string $feature): bool
    {
        return Helpers::hasFeature($feature);
    }
}

if (! function_exists('tenant_enabled_features')) {
    /**
     * Get all enabled features for a given module (or all enabled modules).
     */
    function tenant_enabled_features(?string $module = null): array
    {
        return Helpers::getEnabledFeatures($module);
    }
}

if (! function_exists('tenant_setting')) {
    /**
     * Retrieve a tenant setting or fallback to default.
     *
     * @param  mixed  $default
     * @return mixed
     */
    function tenant_setting(string $key, $default = null)
    {
        if (function_exists('tenant') && tenant()) {
            return tenant()->$key ?? $default;
        }

        return $default;
    }
}

if (! function_exists('tenant_brand_color')) {
    /**
     * Retrieve the tenant primary brand color.
     */
    function tenant_brand_color(string $default = '#315DA9'): string
    {
        return tenant_setting('primary_color', $default) ?: $default;
    }
}

if (! function_exists('tenant_logo_url')) {
    /**
     * Retrieve the tenant logo URL.
     */
    function tenant_logo_url(?string $default = null): ?string
    {
        if (function_exists('tenant') && tenant()) {
            return tenant()->getLogoUrl();
        }

        return $default ?: asset('assets/pages/img/logo-fieldma.png');
    }
}

if (! function_exists('tenant_favicon_url')) {
    /**
     * Retrieve the tenant favicon URL.
     */
    function tenant_favicon_url(?string $default = null): ?string
    {
        if (function_exists('tenant') && tenant()) {
            return tenant()->getFaviconUrl();
        }

        return $default ?: asset('assets/pages/img/logo-fieldma.png');
    }
}
