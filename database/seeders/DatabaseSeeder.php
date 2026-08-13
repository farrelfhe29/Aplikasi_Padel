<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::factory()->create([
            'name' => 'Admin Generasi Sehat',
            'email' => 'admin@generasisehat.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'approved',
            'phone' => '081234567890',
            'address' => 'Surabaya',
        ]);
        
        // Member user for testing
        User::factory()->create([
            'name' => 'Member Padel',
            'email' => 'member@generasisehat.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'status' => 'approved',
            'phone' => '081298765432',
            'address' => 'Sidoarjo',
        ]);
    }
}
