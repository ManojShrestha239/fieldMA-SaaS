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
     * Check if feature is allowed in this plan.
     */
    public function hasFeature(string $featureKey): bool
    {
        if (empty($this->features)) {
            return true;
        }

        return !empty($this->features[$featureKey]);
    }
}
