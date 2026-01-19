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
        // 1. EKSEKUSI SUPER ADMIN DULU (PENTING)
        $this->call([
            SuperAdminSeeder::class,
        ]);

        // 2. Akun Admin Sekretariat
        User::create([
            'name' => 'Admin Sekretariat',
            'email' => 'sekretariat@dinkes.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin', // Set sebagai admin biasa
        ]);

        // 3. Akun Admin Bidang Kesmas
        User::create([
            'name' => 'Admin Kesmas',
            'email' => 'kesmas@dinkes.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 4. Akun Admin Bidang P2P
        User::create([
            'name' => 'Admin P2P',
            'email' => 'p2p@dinkes.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 5. Akun Admin Yankes
        User::create([
            'name' => 'Admin Yankes',
            'email' => 'yankes@dinkes.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }
}