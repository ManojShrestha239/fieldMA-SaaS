<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Ideal for small field teams starting with sales and attendance tracking.',
                'price' => 1000.00,
                'billing_interval' => 'monthly',
                'max_mobile_users' => 5,
                'max_admin_users' => 2,
                'features' => [
                    'attendance' => true,
                    'order_booking' => true,
                    'dealers_management' => true,
                    'notices' => true,
                    'payments' => true,
                    'tada' => false,
                    'site_registration' => false,
                    'raw_inventory' => false,
                    'stock_management' => false,
                    'live_tracking' => false,
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'Full sales force automation with TADA, live tracking, and stock management.',
                'price' => 2500.00,
                'billing_interval' => 'monthly',
                'max_mobile_users' => 25,
                'max_admin_users' => 5,
                'features' => [
                    'attendance' => true,
                    'order_booking' => true,
                    'dealers_management' => true,
                    'notices' => true,
                    'payments' => true,
                    'tada' => true,
                    'site_registration' => true,
                    'raw_inventory' => false,
                    'stock_management' => true,
                    'live_tracking' => true,
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Unlimited users and all modules enabled including manufacturing and custom workflows.',
                'price' => 3000.00,
                'billing_interval' => 'monthly',
                'max_mobile_users' => 0, // 0 = unlimited
                'max_admin_users' => 0,
                'features' => [
                    'attendance' => true,
                    'order_booking' => true,
                    'dealers_management' => true,
                    'notices' => true,
                    'payments' => true,
                    'tada' => true,
                    'site_registration' => true,
                    'raw_inventory' => true,
                    'stock_management' => true,
                    'live_tracking' => true,
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::firstOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
