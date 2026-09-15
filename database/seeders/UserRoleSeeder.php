<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@dispensary.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Dr. Main Doctor',
            'email' => 'doctor@dispensary.com',
            'password' => Hash::make('password123'),
            'role' => 'doctor',
        ]);

        User::create([
            'name' => 'Receptionist',
            'email' => 'reception@dispensary.com',
            'password' => Hash::make('password123'),
            'role' => 'receptionist',
        ]);
    }
}