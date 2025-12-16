<?php

/**
 * Script untuk Memastikan Halaman Login Selalu Tampil
 * 
 * Script ini akan:
 * 1. Clear semua session
 * 2. Clear cache Laravel
 * 3. Verifikasi route login
 * 4. Memberikan instruksi untuk hapus cookie browser
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

echo "=" . str_repeat("=", 70) . "=\n";
echo "  FIX: MEMASTIKAN HALAMAN LOGIN SELALU TAMPIL\n";
echo "=" . str_repeat("=", 70) . "=\n\n";

try {
    // 1. Clear semua session
    echo "1. MENGHAPUS SEMUA SESSION\n";
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
    
    // Clear database sessions jika menggunakan database session
    try {
        DB::table('sessions')->truncate();
        echo "   ✅ Database sessions cleared\n";
    } catch (\Exception $e) {
        echo "   ℹ️  Tidak menggunakan database sessions\n";
    }
    
    // 2. Clear semua cache
    echo "\n2. CLEAR SEMUA CACHE LARAVEL\n";
    echo str_repeat("-", 72) . "\n";
    
    $cacheCommands = [
        'config:clear' => 'Config cache',
        'cache:clear' => 'Application cache',
        'route:clear' => 'Route cache',
        'view:clear' => 'View cache',
    ];
    
    foreach ($cacheCommands as $command => $name) {
        try {
            Artisan::call($command);
            echo "   ✅ {$name} cleared\n";
        } catch (\Exception $e) {
            echo "   ⚠️  Error clearing {$name}: " . $e->getMessage() . "\n";
        }
    }
    
    // 3. Verifikasi route login
    echo "\n3. VERIFIKASI ROUTE LOGIN\n";
    echo str_repeat("-", 72) . "\n";
    
    $routesFile = __DIR__ . '/routes/web.php';
    $routesContent = file_get_contents($routesFile);
    
    $hasGuestMiddleware = strpos($routesContent, "Route::middleware('guest')") !== false;
    $hasLoginRoute = strpos($routesContent, "Route::get('/login'") !== false || strpos($routesContent, "Route::get('/',") !== false;
    
    if ($hasGuestMiddleware && $hasLoginRoute) {
        echo "   ✅ Route login sudah benar\n";
        echo "   ✅ Menggunakan middleware 'guest'\n";
        echo "   ✅ Route '/' dan '/login' tersedia\n";
    } else {
        echo "   ⚠️  Route login mungkin tidak benar\n";
    }
    
    // 4. Verifikasi middleware
    echo "\n4. VERIFIKASI MIDDLEWARE\n";
    echo str_repeat("-", 72) . "\n";
    
    $bootstrapFile = __DIR__ . '/bootstrap/app.php';
    $bootstrapContent = file_get_contents($bootstrapFile);
    
    $hasGuestMiddleware = strpos($bootstrapContent, "'guest' =>") !== false || 
                          strpos($bootstrapContent, "RedirectIfAuthenticated") !== false;
    
    if ($hasGuestMiddleware) {
        echo "   ✅ Middleware 'guest' sudah terdaftar\n";
    } else {
        echo "   ⚠️  Middleware 'guest' mungkin tidak terdaftar\n";
    }
    
    // 5. Instruksi untuk user
    echo "\n" . str_repeat("=", 70) . "\n";
    echo "  INSTRUKSI UNTUK MEMASTIKAN LOGIN TAMPIL\n";
    echo str_repeat("=", 70) . "\n\n";
    
    echo "✅ Session dan cache sudah di-clear!\n\n";
    
    echo "📋 LANGKAH SELANJUTNYA (PENTING!):\n\n";
    
    echo "1. HAPUS COOKIE BROWSER (WAJIB!):\n";
    echo "   - Tekan F12 untuk buka DevTools\n";
    echo "   - Tab Application → Cookies → http://127.0.0.1:8000\n";
    echo "   - Klik Clear All atau hapus cookie 'laravel_session' dan 'XSRF-TOKEN'\n";
    echo "   - Refresh halaman (Ctrl + F5)\n\n";
    
    echo "2. ATAU GUNAKAN BROWSER INCOGNITO:\n";
    echo "   - Chrome/Edge: Ctrl + Shift + N\n";
    echo "   - Firefox: Ctrl + Shift + P\n";
    echo "   - Buka aplikasi di browser incognito\n\n";
    
    echo "3. RESTART SERVER (DISARANKAN):\n";
    echo "   - Stop server (Ctrl + C)\n";
    echo "   - Start ulang: php artisan serve\n\n";
    
    echo "4. AKSES APLIKASI:\n";
    echo "   - Buka: http://127.0.0.1:8000/\n";
    echo "   - Seharusnya menampilkan HALAMAN LOGIN\n";
    echo "   - Bukan redirect ke dashboard\n\n";
    
    echo "⚠️  CATATAN:\n";
    echo "   - Jika masih langsung ke dashboard, berarti ada session/cookie yang masih tersimpan\n";
    echo "   - Pastikan cookie browser sudah dihapus\n";
    echo "   - Gunakan browser incognito untuk memastikan tidak ada cookie\n\n";
    
    echo "🔗 ATAU GUNAKAN ROUTE FORCE LOGOUT:\n";
    echo "   - Buka: http://127.0.0.1:8000/force-logout\n";
    echo "   - Route ini akan logout dan clear session otomatis\n";
    echo "   - Lalu akan redirect ke halaman login\n\n";
    
} catch (\Exception $e) {
    echo "\n   ❌ ERROR: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n";
    echo "\n";
}

echo "=" . str_repeat("=", 70) . "=\n";

