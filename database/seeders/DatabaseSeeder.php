<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin default
        User::firstOrCreate(
            ['email' => 'admin@pondok.test'],
            [
                'name' => 'Admin Pondok',
                'username' => 'admin',
                'tanggal_lahir' => null,
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Uncomment baris di bawah untuk menjalankan seeder santri
        // $this->call(SantriSeeder::class);
    }
}