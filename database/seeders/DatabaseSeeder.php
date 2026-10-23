<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Automatically detects whether running on Central (Landlord) or Tenant database.
     *
     * @return void
     */
    public function run()
    {
        if (function_exists('tenant') && tenant()) {
            $this->call(TenantDatabaseSeeder::class);
        } else {
            $this->call(CentralDatabaseSeeder::class);
        }
    }
}
