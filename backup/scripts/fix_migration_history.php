<?php

/**
 * Script untuk memperbaiki migration history yang corrupt
 * Menghapus record migration yang tidak ada file-nya dari tabel migrations
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔧 Memperbaiki Migration History...\n\n";

try {
    // Cek apakah tabel migrations ada
    if (!Schema::hasTable('migrations')) {
        echo "❌ Tabel migrations tidak ditemukan.\n";
        exit(1);
    }

    // Ambil semua migration files yang ada
    $migrationFiles = glob(database_path('migrations/*.php'));
    $existingMigrations = [];
    
    foreach ($migrationFiles as $file) {
        $basename = basename($file);
        // Extract migration name tanpa timestamp
        if (preg_match('/^\d{4}_\d{2}_\d{2}_\d{6}_(.+)$/', $basename, $matches)) {
            $existingMigrations[] = $basename;
        }
    }

    echo "📁 Migration files yang ditemukan: " . count($existingMigrations) . "\n";

    // Ambil semua record dari tabel migrations
    $migrationRecords = DB::table('migrations')->get();
    echo "📋 Migration records di database: " . count($migrationRecords) . "\n\n";

    $deletedCount = 0;
    $keptCount = 0;

    foreach ($migrationRecords as $record) {
        $migrationName = $record->migration;
        
        // Cek apakah file migration masih ada
        $found = false;
        foreach ($existingMigrations as $file) {
            if (strpos($file, $migrationName) !== false) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            echo "🗑️  Menghapus record migration yang tidak ada file-nya: {$migrationName}\n";
            DB::table('migrations')->where('id', $record->id)->delete();
            $deletedCount++;
        } else {
            $keptCount++;
        }
    }

    echo "\n✅ Selesai!\n";
    echo "📊 Statistik:\n";
    echo "   - Record yang dihapus: {$deletedCount}\n";
    echo "   - Record yang dipertahankan: {$keptCount}\n";
    echo "   - Total record sekarang: " . DB::table('migrations')->count() . "\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

