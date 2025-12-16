<?php

/**
 * Script untuk Menghapus File Sampah Setelah Backup
 * 
 * Script ini akan:
 * 1. Menghapus file-file yang sudah di-backup
 * 2. Hanya menghapus file yang ada di folder backup
 * 3. Membuat log penghapusan
 * 
 * ⚠️ PERINGATAN: Script ini akan menghapus file secara permanen!
 * Pastikan sudah melakukan backup dan verifikasi aplikasi masih berjalan dengan baik!
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=" . str_repeat("=", 70) . "=\n";
echo "  HAPUS FILE SAMPAH SETELAH BACKUP\n";
echo "=" . str_repeat("=", 70) . "=\n\n";

echo "⚠️  PERINGATAN PENTING!\n";
echo "   Script ini akan MENGHAPUS file secara PERMANEN!\n";
echo "   Pastikan:\n";
echo "   1. File sudah di-backup di folder backup/\n";
echo "   2. Aplikasi sudah di-test dan masih berjalan dengan baik\n";
echo "   3. Anda yakin ingin menghapus file-file tersebut\n\n";

echo "Apakah Anda yakin ingin melanjutkan? (ketik 'YA' untuk melanjutkan): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if (strtoupper($line) !== 'YA') {
    echo "\n❌ Dibatalkan. File tidak dihapus.\n";
    exit(0);
}

echo "\n";

$rootPath = __DIR__;
$backupBaseDir = $rootPath . '/backup';
$backupScriptsDir = $backupBaseDir . '/scripts';
$backupDocsDir = $backupBaseDir . '/docs';

$deletedFiles = [];
$errors = [];
$notFoundInBackup = [];

// 1. Hapus file script yang sudah di-backup
echo "1. MENGHAPUS FILE SCRIPT YANG SUDAH DI-BACKUP\n";
echo str_repeat("-", 72) . "\n";

if (is_dir($backupScriptsDir)) {
    $backupScripts = glob($backupScriptsDir . '/*.php');
    
    foreach ($backupScripts as $backupFile) {
        $filename = basename($backupFile);
        
        // Skip file dengan timestamp (duplikat)
        if (preg_match('/_\d{4}-\d{2}-\d{2}_\d{6}\.php$/', $filename)) {
            continue;
        }
        
        $originalFile = $rootPath . '/' . $filename;
        
        if (file_exists($originalFile)) {
            try {
                if (unlink($originalFile)) {
                    $deletedFiles[] = [
                        'type' => 'script',
                        'file' => $originalFile,
                        'backup' => $backupFile
                    ];
                    echo "   ✅ Dihapus: {$filename}\n";
                } else {
                    $errors[] = "Gagal menghapus: {$filename}";
                    echo "   ❌ Gagal menghapus: {$filename}\n";
                }
            } catch (\Exception $e) {
                $errors[] = "Error menghapus {$filename}: " . $e->getMessage();
                echo "   ❌ Error: {$filename} - " . $e->getMessage() . "\n";
            }
        } else {
            $notFoundInBackup[] = $filename;
        }
    }
} else {
    echo "   ⚠️  Folder backup/scripts tidak ditemukan\n";
}

echo "\n";

// 2. Hapus dokumentasi yang sudah di-backup
echo "\n2. MENGHAPUS DOKUMENTASI YANG SUDAH DI-BACKUP\n";
echo str_repeat("-", 72) . "\n";

if (is_dir($backupDocsDir)) {
    $backupDocs = glob($backupDocsDir . '/*.md');
    
    foreach ($backupDocs as $backupFile) {
        $filename = basename($backupFile);
        
        // Skip file dengan timestamp (duplikat)
        if (preg_match('/_\d{4}-\d{2}-\d{2}_\d{6}\.md$/', $filename)) {
            continue;
        }
        
        $originalFile = $rootPath . '/' . $filename;
        
        if (file_exists($originalFile)) {
            try {
                if (unlink($originalFile)) {
                    $deletedFiles[] = [
                        'type' => 'doc',
                        'file' => $originalFile,
                        'backup' => $backupFile
                    ];
                    echo "   ✅ Dihapus: {$filename}\n";
                } else {
                    $errors[] = "Gagal menghapus: {$filename}";
                    echo "   ❌ Gagal menghapus: {$filename}\n";
                }
            } catch (\Exception $e) {
                $errors[] = "Error menghapus {$filename}: " . $e->getMessage();
                echo "   ❌ Error: {$filename} - " . $e->getMessage() . "\n";
            }
        } else {
            $notFoundInBackup[] = $filename;
        }
    }
} else {
    echo "   ⚠️  Folder backup/docs tidak ditemukan\n";
}

echo "\n";

// 3. Buat log penghapusan
echo "\n3. MEMBUAT LOG PENGHAPUSAN\n";
echo str_repeat("-", 72) . "\n";

$logFile = $backupBaseDir . '/delete_log_' . date('Y-m-d_His') . '.txt';
$logContent = "LOG PENGHAPUSAN FILE SAMPAH\n";
$logContent .= "Tanggal: " . date('Y-m-d H:i:s') . "\n";
$logContent .= str_repeat("=", 70) . "\n\n";

$logContent .= "FILE YANG DIHAPUS:\n";
$logContent .= str_repeat("-", 70) . "\n\n";

foreach ($deletedFiles as $item) {
    $logContent .= "Type: {$item['type']}\n";
    $logContent .= "File: {$item['file']}\n";
    $logContent .= "Backup: {$item['backup']}\n";
    $logContent .= "\n";
}

if (count($notFoundInBackup) > 0) {
    $logContent .= "\nFILE TIDAK DITEMUKAN DI ROOT (mungkin sudah dihapus):\n";
    $logContent .= str_repeat("-", 70) . "\n";
    foreach ($notFoundInBackup as $file) {
        $logContent .= "- {$file}\n";
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

file_put_contents($logFile, $logContent);
echo "   ✅ Log penghapusan dibuat: " . basename($logFile) . "\n\n";

// 4. Ringkasan
echo "\n" . str_repeat("=", 70) . "\n";
echo "  RINGKASAN PENGHAPUSAN\n";
echo str_repeat("=", 70) . "\n\n";

echo "📊 Statistik:\n";
echo "   - Total file dihapus: " . count($deletedFiles) . "\n";
echo "   - File tidak ditemukan: " . count($notFoundInBackup) . "\n";
echo "   - Error: " . count($errors) . "\n";
echo "\n";

if (count($deletedFiles) > 0) {
    echo "✅ PENGHAPUSAN SELESAI!\n\n";
    echo "💡 CATATAN:\n";
    echo "   - File-file sudah dihapus dari root folder\n";
    echo "   - File backup masih tersimpan di folder backup/\n";
    echo "   - Log penghapusan tersimpan di: {$logFile}\n";
    echo "   - Test aplikasi untuk memastikan masih berjalan dengan baik\n\n";
} else {
    echo "ℹ️  Tidak ada file yang dihapus\n";
    echo "   Mungkin file sudah dihapus sebelumnya atau tidak ada di backup\n\n";
}

if (count($errors) > 0) {
    echo "❌ ERROR:\n";
    foreach ($errors as $error) {
        echo "   - {$error}\n";
    }
    echo "\n";
}

echo "=" . str_repeat("=", 70) . "=\n";

