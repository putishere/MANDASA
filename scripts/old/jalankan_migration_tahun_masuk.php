<?php

/**
 * Script untuk menjalankan migration tahun_masuk
 * 
 * Jalankan script ini untuk menambahkan kolom tahun_masuk ke tabel santri_detail
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     MIGRATION: TAMBAH KOLOM TAHUN_MASUK                      ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

try {
    // Cek apakah kolom sudah ada
    echo "1. Memeriksa kolom tahun_masuk...\n";
    
    $tableExists = Schema::hasTable('santri_detail');
    if (!$tableExists) {
        echo "   ✗ Tabel 'santri_detail' tidak ditemukan!\n";
        echo "\n   Solusi:\n";
        echo "   - Jalankan: php artisan migrate\n";
        exit(1);
    }
    
    $columnExists = Schema::hasColumn('santri_detail', 'tahun_masuk');
    if ($columnExists) {
        echo "   ✓ Kolom 'tahun_masuk' sudah ada di tabel 'santri_detail'\n\n";
    } else {
        echo "   ⚠ Kolom 'tahun_masuk' belum ada, menjalankan migration...\n\n";
        
        // Jalankan migration
        echo "2. Menjalankan migration...\n";
        try {
            Artisan::call('migrate', ['--path' => 'database/migrations/2025_01_20_000000_add_tahun_masuk_to_santri_detail_table.php']);
            echo "   ✓ Migration berhasil dijalankan\n\n";
        } catch (\Exception $e) {
            echo "   ⚠ Error menjalankan migration: " . $e->getMessage() . "\n";
            echo "   Mencoba menambahkan kolom secara manual...\n";
            
            // Coba tambahkan kolom secara manual
            try {
                // SQLite tidak mendukung YEAR dan AFTER, gunakan INTEGER
                if (config('database.default') === 'sqlite') {
                    DB::statement('ALTER TABLE santri_detail ADD COLUMN tahun_masuk INTEGER');
                } else {
                    DB::statement('ALTER TABLE santri_detail ADD COLUMN tahun_masuk YEAR NULL AFTER nis');
                }
                echo "   ✓ Kolom berhasil ditambahkan secara manual\n\n";
            } catch (\Exception $e2) {
                echo "   ✗ Error: " . $e2->getMessage() . "\n";
                exit(1);
            }
        }
    }
    
    // Verifikasi kolom sudah ada
    echo "3. Memverifikasi kolom tahun_masuk...\n";
    $columnExists = Schema::hasColumn('santri_detail', 'tahun_masuk');
    if ($columnExists) {
        echo "   ✓ Kolom 'tahun_masuk' sudah tersedia\n\n";
    } else {
        echo "   ✗ Kolom 'tahun_masuk' tidak ditemukan setelah migration\n";
        exit(1);
    }
    
    // Update data yang sudah ada dengan tahun default (tahun saat ini)
    echo "4. Memperbarui data yang sudah ada...\n";
    try {
        $updated = DB::table('santri_detail')
            ->whereNull('tahun_masuk')
            ->update(['tahun_masuk' => date('Y')]);
        
        if ($updated > 0) {
            echo "   ✓ {$updated} data santri diperbarui dengan tahun masuk: " . date('Y') . "\n\n";
        } else {
            echo "   ✓ Tidak ada data yang perlu diperbarui\n\n";
        }
    } catch (\Exception $e) {
        echo "   ⚠ Error memperbarui data: " . $e->getMessage() . "\n";
        echo "   (Ini tidak fatal, data baru akan tetap bisa ditambahkan)\n\n";
    }
    
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                    SELESAI                                  ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    echo "✓ Kolom 'tahun_masuk' berhasil ditambahkan!\n";
    echo "\nLangkah selanjutnya:\n";
    echo "1. Refresh halaman tambah/edit santri\n";
    echo "2. Field 'Tahun Masuk' sudah tersedia di form\n";
    echo "3. Data santri baru akan memiliki tahun masuk\n\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

