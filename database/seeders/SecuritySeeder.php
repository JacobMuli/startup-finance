<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SecuritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $recorderRole = Role::firstOrCreate(['name' => 'recorder']);
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);

        // 2. Create the primary Admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'jacobmwalughs@gmail.com'],
            [
                'name' => 'Jacob Mwalugha',
                'password' => Hash::make('password'),
            ]
        );

        // 3. Assign Admin role
        $adminUser->assignRole($adminRole);
    }
}
