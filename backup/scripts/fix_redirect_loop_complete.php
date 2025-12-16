<?php
/**
 * Script Lengkap untuk Memperbaiki Redirect Loop
 * Menghapus session, clear cache, dan memperbaiki role user
 */

echo "=== PERBAIKAN REDIRECT LOOP LENGKAP ===\n\n";

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

// 2. Clear cache Laravel (jika bisa)
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

// 3. Perbaiki role user di database
echo "\n3. Memperbaiki role user di database...\n";
try {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    $users = \App\Models\User::all();
    $fixed = 0;
    
    foreach ($users as $user) {
        $originalRole = $user->role;
        $normalizedRole = strtolower(trim($user->role ?? ''));
        
        // Jika role kosong atau tidak valid, perbaiki
        if (empty($normalizedRole) || !in_array($normalizedRole, ['admin', 'santri'])) {
            if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                $user->role = 'admin';
            } else {
                $user->role = 'santri';
            }
            $user->save();
            $fixed++;
            echo "   ✅ User ID {$user->id}: '{$originalRole}' → '{$user->role}'\n";
        } elseif ($normalizedRole !== $user->role) {
            // Normalisasi role jika ada spasi atau case berbeda
            $user->role = $normalizedRole;
            $user->save();
            $fixed++;
            echo "   ✅ User ID {$user->id}: '{$originalRole}' → '{$user->role}' (normalisasi)\n";
        }
    }
    
    if ($fixed === 0) {
        echo "   ✅ Semua role user sudah benar\n";
    } else {
        echo "   ✅ Diperbaiki {$fixed} user\n";
    }
} catch (\Exception $e) {
    echo "   ⚠️  Gagal memperbaiki role: " . $e->getMessage() . "\n";
    echo "   ⚠️  Jalankan manual dengan tinker:\n";
    echo "      php artisan tinker\n";
    echo "      User::all()->each(function(\$u) { \$u->role = strtolower(trim(\$u->role ?? '')); if(empty(\$u->role)) \$u->role = \$u->email ? 'admin' : 'santri'; \$u->save(); });\n";
}

// 4. Verifikasi middleware
echo "\n4. Verifikasi middleware...\n";
$middlewareFile = __DIR__ . '/app/Http/Middleware/EnsureUserRole.php';
if (file_exists($middlewareFile)) {
    $content = file_get_contents($middlewareFile);
    
    // Cek apakah ada return $next($request) untuk mencegah loop
    if (strpos($content, 'if ($currentRoute === \'santri.dashboard\')') !== false &&
        strpos($content, 'return $next($request)') !== false) {
        echo "   ✅ Middleware EnsureUserRole sudah diperbaiki (mencegah redirect loop)\n";
    } else {
        echo "   ⚠️  Middleware EnsureUserRole mungkin perlu diperbaiki\n";
    }
} else {
    echo "   ❌ File EnsureUserRole.php tidak ditemukan\n";
}

// 5. Instruksi
echo "\n=== INSTRUKSI SELANJUTNYA ===\n";
echo "1. Hapus cookie browser:\n";
echo "   - Klik tombol 'Hapus cookie' di halaman error\n";
echo "   - Atau F12 → Application → Cookies → http://127.0.0.1:8000 → Clear All\n\n";
echo "2. Restart server:\n";
echo "   - Tekan Ctrl+C untuk stop server\n";
echo "   - Jalankan: php artisan serve\n\n";
echo "3. Test login:\n";
echo "   - Buka browser baru atau incognito\n";
echo "   - Akses: http://127.0.0.1:8000/login\n";
echo "   - Login sebagai admin atau santri\n";
echo "   - Seharusnya redirect ke dashboard tanpa error\n\n";
echo "=== SELESAI ===\n";

