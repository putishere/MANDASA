<?php

/**
 * Script Perbaikan Login Admin
 * 
 * Script ini akan:
 * 1. Membuat admin jika belum ada
 * 2. Memperbaiki admin yang sudah ada
 * 3. Memastikan password benar
 * 4. Memverifikasi login bisa dilakukan
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║          PERBAIKAN LOGIN ADMIN                               ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

try {
    // 1. Cek koneksi database
    echo "1. Memeriksa koneksi database...\n";
    try {
        DB::connection()->getPdo();
        echo "   ✓ Koneksi database berhasil\n\n";
    } catch (\Exception $e) {
        echo "   ✗ Koneksi database gagal: " . $e->getMessage() . "\n";
        echo "\n   Solusi:\n";
        echo "   - Pastikan database sudah dibuat\n";
        echo "   - Jalankan: php artisan migrate\n";
        exit(1);
    }
    
    // 2. Cek apakah tabel users ada
    echo "2. Memeriksa tabel users...\n";
    try {
        $tableExists = DB::getSchemaBuilder()->hasTable('users');
        if (!$tableExists) {
            echo "   ✗ Tabel 'users' tidak ditemukan!\n";
            echo "\n   Solusi:\n";
            echo "   - Jalankan: php artisan migrate\n";
            exit(1);
        }
        echo "   ✓ Tabel 'users' ditemukan\n\n";
    } catch (\Exception $e) {
        echo "   ✗ Error: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    // 3. Cari atau buat admin
    echo "3. Memeriksa admin dengan email 'admin@pondok.test'...\n";
    
    $admin = User::where('email', 'admin@pondok.test')->first();
    
    if (!$admin) {
        echo "   ⚠ Admin tidak ditemukan, membuat admin baru...\n";
        
        try {
            $admin = User::create([
                'name' => 'Admin Pondok',
                'email' => 'admin@pondok.test',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'tanggal_lahir' => null,
            ]);
            
            echo "   ✓ Admin berhasil dibuat!\n";
            echo "      ID: {$admin->id}\n";
            echo "      Email: {$admin->email}\n";
            echo "      Role: {$admin->role}\n\n";
            
        } catch (\Exception $e) {
            echo "   ✗ Error membuat admin: " . $e->getMessage() . "\n";
            
            // Coba dengan firstOrCreate
            try {
                echo "   Mencoba dengan firstOrCreate...\n";
                $admin = User::firstOrCreate(
                    ['email' => 'admin@pondok.test'],
                    [
                        'name' => 'Admin Pondok',
                        'username' => 'admin',
                        'password' => Hash::make('admin123'),
                        'role' => 'admin',
                        'tanggal_lahir' => null,
                    ]
                );
                echo "   ✓ Admin dibuat dengan firstOrCreate\n\n";
            } catch (\Exception $e2) {
                echo "   ✗ Error: " . $e2->getMessage() . "\n";
                exit(1);
            }
        }
    } else {
        echo "   ✓ Admin ditemukan!\n";
        echo "      ID: {$admin->id}\n";
        echo "      Email: {$admin->email}\n";
        echo "      Role: {$admin->role}\n\n";
    }
    
    // 4. Perbaiki admin yang sudah ada
    echo "4. Memperbaiki data admin...\n";
    
    $needsUpdate = false;
    
    // Pastikan role adalah 'admin'
    if (strtolower(trim($admin->role ?? '')) !== 'admin') {
        echo "   ⚠ Role tidak benar: '{$admin->role}', memperbaiki...\n";
        $admin->role = 'admin';
        $needsUpdate = true;
    }
    
    // Pastikan password benar
    $passwordCheck = Hash::check('admin123', $admin->password);
    if (!$passwordCheck) {
        echo "   ⚠ Password tidak sesuai, memperbaiki...\n";
        $admin->password = Hash::make('admin123');
        $needsUpdate = true;
    }
    
    // Pastikan username ada
    if (empty($admin->username)) {
        echo "   ⚠ Username kosong, menambahkan...\n";
        $admin->username = 'admin';
        $needsUpdate = true;
    }
    
    // Pastikan name ada
    if (empty($admin->name)) {
        echo "   ⚠ Name kosong, menambahkan...\n";
        $admin->name = 'Admin Pondok';
        $needsUpdate = true;
    }
    
    if ($needsUpdate) {
        $admin->save();
        echo "   ✓ Data admin diperbaiki\n\n";
    } else {
        echo "   ✓ Data admin sudah benar\n\n";
    }
    
    // 5. Verifikasi login bisa dilakukan
    echo "5. Memverifikasi login bisa dilakukan...\n";
    
    $testCredentials = [
        'email' => 'admin@pondok.test',
        'password' => 'admin123'
    ];
    
    // Cek apakah user bisa ditemukan dengan email
    $userFound = User::where('email', 'admin@pondok.test')->first();
    if (!$userFound) {
        echo "   ✗ User tidak ditemukan dengan email 'admin@pondok.test'\n";
        exit(1);
    }
    
    // Cek password
    $passwordValid = Hash::check('admin123', $userFound->password);
    if (!$passwordValid) {
        echo "   ✗ Password tidak valid\n";
        echo "   Memperbaiki password...\n";
        $userFound->password = Hash::make('admin123');
        $userFound->save();
        echo "   ✓ Password diperbaiki\n";
    }
    
    // Cek role
    $roleValid = strtolower(trim($userFound->role ?? '')) === 'admin';
    if (!$roleValid) {
        echo "   ✗ Role tidak valid: '{$userFound->role}'\n";
        echo "   Memperbaiki role...\n";
        $userFound->role = 'admin';
        $userFound->save();
        echo "   ✓ Role diperbaiki\n";
    }
    
    echo "   ✓ Login bisa dilakukan\n\n";
    
    // 6. Tampilkan informasi final
    $admin->refresh();
    
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                    HASIL PERBAIKAN                          ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    echo "Admin sudah siap digunakan!\n\n";
    echo "Informasi Admin:\n";
    echo "  ID: {$admin->id}\n";
    echo "  Name: {$admin->name}\n";
    echo "  Email: {$admin->email}\n";
    echo "  Username: {$admin->username}\n";
    echo "  Role: {$admin->role}\n";
    echo "  Password: admin123 (verified)\n\n";
    
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                  KREDENSIAL LOGIN                           ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    echo "Email: admin@pondok.test\n";
    echo "Password: admin123\n\n";
    
    echo "✓ Login sudah diperbaiki!\n";
    echo "\nLangkah selanjutnya:\n";
    echo "1. Refresh halaman login (Ctrl + F5)\n";
    echo "2. Login dengan kredensial di atas\n";
    echo "3. Jika masih error, hapus cookie browser dan coba lagi\n\n";
    
} catch (\Exception $e) {
    echo "\n❌ FATAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

