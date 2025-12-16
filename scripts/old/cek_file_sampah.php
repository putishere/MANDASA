<?php

/**
 * Script untuk Mengecek File Sampah/Tidak Terpakai
 * 
 * Script ini akan:
 * 1. Mencari file-file yang mungkin tidak diperlukan lagi
 * 2. Menampilkan file-file perbaikan/temporary
 * 3. Menampilkan file-file yang tidak digunakan di routes
 * 4. Memberikan rekomendasi file yang bisa dihapus
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=" . str_repeat("=", 70) . "=\n";
echo "  CEK FILE SAMPAH / TIDAK TERPAKAI\n";
echo "=" . str_repeat("=", 70) . "=\n\n";

$rootPath = __DIR__;
$filesToCheck = [];

// 1. File-file perbaikan/temporary di root
echo "1. FILE PERBAIKAN/TEMPORARY DI ROOT\n";
echo str_repeat("-", 72) . "\n";

$tempFiles = [
    'fix_*.php',
    'clear_*.php',
    'create_*.php',
    'test_*.php',
    'view_*.php',
    'setup_*.php',
    'add_*.php',
    'jalankan_*.php',
];

$foundTempFiles = [];
foreach ($tempFiles as $pattern) {
    $files = glob($rootPath . '/' . $pattern);
    foreach ($files as $file) {
        $filename = basename($file);
        if (!in_array($filename, ['fix_tahun_masuk.php', 'fix_tahun_masuk_simple.php'])) {
            $foundTempFiles[] = $file;
        }
    }
}

if (count($foundTempFiles) > 0) {
    echo "   ⚠️  Ditemukan " . count($foundTempFiles) . " file temporary:\n\n";
    foreach ($foundTempFiles as $file) {
        $filename = basename($file);
        $size = filesize($file);
        $sizeKB = round($size / 1024, 2);
        $modified = date('Y-m-d H:i:s', filemtime($file));
        
        echo "   📄 {$filename}\n";
        echo "      Size: {$sizeKB} KB\n";
        echo "      Modified: {$modified}\n";
        echo "      Path: {$file}\n";
        echo "\n";
    }
} else {
    echo "   ✅ Tidak ada file temporary ditemukan\n\n";
}

// 2. File dokumentasi yang mungkin tidak diperlukan
echo "\n2. FILE DOKUMENTASI PERBAIKAN (Banyak)\n";
echo str_repeat("-", 72) . "\n";

$docFiles = glob($rootPath . '/*.md');
$fixDocs = [];
$otherDocs = [];

foreach ($docFiles as $file) {
    $filename = basename($file);
    if (stripos($filename, 'FIX') !== false || 
        stripos($filename, 'PERBAIKAN') !== false || 
        stripos($filename, 'SOLUSI') !== false ||
        stripos($filename, 'TROUBLESHOOTING') !== false ||
        stripos($filename, 'VERIFIKASI') !== false ||
        stripos($filename, 'LAPORAN') !== false ||
        stripos($filename, 'CARA_PERBAIKI') !== false ||
        stripos($filename, 'CARA_MEMPERBAIKI') !== false ||
        stripos($filename, 'RINGKASAN') !== false) {
        $fixDocs[] = $file;
    } else {
        $otherDocs[] = $file;
    }
}

if (count($fixDocs) > 0) {
    echo "   ⚠️  Ditemukan " . count($fixDocs) . " file dokumentasi perbaikan:\n\n";
    foreach ($fixDocs as $file) {
        $filename = basename($file);
        $size = filesize($file);
        $sizeKB = round($size / 1024, 2);
        
        echo "   📄 {$filename} ({$sizeKB} KB)\n";
    }
    echo "\n   💡 Rekomendasi: File-file ini bisa dipindahkan ke folder 'docs/old/' atau dihapus jika sudah tidak diperlukan\n\n";
} else {
    echo "   ✅ Tidak ada file dokumentasi perbaikan ditemukan\n\n";
}

// 3. File script yang tidak digunakan di routes
echo "\n3. FILE SCRIPT YANG TIDAK DIGUNAKAN DI ROUTES\n";
echo str_repeat("-", 72) . "=\n";

$routesFile = $rootPath . '/routes/web.php';
$routesContent = file_get_contents($routesFile);

$scriptFiles = glob($rootPath . '/*.php');
$unusedScripts = [];

foreach ($scriptFiles as $file) {
    $filename = basename($file);
    
    // Skip file-file penting
    if (in_array($filename, ['artisan', 'index.php', 'server.php'])) {
        continue;
    }
    
    // Cek apakah file digunakan di routes
    if (strpos($routesContent, $filename) === false) {
        // Cek apakah file ini script perbaikan/temporary
        if (strpos($filename, 'fix_') === 0 || 
            strpos($filename, 'clear_') === 0 || 
            strpos($filename, 'create_') === 0 ||
            strpos($filename, 'test_') === 0 ||
            strpos($filename, 'view_') === 0 ||
            strpos($filename, 'setup_') === 0 ||
            strpos($filename, 'add_') === 0 ||
            strpos($filename, 'jalankan_') === 0) {
            $unusedScripts[] = $file;
        }
    }
}

if (count($unusedScripts) > 0) {
    echo "   ⚠️  Ditemukan " . count($unusedScripts) . " script yang tidak digunakan di routes:\n\n";
    foreach ($unusedScripts as $file) {
        $filename = basename($file);
        $size = filesize($file);
        $sizeKB = round($size / 1024, 2);
        
        echo "   📄 {$filename} ({$sizeKB} KB)\n";
    }
    echo "\n   💡 Rekomendasi: File-file ini bisa dihapus atau dipindahkan ke folder 'scripts/old/'\n\n";
} else {
    echo "   ✅ Semua script digunakan atau tidak ada script yang tidak digunakan\n\n";
}

// 4. Route perbaikan yang masih ada
echo "\n4. ROUTE PERBAIKAN YANG MASIH ADA\n";
echo str_repeat("-", 72) . "\n";

$fixRoutes = [
    '/fix-admin-password',
    '/create-admin-now',
    '/fix-login-admin',
    '/migrate-tahun-masuk',
    '/buat-admin',
    '/fix-all',
    '/fix-login-saput',
    '/force-logout',
];

$foundRoutes = [];
foreach ($fixRoutes as $route) {
    if (strpos($routesContent, $route) !== false) {
        $foundRoutes[] = $route;
    }
}

if (count($foundRoutes) > 0) {
    echo "   ⚠️  Ditemukan " . count($foundRoutes) . " route perbaikan yang masih ada:\n\n";
    foreach ($foundRoutes as $route) {
        echo "   🔗 {$route}\n";
    }
    echo "\n   💡 Rekomendasi: Route-route ini bisa dihapus setelah aplikasi stabil\n\n";
} else {
    echo "   ✅ Tidak ada route perbaikan ditemukan\n\n";
}

// 5. File besar yang mungkin tidak diperlukan
echo "\n5. FILE BESAR (>100KB) YANG MUNGKIN TIDAK DIPERLUKAN\n";
echo str_repeat("-", 72) . "\n";

$largeFiles = [];
$allFiles = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($allFiles as $file) {
    if ($file->isFile() && $file->getSize() > 100 * 1024) { // > 100KB
        $path = $file->getPathname();
        $relativePath = str_replace($rootPath . DIRECTORY_SEPARATOR, '', $path);
        
        // Skip vendor dan node_modules
        if (strpos($relativePath, 'vendor') === 0 || 
            strpos($relativePath, 'node_modules') === 0 ||
            strpos($relativePath, 'storage') === 0 ||
            strpos($relativePath, '.git') === 0) {
            continue;
        }
        
        $sizeKB = round($file->getSize() / 1024, 2);
        $largeFiles[] = [
            'path' => $relativePath,
            'size' => $sizeKB
        ];
    }
}

if (count($largeFiles) > 0) {
    echo "   ⚠️  Ditemukan " . count($largeFiles) . " file besar:\n\n";
    usort($largeFiles, function($a, $b) {
        return $b['size'] <=> $a['size'];
    });
    
    foreach ($largeFiles as $file) {
        echo "   📄 {$file['path']} ({$file['size']} KB)\n";
    }
    echo "\n";
} else {
    echo "   ✅ Tidak ada file besar ditemukan\n\n";
}

// 6. Ringkasan dan Rekomendasi
echo "\n" . str_repeat("=", 70) . "\n";
echo "  RINGKASAN DAN REKOMENDASI\n";
echo str_repeat("=", 70) . "\n\n";

$totalTempFiles = count($foundTempFiles);
$totalFixDocs = count($fixDocs);
$totalUnusedScripts = count($unusedScripts);
$totalFixRoutes = count($foundRoutes);

echo "📊 Statistik:\n";
echo "   - File temporary: {$totalTempFiles}\n";
echo "   - Dokumentasi perbaikan: {$totalFixDocs}\n";
echo "   - Script tidak digunakan: {$totalUnusedScripts}\n";
echo "   - Route perbaikan: {$totalFixRoutes}\n";
echo "\n";

if ($totalTempFiles > 0 || $totalFixDocs > 0 || $totalUnusedScripts > 0 || $totalFixRoutes > 0) {
    echo "💡 REKOMENDASI:\n\n";
    
    if ($totalTempFiles > 0) {
        echo "1. File Temporary:\n";
        echo "   - Buat folder 'scripts/old/' atau 'backup/'\n";
        echo "   - Pindahkan file-file temporary ke folder tersebut\n";
        echo "   - Atau hapus jika sudah tidak diperlukan\n\n";
    }
    
    if ($totalFixDocs > 0) {
        echo "2. Dokumentasi Perbaikan:\n";
        echo "   - Buat folder 'docs/old/' atau 'docs/archive/'\n";
        echo "   - Pindahkan file-file dokumentasi perbaikan ke folder tersebut\n";
        echo "   - Atau hapus jika sudah tidak diperlukan\n\n";
    }
    
    if ($totalFixRoutes > 0) {
        echo "3. Route Perbaikan:\n";
        echo "   - Hapus route-route perbaikan dari routes/web.php\n";
        echo "   - Route-route ini hanya untuk troubleshooting\n\n";
    }
    
    echo "⚠️  PENTING:\n";
    echo "   - Backup file-file penting sebelum menghapus\n";
    echo "   - Pastikan aplikasi sudah stabil sebelum menghapus\n";
    echo "   - File-file ini mungkin masih berguna untuk referensi\n\n";
} else {
    echo "✅ Tidak ada file sampah yang ditemukan!\n";
    echo "   Project Anda sudah bersih.\n\n";
}

echo "=" . str_repeat("=", 70) . "=\n";

