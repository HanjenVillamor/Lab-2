<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = env('SEED_ADMIN_PASSWORD');
        $userPassword  = env('SEED_USER_PASSWORD');

        if (!$adminPassword || !$userPassword) {
            throw new \RuntimeException('Set SEED_ADMIN_PASSWORD and SEED_USER_PASSWORD in .env first.');
        }

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make($adminPassword),
                'role'     => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name'     => 'Regular User',
                'password' => Hash::make($userPassword),
                'role'     => 'user',
            ]
        );
    }
}