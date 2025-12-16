<?php
/**
 * Script untuk memperbaiki role user di database
 * Menormalisasi semua role menjadi lowercase dan memastikan konsistensi
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "=== MEMPERBAIKI ROLE USER ===\n\n";

try {
    $users = User::all();
    $fixed = 0;
    $errors = 0;
    
    foreach ($users as $user) {
        $originalRole = $user->role;
        $normalizedRole = strtolower(trim($user->role ?? ''));
        
        // Jika role kosong atau tidak valid
        if (empty($normalizedRole) || !in_array($normalizedRole, ['admin', 'santri'])) {
            // Tentukan role berdasarkan email atau default
            if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                $newRole = 'admin';
            } else {
                $newRole = 'santri';
            }
            
            $user->role = $newRole;
            $user->save();
            echo "✓ User ID {$user->id} ({$user->name}): '{$originalRole}' → '{$newRole}'\n";
            $fixed++;
        } elseif ($normalizedRole !== $originalRole) {
            // Normalisasi role (pastikan lowercase, no whitespace)
            $user->role = $normalizedRole;
            $user->save();
            echo "✓ User ID {$user->id} ({$user->name}): Normalisasi '{$originalRole}' → '{$normalizedRole}'\n";
            $fixed++;
        }
    }
    
    if ($fixed === 0) {
        echo "✓ Semua role user sudah valid\n";
    } else {
        echo "\n✓ Total {$fixed} user diperbaiki\n";
    }
    
    // Verifikasi
    echo "\n=== VERIFIKASI ===\n";
    $adminCount = User::where('role', 'admin')->count();
    $santriCount = User::where('role', 'santri')->count();
    $invalidCount = User::whereNotIn('role', ['admin', 'santri'])->orWhereNull('role')->count();
    
    echo "Admin: {$adminCount}\n";
    echo "Santri: {$santriCount}\n";
    if ($invalidCount > 0) {
        echo "⚠ Invalid: {$invalidCount}\n";
    } else {
        echo "✓ Tidak ada role yang invalid\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== SELESAI ===\n";

