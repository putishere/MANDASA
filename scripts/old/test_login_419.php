<?php
/**
 * Script untuk test login dan cek apakah masih ada error 419
 * Jalankan script ini untuk memverifikasi konfigurasi login
 */

echo "=== TEST LOGIN DAN CEK ERROR 419 ===\n\n";

// 1. Cek file routes
echo "1. Cek routes/web.php...\n";
$routesFile = __DIR__ . '/routes/web.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    // Cek apakah POST /login tidak menggunakan middleware guest
    if (preg_match('/Route::post\([\'"]\/login[\'"]/s', $content)) {
        // Cek apakah ada di dalam middleware guest
        $lines = explode("\n", $content);
        $inGuestGroup = false;
        $postLoginFound = false;
        
        foreach ($lines as $lineNum => $line) {
            if (preg_match('/Route::middleware\([\'"]guest[\'"]\)->group/', $line)) {
                $inGuestGroup = true;
            }
            if (preg_match('/Route::post\([\'"]\/login[\'"]/', $line)) {
                $postLoginFound = true;
                if ($inGuestGroup) {
                    echo "   ❌ Route POST /login masih di dalam middleware guest (baris " . ($lineNum + 1) . ")\n";
                    echo "   ⚠️  Ini menyebabkan error 419!\n";
                } else {
                    echo "   ✅ Route POST /login sudah benar (tidak di dalam middleware guest)\n";
                }
            }
            if (preg_match('/}\);/', $line) && $inGuestGroup) {
                $inGuestGroup = false;
            }
        }
        
        if (!$postLoginFound) {
            echo "   ⚠️  Route POST /login tidak ditemukan\n";
        }
    } else {
        echo "   ❌ Route POST /login tidak ditemukan\n";
    }
} else {
    echo "   ❌ File routes/web.php tidak ditemukan\n";
}

// 2. Cek AuthController
echo "\n2. Cek AuthController...\n";
$controllerFile = __DIR__ . '/app/Http/Controllers/AuthController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    // Cek regenerateToken untuk admin
    if (strpos($content, '$request->session()->regenerateToken()') !== false) {
        echo "   ✅ regenerateToken() ditemukan\n";
    } else {
        echo "   ⚠️  regenerateToken() tidak ditemukan\n";
    }
    
    // Cek intended() untuk redirect
    if (strpos($content, 'redirect()->intended') !== false) {
        echo "   ✅ redirect()->intended() ditemukan\n";
    } else {
        echo "   ⚠️  redirect()->intended() tidak ditemukan\n";
    }
    
    // Cek save() untuk santri
    if (strpos($content, '$request->session()->save()') !== false) {
        echo "   ✅ session()->save() ditemukan\n";
    } else {
        echo "   ⚠️  session()->save() tidak ditemukan\n";
    }
} else {
    echo "   ❌ File AuthController.php tidak ditemukan\n";
}

// 3. Cek login form
echo "\n3. Cek login form...\n";
$formFile = __DIR__ . '/resources/views/Auth/login.blade.php';
if (file_exists($formFile)) {
    $content = file_get_contents($formFile);
    
    // Cek @csrf
    if (strpos($content, '@csrf') !== false) {
        echo "   ✅ @csrf ditemukan di form\n";
    } else {
        echo "   ❌ @csrf tidak ditemukan di form\n";
    }
    
    // Cek meta csrf-token
    if (strpos($content, 'meta name="csrf-token"') !== false) {
        echo "   ✅ Meta csrf-token ditemukan\n";
    } else {
        echo "   ⚠️  Meta csrf-token tidak ditemukan\n";
    }
    
    // Cek JavaScript update token
    if (strpos($content, 'formToken.value = metaToken.getAttribute') !== false) {
        echo "   ✅ JavaScript update token ditemukan\n";
    } else {
        echo "   ⚠️  JavaScript update token tidak ditemukan\n";
    }
} else {
    echo "   ❌ File login.blade.php tidak ditemukan\n";
}

// 4. Instruksi
echo "\n=== INSTRUKSI ===\n";
echo "Jika masih ada error 419:\n\n";
echo "1. Clear session:\n";
echo "   del storage\\framework\\sessions\\*.*\n\n";
echo "2. Clear cache:\n";
echo "   php artisan config:clear\n";
echo "   php artisan cache:clear\n";
echo "   php artisan route:clear\n";
echo "   php artisan view:clear\n\n";
echo "3. Hapus cookie browser:\n";
echo "   F12 → Application → Cookies → http://127.0.0.1:8000 → Clear All\n\n";
echo "4. Restart server:\n";
echo "   Ctrl+C → php artisan serve\n\n";
echo "5. Test dengan browser baru/incognito\n\n";
echo "=== SELESAI ===\n";

