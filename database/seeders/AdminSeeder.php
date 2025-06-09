<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'firstname' => 'System',
            'lastname' => 'Administrator',
            'birthday' => '1990-01-01',
            'gender' => 'other',
            'email' => 'admin@system.com',
            'password' => Hash::make('admin123'),
            'role_id' => 3, // Admin role
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);
    }
} 