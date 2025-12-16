<?php

/**
 * Script untuk memperbaiki error kolom tahun_masuk
 * Jalankan script ini untuk menambahkan kolom tahun_masuk ke tabel santri_detail
 * 
 * Cara menjalankan:
 * - Buka terminal di folder project
 * - Jalankan: php fix_tahun_masuk.php
 * - Atau jika menggunakan Laragon: C:\laragon\bin\php\php-8.x.x\php.exe fix_tahun_masuk.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     PERBAIKAN KOLOM TAHUN_MASUK                              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

try {
    // Cek apakah tabel ada
    echo "1. Memeriksa tabel santri_detail...\n";
    
    $tableExists = Schema::hasTable('santri_detail');
    if (!$tableExists) {
        echo "   ✗ Tabel 'santri_detail' tidak ditemukan!\n";
        echo "\n   Solusi: Jalankan migration terlebih dahulu\n";
        echo "   php artisan migrate\n\n";
        exit(1);
    }
    
    echo "   ✓ Tabel 'santri_detail' ditemukan\n\n";
    
    // Cek apakah kolom sudah ada
    echo "2. Memeriksa kolom tahun_masuk...\n";
    
    if (config('database.default') === 'sqlite') {
        // Untuk SQLite, gunakan PRAGMA
        $columns = DB::select("PRAGMA table_info(santri_detail)");
        $columnExists = false;
        
        foreach ($columns as $column) {
            if ($column->name === 'tahun_masuk') {
                $columnExists = true;
                break;
            }
        }
    } else {
        $columnExists = Schema::hasColumn('santri_detail', 'tahun_masuk');
    }
    
    if ($columnExists) {
        echo "   ✓ Kolom 'tahun_masuk' sudah ada\n\n";
        echo "   Kolom sudah tersedia. Tidak perlu melakukan perubahan.\n\n";
        exit(0);
    }
    
    echo "   ⚠ Kolom 'tahun_masuk' belum ada, menambahkan...\n\n";
    
    // Tambahkan kolom
    echo "3. Menambahkan kolom tahun_masuk...\n";
    
    if (config('database.default') === 'sqlite') {
        // SQLite tidak mendukung YEAR dan AFTER clause
        DB::statement("ALTER TABLE santri_detail ADD COLUMN tahun_masuk INTEGER");
        echo "   ✓ Kolom 'tahun_masuk' (INTEGER) berhasil ditambahkan\n\n";
    } else {
        // MySQL/MariaDB
        DB::statement("ALTER TABLE santri_detail ADD COLUMN tahun_masuk YEAR NULL AFTER nis");
        echo "   ✓ Kolom 'tahun_masuk' (YEAR) berhasil ditambahkan\n\n";
    }
    
    // Verifikasi
    echo "4. Memverifikasi kolom tahun_masuk...\n";
    
    if (config('database.default') === 'sqlite') {
        $columns = DB::select("PRAGMA table_info(santri_detail)");
        $columnExists = false;
        
        foreach ($columns as $column) {
            if ($column->name === 'tahun_masuk') {
                $columnExists = true;
                break;
            }
        }
    } else {
        $columnExists = Schema::hasColumn('santri_detail', 'tahun_masuk');
    }
    
    if ($columnExists) {
        echo "   ✓ Verifikasi berhasil: Kolom 'tahun_masuk' tersedia\n\n";
    } else {
        echo "   ✗ Error: Kolom tidak ditemukan setelah penambahan\n\n";
        exit(1);
    }
    
    // Update data yang sudah ada (opsional)
    echo "5. Memperbarui data yang sudah ada (opsional)...\n";
    try {
        $updated = DB::table('santri_detail')
            ->whereNull('tahun_masuk')
            ->update(['tahun_masuk' => date('Y')]);
        
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

