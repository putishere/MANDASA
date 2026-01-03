<?php
/**
 * Script untuk memperbaiki konfigurasi database di .env
 * Mengubah dari SQLite ke MySQL
 */

$envFile = __DIR__ . '/../.env';

if (!file_exists($envFile)) {
    echo "❌ File .env tidak ditemukan!\n";
    exit(1);
}

echo "========================================\n";
echo "  PERBAIKAN KONFIGURASI DATABASE\n";
echo "========================================\n\n";

// Baca file .env
$envContent = file_get_contents($envFile);

// Update konfigurasi database
$updates = [
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'managemen_data_santri',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',
];

$lines = explode("\n", $envContent);
$updated = false;

foreach ($updates as $key => $value) {
    $found = false;
    foreach ($lines as $i => $line) {
        if (preg_match("/^{$key}=/", $line)) {
            $lines[$i] = "{$key}={$value}";
            $found = true;
            $updated = true;
            echo "✅ Updated: {$key}={$value}\n";
            break;
        }
    }
    
    if (!$found) {
        // Tambahkan jika tidak ada
        $lines[] = "{$key}={$value}";
        $updated = true;
        echo "✅ Added: {$key}={$value}\n";
    }
}

if ($updated) {
    // Simpan file .env
    file_put_contents($envFile, implode("\n", $lines));
    echo "\n✅ File .env berhasil diupdate!\n\n";
    
    echo "📝 Langkah selanjutnya:\n";
    echo "   1. Clear cache: php artisan config:clear\n";
    echo "   2. Clear cache: php artisan cache:clear\n";
    echo "   3. Refresh halaman aplikasi\n\n";
} else {
    echo "ℹ️  Konfigurasi sudah benar, tidak ada perubahan.\n\n";
}

