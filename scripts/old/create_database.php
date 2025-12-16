<?php
/**
 * Script untuk membuat database managemen_data_santri
 * 
 * Script ini akan:
 * 1. Membaca konfigurasi database dari .env
 * 2. Membuat koneksi ke MySQL server
 * 3. Membuat database jika belum ada
 * 4. Menampilkan status hasil
 */

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║          MEMBUAT DATABASE MANAGEMEN_DATA_SANTRI            ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Baca file .env
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    echo "❌ Error: File .env tidak ditemukan!\n";
    echo "   → Silakan jalankan: php setup_aplikasi.php\n";
    exit(1);
}

// Parse .env file
$envContent = file_get_contents($envPath);
$envVars = [];

foreach (explode("\n", $envContent) as $line) {
    $line = trim($line);
    if (empty($line) || strpos($line, '#') === 0) {
        continue;
    }
    
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // Remove quotes if present
        $value = trim($value, '"\'');
        $envVars[$key] = $value;
    }
}

// Ambil konfigurasi database
$dbHost = $envVars['DB_HOST'] ?? '127.0.0.1';
$dbPort = $envVars['DB_PORT'] ?? '3306';
$dbUsername = $envVars['DB_USERNAME'] ?? 'root';
$dbPassword = $envVars['DB_PASSWORD'] ?? '';
$dbName = $envVars['DB_DATABASE'] ?? 'managemen_data_santri';
$dbConnection = $envVars['DB_CONNECTION'] ?? 'mysql';

echo "📋 Konfigurasi Database:\n";
echo "   Host: {$dbHost}\n";
echo "   Port: {$dbPort}\n";
echo "   Username: {$dbUsername}\n";
echo "   Database: {$dbName}\n";
echo "   Connection: {$dbConnection}\n\n";

// Jika menggunakan SQLite, skip
if ($dbConnection === 'sqlite') {
    echo "ℹ️  Menggunakan SQLite, tidak perlu membuat database MySQL.\n";
    echo "   Database SQLite akan dibuat otomatis saat migrasi.\n";
    exit(0);
}

// Coba koneksi ke MySQL server (tanpa database)
try {
    echo "1. Mencoba koneksi ke MySQL server...\n";
    
    $dsn = "mysql:host={$dbHost};port={$dbPort};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUsername, $dbPassword, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "   ✓ Koneksi ke MySQL server berhasil\n\n";
    
    // Cek apakah database sudah ada
    echo "2. Memeriksa apakah database sudah ada...\n";
    $stmt = $pdo->query("SHOW DATABASES LIKE '{$dbName}'");
    $exists = $stmt->rowCount() > 0;
    
    if ($exists) {
        echo "   ⚠️  Database '{$dbName}' sudah ada\n";
        echo "   → Ingin menghapus dan membuat ulang? (y/n): ";
        
        // Untuk non-interactive, skip
        echo "n\n";
        echo "   → Melewati pembuatan database\n";
        echo "   ✓ Database sudah tersedia\n";
    } else {
        // Buat database
        echo "   → Database belum ada, membuat database baru...\n";
        
        $sql = "CREATE DATABASE IF NOT EXISTS `{$dbName}` 
                CHARACTER SET utf8mb4 
                COLLATE utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        
        echo "   ✓ Database '{$dbName}' berhasil dibuat\n";
    }
    
    // Verifikasi database
    echo "\n3. Memverifikasi database...\n";
    $stmt = $pdo->query("SHOW DATABASES LIKE '{$dbName}'");
    if ($stmt->rowCount() > 0) {
        echo "   ✓ Database '{$dbName}' tersedia dan siap digunakan\n";
        
        // Cek karakter set
        $stmt = $pdo->query("SELECT DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME 
                              FROM information_schema.SCHEMATA 
                              WHERE SCHEMA_NAME = '{$dbName}'");
        $dbInfo = $stmt->fetch();
        
        if ($dbInfo) {
            echo "   → Character Set: {$dbInfo['DEFAULT_CHARACTER_SET_NAME']}\n";
            echo "   → Collation: {$dbInfo['DEFAULT_COLLATION_NAME']}\n";
        }
    } else {
        echo "   ❌ Error: Database tidak ditemukan setelah dibuat\n";
        exit(1);
    }
    
    echo "\n╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                    DATABASE BERHASIL DIBUAT                  ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    echo "Langkah selanjutnya:\n";
    echo "1. Jalankan migrasi: php artisan migrate\n";
    echo "2. Jalankan seeder: php artisan db:seed\n";
    echo "3. Atau jalankan setup lengkap: php setup_aplikasi.php\n\n";
    
} catch (PDOException $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n\n";
    
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                      TROUBLESHOOTING                         ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    echo "Kemungkinan masalah:\n";
    echo "1. MySQL/MariaDB tidak berjalan\n";
    echo "   → Pastikan MySQL/MariaDB sudah di-start di Laragon\n";
    echo "   → Atau start manual: net start mysql (Windows)\n\n";
    
    echo "2. Username/Password salah\n";
    echo "   → Periksa konfigurasi di file .env\n";
    echo "   → Default Laragon: username=root, password=(kosong)\n\n";
    
    echo "3. Host/Port salah\n";
    echo "   → Periksa konfigurasi di file .env\n";
    echo "   → Default: host=127.0.0.1, port=3306\n\n";
    
    echo "4. Buat database manual:\n";
    echo "   → Buka phpMyAdmin atau MySQL client\n";
    echo "   → Jalankan: CREATE DATABASE managemen_data_santri CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n\n";
    
    exit(1);
}

