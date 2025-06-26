<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user with role in users table
        $adminUser = User::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin', // This goes in users table
            'email_verified_at' => now(),
        ]);

        // Create admin record - only user_id goes here
        Admin::create([
            'user_id' => $adminUser->id,
            // No role here!
        ]);

        $this->command->info('Admin user created successfully!');
    }
}