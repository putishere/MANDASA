<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\ProfilPondokController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\InfoAplikasiController;
use App\Http\Controllers\ProfilSantriController;
use App\Http\Controllers\AppSettingsController;

// Route utama dan login GET - hanya bisa diakses jika BELUM login
// Middleware 'guest' memastikan user belum login, jika sudah login akan redirect ke dashboard sesuai role
// Jika belum login, akan menampilkan halaman login
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('home');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
});

// Route untuk force logout dan clear session (untuk troubleshooting)
// Route ini memastikan user selalu melihat halaman login
Route::get('/force-logout', function() {
    // Logout user
    \Illuminate\Support\Facades\Auth::logout();
    
    // Invalidate dan regenerate session
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    // Clear session files
    $sessionPath = storage_path('framework/sessions');
    if (\Illuminate\Support\Facades\File::exists($sessionPath)) {
        $files = \Illuminate\Support\Facades\File::files($sessionPath);
        foreach ($files as $file) {
            if ($file->getFilename() !== '.gitignore') {
                \Illuminate\Support\Facades\File::delete($file);
            }
        }
    }
    
    // Clear cache
    try {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
    } catch (\Exception $e) {
        // Ignore cache errors
    }
    
    return redirect()->route('login')
        ->with('success', 'Session telah dihapus. Silakan login kembali.')
        ->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
})->name('force.logout');

