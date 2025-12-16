<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== MEMPERBAIKI PASSWORD SANTRI ===\n\n";

try {
    $santri = User::where('role', 'santri')->get();
    
    if ($santri->count() === 0) {
        echo "Tidak ada santri ditemukan.\n";
        exit;
    }
    
    $fixed = 0;
    $checked = 0;
    
    foreach ($santri as $user) {
        $checked++;
        echo "Memeriksa santri ID {$user->id} - Username: {$user->username}\n";
        
        if (!$user->tanggal_lahir) {
            echo "  ⚠ Tidak ada tanggal lahir, skip...\n";
            continue;
        }
        
        $tanggalLahirFormatted = $user->tanggal_lahir->format('Y-m-d');
        echo "  Tanggal lahir: {$tanggalLahirFormatted}\n";
        
        // Cek apakah password sudah sesuai dengan tanggal lahir
        $passwordMatch = Hash::check($tanggalLahirFormatted, $user->password);
        
        if (!$passwordMatch) {
            echo "  ⚠ Password tidak sesuai dengan tanggal lahir, memperbaiki...\n";
            $user->password = Hash::make($tanggalLahirFormatted);
            $user->save();
            echo "  ✓ Password diperbaiki\n";
            $fixed++;
        } else {
            echo "  ✓ Password sudah sesuai\n";
        }
        
        echo "\n";
    }
    
    echo "=== SELESAI ===\n";
    echo "Total santri diperiksa: {$checked}\n";
    echo "Password yang diperbaiki: {$fixed}\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

