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
     * Check if a feature (or specific sub-feature) is enabled in this plan.
     * Uses canonical feature keys defined in config/features.php.
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

        if (! array_key_exists($featureKey, $this->features)) {
            return false;
        }

        $featureData = $this->features[$featureKey];

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
                return ! empty($subs[$subFeature]);
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
        if (empty($this->features[$featureKey])) {
            return [];
        }

        $featureData = $this->features[$featureKey];
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

    /**
     * Get all enabled features for a given module, or a map of all enabled modules
     * and their features if no module is specified.
     *
     * @param  string|null  $module  Canonical module key (e.g. 'dealer_orders', 'visits')
     */
    public function getEnabledFeatures(?string $module = null): array
    {
        if ($module !== null) {
            return $this->getEnabledFeaturesForModule($module);
        }

        if (empty($this->features) || ! is_array($this->features)) {
            return [];
        }

        $result = [];
        foreach (array_keys($this->features) as $key) {
            $result[$key] = $this->getEnabledSubFeatures($key);
        }

        return $result;
    }

    /**
     * Get all enabled features for a given module.
     *
     * @return array<int, string>
     */
    public function getEnabledFeaturesForModule(string $module): array
    {
        return $this->getEnabledSubFeatures($module);
    }

    /**
     * Alias for getEnabledFeatures().
     */
    public function getAllEnabledFeatures(?string $module = null): array
    {
        return $this->getEnabledFeatures($module);
    }
}
