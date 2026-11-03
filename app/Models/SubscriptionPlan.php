<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_interval',
        'max_mobile_users',
        'max_admin_users',
        'features',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'max_mobile_users' => 'integer',
        'max_admin_users' => 'integer',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'subscription_plan_id');
    }

    /**
     * Map of legacy or alternate feature aliases to canonical feature keys.
     *
     * @var array<string, string>
     */
    protected static array $featureAliases = [
        'orders' => 'dealer_orders',
        'order_booking' => 'dealer_orders',
        'dealer_order' => 'dealer_orders',
        'retailer_order' => 'retailer_orders',
        'payments' => 'collections',
        'tada' => 'daily_reports',
        'expenses' => 'daily_reports',
        'live_tracking' => 'tracking_toggle',
        'stock_management' => 'inventory',
        'raw_inventory' => 'inventory',
        'sites' => 'site_registration',
    ];

    /**
     * Normalize feature key against known aliases.
     */
    public function normalizeFeatureKey(string $key): string
    {
        return static::$featureAliases[$key] ?? $key;
    }

    /**
     * Check if a feature (or specific sub-feature) is enabled in this plan.
     */
    public function hasFeature(string $featureKey, ?string $subFeature = null): bool
    {
        if (empty($this->features)) {
            return false;
        }

        // Support dot notation: "dealer_orders.new_order"
        if (str_contains($featureKey, '.') && $subFeature === null) {
            [$featureKey, $subFeature] = explode('.', $featureKey, 2);
        }

        $canonicalKey = $this->normalizeFeatureKey($featureKey);

        // Check if canonical key or original key exists
        $resolvedKey = null;
        if (array_key_exists($canonicalKey, $this->features)) {
            $resolvedKey = $canonicalKey;
        } elseif (array_key_exists($featureKey, $this->features)) {
            $resolvedKey = $featureKey;
        }

        if ($resolvedKey === null) {
            return false;
        }

        $featureData = $this->features[$resolvedKey];

        // If only checking parent module
        if ($subFeature === null) {
            if (is_bool($featureData)) {
                return $featureData;
            }
            return true;
        }

        // If checking a sub-feature
        if (is_array($featureData) && isset($featureData['sub_features'])) {
            $subs = $featureData['sub_features'];
            if (is_array($subs)) {
                // Check indexed array: ["new_order", "order_history"]
                if (in_array($subFeature, $subs, true)) {
                    return true;
                }
                // Check associative array: ["new_order" => true]
                return !empty($subs[$subFeature]);
            }
        }

        // Fallback for legacy flat boolean parent
        if (is_bool($featureData)) {
            return $featureData;
        }

        return false;
    }

    /**
     * Check if a specific sub-feature is enabled under a module.
     */
    public function hasSubFeature(string $featureKey, string $subFeature): bool
    {
        return $this->hasFeature($featureKey, $subFeature);
    }

    /**
     * Get all enabled sub-features for a given module.
     *
     * @return array<int, string>
     */
    public function getEnabledSubFeatures(string $featureKey): array
    {
        $canonicalKey = $this->normalizeFeatureKey($featureKey);
        $resolvedKey = array_key_exists($canonicalKey, $this->features ?? [])
            ? $canonicalKey
            : (array_key_exists($featureKey, $this->features ?? []) ? $featureKey : null);

        if (!$resolvedKey || empty($this->features[$resolvedKey])) {
            return [];
        }

        $featureData = $this->features[$resolvedKey];
        if (is_array($featureData) && isset($featureData['sub_features']) && is_array($featureData['sub_features'])) {
            $subs = $featureData['sub_features'];
            // If indexed array of strings
            if (array_is_list($subs)) {
                return $subs;
            }
            // If associative array: ['new_order' => true]
            return array_keys(array_filter($subs));
        }

        return [];
    }
}
