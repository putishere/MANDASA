<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SantriSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel santri.
     */
    public function run(): void
    {
        // Santri 1: Ahmad Fauzi
        User::firstOrCreate(
            ['username' => 'fauzi123'],
            [
                'name' => 'Ahmad Fauzi',
            'tanggal_lahir' => '2005-08-15',
                'email' => null,
                'password' => Hash::make('2005-08-15'), // Password default adalah tanggal lahir
                'role' => 'santri',
            ]
        );

        // Santri 2: Siti Aminah
        User::firstOrCreate(
            ['username' => 'aminah456'],
            [
                'name' => 'Siti Aminah',
            'tanggal_lahir' => '2006-03-22',
                'email' => null,
                'password' => Hash::make('2006-03-22'), // Password default adalah tanggal lahir
                'role' => 'santri',
            ]
        );
    }
}