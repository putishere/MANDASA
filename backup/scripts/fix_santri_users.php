<?php

/**
 * Script untuk memperbaiki data santri yang sudah ada
 * - Normalisasi username (trim whitespace)
 * - Normalisasi role menjadi lowercase 'santri'
 * - Pastikan password sudah di-hash dengan benar
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== Memperbaiki Data Santri ===\n\n";

try {
    // Ambil semua user dengan role santri (case-insensitive)
    $santriUsers = User::whereRaw('LOWER(TRIM(role)) = ?', ['santri'])
        ->orWhereRaw('LOWER(TRIM(role)) LIKE ?', ['%santri%'])
        ->get();
    
    echo "Ditemukan {$santriUsers->count()} user santri\n\n";
    
    $fixed = 0;
    $errors = 0;
    
    foreach ($santriUsers as $user) {
        echo "Memproses: {$user->username} (ID: {$user->id})\n";
        
        $updated = false;
        
        // 1. Normalisasi username (trim whitespace)
        $originalUsername = $user->username;
        $normalizedUsername = trim($user->username);
        if ($originalUsername !== $normalizedUsername) {
            echo "  - Username: '{$originalUsername}' -> '{$normalizedUsername}'\n";
            $user->username = $normalizedUsername;
            $updated = true;
        }
        
        // 2. Normalisasi role menjadi lowercase 'santri'
        $originalRole = $user->role;
        $normalizedRole = strtolower(trim($user->role ?? ''));
        if ($normalizedRole !== 'santri') {
            echo "  - Role: '{$originalRole}' -> 'santri'\n";
            $user->role = 'santri';
            $updated = true;
        }
        
        // 3. Pastikan password sudah di-hash (jika belum atau jika tanggal_lahir ada)
        if ($user->tanggal_lahir) {
            // Cek apakah password perlu di-update
            // Jika password masih plain text atau tidak cocok dengan hash tanggal_lahir
            $tanggalLahirStr = $user->tanggal_lahir->format('Y-m-d');
            
            // Cek apakah password adalah plain text tanggal lahir
            if ($user->password === $tanggalLahirStr) {
                echo "  - Password: plain text -> hashed\n";
                $user->password = Hash::make($tanggalLahirStr);
                $updated = true;
            } else {
                // Cek apakah password sudah di-hash tapi tidak cocok
                // Kita tidak bisa cek ini dengan pasti, jadi kita skip
                // Tapi jika password terlalu pendek (kurang dari 60 karakter = belum di-hash), update
                if (strlen($user->password) < 60) {
                    echo "  - Password: invalid hash -> hashed from tanggal_lahir\n";
                    $user->password = Hash::make($tanggalLahirStr);
                    $updated = true;
                }
            }
        }
        
        // 4. Normalisasi name (trim)
        if ($user->name !== trim($user->name)) {
            echo "  - Name: trimming whitespace\n";
            $user->name = trim($user->name);
            $updated = true;
        }
        
        if ($updated) {
            try {
                $user->save();
                echo "  ✓ Berhasil diperbaiki\n\n";
                $fixed++;
            } catch (\Exception $e) {
                echo "  ✗ Error: {$e->getMessage()}\n\n";
                $errors++;
            }
        } else {
            echo "  - Sudah benar, tidak perlu diperbaiki\n\n";
        }
    }
    
    echo "\n=== Selesai ===\n";
    echo "Total diperbaiki: {$fixed}\n";
    echo "Error: {$errors}\n";
    echo "\nData santri sudah dinormalisasi. Login sekarang seharusnya berfungsi dengan baik.\n";
    
} catch (\Exception $e) {
    echo "Error: {$e->getMessage()}\n";
    echo "Stack trace:\n{$e->getTraceAsString()}\n";
    exit(1);
}

