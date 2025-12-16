<?php

/**
 * Script untuk Force Logout Semua User dan Clear Session
 * 
 * Script ini akan:
 * 1. Menghapus semua session files
 * 2. Clear cache Laravel
 * 3. Memastikan semua user ter-logout
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

echo "=" . str_repeat("=", 70) . "=\n";
echo "  FORCE LOGOUT SEMUA USER DAN CLEAR SESSION\n";
echo "=" . str_repeat("=", 70) . "=\n\n";

try {
    // 1. Clear session files
    echo "1. MENGHAPUS SESSION FILES\n";
    echo str_repeat("-", 72) . "\n";
    
    $sessionPath = storage_path('framework/sessions');
    $deletedCount = 0;
    
    if (File::exists($sessionPath)) {
        $files = File::files($sessionPath);
        foreach ($files as $file) {
            if ($file->getFilename() !== '.gitignore') {
                File::delete($file);
                $deletedCount++;
            }
        }
        echo "   ✅ Menghapus {$deletedCount} file session\n";
    } else {
        echo "   ⚠️  Folder sessions tidak ditemukan\n";
    }
    
    // 2. Clear cache
    echo "\n2. CLEAR CACHE LARAVEL\n";
    echo str_repeat("-", 72) . "\n";
    
    try {
        Artisan::call('config:clear');
        echo "   ✅ Config cache cleared\n";
    } catch (\Exception $e) {
        echo "   ⚠️  Error clearing config cache: " . $e->getMessage() . "\n";
    }
    
    try {
        Artisan::call('cache:clear');
        echo "   ✅ Application cache cleared\n";
    } catch (\Exception $e) {
        echo "   ⚠️  Error clearing cache: " . $e->getMessage() . "\n";
    }
    
    try {
        Artisan::call('route:clear');
        echo "   ✅ Route cache cleared\n";
    } catch (\Exception $e) {
        echo "   ⚠️  Error clearing route cache: " . $e->getMessage() . "\n";
    }
    
    try {
        Artisan::call('view:clear');
        echo "   ✅ View cache cleared\n";
    } catch (\Exception $e) {
        echo "   ⚠️  Error clearing view cache: " . $e->getMessage() . "\n";
    }
    
    // 3. Clear database sessions jika menggunakan database session
    echo "\n3. CLEAR DATABASE SESSIONS (jika menggunakan database)\n";
    echo str_repeat("-", 72) . "\n";
    
    try {
        \Illuminate\Support\Facades\DB::table('sessions')->truncate();
        echo "   ✅ Database sessions cleared\n";
    } catch (\Exception $e) {
        echo "   ℹ️  Tidak menggunakan database sessions atau tabel tidak ada\n";
    }
    
    // 4. Kesimpulan
    echo "\n" . str_repeat("-", 72) . "\n";
    echo "✅ SELESAI!\n\n";
    echo "Langkah selanjutnya:\n";
    echo "1. Hapus cookie browser (F12 → Application → Cookies → Clear All)\n";
    echo "2. Atau gunakan browser incognito/private window\n";
    echo "3. Akses aplikasi: http://127.0.0.1:8000\n";
    echo "4. Seharusnya menampilkan halaman LOGIN, bukan dashboard\n\n";
    
} catch (\Exception $e) {
    echo "\n   ❌ ERROR: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n";
    echo "\n";
}

echo "=" . str_repeat("=", 70) . "=\n";

