<?php

declare(strict_types=1);

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * Explicit columns stored in the tenants table.
     * Any other attribute will automatically be stored in JSON 'data'.
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'company_name',
            'slug',
            'email',
            'phone',
            'vat_number',
            'address',
            'status',
            'subscription_plan_id',
            'trial_ends_at',
            'plan_ends_at',
            'data',
            'created_at',
            'updated_at',
        ];
    }

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'plan_ends_at' => 'datetime',
        'data' => 'array',
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isTrial(): bool
    {
        return $this->status === 'trial';
    }

    public function isTrialExpired(): bool
    {
        return $this->isTrial() && $this->trial_ends_at && $this->trial_ends_at->isPast();
    }

    public function isPlanExpired(): bool
    {
        return $this->plan_ends_at && $this->plan_ends_at->isPast();
    }

    public function isExpired(): bool
    {
        return $this->isTrialExpired() || $this->isPlanExpired();
    }

    public function isActive(): bool
    {
        if (in_array($this->status, ['suspended', 'inactive'], true)) {
            return false;
        }

        if ($this->isExpired()) {
            return false;
        }

        return in_array($this->status, ['active', 'trial'], true);
    }

    public function getLogoUrl(): ?string
    {
        $url = $this->logo_url ?? ($this->data['logo_url'] ?? null);
        if ($url) {
            return \Illuminate\Support\Str::startsWith($url, ['http://', 'https://']) ? $url : asset($url);
        }

        return asset('assets/pages/img/logo-fieldma.png');
    }

    public function getFaviconUrl(): ?string
    {
        $url = $this->favicon_url ?? ($this->data['favicon_url'] ?? null);
        if ($url) {
            return \Illuminate\Support\Str::startsWith($url, ['http://', 'https://']) ? $url : asset($url);
        }

        return $this->getLogoUrl();
    }

    public function hasCustomLogo(): bool
    {
        return !empty($this->logo_url ?? ($this->data['logo_url'] ?? null));
    }

    public function hasCustomFavicon(): bool
    {
        return !empty($this->favicon_url ?? ($this->data['favicon_url'] ?? null));
    }

    public function getThemeColor(): string
    {
        return $this->theme_color ?? $this->data['theme_color'] ?? '#315DA9';
    }

    public function getFcmTopic(): string
    {
        return $this->fcm_topic ?? $this->data['fcm_topic'] ?? ($this->slug . '_fieldMA_allUser');
    }

    /**
     * Get the primary subdomain FQDN (e.g. acme.fieldma.com).
     */
    public function getPrimaryDomain(): ?string
    {
        $centralDomain = config('tenancy.central_domains')[0] ?? 'localhost';
        $subdomain = $this->slug . '.' . $centralDomain;

        $match = $this->domains->firstWhere('domain', $subdomain);
        if ($match) {
            return $match->domain;
        }

        return $this->domains->first(function ($d) use ($centralDomain) {
            return \Illuminate\Support\Str::endsWith($d->domain, $centralDomain);
        })?->domain;
    }

    /**
     * Get the optional custom domain (e.g. portal.acmecorp.com).
     */
    public function getCustomDomain(): ?string
    {
        $primary = $this->getPrimaryDomain();

        return $this->domains->first(function ($d) use ($primary) {
            return $d->domain !== $primary && $d->domain !== $this->slug;
        })?->domain;
    }

    /**
     * Check if a feature is enabled for this tenant (checks tenant override, then subscription plan).
     */
    public function hasFeature(string $featureKey): bool
    {
        $overrides = $this->data['feature_overrides'] ?? [];
        if (array_key_exists($featureKey, $overrides)) {
            return (bool) $overrides[$featureKey];
        }

        if ($this->plan) {
            return $this->plan->hasFeature($featureKey);
        }

        return true;
    }

    /**
     * Check if tenant has reached the mobile user limit.
     */
    public function canAddMobileUser(int $currentCount): bool
    {
        if (!$this->plan) {
            return true;
        }

        $max = (int) $this->plan->max_mobile_users;
        if ($max <= 0) {
            return true; // Unlimited
        }

        return $currentCount < $max;
    }
}
