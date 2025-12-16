<?php

/**
 * Script Cepat: Buat Admin Sekarang
 * 
 * Script ini akan membuat admin dengan email admin@pondok.test
 * jika belum ada di database
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║          MEMBUAT ADMIN - admin@pondok.test                  ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

try {
    // Cek apakah admin sudah ada
    $admin = User::where('email', 'admin@pondok.test')->first();
    
    if ($admin) {
        echo "✓ Admin sudah ada di database!\n\n";
        echo "Informasi Admin:\n";
        echo "  ID: {$admin->id}\n";
        echo "  Name: {$admin->name}\n";
        echo "  Email: {$admin->email}\n";
        echo "  Role: {$admin->role}\n";
        echo "  Username: " . ($admin->username ?? 'Tidak ada') . "\n\n";
        
        // Perbaiki jika role tidak benar
        if (strtolower(trim($admin->role)) !== 'admin') {
            echo "⚠ Role tidak benar, memperbaiki...\n";
            $admin->role = 'admin';
            $admin->save();
            echo "✓ Role diperbaiki ke 'admin'\n\n";
        }
        
        // Reset password ke admin123
        echo "⚠ Reset password ke 'admin123'...\n";
        $admin->password = Hash::make('admin123');
        $admin->save();
        echo "✓ Password direset ke 'admin123'\n\n";
        
    } else {
        echo "⚠ Admin tidak ditemukan, membuat admin baru...\n\n";
        
        // Buat admin baru
        $admin = User::create([
            'name' => 'Admin Pondok',
            'email' => 'admin@pondok.test',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'tanggal_lahir' => null,
        ]);
        
        echo "✓ Admin berhasil dibuat!\n\n";
        echo "Informasi Admin:\n";
        echo "  ID: {$admin->id}\n";
        echo "  Name: {$admin->name}\n";
        echo "  Email: {$admin->email}\n";
        echo "  Role: {$admin->role}\n";
        echo "  Username: {$admin->username}\n\n";
    }
    
    // Verifikasi password
    $passwordCheck = Hash::check('admin123', $admin->password);
    if ($passwordCheck) {
        echo "✓ Password verified: admin123\n\n";
    } else {
        echo "⚠ Password verification failed, resetting...\n";
        $admin->password = Hash::make('admin123');
        $admin->save();
        echo "✓ Password direset\n\n";
    }
    
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                    KREDENSIAL LOGIN                         ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    echo "Email: admin@pondok.test\n";
    echo "Password: admin123\n\n";
    
    echo "✓ Admin siap digunakan!\n";
    echo "\nSilakan coba login lagi dengan kredensial di atas.\n\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    
    // Cek apakah tabel users ada
    try {
        $tableExists = \Illuminate\Support\Facades\Schema::hasTable('users');
        if (!$tableExists) {
            echo "\n⚠ Tabel 'users' tidak ditemukan!\n";
            echo "Jalankan migration terlebih dahulu:\n";
            echo "  php artisan migrate\n";
        }
    } catch (\Exception $e2) {
        echo "\n⚠ Tidak bisa mengecek tabel: " . $e2->getMessage() . "\n";
    }
    
    exit(1);
}

