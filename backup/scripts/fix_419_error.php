<?php
/**
 * Script untuk memperbaiki error 419 PAGE EXPIRED
 * Error ini biasanya terjadi karena session expired atau CSRF token tidak valid
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     PERBAIKAN ERROR 419 PAGE EXPIRED                        ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

try {
    // 1. Clear session files
    echo "1. Menghapus file session...\n";
    $sessionPath = storage_path('framework/sessions');
    
    if (File::exists($sessionPath)) {
        $files = File::files($sessionPath);
        $deleted = 0;
        
        foreach ($files as $file) {
            if (File::delete($file)) {
                $deleted++;
            }
        }
        
        echo "   ✓ {$deleted} file session dihapus\n\n";
    } else {
        echo "   ⚠ Folder session tidak ditemukan\n\n";
    }
    
    // 2. Clear cache
    echo "2. Menghapus cache...\n";
    try {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        echo "   ✓ Cache berhasil dihapus\n\n";
    } catch (\Exception $e) {
        echo "   ⚠ Error menghapus cache: " . $e->getMessage() . "\n\n";
    }
    
    // 3. Clear compiled views
    echo "3. Menghapus compiled views...\n";
    $compiledPath = storage_path('framework/views');
    
    if (File::exists($compiledPath)) {
        $files = File::files($compiledPath);
        $deleted = 0;
        
        foreach ($files as $file) {
            if (File::delete($file)) {
                $deleted++;
            }
        }
        
        echo "   ✓ {$deleted} compiled view dihapus\n\n";
    } else {
        echo "   ⚠ Folder compiled views tidak ditemukan\n\n";
    }
    
    // 4. Regenerate app key jika perlu (opsional)
    echo "4. Memverifikasi APP_KEY...\n";
    $envPath = base_path('.env');
    
    if (File::exists($envPath)) {
        $envContent = File::get($envPath);
        
        if (strpos($envContent, 'APP_KEY=') === false || 
            strpos($envContent, 'APP_KEY=') !== false && 
            (strpos($envContent, 'APP_KEY=') === strpos($envContent, 'APP_KEY=base64:')) === false &&
            strpos($envContent, 'APP_KEY=base64:') === false) {
            
            echo "   ⚠ APP_KEY mungkin tidak valid\n";
            echo "   (Tidak perlu regenerate jika aplikasi sudah berjalan)\n\n";
        } else {
            echo "   ✓ APP_KEY sudah ada\n\n";
        }
    } else {
        echo "   ⚠ File .env tidak ditemukan\n\n";
    }
    
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                    SELESAI                                  ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    echo "✓ Perbaikan selesai!\n\n";
    echo "Langkah selanjutnya:\n";
    echo "1. Hapus cookie browser:\n";
    echo "   - Buka Developer Tools (F12)\n";
    echo "   - Tab Application/Storage → Cookies\n";
    echo "   - Hapus semua cookie untuk domain aplikasi\n";
    echo "   - Atau gunakan mode Incognito/Private Browsing\n\n";
    echo "2. Restart server (jika menggunakan php artisan serve):\n";
    echo "   - Tekan Ctrl+C untuk stop server\n";
    echo "   - Jalankan lagi: php artisan serve\n\n";
    echo "3. Refresh halaman browser\n";
    echo "4. Login ulang jika diperlukan\n\n";
    
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

