<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user (updateOrCreate prevents duplicates)
        $admin = \App\Models\User::updateOrCreate(
            ['email' => 'admin@esmooth.com'],
            [
                'name' => 'Admin User',
                'password' => 'admin123',
                'role' => 'admin',
                'email_verified_at' => now()
            ]
        );

        // Create a sample customer
        $customer = \App\Models\User::updateOrCreate(
            ['email' => 'customer@esmooth.com'],
            [
                'name' => 'John Doe',
                'password' => 'customer123',
                'role' => 'customer',
                'email_verified_at' => now()
            ]
        );

        // Create customer profile
        \App\Models\Customer::updateOrCreate(
            ['email' => 'customer@esmooth.com'],
            [
                'name' => 'John Doe',
                'phone' => '+1234567890',
                'address' => '123 Main Street',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'US',
                'user_id' => $customer->id
            ]
        );
    }
}
