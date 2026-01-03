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
});