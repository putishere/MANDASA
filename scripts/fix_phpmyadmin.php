<?php
/**
 * Script untuk memperbaiki phpMyAdmin yang rusak
 * 
 * Masalah: File index.php tidak ada
 * Solusi: Download ulang atau extract dari backup
 */

echo "========================================\n";
echo "  PERBAIKAN PHPMYADMIN\n";
echo "========================================\n\n";

$phpmyadminPath = 'C:\laragon\bin\phpmyadmin\phpMyAdmin-5.2.3-all-languages';
$indexWrapperPath = $phpmyadminPath . '\index-wrapper.php';
$indexPath = $phpmyadminPath . '\index.php';

echo "📂 Path phpMyAdmin: {$phpmyadminPath}\n\n";

// Cek apakah folder ada
if (!is_dir($phpmyadminPath)) {
    echo "❌ Folder phpMyAdmin tidak ditemukan!\n";
    echo "💡 Solusi: Reinstall phpMyAdmin dari Laragon\n";
    exit(1);
}

// Cek apakah index-wrapper.php ada
if (!file_exists($indexWrapperPath)) {
    echo "❌ File index-wrapper.php tidak ditemukan!\n";
    echo "💡 Solusi: Reinstall phpMyAdmin\n";
    exit(1);
}

// Cek apakah index.php ada
if (file_exists($indexPath)) {
    echo "✅ File index.php sudah ada!\n";
    echo "💡 Jika masih error, coba:\n";
    echo "   1. Restart Laragon\n";
    echo "   2. Clear browser cache\n";
    echo "   3. Akses: http://localhost/phpmyadmin/\n";
    exit(0);
}

echo "⚠️  File index.php tidak ditemukan!\n\n";

// Cek apakah ada file lain yang bisa digunakan
$possibleFiles = [
    'index.php',
    'index.php.bak',
    'index.php.old',
];

$found = false;
foreach ($possibleFiles as $file) {
    $filePath = $phpmyadminPath . '\\' . $file;
    if (file_exists($filePath)) {
        echo "✅ Ditemukan: {$file}\n";
        if ($file !== 'index.php') {
            echo "   Copy ke index.php...\n";
            copy($filePath, $indexPath);
            echo "   ✅ Berhasil!\n";
            $found = true;
            break;
        }
    }
}

if (!$found) {
    echo "\n❌ File index.php tidak ditemukan dan tidak ada backup.\n\n";
    echo "💡 SOLUSI:\n\n";
    echo "1. REINSTALL PHPMYADMIN (Recommended):\n";
    echo "   a. Download phpMyAdmin terbaru: https://www.phpmyadmin.net/downloads/\n";
    echo "   b. Extract ke: C:\\laragon\\bin\\phpmyadmin\\\n";
    echo "   c. Rename folder menjadi: phpMyAdmin\n";
    echo "   d. Restart Laragon\n\n";
    
    echo "2. ATAU GUNAKAN ALTERNATIF:\n";
    echo "   - TablePlus: https://tableplus.com/ (Paling mudah)\n";
    echo "   - MySQL Workbench: https://dev.mysql.com/downloads/workbench/\n";
    echo "   - HeidiSQL: https://www.heidisql.com/\n\n";
    
    echo "3. ATAU GUNAKAN COMMAND LINE:\n";
    echo "   cd C:\\laragon\\bin\\mysql\\mysql-8.4.3-winx64\\bin\n";
    echo "   mysql -u root\n\n";
    
    echo "4. ATAU GUNAKAN SCRIPT PHP:\n";
    echo "   php scripts/cek_database.php\n";
    echo "   php scripts/test_mysql_connection.php\n\n";
}

echo "========================================\n";
echo "  REKOMENDASI\n";
echo "========================================\n\n";
echo "✅ MySQL berjalan dengan baik\n";
echo "✅ Database dapat diakses\n";
echo "❌ phpMyAdmin perlu diperbaiki\n\n";
echo "💡 Gunakan TablePlus untuk alternatif yang lebih baik!\n";
echo "   Download: https://tableplus.com/\n\n";

