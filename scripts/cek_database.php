<?php
/**
 * Script untuk cek dan lihat data database tanpa phpMyAdmin
 * 
 * Cara pakai:
 * php scripts/cek_database.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "  CEK DATABASE - Laragon MySQL\n";
echo "========================================\n\n";

try {
    // Pastikan menggunakan MySQL
    $defaultConnection = config('database.default');
    if ($defaultConnection === 'sqlite') {
        echo "⚠️  Database default adalah SQLite. Mencoba koneksi MySQL...\n\n";
        // Coba koneksi MySQL langsung
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');
        $database = env('DB_DATABASE', 'laravel');
        $username = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', '');
        
        try {
            $pdo = new PDO("mysql:host={$host};port={$port}", $username, $password);
            echo "✅ Koneksi MySQL berhasil!\n\n";
            
            // List databases
            echo "📊 Daftar Database:\n";
            echo str_repeat("-", 50) . "\n";
            $stmt = $pdo->query('SHOW DATABASES');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "  - {$row['Database']}\n";
            }
            echo "\n";
            
            // Cek database aplikasi
            $appDatabase = 'managemen_data_santri';
            if ($database && $database !== 'database/database.sqlite') {
                $appDatabase = $database;
            }
            
            echo "📌 Database yang dikonfigurasi: {$database}\n";
            echo "📌 Database aplikasi (ditemukan): {$appDatabase}\n\n";
            
            // Cek database aplikasi
            try {
                $pdo->exec("USE `{$appDatabase}`");
                $stmt = $pdo->query('SHOW TABLES');
                echo "📋 Daftar Tabel di '{$appDatabase}':\n";
                echo str_repeat("-", 50) . "\n";
                $tables = [];
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $tableName = $row[0];
                    $countStmt = $pdo->query("SELECT COUNT(*) FROM `{$tableName}`");
                    $count = $countStmt->fetchColumn();
                    echo "  - {$tableName} ({$count} rows)\n";
                    $tables[] = $tableName;
                }
                echo "\n";
                
                // Tampilkan data dari tabel utama jika ada
                if (in_array('users', $tables)) {
                    echo "👥 Data Users (5 pertama):\n";
                    echo str_repeat("-", 50) . "\n";
                    $stmt = $pdo->query("SELECT id, name, email, role FROM users LIMIT 5");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "  ID: {$row['id']} | Name: {$row['name']} | Email: {$row['email']} | Role: {$row['role']}\n";
                    }
                    echo "\n";
                }
                
                if (in_array('santri', $tables)) {
                    echo "📚 Data Santri (5 pertama):\n";
                    echo str_repeat("-", 50) . "\n";
                    $stmt = $pdo->query("SELECT id, nama, nis FROM santri LIMIT 5");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "  ID: {$row['id']} | Nama: {$row['nama']} | NIS: {$row['nis']}\n";
                    }
                    echo "\n";
                }
            } catch (PDOException $e) {
                echo "  ⚠️  Database '{$appDatabase}' tidak bisa diakses: " . $e->getMessage() . "\n";
            }
            
            echo "\n✅ Selesai!\n";
            exit(0);
        } catch (PDOException $e) {
            echo "❌ Error koneksi MySQL: " . $e->getMessage() . "\n";
            echo "\nPastikan:\n";
            echo "  1. MySQL berjalan di Laragon\n";
            echo "  2. File .env sudah dikonfigurasi dengan benar\n";
            exit(1);
        }
    }
    
    // Cek koneksi
    DB::connection()->getPdo();
    echo "✅ Koneksi database berhasil!\n\n";
    
    // List semua database
    echo "📊 Daftar Database:\n";
    echo str_repeat("-", 50) . "\n";
    $databases = DB::select('SHOW DATABASES');
    foreach ($databases as $db) {
        $dbName = $db->Database;
        echo "  - {$dbName}\n";
    }
    echo "\n";
    
    // Cek database yang digunakan
    $currentDb = DB::getDatabaseName();
    echo "📌 Database yang digunakan: {$currentDb}\n\n";
    
    // List tabel
    echo "📋 Daftar Tabel di '{$currentDb}':\n";
    echo str_repeat("-", 50) . "\n";
    $tables = DB::select('SHOW TABLES');
    $tableKey = "Tables_in_{$currentDb}";
    foreach ($tables as $table) {
        $tableName = $table->$tableKey;
        $count = DB::table($tableName)->count();
        echo "  - {$tableName} ({$count} rows)\n";
    }
    echo "\n";
    
    // Tampilkan beberapa data dari tabel utama
    echo "👥 Data Users:\n";
    echo str_repeat("-", 50) . "\n";
    $users = DB::table('users')->select('id', 'name', 'email', 'role')->limit(5)->get();
    if ($users->count() > 0) {
        foreach ($users as $user) {
            echo "  ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Role: {$user->role}\n";
        }
    } else {
        echo "  Tidak ada data\n";
    }
    echo "\n";
    
    // Cek tabel santri jika ada
    if (DB::getSchemaBuilder()->hasTable('santri')) {
        echo "📚 Data Santri:\n";
        echo str_repeat("-", 50) . "\n";
        $santri = DB::table('santri')->select('id', 'nama', 'nis')->limit(5)->get();
        if ($santri->count() > 0) {
            foreach ($santri as $s) {
                echo "  ID: {$s->id} | Nama: {$s->nama} | NIS: {$s->nis}\n";
            }
        } else {
            echo "  Tidak ada data\n";
        }
        echo "\n";
    }
    
    echo "✅ Selesai!\n";
    echo "\n";
    echo "💡 Tips:\n";
    echo "   - Gunakan TablePlus untuk UI yang lebih baik\n";
    echo "   - Atau gunakan: php artisan tinker\n";
    echo "\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n";
    echo "Pastikan:\n";
    echo "  1. MySQL berjalan di Laragon\n";
    echo "  2. File .env sudah dikonfigurasi dengan benar\n";
    echo "  3. Database sudah dibuat\n";
    exit(1);
}

