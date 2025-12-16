<?php
/**
 * Script untuk memperbaiki semua masalah aplikasi:
 * 1. Membersihkan session dan cache
 * 2. Memperbaiki role user di database
 * 3. Memastikan data konsisten
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== MEMPERBAIKI MASALAH APLIKASI ===\n\n";

// 1. Clear session files
echo "1. Membersihkan session files...\n";
$sessionPath = storage_path('framework/sessions');
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/*');
    $deleted = 0;
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== '.gitignore') {
            unlink($file);
            $deleted++;
        }
    }
    echo "   ✓ Dihapus {$deleted} file session\n";
} else {
    echo "   ✓ Folder session tidak ditemukan\n";
}

// 2. Clear cache
echo "\n2. Membersihkan cache aplikasi...\n";
try {
    \Artisan::call('config:clear');
    echo "   ✓ Config cache cleared\n";
    
    \Artisan::call('cache:clear');
    echo "   ✓ Application cache cleared\n";
    
    \Artisan::call('route:clear');
    echo "   ✓ Route cache cleared\n";
    
    \Artisan::call('view:clear');
    echo "   ✓ View cache cleared\n";
} catch (\Exception $e) {
    echo "   ⚠ Error: " . $e->getMessage() . "\n";
}

// 3. Fix user roles
echo "\n3. Memperbaiki role user di database...\n";
try {
    $users = User::all();
    $fixed = 0;
    
    foreach ($users as $user) {
        $originalRole = $user->role;
        $normalizedRole = strtolower(trim($user->role ?? ''));
        
        // Jika role kosong atau tidak valid, set default berdasarkan email/username
        if (empty($normalizedRole) || !in_array($normalizedRole, ['admin', 'santri'])) {
            // Jika user punya email, kemungkinan admin
            if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                $newRole = 'admin';
            } else {
                // Jika tidak punya email atau username, default ke santri
                $newRole = 'santri';
            }
            
            $user->role = $newRole;
            $user->save();
            echo "   ✓ User ID {$user->id} ({$user->name}): '{$originalRole}' → '{$newRole}'\n";
            $fixed++;
        } else {
            // Normalisasi role (pastikan lowercase, no whitespace)
            if ($normalizedRole !== $originalRole) {
                $user->role = $normalizedRole;
                $user->save();
                echo "   ✓ User ID {$user->id} ({$user->name}): Normalisasi '{$originalRole}' → '{$normalizedRole}'\n";
                $fixed++;
            }
        }
    }
    
    if ($fixed === 0) {
        echo "   ✓ Semua role user sudah valid\n";
    } else {
        echo "   ✓ Total {$fixed} user diperbaiki\n";
    }
} catch (\Exception $e) {
    echo "   ⚠ Error: " . $e->getMessage() . "\n";
}

// 4. Verify admin user exists
echo "\n4. Memverifikasi user admin...\n";
try {
    $admin = User::where('role', 'admin')->first();
    if ($admin) {
        echo "   ✓ Admin ditemukan: {$admin->email} (ID: {$admin->id})\n";
    } else {
        echo "   ⚠ Admin tidak ditemukan! Membuat admin default...\n";
        $admin = User::create([
            'name' => 'Admin Pondok',
            'email' => 'admin@pondok.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        echo "   ✓ Admin default dibuat: admin@pondok.com / admin123\n";
    }
} catch (\Exception $e) {
    echo "   ⚠ Error: " . $e->getMessage() . "\n";
}

// 5. Verify santri users
echo "\n5. Memverifikasi user santri...\n";
try {
    $santriCount = User::where('role', 'santri')->count();
    echo "   ✓ Total {$santriCount} santri ditemukan\n";
} catch (\Exception $e) {
    echo "   ⚠ Error: " . $e->getMessage() . "\n";
}

echo "\n=== SELESAI ===\n";
echo "\nLangkah selanjutnya:\n";
echo "1. Hapus cookie browser untuk 127.0.0.1:8000\n";
echo "2. Restart server: php artisan serve\n";
echo "3. Akses http://127.0.0.1:8000\n";
echo "\nDefault credentials:\n";
echo "- Admin: admin@pondok.com / admin123\n";
echo "- Santri: (username) / (tanggal_lahir format YYYY-MM-DD)\n";

