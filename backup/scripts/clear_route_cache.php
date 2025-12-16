<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "=== MEMBERSIHKAN CACHE ROUTE ===\n\n";

try {
    // Clear route cache
    Artisan::call('route:clear');
    echo "✓ Route cache cleared\n";
    
    // Clear config cache
    Artisan::call('config:clear');
    echo "✓ Config cache cleared\n";
    
    // Clear application cache
    Artisan::call('cache:clear');
    echo "✓ Application cache cleared\n";
    
    // Clear view cache
    Artisan::call('view:clear');
    echo "✓ View cache cleared\n";
    
    echo "\n=== SELESAI ===\n";
    echo "Cache sudah dibersihkan. Silakan coba akses /santri/dashboard lagi.\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