// Route POST login - tidak menggunakan middleware guest karena setelah login berhasil user sudah authenticated
// Jika menggunakan guest, setelah login berhasil akan di-redirect lagi oleh middleware guest
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route sementara untuk fix admin password (HAPUS SETELAH DIGUNAKAN)
Route::get('/fix-admin-password', function() {
    try {
        $admin = \App\Models\User::where('email', 'admin@pondok.test')->first();
        
        if (!$admin) {
            $admin = \App\Models\User::create([
                'name' => 'Admin Pondok',
                'email' => 'admin@pondok.test',
                'username' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin',
            ]);
            $message = "✅ Admin berhasil dibuat!";
        } else {
            $admin->password = \Illuminate\Support\Facades\Hash::make('admin123');
            $admin->role = 'admin';
            $admin->save();
            $message = "✅ Password admin berhasil diperbaiki!";
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'email' => $admin->email,
            'role' => $admin->role,
            'credentials' => [
                'email' => 'admin@pondok.test',
                'password' => 'admin123'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
})->name('fix.admin.password');

// Route cepat: Buat admin sekarang (HAPUS SETELAH DIGUNAKAN)
Route::get('/create-admin-now', function() {
    try {
        // Cek di path baru (scripts/old/) atau path lama (root)
        $scriptPath = base_path('scripts/old/create_admin_now.php');
        if (!file_exists($scriptPath)) {
        $scriptPath = base_path('create_admin_now.php');
        }
        
        if (!file_exists($scriptPath)) {
            return response()->json([
                'success' => false,
                'error' => 'Script create_admin_now.php tidak ditemukan'
            ], 404);
        }
        
        ob_start();
        include $scriptPath;
        $output = ob_get_clean();
        
        return response('<pre style="font-family: monospace; padding: 20px; background: #f5f5f5;">' . htmlspecialchars($output) . '</pre>')
            ->header('Content-Type', 'text/html; charset=utf-8');
            
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
})->name('create.admin.now');

// Route perbaikan login admin (HAPUS SETELAH DIGUNAKAN)
Route::get('/fix-login-admin', function() {
    try {
        // Cek di path baru (scripts/old/) atau path lama (root)
        $scriptPath = base_path('scripts/old/fix_login_admin.php');
        if (!file_exists($scriptPath)) {
        $scriptPath = base_path('fix_login_admin.php');
        }
        
        if (!file_exists($scriptPath)) {
            return response()->json([
                'success' => false,
                'error' => 'Script fix_login_admin.php tidak ditemukan'
            ], 404);
        }
        
        ob_start();
        include $scriptPath;
        $output = ob_get_clean();
        
        return response('<pre style="font-family: monospace; padding: 20px; background: #f5f5f5; white-space: pre-wrap;">' . htmlspecialchars($output) . '</pre>')
            ->header('Content-Type', 'text/html; charset=utf-8');
            
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
})->name('fix.login.admin');

// Route jalankan migration tahun_masuk (HAPUS SETELAH DIGUNAKAN)
Route::get('/migrate-tahun-masuk', function() {
    try {
        // Cek di path baru (scripts/old/) atau path lama (root)
        $scriptPath = base_path('scripts/old/jalankan_migration_tahun_masuk.php');
        if (!file_exists($scriptPath)) {
        $scriptPath = base_path('jalankan_migration_tahun_masuk.php');
        }
        
        if (!file_exists($scriptPath)) {
            return response()->json([
                'success' => false,
                'error' => 'Script jalankan_migration_tahun_masuk.php tidak ditemukan'
            ], 404);
        }
        
        ob_start();
        include $scriptPath;
        $output = ob_get_clean();
        
        return response('<pre style="font-family: monospace; padding: 20px; background: #f5f5f5; white-space: pre-wrap;">' . htmlspecialchars($output) . '</pre>')
            ->header('Content-Type', 'text/html; charset=utf-8');
            
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
})->name('migrate.tahun.masuk');

// Route langsung buat admin (HAPUS SETELAH DIGUNAKAN)
Route::get('/buat-admin', function() {
    try {
        // Cek koneksi database
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        
        // Cari atau buat admin
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@pondok.test'],
            [
                'name' => 'Admin Pondok',
                'username' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin',
                'tanggal_lahir' => null,
            ]
        );
        
        // Pastikan password benar
        $passwordCheck = \Illuminate\Support\Facades\Hash::check('admin123', $admin->password);
        if (!$passwordCheck) {
            $admin->password = \Illuminate\Support\Facades\Hash::make('admin123');
            $admin->save();
        }
        
        // Pastikan role benar
        $adminRole = strtolower(trim($admin->role ?? ''));
        if ($adminRole !== 'admin') {
            $admin->role = 'admin';
            $admin->save();
        }
        
        $admin->refresh();
        
        $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Berhasil Dibuat</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            max-width: 600px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .success-icon {
            font-size: 64px;
            color: #28a745;
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            color: #28a745;
            text-align: center;
            margin-bottom: 30px;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .credentials {
            background: #e7f5e7;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .cred-item {
            margin: 10px 0;
            font-size: 18px;
        }
        .cred-label {
            font-weight: bold;
            color: #333;
        }
        .cred-value {
            color: #28a745;
            font-family: monospace;
        }
        .btn {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            margin-top: 20px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .instructions {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✅</div>
        <h1>Admin Berhasil Dibuat!</h1>
        
        <div class="info-box">
            <strong>Informasi Admin:</strong><br>
            ID: ' . $admin->id . '<br>
            Name: ' . htmlspecialchars($admin->name) . '<br>
            Email: ' . htmlspecialchars($admin->email) . '<br>
            Username: ' . htmlspecialchars($admin->username ?? 'Tidak ada') . '<br>
            Role: ' . htmlspecialchars($admin->role) . '
        </div>
        
        <div class="credentials">
            <h2 style="margin-top: 0; color: #28a745;">Kredensial Login:</h2>
            <div class="cred-item">
                <span class="cred-label">Email:</span>
                <span class="cred-value">admin@pondok.test</span>
            </div>
            <div class="cred-item">
                <span class="cred-label">Password:</span>
                <span class="cred-value">admin123</span>
            </div>
        </div>
        
        <div class="instructions">
            <strong>Langkah Selanjutnya:</strong>
            <ol>
                <li>Klik tombol di bawah untuk kembali ke halaman login</li>
                <li>Login dengan kredensial di atas</li>
                <li>Jika masih error, hapus cookie browser (F12 → Application → Cookies → Clear All)</li>
            </ol>
        </div>
        
        <div style="text-align: center;">
            <a href="/" class="btn">Kembali ke Halaman Login</a>
        </div>
    </div>
</body>
</html>';
        
        return response($html)->header('Content-Type', 'text/html; charset=utf-8');
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('buat.admin');

// Route untuk fix semua masalah aplikasi (HAPUS SETELAH DIGUNAKAN)
Route::get('/fix-all', function() {
    try {
        // Jalankan script perbaikan
        // Cek di path baru (scripts/old/) atau path lama (root)
        $scriptPath = base_path('scripts/old/fix_semua_masalah.php');
        if (!file_exists($scriptPath)) {
        $scriptPath = base_path('fix_semua_masalah.php');
        }
        
        if (!file_exists($scriptPath)) {
            return response()->json([
                'success' => false,
                'error' => 'Script fix_semua_masalah.php tidak ditemukan'
            ], 404);
        }
        
        // Capture output
        ob_start();
        include $scriptPath;
        $output = ob_get_clean();
        
        return response('<pre>' . htmlspecialchars($output) . '</pre>')
            ->header('Content-Type', 'text/html; charset=utf-8');
            
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
})->name('fix.all');

// Route untuk fix login user SAPUT (HAPUS SETELAH DIGUNAKAN)
Route::get('/fix-login-saput', function() {
    try {
        
        $result = [];
        
        // Cari user SAPUT
        $user = \App\Models\User::where(function($query) {
            $query->whereRaw('LOWER(TRIM(username)) = ?', ['saput'])
                  ->orWhereRaw('TRIM(username) = ?', ['SAPUT'])
                  ->orWhere('username', 'SAPUT')
                  ->orWhere('username', 'saput');
        })->first();
        
        if ($user) {
            $result[] = "✓ User ditemukan: ID {$user->id}, Username: {$user->username}, Role: {$user->role}";
            
            // Perbaiki role jika perlu
            $userRole = strtolower(trim($user->role ?? ''));
            if ($userRole !== 'santri') {
                $user->role = 'santri';
                $user->save();
                $result[] = "✓ Role diperbaiki ke 'santri'";
            }
            
            // Pastikan username konsisten
            if (strtolower($user->username) !== 'saput') {
                $user->username = 'SAPUT';
                $user->save();
                $result[] = "✓ Username diperbaiki ke 'SAPUT'";
            }
            
            // Pastikan password sesuai dengan tanggal lahir
            if (!$user->tanggal_lahir) {
                $user->tanggal_lahir = '2005-09-14';
                $result[] = "✓ Tanggal lahir ditambahkan: 2005-09-14";
            }
            
            $tanggalLahirFormatted = $user->tanggal_lahir->format('Y-m-d');
            $passwordMatch = \Illuminate\Support\Facades\Hash::check($tanggalLahirFormatted, $user->password);
            
            if (!$passwordMatch) {
                $user->password = \Illuminate\Support\Facades\Hash::make($tanggalLahirFormatted);
                $user->save();
                $result[] = "✓ Password diperbaiki ke tanggal lahir: {$tanggalLahirFormatted}";
            } else {
                $result[] = "✓ Password sudah sesuai dengan tanggal lahir";
            }
            
            // Cek apakah memiliki santriDetail
            if (!$user->santriDetail) {
                try {
                    \App\Models\SantriDetail::create([
                        'user_id' => $user->id,
                        'nis' => $user->username ?? 'SAPUT',
                        'alamat_santri' => 'Belum diisi',
                        'nama_wali' => 'Belum diisi',
                        'alamat_wali' => 'Belum diisi',
                        'status_santri' => 'aktif',
                    ]);
                    $result[] = "✓ SantriDetail dibuat";
                } catch (\Exception $e) {
                    $result[] = "⚠ Error membuat santriDetail: " . $e->getMessage();
                }
            } else {
                $result[] = "✓ User sudah memiliki santriDetail";
            }
            
            $user->refresh();
            
            return response()->json([
                'success' => true,
                'message' => 'User SAPUT berhasil diperbaiki!',
                'results' => $result,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'name' => $user->name,
                    'role' => $user->role,
                    'tanggal_lahir' => $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : null,
                    'has_santri_detail' => $user->santriDetail ? true : false,
                ],
                'credentials' => [
                    'username' => 'SAPUT',
                    'password' => $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '2005-09-14'
                ]
            ]);
            
        } else {
            // Buat user baru
            $tanggalLahir = '2005-09-14';
            
            $user = \App\Models\User::create([
                'name' => 'Saput',
                'username' => 'SAPUT',
                'email' => null,
                'tanggal_lahir' => $tanggalLahir,
                'password' => \Illuminate\Support\Facades\Hash::make($tanggalLahir),
                'role' => 'santri',
            ]);
            
            $result[] = "✓ User 'SAPUT' dibuat dengan ID: {$user->id}";
            $result[] = "✓ Password default: {$tanggalLahir} (tanggal lahir)";
            
            // Buat santriDetail
            try {
                \App\Models\SantriDetail::create([
                    'user_id' => $user->id,
                    'nis' => 'SAPUT',
                    'alamat_santri' => 'Belum diisi',
                    'nama_wali' => 'Belum diisi',
                    'alamat_wali' => 'Belum diisi',
                    'status_santri' => 'aktif',
                ]);
                $result[] = "✓ SantriDetail dibuat";
            } catch (\Exception $e) {
                $result[] = "⚠ Error membuat santriDetail: " . $e->getMessage();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'User SAPUT berhasil dibuat!',
                'results' => $result,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'name' => $user->name,
                    'role' => $user->role,
                    'tanggal_lahir' => $tanggalLahir,
                    'has_santri_detail' => true,
                ],
                'credentials' => [
                    'username' => 'SAPUT',
                    'password' => $tanggalLahir
                ]
            ]);
        }
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
})->name('fix.login.saput');

// Dashboard santri (hanya bisa diakses role santri) - HARUS DITEMPATKAN SEBELUM ROUTE RESOURCE SANTRI
Route::middleware(['auth','role:santri'])->group(function () {
    Route::get('/santri/dashboard', function () {
        // Refresh user dari database dengan eager loading relasi santriDetail
        // Ini memastikan data selalu terbaru dan sesuai dengan yang diinput
        $user = User::with('santriDetail')->findOrFail(auth()->id());
        
        // Verifikasi bahwa user memiliki santriDetail
        if (!$user->santriDetail) {
            \Log::warning('Santri dashboard accessed but no santriDetail found', [
                'user_id' => $user->id,
                'username' => $user->username
            ]);
            
            return redirect()->route('login')
                ->withErrors(['error' => 'Data santri tidak lengkap. Silakan hubungi admin.']);
        }
        
        $detail = $user->santriDetail;
        
        return view('santri.dashboard', compact('user', 'detail'));
    })->name('santri.dashboard');
    
    // Profil Santri
    Route::get('/santri/profil', [ProfilSantriController::class, 'index'])->name('santri.profil');
    Route::get('/santri/profil/print', [ProfilSantriController::class, 'print'])->name('santri.profil.print');
    
    // Profil Pondok (santri)
    Route::get('/santri/profil-pondok', [ProfilPondokController::class, 'index'])->name('santri.profil-pondok');
    
    // Album Pondok (santri)
    Route::get('/santri/album-pondok', [AlbumController::class, 'index'])->name('santri.album-pondok');
    
    // Download profil santri
    Route::get('/santri/profil/download', [ProfilSantriController::class, 'download'])->name('santri.profil.download');
    
    // Info Aplikasi (santri)
    Route::get('/santri/info-aplikasi', [InfoAplikasiController::class, 'index'])->name('santri.info-aplikasi');
});

// Dashboard admin (hanya bisa diakses role admin)
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // CRUD Santri (hanya admin) - HARUS SETELAH ROUTE SANTRI DASHBOARD
    Route::resource('santri', SantriController::class);
    Route::get('/santri/{id}/download-pdf', [SantriController::class, 'downloadPDF'])->name('santri.download-pdf');
    
    // Profil Pondok (admin)
    Route::get('/admin/profil-pondok', [ProfilPondokController::class, 'index'])->name('admin.profil-pondok');
    Route::get('/admin/profil-pondok/edit', [ProfilPondokController::class, 'edit'])->name('admin.profil-pondok.edit');
    Route::put('/admin/profil-pondok', [ProfilPondokController::class, 'update'])->name('admin.profil-pondok.update');
    
    // Info Aplikasi (admin)
    Route::get('/admin/info-aplikasi', [InfoAplikasiController::class, 'index'])->name('admin.info-aplikasi');
    Route::get('/admin/info-aplikasi/edit', [InfoAplikasiController::class, 'edit'])->name('admin.info-aplikasi.edit');
    Route::put('/admin/info-aplikasi', [InfoAplikasiController::class, 'update'])->name('admin.info-aplikasi.update');
    
    // Album Pondok (admin)
    Route::get('/admin/album', [AlbumController::class, 'manage'])->name('admin.album.manage');
    Route::get('/admin/album/create', [AlbumController::class, 'create'])->name('admin.album.create');
    Route::post('/admin/album', [AlbumController::class, 'store'])->name('admin.album.store');
    Route::get('/admin/album/{id}', [AlbumController::class, 'show'])->name('admin.album.show');
    Route::get('/admin/album/{id}/edit', [AlbumController::class, 'edit'])->name('admin.album.edit');
    Route::put('/admin/album/{id}', [AlbumController::class, 'update'])->name('admin.album.update');
    Route::delete('/admin/album/{id}', [AlbumController::class, 'destroy'])->name('admin.album.destroy');
    
    // Foto dalam album
    Route::post('/admin/album/{id}/fotos', [AlbumController::class, 'storeFoto'])->name('admin.album.fotos.store');
    Route::put('/admin/album/{albumId}/fotos/{fotoId}', [AlbumController::class, 'updateFoto'])->name('admin.album.fotos.update');
    Route::delete('/admin/album/{albumId}/fotos/{fotoId}', [AlbumController::class, 'destroyFoto'])->name('admin.album.fotos.destroy');
    Route::post('/admin/album/{albumId}/fotos/{fotoId}/set-cover', [AlbumController::class, 'setCover'])->name('admin.album.fotos.set-cover');
    
    // Pengaturan Tampilan Aplikasi (admin)
    Route::get('/admin/app-settings', [AppSettingsController::class, 'index'])->name('admin.app-settings.index');
    Route::put('/admin/app-settings', [AppSettingsController::class, 'update'])->name('admin.app-settings.update');
    
    // Edit Terpusat - Edit Semua Fitur (admin)
    Route::get('/admin/unified-edit', [\App\Http\Controllers\UnifiedEditController::class, 'index'])->name('admin.unified-edit.index');
    Route::put('/admin/unified-edit', [\App\Http\Controllers\UnifiedEditController::class, 'update'])->name('admin.unified-edit.update');
    
    // Route untuk organisir file (HAPUS SETELAH DIGUNAKAN)
    Route::get('/admin/organisir-file', function() {
        try {
            // Cek di path baru (scripts/old/) atau path lama (root)
            $scriptPath = base_path('scripts/old/organisir_file.php');
            if (!file_exists($scriptPath)) {
                $scriptPath = base_path('organisir_file.php');
            }
            
            if (!file_exists($scriptPath)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Script organisir_file.php tidak ditemukan'
                ], 404);
            }
            
            ob_start();
            include $scriptPath;
            $output = ob_get_clean();
            
            return response('<pre style="font-family: monospace; padding: 20px; background: #f5f5f5; white-space: pre-wrap; font-size: 14px;">' . htmlspecialchars($output) . '</pre>')
                ->header('Content-Type', 'text/html; charset=utf-8');
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    })->name('admin.organisir.file');
    
    // Route untuk cek apakah semuanya berfungsi (HAPUS SETELAH DIGUNAKAN)
    Route::get('/admin/cek-semua-berfungsi', function() {
        try {
            // Cek di path baru (scripts/old/) atau path lama (root)
            $scriptPath = base_path('cek_semua_berfungsi.php');
            if (!file_exists($scriptPath)) {
                $scriptPath = base_path('scripts/old/cek_semua_berfungsi.php');
            }
            
            if (!file_exists($scriptPath)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Script cek_semua_berfungsi.php tidak ditemukan'
                ], 404);
            }
            
            ob_start();
            include $scriptPath;
            $output = ob_get_clean();
            
            return response('<pre style="font-family: monospace; padding: 20px; background: #f5f5f5; white-space: pre-wrap; font-size: 14px;">' . htmlspecialchars($output) . '</pre>')
                ->header('Content-Type', 'text/html; charset=utf-8');
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    })->name('admin.cek.semua.berfungsi');
    
    // Route test perbaikan data santri (HAPUS SETELAH DIGUNAKAN)
    Route::get('/admin/test-data-santri', function() {
        try {
            $santriList = User::where('role', 'santri')
                ->with('santriDetail')
                ->get();
            
            $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Perbaikan Data Santri Dashboard</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #28a745;
            border-bottom: 3px solid #28a745;
            padding-bottom: 10px;
        }
        h2 {
            color: #20c997;
            margin-top: 30px;
            border-left: 4px solid #20c997;
            padding-left: 15px;
        }
        .info-box {
            background: #e7f5e7;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #28a745;
            color: white;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85em;
            font-weight: bold;
        }
        .badge-success {
            background: #28a745;
            color: white;
        }
        .badge-warning {
            background: #ffc107;
            color: #333;
        }
        .badge-danger {
            background: #dc3545;
            color: white;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        .method-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .method-box {
            padding: 15px;
            border-radius: 5px;
        }
        .method-old {
            background: #ffe6e6;
            border: 2px solid #dc3545;
        }
        .method-new {
            background: #e6ffe6;
            border: 2px solid #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Test Perbaikan Data Santri Dashboard</h1>
        
        <div class="success-box">
            <strong>✅ Status:</strong> Perbaikan sudah diterapkan! Dashboard akan selalu menampilkan data terbaru dari database.
        </div>
        
        <h2>1. Perbaikan yang Dilakukan</h2>
        
        <div class="method-comparison">
            <div class="method-box method-old">
                <h3>❌ Metode Lama</h3>
                <code>auth()->user()</code>
                <ul>
                    <li>Mengambil dari session (mungkin data lama)</li>
                    <li>Relasi tidak ter-load otomatis</li>
                    <li>Data mungkin tidak terbaru</li>
                </ul>
            </div>
            <div class="method-box method-new">
                <h3>✅ Metode Baru</h3>
                <code>User::with(\'santriDetail\')->findOrFail(auth()->id())</code>
                <ul>
                    <li>Mengambil langsung dari database</li>
                    <li>Eager loading relasi santriDetail</li>
                    <li>Data selalu terbaru</li>
                </ul>
            </div>
        </div>
        
        <h2>2. Data Santri di Database</h2>';
            
            if ($santriList->count() === 0) {
                $html .= '<div class="warning-box">
                    <strong>⚠️ Tidak ada data santri di database.</strong><br>
                    Silakan input data santri terlebih dahulu sebagai admin.
                </div>';
            } else {
                $html .= '<p>Total Santri: <strong>' . $santriList->count() . '</strong></p>';
                $html .= '<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Tanggal Lahir</th>
                            <th>NIS</th>
                            <th>Status</th>
                            <th>SantriDetail</th>
                        </tr>
                    </thead>
                    <tbody>';
                
                foreach ($santriList as $santri) {
                    $hasDetail = $santri->santriDetail ? true : false;
                    $detailBadge = $hasDetail 
                        ? '<span class="badge badge-success">✅ Ada</span>' 
                        : '<span class="badge badge-danger">❌ Tidak Ada</span>';
                    
                    $nis = $hasDetail ? $santri->santriDetail->nis : '-';
                    $status = $hasDetail ? $santri->santriDetail->status_santri : '-';
                    $tanggalLahir = $santri->tanggal_lahir ? $santri->tanggal_lahir->format('Y-m-d') : '-';
                    
                    $html .= '<tr>
                        <td>' . $santri->id . '</td>
                        <td><strong>' . htmlspecialchars($santri->name) . '</strong></td>
                        <td>' . htmlspecialchars($santri->username) . '</td>
                        <td>' . $tanggalLahir . '</td>
                        <td>' . htmlspecialchars($nis) . '</td>
                        <td>' . htmlspecialchars($status) . '</td>
                        <td>' . $detailBadge . '</td>
                    </tr>';
                }
                
                $html .= '</tbody></table>';
            }
            
            // Verifikasi perbaikan
            $routesFile = __DIR__ . '/routes/web.php';
            $routesContent = file_get_contents($routesFile);
            $routesFixed = strpos($routesContent, "User::with('santriDetail')->findOrFail(auth()->id())") !== false;
            
            $controllerFile = __DIR__ . '/app/Http/Controllers/ProfilSantriController.php';
            $controllerContent = file_get_contents($controllerFile);
            $controllerFixed = strpos($controllerContent, "User::with('santriDetail')->findOrFail(auth()->id())") !== false;
            
            $html .= '<h2>3. Verifikasi Perbaikan</h2>
        
        <table>
            <thead>
                <tr>
                    <th>File</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>routes/web.php</code></td>
                    <td>' . ($routesFixed ? '<span class="badge badge-success">✅ Diperbaiki</span>' : '<span class="badge badge-danger">❌ Belum</span>') . '</td>
                    <td>Route dashboard santri</td>
                </tr>
                <tr>
                    <td><code>ProfilSantriController.php</code></td>
                    <td>' . ($controllerFixed ? '<span class="badge badge-success">✅ Diperbaiki</span>' : '<span class="badge badge-danger">❌ Belum</span>') . '</td>
                    <td>Controller profil santri</td>
                </tr>
            </tbody>
        </table>
        
        <h2>4. Cara Test</h2>
        <ol>
            <li>Login sebagai admin</li>
            <li>Input data santri baru</li>
            <li>Logout dari admin</li>
            <li>Login sebagai santri yang baru dibuat</li>
            <li>Masuk ke dashboard - data harus sesuai dengan yang diinput</li>
        </ol>
        
        <h2>5. Langkah Selanjutnya</h2>
        <div class="info-box">
            <strong>Jika masih ada masalah:</strong>
            <ol>
                <li>Clear cache: <code>php artisan config:clear && php artisan cache:clear</code></li>
                <li>Hapus cookie browser (F12 → Application → Cookies → Clear All)</li>
                <li>Restart server: <code>php artisan serve</code></li>
                <li>Test dengan browser incognito/private window</li>
            </ol>
        </div>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="' . route('admin.dashboard') . '" style="display: inline-block; background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                ← Kembali ke Dashboard Admin
            </a>
        </div>
    </div>
</body>
</html>';
            
            return response($html)->header('Content-Type', 'text/html; charset=utf-8');
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    })->name('admin.test.data.santri');
});