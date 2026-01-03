<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * PERINGATAN: Seeder ini hanya untuk development!
     * Untuk production, buat admin manual dengan password kuat setelah deployment.
     */
    public function run(): void
    {
        // Admin default - hanya dibuat jika belum ada
        // Menggunakan environment variables untuk fleksibilitas
        $adminEmail = env('ADMIN_EMAIL', 'admin@pondok.test');
        $adminUsername = env('ADMIN_USERNAME', 'admin');
        $adminPassword = env('ADMIN_PASSWORD', 'admin123');
        $adminName = env('ADMIN_NAME', 'Admin Pondok');
        
        // Hanya buat admin jika belum ada
        User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'username' => $adminUsername,
                'tanggal_lahir' => null,
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
            ]
        );

        // Uncomment baris di bawah untuk menjalankan seeder santri
        // $this->call(SantriSeeder::class);
    }
}