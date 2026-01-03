<?php

/**
 * Script untuk update .env dari SQLite ke MySQL
 */

$envPath = __DIR__ . '/../.env';

if (!file_exists($envPath)) {
    echo "❌ File .env tidak ditemukan!\n";
    echo "   Silakan copy ENV_TEMPLATE.txt ke .env terlebih dahulu.\n";
    exit(1);
}

// Baca file .env
$envContent = file_get_contents($envPath);

// Update konfigurasi database
$replacements = [
    'DB_CONNECTION=sqlite' => 'DB_CONNECTION=mysql',
    '# DB_CONNECTION=mysql' => 'DB_CONNECTION=mysql',
    'DB_DATABASE=database/database.sqlite' => 'DB_DATABASE=managemen_data_santri',
    '# DB_DATABASE=managemen_data_santri' => 'DB_DATABASE=managemen_data_santri',
];

// Uncomment MySQL config jika di-comment
$envContent = preg_replace('/^#\s*DB_HOST=/m', 'DB_HOST=', $envContent);
$envContent = preg_replace('/^#\s*DB_PORT=/m', 'DB_PORT=', $envContent);
$envContent = preg_replace('/^#\s*DB_USERNAME=/m', 'DB_USERNAME=', $envContent);
$envContent = preg_replace('/^#\s*DB_PASSWORD=/m', 'DB_PASSWORD=', $envContent);

// Apply replacements
foreach ($replacements as $search => $replace) {
    $envContent = str_replace($search, $replace, $envContent);
}

// Pastikan konfigurasi MySQL ada
if (strpos($envContent, 'DB_HOST=127.0.0.1') === false) {
    // Cari baris DB_CONNECTION dan tambahkan setelahnya
    $envContent = preg_replace(
        '/(DB_CONNECTION=mysql\s*\n)/',
        "$1DB_HOST=127.0.0.1\nDB_PORT=3306\n",
        $envContent
    );
}

if (strpos($envContent, 'DB_PORT=3306') === false && strpos($envContent, 'DB_CONNECTION=mysql') !== false) {
    $envContent = preg_replace(
        '/(DB_HOST=127\.0\.0\.1\s*\n)/',
        "$1DB_PORT=3306\n",
        $envContent
    );
}

if (strpos($envContent, 'DB_USERNAME=root') === false && strpos($envContent, 'DB_CONNECTION=mysql') !== false) {
    $envContent = preg_replace(
        '/(DB_PORT=3306\s*\n)/',
        "$1DB_USERNAME=root\n",
        $envContent
    );
}

if (strpos($envContent, 'DB_PASSWORD=') === false && strpos($envContent, 'DB_CONNECTION=mysql') !== false) {
    $envContent = preg_replace(
        '/(DB_USERNAME=root\s*\n)/',
        "$1DB_PASSWORD=\n",
        $envContent
    );
}

// Comment SQLite config
$envContent = preg_replace('/^DB_CONNECTION=sqlite/m', '# DB_CONNECTION=sqlite', $envContent);
$envContent = preg_replace('/^DB_DATABASE=database\/database\.sqlite/m', '# DB_DATABASE=database/database.sqlite', $envContent);

// Simpan file
file_put_contents($envPath, $envContent);

echo "✅ File .env berhasil diupdate ke MySQL!\n";
echo "\nKonfigurasi database:\n";
echo "DB_CONNECTION=mysql\n";
echo "DB_HOST=127.0.0.1\n";
echo "DB_PORT=3306\n";
echo "DB_DATABASE=managemen_data_santri\n";
echo "DB_USERNAME=root\n";
echo "DB_PASSWORD=\n";
echo "\n";

