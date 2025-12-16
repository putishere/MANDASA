<?php
/**
 * Script untuk memperbaiki role user di database
 * 
 * Jalankan dengan: php fix_role_user.php
 * Atau melalui tinker: php artisan tinker
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Script Perbaikan Role User ===\n\n";

// Cek semua user dan role mereka
$users = User::all();

echo "Daftar User dan Role:\n";
echo str_repeat("-", 80) . "\n";
printf("%-5s %-30s %-20s %-15s %-10s\n", "ID", "Name", "Email/Username", "Raw Role", "Status");
echo str_repeat("-", 80) . "\n";

$fixed = 0;
foreach ($users as $user) {
    $rawRole = $user->role;
    $normalizedRole = strtolower(trim($rawRole ?? ''));
    $status = 'OK';
    
    // Cek masalah
    if (empty($normalizedRole) || $normalizedRole === '') {
        $status = 'EMPTY';
    } elseif (!in_array($normalizedRole, ['admin', 'santri'])) {
        $status = 'INVALID';
    } elseif ($rawRole !== $normalizedRole) {
        $status = 'NEEDS FIX';
    }
    
    $identifier = $user->email ?? $user->username ?? 'N/A';
    printf("%-5d %-30s %-20s %-15s %-10s\n", 
        $user->id, 
        substr($user->name, 0, 30), 
        substr($identifier, 0, 20),
        $rawRole ?? 'NULL',
        $status
    );
    
    // Perbaiki jika perlu
    if ($status !== 'OK') {
        // Tentukan role yang benar berdasarkan data user
        $correctRole = null;
        
        if ($user->email && !$user->username) {
            // User dengan email biasanya admin
            $correctRole = 'admin';
        } elseif ($user->username && !$user->email) {
            // User dengan username biasanya santri
            $correctRole = 'santri';
        } elseif ($user->email && $user->username) {
            // Jika ada keduanya, cek apakah ada santriDetail
            if ($user->santriDetail) {
                $correctRole = 'santri';
            } else {
                $correctRole = 'admin';
            }
        }
        
        if ($correctRole) {
            $user->role = $correctRole;
            $user->save();
            echo "  -> Diperbaiki menjadi: {$correctRole}\n";
            $fixed++;
        } else {
            echo "  -> TIDAK BISA DIPERBAIKI OTOMATIS (perlu input manual)\n";
        }
    }
}

echo str_repeat("-", 80) . "\n";
echo "\nTotal user: " . $users->count() . "\n";
echo "User yang diperbaiki: {$fixed}\n";
echo "\n=== Selesai ===\n";

