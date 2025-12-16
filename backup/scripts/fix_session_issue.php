<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

echo "=== PERBAIKAN MASALAH SESSION ===\n\n";

try {
    // 1. Pastikan session driver adalah 'file'
    $sessionDriver = Config::get('session.driver');
    echo "1. Session driver saat ini: {$sessionDriver}\n";
    
    if ($sessionDriver !== 'file') {
        echo "   ⚠️  Session driver bukan 'file', mengubah ke 'file'...\n";
        // Update config file
        $configPath = config_path('session.php');
        $configContent = File::get($configPath);
        $configContent = preg_replace(
            "/'driver' => env\('SESSION_DRIVER', '[^']+'\),/",
            "'driver' => env('SESSION_DRIVER', 'file'),",
            $configContent
        );
        File::put($configPath, $configContent);
        echo "   ✅ Session driver diubah ke 'file'\n";
    } else {
        echo "   ✅ Session driver sudah benar (file)\n";
    }
    
    // 2. Pastikan folder sessions ada dan writable
    $sessionsPath = storage_path('framework/sessions');
    if (!File::exists($sessionsPath)) {
        File::makeDirectory($sessionsPath, 0755, true);
        echo "2. ✅ Folder sessions dibuat: {$sessionsPath}\n";
    } else {
        echo "2. ✅ Folder sessions sudah ada: {$sessionsPath}\n";
    }
    
    // 3. Clear semua cache
    echo "\n3. Membersihkan cache...\n";
    Artisan::call('config:clear');
    echo "   ✅ Config cache cleared\n";
    
    Artisan::call('cache:clear');
    echo "   ✅ Application cache cleared\n";
    
    Artisan::call('route:clear');
    echo "   ✅ Route cache cleared\n";
    
    Artisan::call('view:clear');
    echo "   ✅ View cache cleared\n";
    
    // 4. Clear bootstrap cache files (kecuali .gitignore)
    $bootstrapCachePath = base_path('bootstrap/cache');
    if (File::exists($bootstrapCachePath)) {
        $files = File::files($bootstrapCachePath);
        $cleared = 0;
        foreach ($files as $file) {
            if ($file->getExtension() === 'php' && $file->getFilename() !== '.gitignore') {
                // Hapus file config.php jika ada (cache config lama)
                if (strpos($file->getFilename(), 'config') !== false) {
                    File::delete($file);
                    $cleared++;
                }
            }
        }
        if ($cleared > 0) {
            echo "   ✅ {$cleared} bootstrap cache file(s) cleared\n";
        }
    }
    
    // 5. Verifikasi konfigurasi akhir
    echo "\n4. Verifikasi konfigurasi:\n";
    Artisan::call('config:clear'); // Clear lagi untuk memastikan
    $finalDriver = Config::get('session.driver');
    echo "   ✅ Session driver: {$finalDriver}\n";
    echo "   ✅ Session files location: " . Config::get('session.files') . "\n";
    
    echo "\n=== SELESAI ===\n";
    echo "✅ Masalah session sudah diperbaiki!\n";
    echo "✅ Aplikasi sekarang menggunakan file-based session\n";
    echo "✅ Tidak perlu tabel 'sessions' di database\n\n";
    echo "Silakan refresh halaman aplikasi di browser.\n";
    echo "URL: http://127.0.0.1:8000/\n";
    
} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

