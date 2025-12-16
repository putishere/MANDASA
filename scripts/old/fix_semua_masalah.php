<?php

/**
 * Script Perbaikan Lengkap - Managemen Data Santri
 * 
 * Script ini akan memperbaiki semua masalah yang mungkin terjadi:
 * 1. Membersihkan cache dan session
 * 2. Memperbaiki role user di database
 * 3. Membuat admin default jika tidak ada
 * 4. Memperbaiki user santri yang bermasalah
 * 5. Memastikan semua user memiliki data yang benar
 * 6. Membuat storage link jika belum ada
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\SantriDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║   SCRIPT PERBAIKAN LENGKAP - MANAGEMEN DATA SANTRI         ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$errors = [];
$warnings = [];
$success = [];

try {
    // ============================================================
    // 1. CLEAR CACHE DAN SESSION
    // ============================================================
    echo "1. Membersihkan cache dan session...\n";
    
    try {
        Artisan::call('config:clear');
        $success[] = "✓ Config cache cleared";
    } catch (\Exception $e) {
        $warnings[] = "⚠ Config cache clear failed: " . $e->getMessage();
    }
    
    try {
        Artisan::call('cache:clear');
        $success[] = "✓ Application cache cleared";
    } catch (\Exception $e) {
        $warnings[] = "⚠ Application cache clear failed: " . $e->getMessage();
    }
    
    try {
        Artisan::call('route:clear');
        $success[] = "✓ Route cache cleared";
    } catch (\Exception $e) {
        $warnings[] = "⚠ Route cache clear failed: " . $e->getMessage();
    }
    
    try {
        Artisan::call('view:clear');
        $success[] = "✓ View cache cleared";
    } catch (\Exception $e) {
        $warnings[] = "⚠ View cache clear failed: " . $e->getMessage();
    }
    
    try {
        Artisan::call('optimize:clear');
        $success[] = "✓ Optimize cache cleared";
    } catch (\Exception $e) {
        $warnings[] = "⚠ Optimize cache clear failed: " . $e->getMessage();
    }
    
    // Clear session files
    try {
        $sessionPath = storage_path('framework/sessions');
        if (File::exists($sessionPath)) {
            $files = File::files($sessionPath);
            $deleted = 0;
            foreach ($files as $file) {
                if (basename($file) !== '.gitignore') {
                    File::delete($file);
                    $deleted++;
                }
            }
            if ($deleted > 0) {
                $success[] = "✓ {$deleted} session files deleted";
            } else {
                $success[] = "✓ No session files to delete";
            }
        }
    } catch (\Exception $e) {
        $warnings[] = "⚠ Session files clear failed: " . $e->getMessage();
    }
    
    echo "   Selesai!\n\n";
    
    // ============================================================
    // 2. PERBAIKI ROLE USER DI DATABASE
    // ============================================================
    echo "2. Memperbaiki role user di database...\n";
    
    try {
        $users = User::all();
        $fixed = 0;
        
        foreach ($users as $user) {
            $originalRole = $user->role;
            $userRole = strtolower(trim($user->role ?? ''));
            
            // Jika role kosong atau tidak valid, tentukan berdasarkan data
            if (empty($userRole) || !in_array($userRole, ['admin', 'santri'])) {
                if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL) && !$user->username) {
                    $userRole = 'admin';
                } elseif ($user->username) {
                    $userRole = 'santri';
                } else {
                    $userRole = 'admin'; // default
                }
                
                $user->role = $userRole;
                $user->save();
                $fixed++;
                echo "   ✓ User {$user->id} ({$user->name}): role diperbaiki dari '{$originalRole}' ke '{$userRole}'\n";
            } else {
                // Normalisasi role (pastikan lowercase, no spaces)
                if ($user->role !== $userRole) {
                    $user->role = $userRole;
                    $user->save();
                    $fixed++;
                    echo "   ✓ User {$user->id} ({$user->name}): role dinormalisasi ke '{$userRole}'\n";
                }
            }
        }
        
        if ($fixed > 0) {
            $success[] = "✓ {$fixed} user roles fixed";
        } else {
            $success[] = "✓ All user roles are correct";
        }
    } catch (\Exception $e) {
        $errors[] = "✗ Error fixing user roles: " . $e->getMessage();
    }
    
    echo "   Selesai!\n\n";
    
    // ============================================================
    // 3. BUAT ADMIN DEFAULT JIKA TIDAK ADA
    // ============================================================
    echo "3. Memverifikasi admin default...\n";
    
    try {
        $admin = User::where('email', 'admin@pondok.test')
            ->orWhere(function($query) {
                $query->where('role', 'admin')
                      ->whereNotNull('email');
            })
            ->first();
        
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Pondok',
                'email' => 'admin@pondok.test',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'tanggal_lahir' => null,
            ]);
            $success[] = "✓ Admin default created: admin@pondok.test / admin123";
            echo "   ✓ Admin default dibuat: admin@pondok.test / admin123\n";
        } else {
            // Pastikan password benar
            if (!Hash::check('admin123', $admin->password)) {
                $admin->password = Hash::make('admin123');
                $admin->save();
                $success[] = "✓ Admin password reset to: admin123";
                echo "   ✓ Password admin direset ke: admin123\n";
            }
            
            // Pastikan role benar
            if (strtolower(trim($admin->role)) !== 'admin') {
                $admin->role = 'admin';
                $admin->save();
                $success[] = "✓ Admin role fixed";
                echo "   ✓ Role admin diperbaiki\n";
            }
            
            $success[] = "✓ Admin default exists: {$admin->email}";
            echo "   ✓ Admin default sudah ada: {$admin->email}\n";
        }
    } catch (\Exception $e) {
        $errors[] = "✗ Error creating admin: " . $e->getMessage();
    }
    
    echo "   Selesai!\n\n";
    
    // ============================================================
    // 4. PERBAIKI USER SANTRI YANG BERMASALAH
    // ============================================================
    echo "4. Memperbaiki user santri yang bermasalah...\n";
    
    try {
        $santris = User::where('role', 'santri')->get();
        $fixed = 0;
        
        foreach ($santris as $santri) {
            $needsFix = false;
            
            // Pastikan username tidak kosong
            if (empty($santri->username)) {
                $santri->username = 'santri_' . $santri->id;
                $needsFix = true;
                echo "   ⚠ User {$santri->id}: username kosong, dibuat: {$santri->username}\n";
            }
            
            // Pastikan tanggal lahir ada
            if (!$santri->tanggal_lahir) {
                $santri->tanggal_lahir = '2005-01-01';
                $needsFix = true;
                echo "   ⚠ User {$santri->id}: tanggal lahir kosong, diisi: 2005-01-01\n";
            }
            
            // Pastikan password sesuai dengan tanggal lahir
            if ($santri->tanggal_lahir) {
                $tanggalLahirFormatted = $santri->tanggal_lahir->format('Y-m-d');
                $passwordMatch = Hash::check($tanggalLahirFormatted, $santri->password);
                
                if (!$passwordMatch) {
                    $santri->password = Hash::make($tanggalLahirFormatted);
                    $needsFix = true;
                    echo "   ⚠ User {$santri->id}: password tidak sesuai, diperbaiki ke: {$tanggalLahirFormatted}\n";
                }
            }
            
            // Pastikan memiliki santriDetail
            if (!$santri->santriDetail) {
                try {
                    SantriDetail::create([
                        'user_id' => $santri->id,
                        'nis' => $santri->username ?? 'SANTRI_' . $santri->id,
                        'alamat_santri' => 'Belum diisi',
                        'nama_wali' => 'Belum diisi',
                        'alamat_wali' => 'Belum diisi',
                        'status_santri' => 'aktif',
                    ]);
                    $needsFix = true;
                    echo "   ⚠ User {$santri->id}: santriDetail dibuat\n";
                } catch (\Exception $e) {
                    $warnings[] = "⚠ User {$santri->id}: Cannot create santriDetail - " . $e->getMessage();
                }
            }
            
            if ($needsFix) {
                $santri->save();
                $fixed++;
            }
        }
        
        if ($fixed > 0) {
            $success[] = "✓ {$fixed} santri users fixed";
        } else {
            $success[] = "✓ All santri users are correct";
        }
    } catch (\Exception $e) {
        $errors[] = "✗ Error fixing santri users: " . $e->getMessage();
    }
    
    echo "   Selesai!\n\n";
    
    // ============================================================
    // 5. BUAT STORAGE LINK JIKA BELUM ADA
    // ============================================================
    echo "5. Memverifikasi storage link...\n";
    
    try {
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');
        
        if (!File::exists($linkPath)) {
            if (File::exists($targetPath)) {
                // Windows: gunakan mklink atau copy
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    // Coba buat symbolic link
                    $command = "mklink /D \"{$linkPath}\" \"{$targetPath}\"";
                    exec($command, $output, $return);
                    
                    if ($return !== 0) {
                        // Jika symbolic link gagal, gunakan artisan
                        Artisan::call('storage:link');
                        $success[] = "✓ Storage link created via artisan";
                    } else {
                        $success[] = "✓ Storage link created";
                    }
                } else {
                    // Linux/Mac
                    symlink($targetPath, $linkPath);
                    $success[] = "✓ Storage link created";
                }
                echo "   ✓ Storage link dibuat\n";
            } else {
                File::makeDirectory($targetPath, 0755, true);
                Artisan::call('storage:link');
                $success[] = "✓ Storage directory and link created";
                echo "   ✓ Storage directory dan link dibuat\n";
            }
        } else {
            $success[] = "✓ Storage link already exists";
            echo "   ✓ Storage link sudah ada\n";
        }
    } catch (\Exception $e) {
        $warnings[] = "⚠ Storage link creation failed: " . $e->getMessage();
    }
    
    echo "   Selesai!\n\n";
    
    // ============================================================
    // 6. VERIFIKASI DATABASE CONNECTION
    // ============================================================
    echo "6. Memverifikasi koneksi database...\n";
    
    try {
        DB::connection()->getPdo();
        $success[] = "✓ Database connection OK";
        echo "   ✓ Koneksi database berhasil\n";
    } catch (\Exception $e) {
        $errors[] = "✗ Database connection failed: " . $e->getMessage();
        echo "   ✗ Koneksi database gagal: " . $e->getMessage() . "\n";
    }
    
    echo "   Selesai!\n\n";
    
    // ============================================================
    // 7. RINGKASAN HASIL
    // ============================================================
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                    RINGKASAN HASIL                          ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    if (count($success) > 0) {
        echo "✅ BERHASIL:\n";
        foreach ($success as $msg) {
            echo "   {$msg}\n";
        }
        echo "\n";
    }
    
    if (count($warnings) > 0) {
        echo "⚠️  PERINGATAN:\n";
        foreach ($warnings as $msg) {
            echo "   {$msg}\n";
        }
        echo "\n";
    }
    
    if (count($errors) > 0) {
        echo "❌ ERROR:\n";
        foreach ($errors as $msg) {
            echo "   {$msg}\n";
        }
        echo "\n";
    }
    
    // ============================================================
    // 8. INFORMASI KREDENSIAL
    // ============================================================
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                  KREDENSIAL DEFAULT                         ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    echo "ADMIN:\n";
    echo "   Email: admin@pondok.test\n";
    echo "   Password: admin123\n\n";
    
    echo "SANTRI:\n";
    $santris = User::where('role', 'santri')->get();
    if ($santris->count() > 0) {
        foreach ($santris->take(5) as $santri) {
            $password = $santri->tanggal_lahir ? $santri->tanggal_lahir->format('Y-m-d') : '2005-01-01';
            echo "   Username: {$santri->username}\n";
            echo "   Password: {$password}\n";
            echo "   ---\n";
        }
        if ($santris->count() > 5) {
            echo "   ... dan " . ($santris->count() - 5) . " santri lainnya\n";
        }
    } else {
        echo "   Belum ada data santri\n";
    }
    
    echo "\n";
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                    SELESAI                                  ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    echo "Langkah selanjutnya:\n";
    echo "1. Hapus cookie browser untuk 127.0.0.1:8000\n";
    echo "2. Restart server: php artisan serve\n";
    echo "3. Akses http://127.0.0.1:8000\n";
    echo "4. Login dengan kredensial di atas\n\n";
    
    if (count($errors) > 0) {
        exit(1);
    }
    
} catch (\Exception $e) {
    echo "\n❌ FATAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

