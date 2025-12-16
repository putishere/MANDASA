<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

echo "=== CLEAR CONFIG CACHE ===\n\n";

try {
    // Clear config cache
    Artisan::call('config:clear');
    echo "✅ Config cache cleared\n";
    
    // Clear application cache
    Artisan::call('cache:clear');
    echo "✅ Application cache cleared\n";
    
    // Clear route cache
    Artisan::call('route:clear');
    echo "✅ Route cache cleared\n";
    
    // Clear view cache
    Artisan::call('view:clear');
    echo "✅ View cache cleared\n";
    
    // Clear bootstrap cache files
    $bootstrapCachePath = base_path('bootstrap/cache');
    if (File::exists($bootstrapCachePath)) {
        $files = File::files($bootstrapCachePath);
        foreach ($files as $file) {
            if ($file->getExtension() === 'php' && $file->getFilename() !== '.gitignore') {
                File::delete($file);
            }
        }
        echo "✅ Bootstrap cache files cleared\n";
    }
    
    echo "\n=== SELESAI ===\n";
    echo "Session driver sekarang menggunakan 'file' (tidak perlu tabel database).\n";
    echo "Silakan refresh halaman aplikasi.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

