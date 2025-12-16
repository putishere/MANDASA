<?php

/**
 * Script untuk Backup File Sampah/Tidak Terpakai
 * 
 * Script ini akan:
 * 1. Membuat folder backup jika belum ada
 * 2. Memindahkan file-file temporary/perbaikan ke folder backup
 * 3. Memindahkan dokumentasi perbaikan ke folder backup
 * 4. Membuat log file backup
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=" . str_repeat("=", 70) . "=\n";
echo "  BACKUP FILE SAMPAH / TIDAK TERPAKAI\n";
echo "=" . str_repeat("=", 70) . "=\n\n";

$rootPath = __DIR__;
$backupBaseDir = $rootPath . '/backup';
$backupScriptsDir = $backupBaseDir . '/scripts';
$backupDocsDir = $backupBaseDir . '/docs';
$backupRoutesDir = $backupBaseDir . '/routes';

$backedUpFiles = [];
$errors = [];

// Buat folder backup jika belum ada
if (!is_dir($backupBaseDir)) {
    mkdir($backupBaseDir, 0755, true);
    echo "✅ Folder backup dibuat: {$backupBaseDir}\n";
}

if (!is_dir($backupScriptsDir)) {
    mkdir($backupScriptsDir, 0755, true);
    echo "✅ Folder backup/scripts dibuat\n";
}

if (!is_dir($backupDocsDir)) {
    mkdir($backupDocsDir, 0755, true);
    echo "✅ Folder backup/docs dibuat\n";
}

if (!is_dir($backupRoutesDir)) {
    mkdir($backupRoutesDir, 0755, true);
    echo "✅ Folder backup/routes dibuat\n";
}

echo "\n";

// 1. Backup file-file temporary/perbaikan
echo "1. BACKUP FILE TEMPORARY/PERBAIKAN\n";
echo str_repeat("-", 72) . "\n";

$tempFilePatterns = [
    'fix_*.php',
    'clear_*.php',
    'create_*.php',
    'test_*.php',
    'view_*.php',
    'setup_*.php',
    'add_*.php',
    'jalankan_*.php',
    'force_logout_all.php',
    'cek_file_sampah.php',
];

$tempFiles = [];
foreach ($tempFilePatterns as $pattern) {
    $files = glob($rootPath . '/' . $pattern);
    foreach ($files as $file) {
        $filename = basename($file);
        // Skip file penting
        if (in_array($filename, ['artisan', 'index.php', 'server.php'])) {
            continue;
        }
        $tempFiles[] = $file;
    }
}

if (count($tempFiles) > 0) {
    echo "   Ditemukan " . count($tempFiles) . " file temporary:\n\n";
    
    foreach ($tempFiles as $file) {
        $filename = basename($file);
        $destFile = $backupScriptsDir . '/' . $filename;
        
        try {
            if (file_exists($destFile)) {
                // Jika file sudah ada, tambahkan timestamp
                $timestamp = date('Y-m-d_His');
                $nameParts = pathinfo($filename);
                $destFile = $backupScriptsDir . '/' . $nameParts['filename'] . '_' . $timestamp . '.' . $nameParts['extension'];
            }
            
            if (copy($file, $destFile)) {
                $backedUpFiles[] = [
                    'type' => 'script',
                    'original' => $file,
                    'backup' => $destFile,
                    'size' => filesize($file)
                ];
                echo "   ✅ {$filename} → backup/scripts/\n";
            } else {
                $errors[] = "Gagal backup: {$filename}";
                echo "   ❌ Gagal backup: {$filename}\n";
            }
        } catch (\Exception $e) {
            $errors[] = "Error backup {$filename}: " . $e->getMessage();
            echo "   ❌ Error: {$filename} - " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
} else {
    echo "   ✅ Tidak ada file temporary ditemukan\n\n";
}

// 2. Backup dokumentasi perbaikan
echo "\n2. BACKUP DOKUMENTASI PERBAIKAN\n";
echo str_repeat("-", 72) . "\n";

$docFiles = glob($rootPath . '/*.md');
$fixDocs = [];

foreach ($docFiles as $file) {
    $filename = basename($file);
    
    // Skip dokumentasi penting
    $importantDocs = [
        'README.md',
        'ALUR_APLIKASI.md',
        'ALUR_APLIKASI_LENGKAP.md',
        'ROUTES_DOCUMENTATION.md',
        'INFORMASI_DATABASE.md',
        'PANDUAN_DEPLOY_HOSTING.md',
        'PANDUAN_DEPLOY_INFINITYFREE.md',
        'CHECKLIST_DEPLOY_INFINITYFREE.md',
        'CHECKLIST_SEBELUM_DEPLOY.md',
        'QUICK_START_DEPLOY.md',
        'DOMAIN_DAN_HOSTING_GRATIS.md',
        'CARA_LIHAT_DATABASE.md',
        'CARA_BUAT_DATABASE.md',
        'CARA_MENJALANKAN_APLIKASI.md',
        'PANDUAN_SETUP.md',
        'PANDUAN_KONVERSI_KE_WORD.md',
        'TEMPLATE_FORMAT_SKRIPSI.md',
        'QUICK_CONVERT.md',
        'BAB_2_LANDASAN_TEORI.md',
        'CARA_CEK_FILE_SAMPAH.md',
    ];
    
    if (in_array($filename, $importantDocs)) {
        continue;
    }
    
    // Cek apakah file dokumentasi perbaikan
    if (stripos($filename, 'FIX') !== false || 
        stripos($filename, 'PERBAIKAN') !== false || 
        stripos($filename, 'SOLUSI') !== false ||
        stripos($filename, 'TROUBLESHOOTING') !== false ||
        stripos($filename, 'VERIFIKASI') !== false ||
        stripos($filename, 'LAPORAN') !== false ||
        stripos($filename, 'CARA_PERBAIKI') !== false ||
        stripos($filename, 'CARA_MEMPERBAIKI') !== false ||
        stripos($filename, 'RINGKASAN') !== false ||
        stripos($filename, 'STATUS') !== false ||
        stripos($filename, 'TAMBAHAN') !== false ||
        stripos($filename, 'PERBEDAAN') !== false ||
        stripos($filename, 'STUDI_KASUS') !== false) {
        $fixDocs[] = $file;
    }
}

if (count($fixDocs) > 0) {
    echo "   Ditemukan " . count($fixDocs) . " file dokumentasi perbaikan:\n\n";
    
    foreach ($fixDocs as $file) {
        $filename = basename($file);
        $destFile = $backupDocsDir . '/' . $filename;
        
        try {
            if (file_exists($destFile)) {
                // Jika file sudah ada, tambahkan timestamp
                $timestamp = date('Y-m-d_His');
                $nameParts = pathinfo($filename);
                $destFile = $backupDocsDir . '/' . $nameParts['filename'] . '_' . $timestamp . '.' . $nameParts['extension'];
            }
            
            if (copy($file, $destFile)) {
                $backedUpFiles[] = [
                    'type' => 'doc',
                    'original' => $file,
                    'backup' => $destFile,
                    'size' => filesize($file)
                ];
                echo "   ✅ {$filename} → backup/docs/\n";
            } else {
                $errors[] = "Gagal backup: {$filename}";
                echo "   ❌ Gagal backup: {$filename}\n";
            }
        } catch (\Exception $e) {
            $errors[] = "Error backup {$filename}: " . $e->getMessage();
            echo "   ❌ Error: {$filename} - " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
} else {
    echo "   ✅ Tidak ada dokumentasi perbaikan ditemukan\n\n";
}

// 3. Buat file log backup
echo "\n3. MEMBUAT LOG BACKUP\n";
echo str_repeat("-", 72) . "\n";

$logFile = $backupBaseDir . '/backup_log_' . date('Y-m-d_His') . '.txt';
$logContent = "BACKUP FILE SAMPAH\n";
$logContent .= "Tanggal: " . date('Y-m-d H:i:s') . "\n";
$logContent .= str_repeat("=", 70) . "\n\n";

$logContent .= "FILE YANG DIBACKUP:\n";
$logContent .= str_repeat("-", 70) . "\n\n";

$totalSize = 0;
foreach ($backedUpFiles as $item) {
    $sizeKB = round($item['size'] / 1024, 2);
    $totalSize += $item['size'];
    $logContent .= "Type: {$item['type']}\n";
    $logContent .= "Original: {$item['original']}\n";
    $logContent .= "Backup: {$item['backup']}\n";
    $logContent .= "Size: {$sizeKB} KB\n";
    $logContent .= "\n";
}

$logContent .= "\n" . str_repeat("=", 70) . "\n";
$logContent .= "Total File: " . count($backedUpFiles) . "\n";
$logContent .= "Total Size: " . round($totalSize / 1024, 2) . " KB\n";

if (count($errors) > 0) {
    $logContent .= "\nERROR:\n";
    $logContent .= str_repeat("-", 70) . "\n";
    foreach ($errors as $error) {
        $logContent .= "- {$error}\n";
    }
}

file_put_contents($logFile, $logContent);
echo "   ✅ Log backup dibuat: " . basename($logFile) . "\n\n";

// 4. Ringkasan
echo "\n" . str_repeat("=", 70) . "\n";
echo "  RINGKASAN BACKUP\n";
echo str_repeat("=", 70) . "\n\n";

echo "📊 Statistik:\n";
echo "   - Total file di-backup: " . count($backedUpFiles) . "\n";
echo "   - Total size: " . round($totalSize / 1024, 2) . " KB\n";
echo "   - Error: " . count($errors) . "\n";
echo "\n";

echo "📁 Lokasi Backup:\n";
echo "   - Scripts: {$backupScriptsDir}\n";
echo "   - Docs: {$backupDocsDir}\n";
echo "   - Log: {$logFile}\n";
echo "\n";

if (count($backedUpFiles) > 0) {
    echo "✅ BACKUP SELESAI!\n\n";
    echo "💡 LANGKAH SELANJUTNYA:\n";
    echo "   1. Verifikasi file-file di folder backup/\n";
    echo "   2. Test aplikasi untuk memastikan masih berjalan dengan baik\n";
    echo "   3. Jika aplikasi masih berjalan dengan baik, file-file original bisa dihapus\n";
    echo "   4. File-file sudah di-backup di folder backup/\n\n";
    
    echo "⚠️  PERINGATAN:\n";
    echo "   - File-file masih ada di root folder (belum dihapus)\n";
    echo "   - Hapus file-file original setelah verifikasi aplikasi masih berjalan\n";
    echo "   - File backup tersimpan di folder backup/\n\n";
} else {
    echo "ℹ️  Tidak ada file yang perlu di-backup\n";
    echo "   Atau semua file sudah di-backup sebelumnya\n\n";
}

if (count($errors) > 0) {
    echo "❌ ERROR:\n";
    foreach ($errors as $error) {
        echo "   - {$error}\n";
    }
    echo "\n";
}

echo "=" . str_repeat("=", 70) . "=\n";

