<?php

/**
 * Script untuk Menghapus File yang Benar-Benar Tidak Terpakai
 * 
 * Script ini akan:
 * 1. Mengidentifikasi file yang benar-benar tidak terpakai
 * 2. Backup file sebelum dihapus
 * 3. Menampilkan preview file yang akan dihapus
 * 4. Meminta konfirmasi sebelum menghapus
 */

$rootPath = __DIR__;

echo "=" . str_repeat("=", 70) . "=\n";
echo "  HAPUS FILE YANG BENAR-BENAR TIDAK TERPAKAI\n";
echo "=" . str_repeat("=", 70) . "\n\n";

// File yang AMAN DIHAPUS (tidak mempengaruhi proyek)
$filesToDelete = [
    // File temporary .md di root
    'CEK_RINGKAS.md' => 'File temporary untuk cek ringkas',
    'RINGKASAN_CEK_FUNGSI.md' => 'File temporary untuk ringkasan cek fungsi',
    'HASIL_CEK_FUNGSI.md' => 'File temporary untuk hasil cek fungsi',
    'RINGKASAN_ORGANISASI_FILE.md' => 'File temporary untuk ringkasan organisasi file',
    'RINGKASAN_FILE_MD.md' => 'File temporary untuk ringkasan file .md',
    
    // Script temporary yang tidak digunakan oleh route
    'cek_file_md.php' => 'Script temporary untuk cek file .md',
    
    // File ini sendiri (akan dihapus terakhir)
    'hapus_file_tidak_terpakai.php' => 'Script ini sendiri',
];

// File yang TIDAK BOLEH DIHAPUS (masih digunakan)
$filesInUse = [
    'cek_semua_berfungsi.php' => 'Masih digunakan oleh route /admin/cek-semua-berfungsi',
    'README.md' => 'Dokumentasi utama aplikasi',
    'composer.json' => 'File dependency management',
    'package.json' => 'File dependency management',
    'artisan' => 'File Laravel artisan',
    '.env' => 'File konfigurasi environment',
    '.env.example' => 'File contoh environment',
];

echo "1. MENGIDENTIFIKASI FILE YANG AMAN DIHAPUS\n";
echo str_repeat("-", 72) . "\n";

$filesFound = [];
$filesNotFound = [];

foreach ($filesToDelete as $file => $description) {
    $filePath = $rootPath . '/' . $file;
    if (file_exists($filePath)) {
        $size = filesize($filePath);
        $sizeKB = round($size / 1024, 2);
        echo "   ✅ {$file} ({$sizeKB} KB) - {$description}\n";
        $filesFound[$file] = [
            'path' => $filePath,
            'size' => $size,
            'description' => $description
        ];
    } else {
        echo "   ⚠️  {$file} - TIDAK ADA ({$description})\n";
        $filesNotFound[] = $file;
    }
}

echo "\n";

if (count($filesFound) === 0) {
    echo "✅ TIDAK ADA FILE YANG PERLU DIHAPUS\n";
    echo "   Semua file temporary sudah dihapus atau tidak ada.\n\n";
    exit(0);
}

echo "2. FILE YANG TIDAK BOLEH DIHAPUS (MASIH DIGUNAKAN)\n";
echo str_repeat("-", 72) . "\n";

foreach ($filesInUse as $file => $reason) {
    $filePath = $rootPath . '/' . $file;
    if (file_exists($filePath)) {
        echo "   🔒 {$file} - {$reason}\n";
    }
}

echo "\n";

// Cek apakah ada referensi ke file yang akan dihapus
echo "3. MENGECEK REFERENSI KE FILE YANG AKAN DIHAPUS\n";
echo str_repeat("-", 72) . "\n";

$routesFile = $rootPath . '/routes/web.php';
$hasReferences = false;

if (file_exists($routesFile)) {
    $routesContent = file_get_contents($routesFile);
    
    foreach ($filesFound as $file => $info) {
        // Skip file ini sendiri
        if ($file === 'hapus_file_tidak_terpakai.php') {
            continue;
        }
        
        if (strpos($routesContent, $file) !== false) {
            echo "   ⚠️  {$file} - MASIH DIREFERENSIKAN DI routes/web.php\n";
            $hasReferences = true;
            // Hapus dari list yang akan dihapus
            unset($filesFound[$file]);
        }
    }
}

if (!$hasReferences) {
    echo "   ✅ Tidak ada referensi ke file yang akan dihapus\n";
}

echo "\n";

// Buat backup folder
$backupDir = $rootPath . '/backup/temp_deleted_' . date('Y-m-d_His');
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

