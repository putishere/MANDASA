<?php
/**
 * Script untuk memperbaiki masalah login
 * Menghapus session, clear cache, dan memastikan konfigurasi benar
 */

echo "=== PERBAIKAN LOGIN FINAL ===\n\n";

// 1. Hapus semua file session
echo "1. Menghapus file session...\n";
$sessionPath = __DIR__ . '/storage/framework/sessions';
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/*');
    $deleted = 0;
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== '.gitignore') {
            unlink($file);
            $deleted++;
        }
    }
    echo "   ✅ Dihapus {$deleted} file session\n";
} else {
    echo "   ⚠️  Folder session tidak ditemukan\n";
}

// 2. Clear cache (jika bisa menjalankan artisan)
echo "\n2. Clear cache Laravel...\n";
$commands = [
    'config:clear',
    'cache:clear',
    'route:clear',
    'view:clear'
];

foreach ($commands as $cmd) {
    $output = [];
    $return = 0;
    exec("php artisan {$cmd} 2>&1", $output, $return);
    if ($return === 0) {
        echo "   ✅ php artisan {$cmd}\n";
    } else {
        echo "   ⚠️  php artisan {$cmd} - Gagal (jalankan manual)\n";
    }
}

// 3. Verifikasi route
echo "\n3. Verifikasi route...\n";
$routesFile = __DIR__ . '/routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    // Cek apakah POST /login menggunakan middleware guest
    if (preg_match('/Route::middleware\([\'"]guest[\'"]\)->group.*?Route::post\([\'"]\/login[\'"]/', $content)) {
        echo "   ❌ Route POST /login masih menggunakan middleware guest!\n";
        echo "   ⚠️  Perlu diperbaiki di routes/web.php\n";
    } else {
        echo "   ✅ Route POST /login sudah benar (tidak menggunakan middleware guest)\n";
    }
    
    // Cek apakah GET /login menggunakan middleware guest
    if (preg_match('/Route::middleware\([\'"]guest[\'"]\)->group.*?Route::get\([\'"]\/login[\'"]/', $content)) {
        echo "   ✅ Route GET /login sudah benar (menggunakan middleware guest)\n";
    } else {
        echo "   ⚠️  Route GET /login mungkin tidak menggunakan middleware guest\n";
    }
} else {
    echo "   ❌ File routes/web.php tidak ditemukan\n";
}

// 4. Verifikasi AuthController
echo "\n4. Verifikasi AuthController...\n";
$controllerFile = __DIR__ . '/app/Http/Controllers/AuthController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    // Cek apakah ada method login
    if (strpos($content, 'public function login') !== false) {
        echo "   ✅ Method login() ditemukan\n";
    } else {
        echo "   ❌ Method login() tidak ditemukan\n";
    }
    
    // Cek apakah ada redirect setelah login
    if (strpos($content, 'redirect()->route(\'admin.dashboard\')') !== false) {
        echo "   ✅ Redirect ke admin.dashboard ditemukan\n";
    } else {
        echo "   ⚠️  Redirect ke admin.dashboard tidak ditemukan\n";
    }
    
    if (strpos($content, 'redirect()->route(\'santri.dashboard\')') !== false) {
        echo "   ✅ Redirect ke santri.dashboard ditemukan\n";
    } else {
        echo "   ⚠️  Redirect ke santri.dashboard tidak ditemukan\n";
    }
} else {
    echo "   ❌ File AuthController.php tidak ditemukan\n";
}

// 5. Instruksi
echo "\n=== INSTRUKSI SELANJUTNYA ===\n";
echo "1. Hapus cookie browser:\n";
echo "   - Tekan F12 → Application → Cookies → http://127.0.0.1:8000 → Clear All\n";
echo "   - Atau gunakan browser baru/incognito\n\n";
echo "2. Restart server:\n";
echo "   - Tekan Ctrl+C untuk stop server\n";
echo "   - Jalankan: php artisan serve\n\n";
echo "3. Test login:\n";
echo "   - Buka: http://127.0.0.1:8000/login\n";
echo "   - Login sebagai admin (email: admin@pondok.test, password: admin123)\n";
echo "   - Atau login sebagai santri (username dan tanggal lahir)\n\n";
echo "=== SELESAI ===\n";

