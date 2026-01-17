<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Sekretariat (Bisa dianggap Super Admin atau Admin Utama)
        User::create([
            'name' => 'Admin Sekretariat',
            'email' => 'sekretariat@dinkes.go.id',
            'password' => Hash::make('password123'), // Password sama semua biar mudah diingat
        ]);

        // 2. Akun Admin Bidang Kesmas
        User::create([
            'name' => 'Admin Kesmas',
            'email' => 'kesmas@dinkes.go.id',
            'password' => Hash::make('password123'),
        ]);

        // 3. Akun Admin Bidang P2P
        User::create([
            'name' => 'Admin P2P',
            'email' => 'p2p@dinkes.go.id',
            'password' => Hash::make('password123'),
        ]);

        // 4. Akun Admin Yankes
        User::create([
            'name' => 'Admin Yankes',
            'email' => 'yankes@dinkes.go.id',
            'password' => Hash::make('password123'),
        ]);
    }
}