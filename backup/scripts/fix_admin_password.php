<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Set output buffering untuk memastikan output muncul
ob_start();

echo "=== PERBAIKI PASSWORD ADMIN ===\n\n";

try {
    // Cari admin
    $admin = User::where('email', 'admin@pondok.test')->first();

    if (!$admin) {
        echo "❌ Admin tidak ditemukan!\n";
        echo "Membuat admin baru...\n";
        
        $admin = User::create([
            'name' => 'Admin Pondok',
            'email' => 'admin@pondok.test',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        
        echo "✅ Admin berhasil dibuat!\n";
        echo "   ID: {$admin->id}\n";
        echo "   Email: {$admin->email}\n";
    } else {
        echo "✅ Admin ditemukan: {$admin->email}\n";
        echo "   ID: {$admin->id}\n";
        echo "   Role saat ini: {$admin->role}\n";
        echo "Memperbaiki password...\n";
        
        // Perbaiki password
        $admin->password = Hash::make('admin123');
        $admin->role = 'admin'; // Pastikan role benar
        $admin->save();
        
        echo "✅ Password admin berhasil diperbaiki!\n";
        echo "   Role diperbarui menjadi: {$admin->role}\n";
    }

    echo "\n=== KREDENSIAL LOGIN ===\n";
    echo "Email: admin@pondok.test\n";
    echo "Password: admin123\n";
    echo "\n✅ Selesai! Silakan coba login lagi!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n";
}

// Flush output
ob_end_flush();

