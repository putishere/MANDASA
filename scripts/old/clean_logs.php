<?php

/**
 * Script untuk membersihkan log file Laravel yang terlalu besar
 * Menghapus log lama dan membuat log baru yang kosong
 */

$logPath = __DIR__ . '/storage/logs/laravel.log';

echo "🧹 Membersihkan Log File...\n\n";

try {
    if (!file_exists($logPath)) {
        echo "ℹ️  Log file tidak ditemukan: {$logPath}\n";
        exit(0);
    }

    $fileSize = filesize($logPath);
    $fileSizeMB = round($fileSize / 1024 / 1024, 2);

    echo "📊 Ukuran log file saat ini: {$fileSizeMB} MB\n";

    if ($fileSize > 10 * 1024 * 1024) { // Lebih dari 10MB
        echo "⚠️  Log file terlalu besar (>10MB), akan dibersihkan...\n";
        
        // Backup log lama (opsional)
        $backupPath = $logPath . '.' . date('Y-m-d_His') . '.bak';
        if (copy($logPath, $backupPath)) {
            echo "💾 Backup log dibuat: " . basename($backupPath) . "\n";
        }

        // Hapus log lama dan buat yang baru
        file_put_contents($logPath, '');
        echo "✅ Log file berhasil dibersihkan!\n";
        
        $newSize = filesize($logPath);
        echo "📊 Ukuran log file sekarang: " . round($newSize / 1024, 2) . " KB\n";
    } else {
        echo "ℹ️  Ukuran log file masih normal, tidak perlu dibersihkan.\n";
        echo "💡 Untuk membersihkan secara manual, hapus file: storage/logs/laravel.log\n";
    }

    // Bersihkan log backup yang terlalu lama (lebih dari 7 hari)
    $backupFiles = glob(__DIR__ . '/storage/logs/laravel.log.*.bak');
    $deletedBackups = 0;
    
    foreach ($backupFiles as $backupFile) {
        $fileTime = filemtime($backupFile);
        $daysOld = (time() - $fileTime) / (24 * 60 * 60);
        
        if ($daysOld > 7) {
            unlink($backupFile);
            $deletedBackups++;
            echo "🗑️  Menghapus backup lama: " . basename($backupFile) . " ({$daysOld} hari)\n";
        }
    }

    if ($deletedBackups > 0) {
        echo "✅ {$deletedBackups} backup lama berhasil dihapus.\n";
    }

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n✅ Selesai!\n";

