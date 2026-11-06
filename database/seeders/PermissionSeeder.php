<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            "home",
            "map",
            "notices",
            "dealer_orders",
            "collection",
            "schedule",
            "chat",
            "complaint",
            "reports",
            "daily_reports",
            "setup",
            "productinventory",
            "user",
            "dealer",
            "area",
            "product",
            "privacy-policy",
            "miscellaneous",
            "bank",
            "upload-due",
            "distance",
            "denomination",
            "purpose",
            "role",
            "auto-checkout",
            "site_registration",
            "tracking_toggle",
            "team_hierarchy",
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => strtolower($permission)],
                ['guard_name' => 'web']
            );
        }
    }
}
