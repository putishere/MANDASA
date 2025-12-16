<?php

/**
 * Script untuk Mengorganisir Susunan File Project
 * 
 * Script ini akan:
 * 1. Membuat struktur folder yang rapi
 * 2. Memindahkan file-file ke folder yang sesuai
 * 3. Membuat dokumentasi struktur folder
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=" . str_repeat("=", 70) . "=\n";
echo "  ORGANISIR SUSUNAN FILE PROJECT\n";
echo "=" . str_repeat("=", 70) . "=\n\n";

$rootPath = __DIR__;

// Struktur folder yang akan dibuat
$folderStructure = [
    'scripts' => [
        'old' => 'Script perbaikan/temporary yang sudah tidak digunakan',
        'tools' => 'Script tools yang masih digunakan',
    ],
    'docs' => [
        'old' => 'Dokumentasi perbaikan yang sudah tidak relevan',
        'archive' => 'Dokumentasi archive',
        'guides' => 'Panduan dan tutorial',
    ],
    'backup' => [
        'scripts' => 'Backup script',
        'docs' => 'Backup dokumentasi',
    ],
];

// 1. Buat struktur folder
echo "1. MEMBUAT STRUKTUR FOLDER\n";
echo str_repeat("-", 72) . "\n";

foreach ($folderStructure as $mainFolder => $subFolders) {
    $mainPath = $rootPath . '/' . $mainFolder;
    
    if (!is_dir($mainPath)) {
        mkdir($mainPath, 0755, true);
        echo "   ✅ Folder dibuat: {$mainFolder}/\n";
    } else {
        echo "   ℹ️  Folder sudah ada: {$mainFolder}/\n";
    }
    
    foreach ($subFolders as $subFolder => $description) {
        $subPath = $mainPath . '/' . $subFolder;
        
        if (!is_dir($subPath)) {
            mkdir($subPath, 0755, true);
            echo "   ✅ Folder dibuat: {$mainFolder}/{$subFolder}/\n";
        } else {
            echo "   ℹ️  Folder sudah ada: {$mainFolder}/{$subFolder}/\n";
        }
        
        // Buat file README di setiap folder
        $readmeFile = $subPath . '/README.md';
        if (!file_exists($readmeFile)) {
            $readmeContent = "# {$mainFolder}/{$subFolder}\n\n";
            $readmeContent .= "{$description}\n\n";
            $readmeContent .= "Folder ini berisi file-file yang dipindahkan dari root folder untuk organisasi yang lebih baik.\n";
            file_put_contents($readmeFile, $readmeContent);
        }
    }
}

echo "\n";

// 2. Pindahkan file script temporary ke scripts/old/
echo "2. MEMINDAHKAN FILE SCRIPT TEMPORARY\n";
echo str_repeat("-", 72) . "\n";

$tempScripts = [
    'fix_*.php',
    'clear_*.php',
    'create_*.php',
    'test_*.php',
    'view_*.php',
    'setup_*.php',
    'add_*.php',
    'jalankan_*.php',
    'force_logout_all.php',
    'cek_file_sampah.php',
    'backup_file_sampah.php',
    'hapus_file_sampah.php',
    'organisir_file.php', // Script ini sendiri akan dipindahkan terakhir
];

$movedScripts = [];
$scriptsOldDir = $rootPath . '/scripts/old';

foreach ($tempScripts as $pattern) {
    $files = glob($rootPath . '/' . $pattern);
    foreach ($files as $file) {
        $filename = basename($file);
        
        // Skip file penting
        if (in_array($filename, ['artisan', 'index.php', 'server.php'])) {
            continue;
        }
        
        // Skip script ini sendiri (akan dipindahkan terakhir)
        if ($filename === 'organisir_file.php') {
            continue;
        }
        
        $destFile = $scriptsOldDir . '/' . $filename;
        
        try {
            if (file_exists($destFile)) {
                // Jika file sudah ada, tambahkan timestamp
                $timestamp = date('Y-m-d_His');
                $nameParts = pathinfo($filename);
                $destFile = $scriptsOldDir . '/' . $nameParts['filename'] . '_' . $timestamp . '.' . $nameParts['extension'];
            }
            
            if (rename($file, $destFile)) {
                $movedScripts[] = $filename;
                echo "   ✅ Dipindahkan: {$filename} → scripts/old/\n";
            } else {
                echo "   ❌ Gagal memindahkan: {$filename}\n";
            }
        } catch (\Exception $e) {
            echo "   ❌ Error: {$filename} - " . $e->getMessage() . "\n";
        }
    }
}

if (count($movedScripts) === 0) {
    echo "   ℹ️  Tidak ada file script temporary ditemukan atau sudah dipindahkan\n";
}

echo "\n";

// 3. Pindahkan dokumentasi perbaikan ke docs/old/
echo "3. MEMINDAHKAN DOKUMENTASI PERBAIKAN\n";
echo str_repeat("-", 72) . "\n";

$docFiles = glob($rootPath . '/*.md');
$importantDocs = [
    'README.md',
    'ALUR_APLIKASI.md',
    'ALUR_APLIKASI_LENGKAP.md',
    'ROUTES_DOCUMENTATION.md',
    'INFORMASI_DATABASE.md',
    'PANDUAN_DEPLOY_HOSTING.md',
    'PANDUAN_DEPLOY_INFINITYFREE.md',
    'CHECKLIST_DEPLOY_INFINITYFREE.md',
    'CHECKLIST_SEBELUM_DEPLOY.md',
    'QUICK_START_DEPLOY.md',
    'DOMAIN_DAN_HOSTING_GRATIS.md',
    'CARA_LIHAT_DATABASE.md',
    'CARA_BUAT_DATABASE.md',
    'CARA_MENJALANKAN_APLIKASI.md',
    'PANDUAN_SETUP.md',
    'PANDUAN_KONVERSI_KE_WORD.md',
    'TEMPLATE_FORMAT_SKRIPSI.md',
    'QUICK_CONVERT.md',
    'BAB_2_LANDASAN_TEORI.md',
];

$fixDocs = [];
$docsOldDir = $rootPath . '/docs/old';

foreach ($docFiles as $file) {
    $filename = basename($file);
    
    // Skip dokumentasi penting
    if (in_array($filename, $importantDocs)) {
        continue;
    }
    
    // Cek apakah file dokumentasi perbaikan
    if (stripos($filename, 'FIX') !== false || 
        stripos($filename, 'PERBAIKAN') !== false || 
        stripos($filename, 'SOLUSI') !== false ||
        stripos($filename, 'TROUBLESHOOTING') !== false ||
        stripos($filename, 'VERIFIKASI') !== false ||
        stripos($filename, 'LAPORAN') !== false ||
        stripos($filename, 'CARA_PERBAIKI') !== false ||
        stripos($filename, 'CARA_MEMPERBAIKI') !== false ||
        stripos($filename, 'RINGKASAN') !== false ||
        stripos($filename, 'STATUS') !== false ||
        stripos($filename, 'TAMBAHAN') !== false ||
        stripos($filename, 'PERBEDAAN') !== false ||
        stripos($filename, 'STUDI_KASUS') !== false ||
        stripos($filename, 'CARA_BACKUP') !== false ||
        stripos($filename, 'CARA_CEK') !== false ||
        stripos($filename, 'SOLUSI_LOGIN') !== false) {
        $fixDocs[] = $file;
    }
}

$movedDocs = [];
foreach ($fixDocs as $file) {
    $filename = basename($file);
    $destFile = $docsOldDir . '/' . $filename;
    
    try {
        if (file_exists($destFile)) {
            // Jika file sudah ada, tambahkan timestamp
            $timestamp = date('Y-m-d_His');
            $nameParts = pathinfo($filename);
            $destFile = $docsOldDir . '/' . $nameParts['filename'] . '_' . $timestamp . '.' . $nameParts['extension'];
        }
        
        if (rename($file, $destFile)) {
            $movedDocs[] = $filename;
            echo "   ✅ Dipindahkan: {$filename} → docs/old/\n";
        } else {
            echo "   ❌ Gagal memindahkan: {$filename}\n";
        }
    } catch (\Exception $e) {
        echo "   ❌ Error: {$filename} - " . $e->getMessage() . "\n";
    }
}

if (count($movedDocs) === 0) {
    echo "   ℹ️  Tidak ada dokumentasi perbaikan ditemukan atau sudah dipindahkan\n";
}

echo "\n";

// 4. Buat file struktur folder
echo "4. MEMBUAT DOKUMENTASI STRUKTUR FOLDER\n";
echo str_repeat("-", 72) . "\n";

$structureDoc = $rootPath . '/STRUKTUR_FOLDER.md';
$structureContent = "# 📁 Struktur Folder Project\n\n";
$structureContent .= "Dokumentasi struktur folder project setelah organisasi.\n\n";
$structureContent .= "## 📂 Struktur Folder\n\n";
$structureContent .= "```\n";
$structureContent .= "project/\n";
$structureContent .= "├── app/                    # Application code\n";
$structureContent .= "│   ├── Http/\n";
$structureContent .= "│   │   ├── Controllers/     # Controllers\n";
$structureContent .= "│   │   └── Middleware/      # Middleware\n";
$structureContent .= "│   ├── Models/              # Models\n";
$structureContent .= "│   └── Providers/           # Service Providers\n";
$structureContent .= "├── bootstrap/               # Bootstrap files\n";
$structureContent .= "├── config/                  # Configuration files\n";
$structureContent .= "├── database/                # Database files\n";
$structureContent .= "│   ├── migrations/          # Migrations\n";
$structureContent .= "│   └── seeders/             # Seeders\n";
$structureContent .= "├── public/                  # Public assets\n";
$structureContent .= "├── resources/               # Resources\n";
$structureContent .= "│   ├── views/               # Blade templates\n";
$structureContent .= "│   ├── css/                 # CSS files\n";
$structureContent .= "│   └── js/                  # JavaScript files\n";
$structureContent .= "├── routes/                  # Route files\n";
$structureContent .= "├── storage/                 # Storage files\n";
$structureContent .= "├── tests/                   # Test files\n";
$structureContent .= "├── vendor/                  # Composer dependencies\n";
$structureContent .= "├── scripts/                 # Script files\n";
$structureContent .= "│   ├── old/                 # Script perbaikan/temporary (sudah tidak digunakan)\n";
$structureContent .= "│   └── tools/                # Script tools yang masih digunakan\n";
$structureContent .= "├── docs/                    # Dokumentasi\n";
$structureContent .= "│   ├── old/                 # Dokumentasi perbaikan (sudah tidak relevan)\n";
$structureContent .= "│   ├── archive/             # Dokumentasi archive\n";
$structureContent .= "│   └── guides/              # Panduan dan tutorial\n";
$structureContent .= "├── backup/                  # Backup files\n";
$structureContent .= "│   ├── scripts/             # Backup script\n";
$structureContent .= "│   └── docs/                # Backup dokumentasi\n";
$structureContent .= "├── README.md                # Dokumentasi utama\n";
$structureContent .= "├── composer.json            # Composer dependencies\n";
$structureContent .= "├── package.json             # NPM dependencies\n";
$structureContent .= "└── .env                     # Environment configuration\n";
$structureContent .= "```\n\n";

$structureContent .= "## 📝 Keterangan Folder\n\n";

$structureContent .= "### scripts/\n";
$structureContent .= "- **old/**: Script perbaikan/temporary yang sudah tidak digunakan\n";
$structureContent .= "- **tools/**: Script tools yang masih digunakan\n\n";

$structureContent .= "### docs/\n";
$structureContent .= "- **old/**: Dokumentasi perbaikan yang sudah tidak relevan\n";
$structureContent .= "- **archive/**: Dokumentasi archive\n";
$structureContent .= "- **guides/**: Panduan dan tutorial\n\n";

$structureContent .= "### backup/\n";
$structureContent .= "- **scripts/**: Backup script\n";
$structureContent .= "- **docs/**: Backup dokumentasi\n\n";

$structureContent .= "## 📄 File Penting di Root\n\n";
$structureContent .= "File-file berikut tetap berada di root folder karena penting untuk aplikasi:\n\n";
$structureContent .= "- `README.md` - Dokumentasi utama\n";
$structureContent .= "- `composer.json` - Composer dependencies\n";
$structureContent .= "- `package.json` - NPM dependencies\n";
$structureContent .= "- `.env` - Environment configuration\n";
$structureContent .= "- `artisan` - Laravel artisan command\n";
$structureContent .= "- File konfigurasi lainnya\n\n";

$structureContent .= "## 🔄 File yang Dipindahkan\n\n";
$structureContent .= "### Script Temporary:\n";
foreach ($movedScripts as $script) {
    $structureContent .= "- `{$script}` → `scripts/old/`\n";
}

$structureContent .= "\n### Dokumentasi Perbaikan:\n";
foreach ($movedDocs as $doc) {
    $structureContent .= "- `{$doc}` → `docs/old/`\n";
}

$structureContent .= "\n## ✅ Status\n\n";
$structureContent .= "**Tanggal Organisasi:** " . date('Y-m-d H:i:s') . "\n\n";
$structureContent .= "- ✅ Struktur folder sudah dibuat\n";
$structureContent .= "- ✅ File script temporary sudah dipindahkan\n";
$structureContent .= "- ✅ Dokumentasi perbaikan sudah dipindahkan\n";
$structureContent .= "- ✅ Root folder lebih bersih dan terorganisir\n\n";

file_put_contents($structureDoc, $structureContent);
echo "   ✅ Dokumentasi struktur folder dibuat: STRUKTUR_FOLDER.md\n\n";

// 5. Ringkasan
echo "\n" . str_repeat("=", 70) . "\n";
echo "  RINGKASAN ORGANISASI\n";
echo str_repeat("=", 70) . "\n\n";

echo "📊 Statistik:\n";
echo "   - Script yang dipindahkan: " . count($movedScripts) . "\n";
echo "   - Dokumentasi yang dipindahkan: " . count($movedDocs) . "\n";
echo "   - Total file dipindahkan: " . (count($movedScripts) + count($movedDocs)) . "\n";
echo "\n";

echo "📁 Struktur Folder:\n";
echo "   - scripts/old/ - Script temporary\n";
echo "   - docs/old/ - Dokumentasi perbaikan\n";
echo "   - backup/ - Backup files\n";
echo "\n";

echo "✅ ORGANISASI SELESAI!\n\n";
echo "💡 CATATAN:\n";
echo "   - File-file sudah dipindahkan ke folder yang sesuai\n";
echo "   - Root folder sekarang lebih bersih\n";
echo "   - Dokumentasi struktur folder: STRUKTUR_FOLDER.md\n";
echo "   - File-file masih bisa diakses di folder baru\n\n";

echo "=" . str_repeat("=", 70) . "=\n";

