<?php
/**
 * Script untuk test dan verifikasi akses dashboard santri
 */

echo "=== TEST DASHBOARD SANTRI ===\n\n";

try {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // 1. Cek route santri.dashboard
    echo "1. Cek route santri.dashboard...\n";
    try {
        $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName('santri.dashboard');
        if ($route) {
            echo "   ✅ Route 'santri.dashboard' ditemukan\n";
            echo "   ✅ URL: " . $route->uri() . "\n";
            echo "   ✅ Method: " . implode(', ', $route->methods()) . "\n";
            
            // Cek middleware
            $middleware = $route->middleware();
            echo "   ✅ Middleware: " . implode(', ', $middleware) . "\n";
            
            if (in_array('auth', $middleware) && in_array('role:santri', $middleware)) {
                echo "   ✅ Middleware sudah benar (auth, role:santri)\n";
            } else {
                echo "   ⚠️  Middleware mungkin tidak lengkap\n";
            }
        } else {
            echo "   ❌ Route 'santri.dashboard' tidak ditemukan\n";
        }
    } catch (\Exception $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n";
    }
    
    // 2. Cek user santri di database
    echo "\n2. Cek user santri di database...\n";
    $santriUsers = \App\Models\User::whereRaw('LOWER(TRIM(role)) = ?', ['santri'])->get();
    
    if ($santriUsers->count() > 0) {
        echo "   ✅ Ditemukan {$santriUsers->count()} user santri\n";
        foreach ($santriUsers->take(5) as $santri) {
            $role = strtolower(trim($santri->role ?? ''));
            $hasDetail = $santri->santriDetail ? 'Yes' : 'No';
            echo "   - ID: {$santri->id}, Username: '{$santri->username}', Role: '{$santri->role}' (normalized: '{$role}'), Has Detail: {$hasDetail}\n";
        }
    } else {
        echo "   ⚠️  Tidak ada user santri ditemukan\n";
    }
    
    // 3. Cek view santri.dashboard
    echo "\n3. Cek view santri.dashboard...\n";
    $viewPath = __DIR__ . '/resources/views/santri/dashboard.blade.php';
    if (file_exists($viewPath)) {
        echo "   ✅ View 'santri/dashboard.blade.php' ditemukan\n";
        $content = file_get_contents($viewPath);
        
        // Cek apakah menggunakan layout
        if (strpos($content, "@extends('layouts.app')") !== false) {
            echo "   ✅ Menggunakan layout 'layouts.app'\n";
        } else {
            echo "   ⚠️  Tidak menggunakan layout 'layouts.app'\n";
        }
    } else {
        echo "   ❌ View 'santri/dashboard.blade.php' tidak ditemukan\n";
    }
    
    // 4. Cek middleware EnsureUserRole
    echo "\n4. Cek middleware EnsureUserRole...\n";
    $middlewareFile = __DIR__ . '/app/Http/Middleware/EnsureUserRole.php';
    if (file_exists($middlewareFile)) {
        $content = file_get_contents($middlewareFile);
        
        // Cek apakah ada refresh user
        if (strpos($content, '$user->refresh()') !== false) {
            echo "   ✅ Middleware melakukan refresh user dari database\n";
        } else {
            echo "   ⚠️  Middleware tidak melakukan refresh user\n";
        }
        
        // Cek apakah ada validasi role santri
        if (strpos($content, "userRole === 'santri'") !== false) {
            echo "   ✅ Middleware memvalidasi role 'santri'\n";
        } else {
            echo "   ⚠️  Middleware mungkin tidak memvalidasi role 'santri'\n";
        }
        
        // Cek apakah ada pencegahan redirect loop
        if (strpos($content, '$currentRoute === \'santri.dashboard\'') !== false &&
            strpos($content, 'return $next($request)') !== false) {
            echo "   ✅ Middleware mencegah redirect loop\n";
        } else {
            echo "   ⚠️  Middleware mungkin tidak mencegah redirect loop\n";
        }
    } else {
        echo "   ❌ File EnsureUserRole.php tidak ditemukan\n";
    }
    
    // 5. Cek AuthController
    echo "\n5. Cek AuthController...\n";
    $controllerFile = __DIR__ . '/app/Http/Controllers/AuthController.php';
    if (file_exists($controllerFile)) {
        $content = file_get_contents($controllerFile);
        
        // Cek apakah ada refresh user setelah login
        if (strpos($content, '$user->refresh()') !== false || strpos($content, '$loggedInUser->refresh()') !== false) {
            echo "   ✅ AuthController melakukan refresh user setelah login\n";
        } else {
            echo "   ⚠️  AuthController tidak melakukan refresh user\n";
        }
        
        // Cek redirect ke santri.dashboard
        if (strpos($content, "redirect()->route('santri.dashboard')") !== false) {
            echo "   ✅ AuthController redirect ke santri.dashboard setelah login\n";
        } else {
            echo "   ⚠️  AuthController mungkin tidak redirect ke santri.dashboard\n";
        }
    } else {
        echo "   ❌ File AuthController.php tidak ditemukan\n";
    }
    
    // 6. Instruksi
    echo "\n=== INSTRUKSI ===\n";
    echo "Jika dashboard santri masih tidak bisa diakses:\n\n";
    echo "1. Clear session dan cookie:\n";
    echo "   del storage\\framework\\sessions\\*.*\n";
    echo "   F12 → Application → Cookies → Clear All\n\n";
    echo "2. Clear cache:\n";
    echo "   php artisan config:clear\n";
    echo "   php artisan cache:clear\n";
    echo "   php artisan route:clear\n";
    echo "   php artisan view:clear\n\n";
    echo "3. Restart server:\n";
    echo "   Ctrl+C → php artisan serve\n\n";
    echo "4. Test login:\n";
    echo "   - Buka browser baru/incognito\n";
    echo "   - Login sebagai santri\n";
    echo "   - Seharusnya redirect ke /santri/dashboard\n\n";
    echo "5. Cek log Laravel:\n";
    echo "   notepad storage\\logs\\laravel.log\n";
    echo "   Cari: 'Santri login successful' atau 'Role mismatch'\n\n";
    echo "=== SELESAI ===\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

