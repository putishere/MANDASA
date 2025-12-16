<?php

/**
 * Script untuk Menghapus File Dokumentasi Perbaikan yang Tidak Diperlukan
 * 
 * Script ini akan:
 * 1. Mengidentifikasi file dokumentasi perbaikan yang bisa dihapus
 * 2. Membuat backup sebelum menghapus
 * 3. Menampilkan preview daftar file yang akan dihapus
 * 4. Meminta konfirmasi sebelum menghapus
 * 5. Menghapus file yang dikonfirmasi
 * 6. Membuat log penghapusan
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=" . str_repeat("=", 70) . "=\n";
echo "  HAPUS DOKUMENTASI PERBAIKAN YANG TIDAK DIPERLUKAN\n";
echo "=" . str_repeat("=", 70) . "=\n\n";

$rootPath = __DIR__;
$backupDocsDir = $rootPath . '/backup/docs';

// File penting yang TIDAK BOLEH dihapus
$importantFiles = [
    'README.md',
    'ALUR_APLIKASI.md',
    'ALUR_APLIKASI_LENGKAP.md',
    'ROUTES_DOCUMENTATION.md',
    'INFORMASI_DATABASE.md',
    'PANDUAN_DEPLOY_HOSTING.md',
    'PANDUAN_DEPLOY_INFINITYFREE.md',
    'PANDUAN_KONVERSI_KE_WORD.md',
    'PANDUAN_SETUP.md',
    'CHECKLIST_DEPLOY_INFINITYFREE.md',
    'CHECKLIST_SEBELUM_DEPLOY.md',
    'CHECKLIST_PERSIAPAN.md',
    'QUICK_START_DEPLOY.md',
    'QUICK_CONVERT.md',
    'TEMPLATE_FORMAT_SKRIPSI.md',
    'BAB_2_LANDASAN_TEORI.md',
    'DOMAIN_DAN_HOSTING_GRATIS.md',
    'CARA_LIHAT_DATABASE.md',
    'CARA_BUAT_DATABASE.md',
    'CARA_MENJALANKAN_APLIKASI.md',
    'CARA_LOGIN_ADMIN.md',
    'TROUBLESHOOTING_403.md',
    'PERBAIKAN_YANG_DILAKUKAN.md',
    'FIX_STORAGE_LINK.md',
];

// Pattern file yang bisa dihapus (dokumentasi perbaikan)
$patternsToDelete = [
    'FIX_*.md',
    'PERBAIKAN_*.md',
    'SOLUSI_*.md',
    'VERIFIKASI_*.md',
    'LAPORAN_*.md',
    'CARA_PERBAIKI_*.md',
    'CARA_MEMPERBAIKI_*.md',
    'RINGKASAN_*.md',
    'STATUS_*.md',
    'TAMBAHAN_*.md',
    'PERBEDAAN_*.md',
    'STUDI_KASUS_*.md',
    'CEK_*.md',
    'CARA_BACKUP_*.md',
    'CARA_CEK_*.md',
    'SOLUSI_LOGIN_*.md',
    'CARA_LIHAT_HASIL_*.md',
    'CARA_ORGANISIR_*.md',
];

// 1. Buat folder backup jika belum ada
if (!is_dir($backupDocsDir)) {
    mkdir($backupDocsDir, 0755, true);
    echo "✅ Folder backup/docs dibuat\n\n";
}

// 2. Kumpulkan semua file .md di root
echo "1. MENGIDENTIFIKASI FILE DOKUMENTASI PERBAIKAN\n";
echo str_repeat("-", 72) . "\n";

$allMdFiles = glob($rootPath . '/*.md');
$filesToDelete = [];
$totalSize = 0;

foreach ($allMdFiles as $file) {
    $filename = basename($file);
    
    // Skip file penting
    if (in_array($filename, $importantFiles)) {
        continue;
    }
    
    // Cek apakah file sesuai pattern yang bisa dihapus
    $shouldDelete = false;
    foreach ($patternsToDelete as $pattern) {
        $patternRegex = '/^' . str_replace(['*', '.'], ['.*', '\.'], $pattern) . '$/i';
        if (preg_match($patternRegex, $filename)) {
            $shouldDelete = true;
            break;
        }
    }
    
    if ($shouldDelete) {
        $fileSize = filesize($file);
        $totalSize += $fileSize;
        $filesToDelete[] = [
            'path' => $file,
            'filename' => $filename,
            'size' => $fileSize,
            'size_kb' => round($fileSize / 1024, 2)
        ];
    }
}

if (count($filesToDelete) === 0) {
    echo "   ℹ️  Tidak ada file dokumentasi perbaikan ditemukan\n";
    echo "   Atau semua file sudah dihapus sebelumnya\n\n";
    echo "=" . str_repeat("=", 70) . "=\n";
    exit(0);
}

echo "   Ditemukan " . count($filesToDelete) . " file dokumentasi perbaikan:\n\n";

// Tampilkan preview
foreach ($filesToDelete as $index => $file) {
    $num = $index + 1;
    echo "   [{$num}] {$file['filename']} ({$file['size_kb']} KB)\n";
}

echo "\n   Total size: " . round($totalSize / 1024, 2) . " KB\n\n";

// 3. Backup file sebelum menghapus
echo "2. MEMBUAT BACKUP FILE\n";
echo str_repeat("-", 72) . "\n";

$backedUpFiles = [];
foreach ($filesToDelete as $file) {
    $destFile = $backupDocsDir . '/' . $file['filename'];
    
    try {
        if (file_exists($destFile)) {
            // Jika file sudah ada, tambahkan timestamp
            $timestamp = date('Y-m-d_His');
            $nameParts = pathinfo($file['filename']);
            $destFile = $backupDocsDir . '/' . $nameParts['filename'] . '_' . $timestamp . '.' . $nameParts['extension'];
        }
        
        if (copy($file['path'], $destFile)) {
            $backedUpFiles[] = $file['filename'];
            echo "   ✅ Backup: {$file['filename']}\n";
        } else {
            echo "   ❌ Gagal backup: {$file['filename']}\n";
        }
    } catch (\Exception $e) {
        echo "   ❌ Error backup {$file['filename']}: " . $e->getMessage() . "\n";
    }
}

echo "\n   Total file di-backup: " . count($backedUpFiles) . "\n\n";

// 4. Konfirmasi sebelum menghapus
echo "\n" . str_repeat("=", 70) . "\n";
echo "  KONFIRMASI PENGHAPUSAN\n";
echo str_repeat("=", 70) . "\n\n";

echo "⚠️  PERINGATAN:\n";
echo "   Script ini akan MENGHAPUS " . count($filesToDelete) . " file secara PERMANEN!\n";
echo "   File sudah di-backup di folder: backup/docs/\n\n";

echo "File yang akan dihapus:\n";
foreach ($filesToDelete as $file) {
    echo "   - {$file['filename']}\n";
}

echo "\nApakah Anda yakin ingin menghapus file-file tersebut? (ketik 'YA' untuk melanjutkan): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if (strtoupper($line) !== 'YA') {
    echo "\n❌ Dibatalkan. File tidak dihapus.\n";
    echo "   File backup tersimpan di: backup/docs/\n\n";
    echo "=" . str_repeat("=", 70) . "=\n";
    exit(0);
}

echo "\n";

// 5. Hapus file
echo "3. MENGHAPUS FILE\n";
echo str_repeat("-", 72) . "\n";

$deletedFiles = [];
$errors = [];

foreach ($filesToDelete as $file) {
    try {
        if (unlink($file['path'])) {
            $deletedFiles[] = $file['filename'];
            echo "   ✅ Dihapus: {$file['filename']}\n";
        } else {
            $errors[] = "Gagal menghapus: {$file['filename']}";
            echo "   ❌ Gagal menghapus: {$file['filename']}\n";
        }
    } catch (\Exception $e) {
        $errors[] = "Error menghapus {$file['filename']}: " . $e->getMessage();
        echo "   ❌ Error: {$file['filename']} - " . $e->getMessage() . "\n";
    }
}

echo "\n";

// 6. Buat log penghapusan
echo "4. MEMBUAT LOG PENGHAPUSAN\n";
echo str_repeat("-", 72) . "\n";

$logFile = $backupDocsDir . '/delete_log_' . date('Y-m-d_His') . '.txt';
$logContent = "LOG PENGHAPUSAN DOKUMENTASI PERBAIKAN\n";
$logContent .= "Tanggal: " . date('Y-m-d H:i:s') . "\n";
$logContent .= str_repeat("=", 70) . "\n\n";

$logContent .= "FILE YANG DIHAPUS:\n";
$logContent .= str_repeat("-", 70) . "\n\n";

foreach ($deletedFiles as $filename) {
    $fileInfo = null;
    foreach ($filesToDelete as $file) {
        if ($file['filename'] === $filename) {
            $fileInfo = $file;
            break;
        }
    }
    
    if ($fileInfo) {
        $logContent .= "File: {$filename}\n";
        $logContent .= "Size: {$fileInfo['size_kb']} KB\n";
        $logContent .= "Backup: backup/docs/{$filename}\n";
        $logContent .= "\n";
    }
}

if (count($errors) > 0) {
    $logContent .= "\nERROR:\n";
    $logContent .= str_repeat("-", 70) . "\n";
    foreach ($errors as $error) {
        $logContent .= "- {$error}\n";
    }
}

$logContent .= "\n" . str_repeat("=", 70) . "\n";
$logContent .= "Total File Dihapus: " . count($deletedFiles) . "\n";
$logContent .= "Total Size: " . round($totalSize / 1024, 2) . " KB\n";

file_put_contents($logFile, $logContent);
echo "   ✅ Log penghapusan dibuat: " . basename($logFile) . "\n\n";

// 7. Ringkasan
echo "\n" . str_repeat("=", 70) . "\n";
echo "  RINGKASAN PENGHAPUSAN\n";
echo str_repeat("=", 70) . "\n\n";

echo "📊 Statistik:\n";
echo "   - Total file dihapus: " . count($deletedFiles) . "\n";
echo "   - Total size: " . round($totalSize / 1024, 2) . " KB\n";
echo "   - Error: " . count($errors) . "\n";
echo "\n";

echo "📁 Lokasi Backup:\n";
echo "   - Folder: backup/docs/\n";
echo "   - Log: " . basename($logFile) . "\n";
echo "\n";

if (count($deletedFiles) > 0) {
    echo "✅ PENGHAPUSAN SELESAI!\n\n";
    echo "💡 CATATAN:\n";
    echo "   - File-file sudah dihapus dari root folder\n";
    echo "   - File backup tersimpan di: backup/docs/\n";
    echo "   - Log penghapusan tersimpan di: {$logFile}\n";
    echo "   - Root folder sekarang lebih bersih\n\n";
} else {
    echo "ℹ️  Tidak ada file yang dihapus\n";
    echo "   Mungkin file sudah dihapus sebelumnya atau ada error\n\n";
}

if (count($errors) > 0) {
    echo "❌ ERROR:\n";
    foreach ($errors as $error) {
        echo "   - {$error}\n";
    }
    echo "\n";
}

echo "=" . str_repeat("=", 70) . "=\n";