echo "4. MEMBUAT BACKUP SEBELUM MENGHAPUS\n";
echo str_repeat("-", 72) . "\n";
echo "   📦 Backup folder: backup/temp_deleted_" . date('Y-m-d_His') . "/\n\n";

// Ringkasan
echo str_repeat("=", 70) . "\n";
echo "  RINGKASAN\n";
echo str_repeat("=", 70) . "\n\n";

$totalSize = 0;
foreach ($filesFound as $file => $info) {
    $totalSize += $info['size'];
}

$totalSizeKB = round($totalSize / 1024, 2);
$totalSizeMB = round($totalSize / 1024 / 1024, 2);

echo "📊 Statistik:\n";
echo "   - File yang akan dihapus: " . count($filesFound) . " file\n";
echo "   - Total ukuran: {$totalSizeKB} KB ({$totalSizeMB} MB)\n";
echo "   - Backup folder: backup/temp_deleted_" . date('Y-m-d_His') . "/\n";
echo "\n";

if (count($filesFound) === 0) {
    echo "✅ TIDAK ADA FILE YANG PERLU DIHAPUS\n";
    echo "   Semua file masih digunakan atau sudah tidak ada.\n\n";
    exit(0);
}

echo "📋 File yang akan dihapus:\n";
foreach ($filesFound as $file => $info) {
    echo "   - {$file} ({$info['description']})\n";
}

echo "\n";

// Konfirmasi
echo "⚠️  PERINGATAN:\n";
echo "   File-file di atas akan dihapus setelah di-backup.\n";
echo "   Backup akan disimpan di: backup/temp_deleted_" . date('Y-m-d_His') . "/\n";
echo "\n";

echo "Apakah Anda yakin ingin menghapus file-file di atas? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));

if (strtolower($line) !== 'yes' && strtolower($line) !== 'y') {
    echo "\n❌ DIBATALKAN - Tidak ada file yang dihapus.\n\n";
    exit(0);
}

fclose($handle);

echo "\n";

// Backup dan hapus
echo "5. MEMBACKUP DAN MENGHAPUS FILE\n";
echo str_repeat("-", 72) . "\n";

$backedUp = 0;
$deleted = 0;
$errors = [];

foreach ($filesFound as $file => $info) {
    try {
        // Backup
        $backupPath = $backupDir . '/' . $file;
        if (copy($info['path'], $backupPath)) {
            echo "   📦 Backup: {$file}\n";
            $backedUp++;
        } else {
            echo "   ⚠️  Gagal backup: {$file}\n";
            $errors[] = "Gagal backup: {$file}";
            continue;
        }
        
        // Hapus
        if (unlink($info['path'])) {
            echo "   ✅ Dihapus: {$file}\n";
            $deleted++;
        } else {
            echo "   ❌ Gagal hapus: {$file}\n";
            $errors[] = "Gagal hapus: {$file}";
        }
    } catch (\Exception $e) {
        echo "   ❌ Error: {$file} - " . $e->getMessage() . "\n";
        $errors[] = "Error: {$file} - " . $e->getMessage();
    }
}

echo "\n";

// Hasil akhir
echo str_repeat("=", 70) . "\n";
echo "  HASIL AKHIR\n";
echo str_repeat("=", 70) . "\n\n";

echo "📊 Statistik:\n";
echo "   - ✅ File di-backup: {$backedUp} file\n";
echo "   - ✅ File dihapus: {$deleted} file\n";

if (count($errors) > 0) {
    echo "   - ❌ Error: " . count($errors) . " file\n";
    echo "\n";
    echo "⚠️  Error yang terjadi:\n";
    foreach ($errors as $error) {
        echo "   - {$error}\n";
    }
} else {
    echo "   - ✅ Tidak ada error\n";
}

echo "\n";

if ($deleted > 0) {
    echo "✅ BERHASIL!\n";
    echo "   {$deleted} file telah dihapus.\n";
    echo "   Backup disimpan di: backup/temp_deleted_" . date('Y-m-d_His') . "/\n";
    echo "\n";
    echo "💡 CATATAN:\n";
    echo "   - File yang dihapus sudah di-backup\n";
    echo "   - Jika diperlukan, file bisa dikembalikan dari folder backup\n";
    echo "   - Proyek tidak akan terpengaruh karena file yang dihapus tidak digunakan\n";
} else {
    echo "⚠️  TIDAK ADA FILE YANG DIHAPUS\n";
    echo "   Periksa error di atas.\n";
}

echo "\n";
echo "=" . str_repeat("=", 70) . "=\n";

