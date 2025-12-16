<?php
/**
 * Script untuk menghapus semua session
 * 
 * Jalankan dengan: php clear_session.php
 * 
 * Gunakan script ini jika ada masalah dengan session yang tersimpan
 * yang menyebabkan redirect otomatis ke dashboard
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

echo "=== Clear Session ===\n\n";

// Clear session files
$sessionPath = storage_path('framework/sessions');
if (File::exists($sessionPath)) {
    $files = File::files($sessionPath);
    $count = 0;
    foreach ($files as $file) {
        if ($file->getExtension() === 'php') {
            File::delete($file);
            $count++;
        }
    }
    echo "✓ Dihapus {$count} file session\n";
} else {
    echo "✗ Folder session tidak ditemukan\n";
}

// Clear session database (jika menggunakan database session)
try {
    if (DB::getSchemaBuilder()->hasTable('sessions')) {
        $deleted = DB::table('sessions')->delete();
        echo "✓ Dihapus {$deleted} record session dari database\n";
    }
} catch (\Exception $e) {
    echo "ℹ Tidak menggunakan database session atau tabel tidak ada\n";
}

// Clear cache
echo "\n=== Clear Cache ===\n";
exec('php artisan config:clear', $output1);
exec('php artisan cache:clear', $output2);
exec('php artisan route:clear', $output3);
exec('php artisan view:clear', $output4);

echo "✓ Config cache cleared\n";
echo "✓ Application cache cleared\n";
echo "✓ Route cache cleared\n";
echo "✓ View cache cleared\n";

echo "\n=== Selesai ===\n";
echo "Sekarang coba akses http://127.0.0.1:8000/ lagi\n";
echo "Anda akan melihat halaman login, bukan redirect ke dashboard\n";

