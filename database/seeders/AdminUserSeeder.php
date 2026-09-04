<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the admin account documented in the README.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@example.test'],
            [
                'name' => 'FÉM Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ],
        );
    }
}
