<?php
/**
 * Script sederhana untuk menambahkan kolom tahun_masuk
 * Tidak perlu Laravel bootstrap, langsung akses SQLite
 */

$dbPath = __DIR__ . '/database/database.sqlite';

if (!file_exists($dbPath)) {
    die("Error: Database file tidak ditemukan di: $dbPath\n");
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "\n";
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║     PERBAIKAN KOLOM TAHUN_MASUK                              ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    // Cek apakah kolom sudah ada
    echo "1. Memeriksa kolom tahun_masuk...\n";
    $stmt = $pdo->query("PRAGMA table_info(santri_detail)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $columnExists = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'tahun_masuk') {
            $columnExists = true;
            break;
        }
    }
    
    if ($columnExists) {
        echo "   ✓ Kolom 'tahun_masuk' sudah ada\n\n";
        echo "   Kolom sudah tersedia. Tidak perlu melakukan perubahan.\n\n";
        exit(0);
    }
    
    echo "   ⚠ Kolom 'tahun_masuk' belum ada, menambahkan...\n\n";
    
    // Tambahkan kolom
    echo "2. Menambahkan kolom tahun_masuk...\n";
    $pdo->exec("ALTER TABLE santri_detail ADD COLUMN tahun_masuk INTEGER");
    echo "   ✓ Kolom 'tahun_masuk' (INTEGER) berhasil ditambahkan\n\n";
    
    // Verifikasi
    echo "3. Memverifikasi kolom tahun_masuk...\n";
    $stmt = $pdo->query("PRAGMA table_info(santri_detail)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $columnExists = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'tahun_masuk') {
            $columnExists = true;
            break;
        }
    }
    
    if ($columnExists) {
        echo "   ✓ Verifikasi berhasil: Kolom 'tahun_masuk' tersedia\n\n";
    } else {
        echo "   ✗ Error: Kolom tidak ditemukan setelah penambahan\n\n";
        exit(1);
    }
    
    // Update data yang sudah ada dengan tahun default
    echo "4. Memperbarui data yang sudah ada (opsional)...\n";
    try {
        $stmt = $pdo->prepare("UPDATE santri_detail SET tahun_masuk = ? WHERE tahun_masuk IS NULL");
        $stmt->execute([date('Y')]);
        $updated = $stmt->rowCount();
        
        if ($updated > 0) {
            echo "   ✓ {$updated} record diperbarui dengan tahun masuk: " . date('Y') . "\n\n";
        } else {
            echo "   ✓ Tidak ada data yang perlu diperbarui\n\n";
        }
    } catch (\Exception $e) {
        echo "   ⚠ Peringatan: " . $e->getMessage() . "\n";
        echo "   (Ini tidak fatal, data baru tetap bisa ditambahkan)\n\n";
    }
    
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                    SELESAI                                  ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    echo "✓ Kolom 'tahun_masuk' berhasil ditambahkan ke tabel 'santri_detail'!\n\n";
    echo "Langkah selanjutnya:\n";
    echo "1. Refresh halaman edit santri di browser\n";
    echo "2. Form edit sekarang sudah bisa menyimpan tahun masuk\n";
    echo "3. Error 'no such column: tahun_masuk' sudah teratasi\n\n";
    
} catch (\Exception $e) {
    echo "\n";
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                      ERROR                                  ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    exit(1);
}

