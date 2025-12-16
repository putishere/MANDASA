<?php
/**
 * Script Setup Lengkap untuk Aplikasi Managemen Data Santri
 * 
 * Script ini akan:
 * 1. Membuat file .env jika belum ada
 * 2. Generate application key jika belum ada
 * 3. Memastikan folder storage dan cache writable
 * 4. Clear semua cache
 * 5. Memperbaiki role user di database
 * 6. Membuat admin default jika belum ada
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     SETUP APLIKASI MANAGEMEN DATA SANTRI - PP HS AL-FAKKAR   ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$errors = [];
$warnings = [];

// 1. Cek file .env
echo "1. Memeriksa file .env...\n";
$envPath = __DIR__ . '/.env';
$envExamplePath = __DIR__ . '/.env.example';

if (!file_exists($envPath)) {
    echo "   ⚠ File .env tidak ditemukan!\n";
    
    if (file_exists($envExamplePath)) {
        echo "   → Menyalin dari .env.example...\n";
        copy($envExamplePath, $envPath);
        echo "   ✓ File .env dibuat dari .env.example\n";
    } else {
        echo "   → Membuat file .env baru...\n";
        $envContent = <<<'ENV'
APP_NAME="Managemen Data Santri"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://127.0.0.1:8000

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=managemen_data_santri
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
ENV;
        file_put_contents($envPath, $envContent);
        echo "   ✓ File .env dibuat\n";
    }
} else {
    echo "   ✓ File .env sudah ada\n";
}

// 2. Generate application key jika belum ada
echo "\n2. Memeriksa application key...\n";
try {
    $envContent = file_get_contents($envPath);
    if (strpos($envContent, 'APP_KEY=') !== false && 
        (strpos($envContent, 'APP_KEY=') === strpos($envContent, 'APP_KEY=') && 
         substr($envContent, strpos($envContent, 'APP_KEY=') + 8, 1) === "\n")) {
        echo "   ⚠ APP_KEY belum di-generate\n";
        echo "   → Generating application key...\n";
        Artisan::call('key:generate');
        echo "   ✓ Application key di-generate\n";
    } else {
        // Cek apakah key sudah ada
        preg_match('/APP_KEY=(.+)/', $envContent, $matches);
        if (empty($matches[1]) || trim($matches[1]) === '') {
            echo "   ⚠ APP_KEY kosong\n";
            echo "   → Generating application key...\n";
            Artisan::call('key:generate');
            echo "   ✓ Application key di-generate\n";
        } else {
            echo "   ✓ Application key sudah ada\n";
        }
    }
} catch (\Exception $e) {
    $errors[] = "Error generate key: " . $e->getMessage();
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// 3. Memastikan folder storage dan cache writable
echo "\n3. Memeriksa permission folder storage dan cache...\n";
$folders = [
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];

foreach ($folders as $folder) {
    $path = __DIR__ . '/' . $folder;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
        echo "   ✓ Folder dibuat: {$folder}\n";
    } else {
        echo "   ✓ Folder ada: {$folder}\n";
    }
}

// 4. Clear session files
echo "\n4. Membersihkan session files...\n";
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
    echo "   ✓ Folder session tidak ditemukan (akan dibuat)\n";
}

// 5. Clear cache
echo "\n5. Membersihkan cache aplikasi...\n";
try {
    Artisan::call('config:clear');
    echo "   ✓ Config cache cleared\n";
    
    Artisan::call('cache:clear');
    echo "   ✓ Application cache cleared\n";
    
    Artisan::call('route:clear');
    echo "   ✓ Route cache cleared\n";
    
    Artisan::call('view:clear');
    echo "   ✓ View cache cleared\n";
    
    Artisan::call('optimize:clear');
    echo "   ✓ Optimize cache cleared\n";
} catch (\Exception $e) {
    $warnings[] = "Error clear cache: " . $e->getMessage();
    echo "   ⚠ Warning: " . $e->getMessage() . "\n";
}

// 6. Cek koneksi database dan migrate jika perlu
echo "\n6. Memeriksa koneksi database...\n";
try {
    DB::connection()->getPdo();
    echo "   ✓ Koneksi database berhasil\n";
    
    // Cek apakah tabel users ada
    $tables = DB::select('SHOW TABLES');
    $tableNames = array_map(function($table) {
        return array_values((array)$table)[0];
    }, $tables);
    
    if (!in_array('users', $tableNames)) {
        echo "   ⚠ Tabel users tidak ditemukan\n";
        echo "   → Menjalankan migrasi...\n";
        try {
            Artisan::call('migrate', ['--force' => true]);
            echo "   ✓ Migrasi berhasil\n";
        } catch (\Exception $e) {
            $errors[] = "Error migrate: " . $e->getMessage();
            echo "   ✗ Error: " . $e->getMessage() . "\n";
            echo "   → Silakan jalankan manual: php artisan migrate\n";
        }
    } else {
        echo "   ✓ Tabel users sudah ada\n";
    }
} catch (\Exception $e) {
    $warnings[] = "Database tidak tersedia: " . $e->getMessage();
    echo "   ⚠ Warning: Database tidak tersedia - " . $e->getMessage() . "\n";
    echo "   → Pastikan database sudah dibuat dan konfigurasi di .env benar\n";
}

// 7. Fix user roles dan buat admin default
echo "\n7. Memperbaiki role user dan membuat admin default...\n";
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
    
    // Buat admin default jika belum ada
    $admin = User::where('role', 'admin')->first();
    if (!$admin) {
        echo "   ⚠ Admin tidak ditemukan\n";
        echo "   → Membuat admin default...\n";
        
        // Cek apakah email admin@pondok.test sudah ada
        $existingAdmin = User::where('email', 'admin@pondok.test')->first();
        if ($existingAdmin) {
            $existingAdmin->role = 'admin';
            $existingAdmin->save();
            echo "   ✓ User dengan email admin@pondok.test diperbaiki menjadi admin\n";
        } else {
            $admin = User::firstOrCreate(
                ['email' => 'admin@pondok.test'],
                [
                    'name' => 'Admin Pondok',
                    'username' => 'admin',
                    'tanggal_lahir' => null,
                    'password' => Hash::make('admin123'),
                    'role' => 'admin',
                ]
            );
            echo "   ✓ Admin default dibuat: admin@pondok.test / admin123\n";
        }
    } else {
        echo "   ✓ Admin sudah ada: {$admin->email}\n";
    }
} catch (\Exception $e) {
    $warnings[] = "Error fix users: " . $e->getMessage();
    echo "   ⚠ Warning: " . $e->getMessage() . "\n";
}

// 8. Buat storage link
echo "\n8. Membuat storage link...\n";
try {
    $linkPath = public_path('storage');
    if (!file_exists($linkPath)) {
        Artisan::call('storage:link');
        echo "   ✓ Storage link dibuat\n";
    } else {
        echo "   ✓ Storage link sudah ada\n";
    }
} catch (\Exception $e) {
    $warnings[] = "Error storage link: " . $e->getMessage();
    echo "   ⚠ Warning: " . $e->getMessage() . "\n";
    echo "   → Silakan jalankan manual: php artisan storage:link\n";
}

// Summary
echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║                        RINGKASAN SETUP                       ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

if (empty($errors)) {
    echo "✓ Setup berhasil diselesaikan!\n\n";
} else {
    echo "⚠ Setup selesai dengan beberapa error:\n";
    foreach ($errors as $error) {
        echo "  - {$error}\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠ Warning:\n";
    foreach ($warnings as $warning) {
        echo "  - {$warning}\n";
    }
    echo "\n";
}

echo "Langkah selanjutnya:\n";
echo "1. Pastikan database sudah dibuat dan konfigurasi di .env benar\n";
echo "2. Jika belum migrate, jalankan: php artisan migrate\n";
echo "3. Jika belum seed, jalankan: php artisan db:seed\n";
echo "4. Hapus cookie browser untuk 127.0.0.1:8000 (jika ada masalah redirect loop)\n";
echo "5. Restart server: php artisan serve\n";
echo "6. Akses http://127.0.0.1:8000\n\n";

echo "Default credentials:\n";
echo "- Admin: admin@pondok.test / admin123\n";
echo "- Santri: (username) / (tanggal_lahir format YYYY-MM-DD)\n\n";

echo "Jika ada masalah:\n";
echo "- Lihat dokumentasi di folder root\n";
echo "- Cek log di storage/logs/laravel.log\n";
echo "- Jalankan: php fix_all_issues.php\n\n";

