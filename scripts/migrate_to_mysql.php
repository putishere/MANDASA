<?php

/**
 * Script Migrasi Database dari SQLite ke MySQL
 * 
 * Script ini akan:
 * 1. Membaca data dari SQLite
 * 2. Membuat database MySQL (jika belum ada)
 * 3. Menjalankan migration
 * 4. Memindahkan data dari SQLite ke MySQL
 * 
 * PERINGATAN: Backup data SQLite terlebih dahulu!
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "========================================\n";
echo "  MIGRASI DATABASE SQLITE KE MYSQL\n";
echo "========================================\n\n";

try {
    // Step 1: Backup SQLite database
    echo "📦 Step 1: Membuat backup SQLite...\n";
    $sqlitePath = database_path('database.sqlite');
    $backupPath = database_path('database_backup_' . date('Y-m-d_His') . '.sqlite');
    
    if (file_exists($sqlitePath)) {
        copy($sqlitePath, $backupPath);
        echo "✅ Backup berhasil dibuat: " . basename($backupPath) . "\n\n";
    } else {
        echo "⚠️  File SQLite tidak ditemukan, lanjutkan tanpa backup...\n\n";
    }
    
    // Step 2: Cek koneksi MySQL
    echo "🔌 Step 2: Mengecek koneksi MySQL...\n";
    
    // Set connection ke MySQL untuk testing
    config(['database.default' => 'mysql']);
    
    try {
        DB::connection('mysql')->getPdo();
        echo "✅ Koneksi MySQL berhasil!\n\n";
    } catch (\Exception $e) {
        echo "❌ Error: Koneksi MySQL gagal!\n";
        echo "   Pastikan:\n";
        echo "   1. MySQL/MariaDB sudah running\n";
        echo "   2. Database sudah dibuat (lihat docs/guides/create_database.sql)\n";
        echo "   3. Konfigurasi di .env sudah benar\n";
        echo "   Error: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    // Step 3: Buat database jika belum ada
    echo "🗄️  Step 3: Membuat database MySQL...\n";
    $dbName = env('DB_DATABASE', 'managemen_data_santri');
    
    try {
        // Connect tanpa database untuk membuat database
        $pdo = new PDO(
            "mysql:host=" . env('DB_HOST', '127.0.0.1') . ";port=" . env('DB_PORT', '3306'),
            env('DB_USERNAME', 'root'),
            env('DB_PASSWORD', '')
        );
        
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Database '{$dbName}' siap digunakan!\n\n";
    } catch (\Exception $e) {
        echo "⚠️  Database mungkin sudah ada atau error: " . $e->getMessage() . "\n\n";
    }
    
    // Step 4: Jalankan migration di MySQL
    echo "🔄 Step 4: Menjalankan migration di MySQL...\n";
    config(['database.default' => 'mysql']);
    
    try {
        Artisan::call('migrate:fresh', ['--force' => true]);
        echo "✅ Migration berhasil dijalankan!\n\n";
    } catch (\Exception $e) {
        echo "❌ Error saat migration: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    // Step 5: Migrasi data dari SQLite ke MySQL
    echo "📊 Step 5: Memindahkan data dari SQLite ke MySQL...\n";
    
    // Set connection ke SQLite untuk membaca data
    config(['database.default' => 'sqlite']);
    
    $tables = [
        'users',
        'santri_detail',
        'profil_pondok',
        'info_aplikasi',
        'app_settings',
        'album_pondok',
        'album_fotos',
    ];
    
    $totalRecords = 0;
    
    foreach ($tables as $table) {
        try {
            // Cek apakah tabel ada di SQLite
            if (!Schema::connection('sqlite')->hasTable($table)) {
                echo "   ⚠️  Tabel '{$table}' tidak ada di SQLite, skip...\n";
                continue;
            }
            
            // Baca data dari SQLite
            $data = DB::connection('sqlite')->table($table)->get();
            
            if ($data->isEmpty()) {
                echo "   ℹ️  Tabel '{$table}': Tidak ada data\n";
                continue;
            }
            
            // Insert ke MySQL
            config(['database.default' => 'mysql']);
            
            $count = 0;
            foreach ($data as $row) {
                try {
                    DB::connection('mysql')->table($table)->insert((array) $row);
                    $count++;
                } catch (\Exception $e) {
                    // Skip jika duplicate atau error lain
                    echo "   ⚠️  Skip record di '{$table}': " . $e->getMessage() . "\n";
                }
            }
            
            echo "   ✅ Tabel '{$table}': {$count} record berhasil dipindahkan\n";
            $totalRecords += $count;
            
        } catch (\Exception $e) {
            echo "   ❌ Error pada tabel '{$table}': " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n✅ Total {$totalRecords} record berhasil dipindahkan!\n\n";
    
    // Step 6: Verifikasi
    echo "🔍 Step 6: Verifikasi data...\n";
    config(['database.default' => 'mysql']);
    
    foreach ($tables as $table) {
        try {
            if (Schema::connection('mysql')->hasTable($table)) {
                $count = DB::connection('mysql')->table($table)->count();
                echo "   ✅ Tabel '{$table}': {$count} record\n";
            }
        } catch (\Exception $e) {
            echo "   ⚠️  Tabel '{$table}': " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n";
    echo "========================================\n";
    echo "  MIGRASI SELESAI!\n";
    echo "========================================\n";
    echo "\n📝 Langkah selanjutnya:\n";
    echo "1. Update file .env:\n";
    echo "   DB_CONNECTION=mysql\n";
    echo "   DB_DATABASE=managemen_data_santri\n";
    echo "\n2. Clear cache:\n";
    echo "   php artisan config:clear\n";
    echo "   php artisan cache:clear\n";
    echo "\n3. Test aplikasi untuk memastikan semua berfungsi\n";
    echo "\n4. Backup file SQLite tetap disimpan di: " . basename($backupPath) . "\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

