<?php

/**
 * Script untuk menambahkan kolom tahun_masuk ke tabel santri_detail
 * Jalankan dengan: php add_tahun_masuk_column.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

try {
    echo "Memeriksa struktur tabel santri_detail...\n";
    
    // Cek apakah kolom sudah ada
    $columns = DB::select("PRAGMA table_info(santri_detail)");
    $columnExists = false;
    
    foreach ($columns as $column) {
        if ($column->name === 'tahun_masuk') {
            $columnExists = true;
            break;
        }
    }
    
    if ($columnExists) {
        echo "✓ Kolom tahun_masuk sudah ada di tabel santri_detail.\n";
        exit(0);
    }
    
    echo "Menambahkan kolom tahun_masuk ke tabel santri_detail...\n";
    
    // Tambahkan kolom tahun_masuk
    // SQLite tidak mendukung tipe YEAR, gunakan INTEGER
    // SQLite juga tidak mendukung AFTER clause
    if (config('database.default') === 'sqlite') {
        DB::statement("ALTER TABLE santri_detail ADD COLUMN tahun_masuk INTEGER");
    } else {
        DB::statement("ALTER TABLE santri_detail ADD COLUMN tahun_masuk YEAR NULL AFTER nis");
    }
    
    echo "✓ Kolom tahun_masuk berhasil ditambahkan!\n";
    
    // Verifikasi
    $columns = DB::select("PRAGMA table_info(santri_detail)");
    $columnExists = false;
    
    foreach ($columns as $column) {
        if ($column->name === 'tahun_masuk') {
            $columnExists = true;
            echo "✓ Verifikasi: Kolom tahun_masuk berhasil ditambahkan.\n";
            break;
        }
    }
    
    if (!$columnExists) {
        echo "✗ Error: Kolom tahun_masuk tidak ditemukan setelah penambahan.\n";
        exit(1);
    }
    
    echo "\nSelesai! Kolom tahun_masuk telah ditambahkan ke tabel santri_detail.\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

