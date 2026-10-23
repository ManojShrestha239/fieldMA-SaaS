<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        SuperAdmin::firstOrCreate(
            ['email' => 'superadmin@fieldma.com'],
            [
                'name' => 'FieldMA Super Admin',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
    }
}
