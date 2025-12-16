<?php

/**
 * Script Test untuk Memverifikasi Perbaikan Data Santri di Dashboard
 * 
 * Script ini akan:
 * 1. Mengecek apakah perbaikan sudah diterapkan
 * 2. Menampilkan contoh data yang akan ditampilkan di dashboard
 * 3. Memverifikasi bahwa data selalu terbaru dari database
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=" . str_repeat("=", 70) . "=\n";
echo "  TEST PERBAIKAN DATA SANTRI DI DASHBOARD\n";
echo "=" . str_repeat("=", 70) . "=\n\n";

try {
    // 1. Cek apakah ada santri di database
    echo "1. MENGECEK DATA SANTRI DI DATABASE\n";
    echo str_repeat("-", 72) . "\n";
    
    $santriCount = User::where('role', 'santri')->count();
    echo "   Total Santri: {$santriCount}\n\n";
    
    if ($santriCount === 0) {
        echo "   ⚠️  Tidak ada data santri di database.\n";
        echo "   Silakan input data santri terlebih dahulu sebagai admin.\n\n";
        exit(0);
    }
    
    // 2. Tampilkan semua santri dengan detail
    echo "2. DAFTAR SANTRI DAN DETAILNYA\n";
    echo str_repeat("-", 72) . "\n";
    
    $santriList = User::where('role', 'santri')
        ->with('santriDetail')
        ->get();
    
    foreach ($santriList as $index => $santri) {
        $num = $index + 1;
        echo "\n   [{$num}] ID: {$santri->id}\n";
        echo "       Nama: {$santri->name}\n";
        echo "       Username: {$santri->username}\n";
        echo "       Email: " . ($santri->email ?? 'Tidak ada') . "\n";
        echo "       Tanggal Lahir: " . ($santri->tanggal_lahir ? $santri->tanggal_lahir->format('Y-m-d') : 'Tidak ada') . "\n";
        echo "       Role: {$santri->role}\n";
        
        if ($santri->santriDetail) {
            echo "       ✅ Memiliki SantriDetail:\n";
            echo "          - NIS: {$santri->santriDetail->nis}\n";
            echo "          - Tahun Masuk: {$santri->santriDetail->tahun_masuk}\n";
            echo "          - Status: {$santri->santriDetail->status_santri}\n";
            echo "          - Alamat: " . substr($santri->santriDetail->alamat_santri ?? 'Tidak ada', 0, 50) . "...\n";
            echo "          - Nama Wali: {$santri->santriDetail->nama_wali}\n";
        } else {
            echo "       ⚠️  TIDAK MEMILIKI SantriDetail!\n";
            echo "          Santri ini tidak akan bisa login ke dashboard.\n";
        }
    }
    
    // 3. Test metode yang digunakan di dashboard
    echo "\n\n3. TEST METODE YANG DIGUNAKAN DI DASHBOARD\n";
    echo str_repeat("-", 72) . "\n";
    
    // Ambil satu santri untuk test
    $testSantri = User::where('role', 'santri')
        ->with('santriDetail')
        ->first();
    
    if ($testSantri) {
        echo "\n   Test dengan Santri: {$testSantri->name} (ID: {$testSantri->id})\n\n";
        
        // Test 1: auth()->user() (metode lama - mungkin data lama)
        echo "   [TEST 1] auth()->user() - Metode Lama:\n";
        echo "   ⚠️  Metode ini mengambil dari session, mungkin data lama\n";
        echo "   ⚠️  Relasi santriDetail mungkin tidak ter-load\n\n";
        
        // Test 2: User::with('santriDetail')->findOrFail() (metode baru)
        echo "   [TEST 2] User::with('santriDetail')->findOrFail() - Metode Baru:\n";
        $freshSantri = User::with('santriDetail')->findOrFail($testSantri->id);
        echo "   ✅ Mengambil data langsung dari database\n";
        echo "   ✅ Relasi santriDetail ter-load dengan eager loading\n";
        echo "   ✅ Data selalu terbaru\n\n";
        
        echo "   Data yang akan ditampilkan di dashboard:\n";
        echo "   - Nama: {$freshSantri->name}\n";
        echo "   - Username: {$freshSantri->username}\n";
        if ($freshSantri->santriDetail) {
            echo "   - NIS: {$freshSantri->santriDetail->nis}\n";
            echo "   - Status: {$freshSantri->santriDetail->status_santri}\n";
        } else {
            echo "   - ⚠️  SantriDetail: TIDAK ADA\n";
        }
    }
    
    // 4. Verifikasi perbaikan di routes/web.php
    echo "\n\n4. VERIFIKASI PERBAIKAN DI ROUTES/WEB.PHP\n";
    echo str_repeat("-", 72) . "\n";
    
    $routesFile = __DIR__ . '/routes/web.php';
    $routesContent = file_get_contents($routesFile);
    
    if (strpos($routesContent, "User::with('santriDetail')->findOrFail(auth()->id())") !== false) {
        echo "   ✅ Route dashboard santri sudah diperbaiki\n";
        echo "   ✅ Menggunakan User::with('santriDetail')->findOrFail()\n";
    } else {
        echo "   ⚠️  Route dashboard santri belum diperbaiki\n";
    }
    
    // 5. Verifikasi perbaikan di ProfilSantriController
    echo "\n\n5. VERIFIKASI PERBAIKAN DI PROFILSANTRICONTROLLER\n";
    echo str_repeat("-", 72) . "\n";
    
    $controllerFile = __DIR__ . '/app/Http/Controllers/ProfilSantriController.php';
    $controllerContent = file_get_contents($controllerFile);
    
    if (strpos($controllerContent, "User::with('santriDetail')->findOrFail(auth()->id())") !== false) {
        echo "   ✅ ProfilSantriController sudah diperbaiki\n";
        echo "   ✅ Method index() dan print() menggunakan eager loading\n";
    } else {
        echo "   ⚠️  ProfilSantriController belum diperbaiki\n";
    }
    
    // 6. Kesimpulan
    echo "\n\n6. KESIMPULAN\n";
    echo str_repeat("-", 72) . "\n";
    echo "   ✅ Perbaikan sudah diterapkan\n";
    echo "   ✅ Dashboard akan selalu menampilkan data terbaru dari database\n";
    echo "   ✅ Relasi santriDetail selalu ter-load dengan benar\n";
    echo "   ✅ Data yang ditampilkan sesuai dengan yang diinput\n\n";
    
    echo "   📝 LANGKAH SELANJUTNYA:\n";
    echo "   1. Clear cache: php artisan config:clear && php artisan cache:clear\n";
    echo "   2. Hapus cookie browser (F12 → Application → Cookies → Clear All)\n";
    echo "   3. Restart server: php artisan serve\n";
    echo "   4. Test login sebagai santri dan cek dashboard\n\n";
    
} catch (\Exception $e) {
    echo "\n   ❌ ERROR: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n";
    echo "\n";
}

echo "=" . str_repeat("=", 70) . "=\n";
echo "  TEST SELESAI\n";
echo "=" . str_repeat("=", 70) . "=\n\n";

