<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the main admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'account_type' => 'personal',
                'is_admin' => true,
                'email_verified_at' => now(),
                'last_active_at' => now(),
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Password: password');
        } else {
            // Update existing admin to ensure they have admin privileges
            $admin->update(['is_admin' => true]);
            $this->command->info('Admin user already exists and privileges updated.');
        }

        // Create a second admin user for testing
        $secondAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'account_type' => 'personal',
                'is_admin' => true,
                'email_verified_at' => now(),
                'last_active_at' => now(),
            ]
        );

        if ($secondAdmin->wasRecentlyCreated) {
            $this->command->info('Super admin user created successfully!');
            $this->command->info('Email: superadmin@example.com');
            $this->command->info('Password: password');
        }
    }
}