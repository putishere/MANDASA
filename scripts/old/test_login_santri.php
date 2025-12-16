<?php

/**
 * Script untuk test login santri
 * Memeriksa apakah data santri di database bisa digunakan untuk login
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== TEST LOGIN SANTRI ===\n\n";

try {
    // Ambil semua user dengan role santri
    $santriUsers = User::whereRaw('LOWER(TRIM(role)) = ?', ['santri'])
        ->orWhereRaw('LOWER(TRIM(role)) LIKE ?', ['%santri%'])
        ->with('santriDetail')
        ->get();
    
    if ($santriUsers->isEmpty()) {
        echo "❌ TIDAK ADA DATA SANTRI DI DATABASE!\n";
        echo "   Jalankan seeder: php artisan db:seed --class=SantriSeeder\n\n";
        exit(1);
    }
    
    echo "Ditemukan {$santriUsers->count()} user santri:\n\n";
    
    $allValid = true;
    
    foreach ($santriUsers as $user) {
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📋 SANTRI: {$user->name}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        $errors = [];
        $warnings = [];
        
        // 1. Cek username
        $username = trim($user->username ?? '');
        if (empty($username)) {
            $errors[] = "❌ Username kosong";
            $allValid = false;
        } else {
            echo "✅ Username: '{$username}'\n";
            
            // Test case-insensitive search
            $found = User::whereRaw('LOWER(TRIM(username)) = ?', [strtolower($username)])
                ->whereRaw('LOWER(TRIM(role)) = ?', ['santri'])
                ->first();
            
            if ($found && $found->id === $user->id) {
                echo "   ✅ Case-insensitive search: BERHASIL\n";
            } else {
                $errors[] = "❌ Case-insensitive search: GAGAL";
                $allValid = false;
            }
        }
        
        // 2. Cek role
        $role = strtolower(trim($user->role ?? ''));
        if ($role !== 'santri') {
            $warnings[] = "⚠️  Role: '{$user->role}' (harus 'santri')";
            echo "⚠️  Role: '{$user->role}' (akan dinormalisasi menjadi 'santri' saat login)\n";
        } else {
            echo "✅ Role: '{$role}'\n";
        }
        
        // 3. Cek password
        if (empty($user->password)) {
            $errors[] = "❌ Password kosong";
            $allValid = false;
        } else {
            echo "✅ Password: Sudah di-hash\n";
            
            // Test dengan tanggal lahir
            if ($user->tanggal_lahir) {
                $tanggalLahirStr = $user->tanggal_lahir->format('Y-m-d');
                echo "   📅 Tanggal Lahir: {$tanggalLahirStr}\n";
                
                // Test Hash::check
                $passwordMatch = Hash::check($tanggalLahirStr, $user->password);
                if ($passwordMatch) {
                    echo "   ✅ Hash::check dengan tanggal lahir: BERHASIL\n";
                    echo "   💡 Password untuk login: {$tanggalLahirStr}\n";
                } else {
                    $errors[] = "❌ Hash::check dengan tanggal lahir: GAGAL";
                    $allValid = false;
                }
            } else {
                $errors[] = "❌ Tanggal lahir kosong (tidak bisa generate password default)";
                $allValid = false;
            }
        }
        
        // 4. Cek santriDetail
        if (!$user->santriDetail) {
            $errors[] = "❌ SantriDetail tidak ada (wajib untuk login)";
            $allValid = false;
            echo "❌ SantriDetail: TIDAK ADA\n";
        } else {
            echo "✅ SantriDetail: ADA\n";
            echo "   - NIS: {$user->santriDetail->nis}\n";
            echo "   - Status: {$user->santriDetail->status_santri}\n";
        }
        
        // 5. Simulasi login
        echo "\n🔐 SIMULASI LOGIN:\n";
        if (!empty($errors)) {
            echo "   ❌ Login akan GAGAL karena ada error di atas\n";
        } else {
            echo "   ✅ Login akan BERHASIL\n";
            echo "   📝 Kredensial:\n";
            echo "      Username: {$username}\n";
            echo "      Password: " . ($user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : 'N/A') . "\n";
        }
        
        // Tampilkan errors dan warnings
        if (!empty($errors)) {
            echo "\n❌ ERRORS:\n";
            foreach ($errors as $error) {
                echo "   {$error}\n";
            }
        }
        
        if (!empty($warnings)) {
            echo "\n⚠️  WARNINGS:\n";
            foreach ($warnings as $warning) {
                echo "   {$warning}\n";
            }
        }
        
        echo "\n";
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📊 RINGKASAN:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    if ($allValid) {
        echo "✅ SEMUA DATA SANTRI VALID - LOGIN SANTRI SIAP DIGUNAKAN\n\n";
        echo "💡 CARA LOGIN:\n";
        echo "   1. Buka: http://127.0.0.1:8000/login\n";
        echo "   2. Masukkan username (bukan email)\n";
        echo "   3. Masukkan password: tanggal lahir (format: YYYY-MM-DD)\n";
        echo "   4. Klik 'Masuk'\n\n";
    } else {
        echo "❌ ADA MASALAH DENGAN DATA SANTRI\n";
        echo "   Jalankan script perbaikan: php fix_santri_users.php\n\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: {$e->getMessage()}\n";
    echo "Stack trace:\n{$e->getTraceAsString()}\n";
    exit(1);
}

