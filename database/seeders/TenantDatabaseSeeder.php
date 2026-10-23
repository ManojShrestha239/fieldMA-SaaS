<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the tenant database with initial roles, permissions, and settings.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            IssueTypeSedder::class,
            SiteProductSeeder::class,
            TadaAuthorizedUserSeeder::class,
            TadaExpenseAuthorizedUserSeeder::class,
        ]);
    }
}
