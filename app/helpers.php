<?php

use App\Utilities\Helpers;

if (!function_exists('tenant_has_feature')) {
    /**
     * Check if current tenant has a feature flag enabled.
     *
     * @param string $feature
     * @return bool
     */
    function tenant_has_feature(string $feature): bool
    {
        return Helpers::hasFeature($feature);
    }
}

if (!function_exists('tenant_setting')) {
    /**
     * Retrieve a tenant setting or fallback to default.
     *
     * @param string $key
     * @param mixed $default
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

if (!function_exists('tenant_brand_color')) {
    /**
     * Retrieve the tenant primary brand color.
     *
     * @param string $default
     * @return string
     */
    function tenant_brand_color(string $default = '#315DA9'): string
    {
        return tenant_setting('primary_color', $default) ?: $default;
    }
}

if (!function_exists('tenant_logo_url')) {
    /**
     * Retrieve the tenant logo URL.
     *
     * @param string|null $default
     * @return string|null
     */
    function tenant_logo_url(?string $default = null): ?string
    {
        $logo = tenant_setting('logo_url');
        if ($logo) {
            return $logo;
        }
        return $default ?: asset('assets/layouts/layout2/img/logo-default.png');
    }
}
