<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@dinkes.com', // Ganti email sesuka Anda
            'password' => Hash::make('password123'), // Ganti password yang aman
            'role' => 'super_admin',
        ]);
    }
}