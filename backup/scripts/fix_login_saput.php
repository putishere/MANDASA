<?php

/**
 * Script untuk memperbaiki login user SAPUT
 * Menambahkan user SAPUT jika belum ada, atau memperbaiki data yang ada
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\SantriDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== PERBAIKAN LOGIN USER SAPUT ===\n\n";

try {
    // 1. Cek apakah user SAPUT sudah ada
    echo "1. Mencari user dengan username 'SAPUT'...\n";
    
    $user = User::where(function($query) {
        $query->whereRaw('LOWER(TRIM(username)) = ?', ['saput'])
              ->orWhereRaw('TRIM(username) = ?', ['SAPUT'])
              ->orWhere('username', 'SAPUT')
              ->orWhere('username', 'saput');
    })->first();
    
    if ($user) {
        echo "   ✓ User ditemukan: ID {$user->id}, Username: {$user->username}, Role: {$user->role}\n";
        
        // Perbaiki role jika perlu
        $userRole = strtolower(trim($user->role ?? ''));
        if ($userRole !== 'santri') {
            echo "   ⚠ Role tidak sesuai ({$user->role}), memperbaiki ke 'santri'...\n";
            $user->role = 'santri';
            $user->save();
            echo "   ✓ Role diperbaiki\n";
        }
        
        // Pastikan username konsisten
        if (strtolower($user->username) !== 'saput') {
            echo "   ⚠ Username tidak konsisten, memperbaiki...\n";
            $user->username = 'SAPUT';
            $user->save();
            echo "   ✓ Username diperbaiki ke 'SAPUT'\n";
        }
        
        // Pastikan password sesuai dengan tanggal lahir
        if ($user->tanggal_lahir) {
            $tanggalLahirFormatted = $user->tanggal_lahir->format('Y-m-d');
            $passwordMatch = Hash::check($tanggalLahirFormatted, $user->password);
            
            if (!$passwordMatch) {
                echo "   ⚠ Password tidak sesuai dengan tanggal lahir, memperbaiki...\n";
                $user->password = Hash::make($tanggalLahirFormatted);
                $user->save();
                echo "   ✓ Password diperbaiki ke tanggal lahir: {$tanggalLahirFormatted}\n";
            } else {
                echo "   ✓ Password sudah sesuai dengan tanggal lahir\n";
            }
        } else {
            echo "   ⚠ Tanggal lahir tidak ada, perlu ditambahkan\n";
            echo "   Masukkan tanggal lahir (format: YYYY-MM-DD, contoh: 2005-09-14): ";
            $tanggalLahir = trim(fgets(STDIN));
            
            if ($tanggalLahir && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalLahir)) {
                $user->tanggal_lahir = $tanggalLahir;
                $user->password = Hash::make($tanggalLahir);
                $user->save();
                echo "   ✓ Tanggal lahir dan password ditambahkan: {$tanggalLahir}\n";
            } else {
                echo "   ⚠ Format tanggal lahir tidak valid, menggunakan default: 2005-09-14\n";
                $user->tanggal_lahir = '2005-09-14';
                $user->password = Hash::make('2005-09-14');
                $user->save();
                echo "   ✓ Tanggal lahir default ditambahkan: 2005-09-14\n";
            }
        }
        
        // Cek apakah memiliki santriDetail
        if (!$user->santriDetail) {
            echo "   ⚠ User tidak memiliki santriDetail, membuat...\n";
            try {
                SantriDetail::create([
                    'user_id' => $user->id,
                    'nis' => $user->username ?? 'SAPUT',
                    'alamat_santri' => 'Belum diisi',
                    'nama_wali' => 'Belum diisi',
                    'alamat_wali' => 'Belum diisi',
                    'status_santri' => 'aktif',
                ]);
                echo "   ✓ SantriDetail dibuat\n";
            } catch (\Exception $e) {
                echo "   ⚠ Error membuat santriDetail: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ✓ User sudah memiliki santriDetail\n";
        }
        
    } else {
        echo "   ⚠ User 'SAPUT' tidak ditemukan, membuat user baru...\n";
        
        // Buat user baru
        $tanggalLahir = '2005-09-14'; // Default tanggal lahir
        
        $user = User::create([
            'name' => 'Saput',
            'username' => 'SAPUT',
            'email' => null,
            'tanggal_lahir' => $tanggalLahir,
            'password' => Hash::make($tanggalLahir),
            'role' => 'santri',
        ]);
        
        echo "   ✓ User 'SAPUT' dibuat dengan ID: {$user->id}\n";
        echo "   ✓ Password default: {$tanggalLahir} (tanggal lahir)\n";
        
        // Buat santriDetail
        try {
            SantriDetail::create([
                'user_id' => $user->id,
                'nis' => 'SAPUT',
                'alamat_santri' => 'Belum diisi',
                'nama_wali' => 'Belum diisi',
                'alamat_wali' => 'Belum diisi',
                'status_santri' => 'aktif',
            ]);
            echo "   ✓ SantriDetail dibuat\n";
        } catch (\Exception $e) {
            echo "   ⚠ Error membuat santriDetail: " . $e->getMessage() . "\n";
        }
    }
    
    // Refresh user dari database
    $user->refresh();
    
    echo "\n=== INFORMASI USER SAPUT ===\n";
    echo "ID: {$user->id}\n";
    echo "Username: {$user->username}\n";
    echo "Name: {$user->name}\n";
    echo "Role: {$user->role}\n";
    echo "Tanggal Lahir: " . ($user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : 'Tidak ada') . "\n";
    echo "Password: {$user->tanggal_lahir->format('Y-m-d')} (tanggal lahir)\n";
    echo "Memiliki SantriDetail: " . ($user->santriDetail ? 'Ya' : 'Tidak') . "\n";
    
    echo "\n=== KREDENSIAL LOGIN ===\n";
    echo "Username: SAPUT\n";
    echo "Password: " . ($user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '2005-09-14') . "\n";
    
    echo "\n=== SELESAI ===\n";
    echo "\nSilakan coba login lagi dengan kredensial di atas.\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

