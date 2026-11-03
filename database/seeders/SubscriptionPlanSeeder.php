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
                    'notices' => (object) [],
                    'dealer_orders' => [
                        'sub_features' => ['new_order', 'order_history'],
                    ],
                    'collections' => [
                        'sub_features' => ['new_collection', 'collection_history'],
                    ],
                    'schedules' => (object) [],
                    'visits' => [
                        'sub_features' => ['online_visit', 'offline_visit'],
                    ],
                    'reminder_alert' => (object) [],
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
                    'notices' => (object) [],
                    'dealer_orders' => [
                        'sub_features' => ['new_order', 'order_history', 'saved_orders'],
                    ],
                    'retailer_orders' => [
                        'sub_features' => ['new_order', 'order_history', 'saved_orders'],
                    ],
                    'collections' => [
                        'sub_features' => ['new_collection', 'collection_history'],
                    ],
                    'schedules' => (object) [],
                    'chats' => (object) [],
                    'complaints' => (object) [],
                    'visits' => [
                        'sub_features' => ['online_visit', 'offline_visit', 'unlisted_visit'],
                    ],
                    'reminder_alert' => (object) [],
                    'position' => [
                        'sub_features' => ['dealer_position', 'dealer_reposition'],
                    ],
                    'daily_reports' => (object) [],
                    'tracking_toggle' => (object) [],
                    'team_hierarchy' => (object) [],
                    'site_registration' => (object) [],
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
                    'notices' => (object) [],
                    'dealer_orders' => [
                        'sub_features' => ['new_order', 'order_history', 'saved_orders'],
                    ],
                    'retailer_orders' => [
                        'sub_features' => ['new_order', 'order_history', 'saved_orders'],
                    ],
                    'collections' => [
                        'sub_features' => ['new_collection', 'collection_history'],
                    ],
                    'schedules' => (object) [],
                    'chats' => (object) [],
                    'complaints' => (object) [],
                    'visits' => [
                        'sub_features' => ['online_visit', 'offline_visit', 'unlisted_visit'],
                    ],
                    'reminder_alert' => (object) [],
                    'position' => [
                        'sub_features' => ['dealer_position', 'dealer_reposition'],
                    ],
                    'inventory' => (object) [],
                    'daily_reports' => (object) [],
                    'tracking_toggle' => (object) [],
                    'team_hierarchy' => (object) [],
                    'site_registration' => (object) [],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
