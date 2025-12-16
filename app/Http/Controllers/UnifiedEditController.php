<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\ProfilPondok;
use App\Models\InfoAplikasi;
use App\Models\User;
use App\Models\SantriDetail;
use App\Models\AlbumPondok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UnifiedEditController extends Controller
{
    /**
     * Display unified edit page for all features
     */
    public function index()
    {
        // Refresh instance untuk memastikan data terbaru
        $profilPondok = ProfilPondok::getInstance()->fresh();
        $infoAplikasi = InfoAplikasi::getInstance()->fresh();
        
        // Default settings jika belum ada
        $defaultSettings = [
            'app_name' => 'Managemen Data Santri',
            'app_subtitle' => 'PP HS AL-FAKKAR',
            'app_title' => 'PP HS AL-FAKKAR',
            'app_description' => 'Sistem Manajemen Data Santri',
            'app_logo' => null,
            'app_favicon' => null,
            'primary_color' => '#28a745',
            'secondary_color' => '#d4edda',
            'footer_text' => '© ' . date('Y') . ' PP HS Al-Fakkar. All rights reserved.',
        ];

        foreach ($defaultSettings as $key => $defaultValue) {
            if (!AppSetting::where('key', $key)->exists()) {
                $type = in_array($key, ['app_logo', 'app_favicon']) ? 'image' : 
                       (in_array($key, ['primary_color', 'secondary_color']) ? 'color' : 'text');
                $group = in_array($key, ['app_logo', 'app_favicon', 'primary_color', 'secondary_color']) ? 'appearance' : 'general';
                
                AppSetting::set($key, $defaultValue, $type, $group);
            }
        }

        // Get fresh settings data
        $appSettings = AppSetting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        
        // Get preview values for settings (fresh from database, tanpa cache)
        // Clear cache dulu untuk memastikan data terbaru
        \Illuminate\Support\Facades\Cache::forget('app_settings');
        \Illuminate\Support\Facades\Cache::forget('app_settings_grouped');
        
        $previewSettings = [
            'app_name' => AppSetting::where('key', 'app_name')->value('value') ?? 'Managemen Data Santri',
            'app_title' => AppSetting::where('key', 'app_title')->value('value') ?? 'PP HS AL-FAKKAR',
            'primary_color' => AppSetting::where('key', 'primary_color')->value('value') ?? '#28a745',
            'app_logo' => AppSetting::where('key', 'app_logo')->value('value'),
        ];
        
        // Get data Santri untuk tab Data Santri
        $santri = User::where('role', 'santri')
            ->with('santriDetail')
            ->orderBy('name', 'asc')
            ->paginate(10);
        
        // Get data Album untuk tab Album Pondok
        $albums = AlbumPondok::with(['coverFoto', 'fotos' => function($q) {
            $q->orderBy('urutan')->orderBy('created_at');
        }])->orderBy('urutan')->orderBy('created_at', 'desc')->paginate(12);
        
        $kategoriOptions = AlbumPondok::getKategoriOptions();
        
        return view('admin.unified-edit.index', compact('profilPondok', 'infoAplikasi', 'appSettings', 'previewSettings', 'santri', 'albums', 'kategoriOptions'));
    }

    /**
     * Update all features
     */
    public function update(Request $request)
    {
        try {
            $updated = false;

            // Update Profil Pondok - selalu coba update jika ada input
            if ($request->has('profil_pondok')) {
                try {
                    $this->updateProfilPondok($request);
                    $updated = true;
                } catch (\Exception $e) {
                    // Skip jika tidak ada data yang valid
                }
            }

            // Update Info Aplikasi - selalu coba update jika ada input
            if ($request->has('info_aplikasi')) {
                try {
                    $this->updateInfoAplikasi($request);
                    $updated = true;
                } catch (\Exception $e) {
                    // Skip jika tidak ada data yang valid
                }
            }

            // Update App Settings - selalu coba update jika ada input
            if ($request->has('app_settings')) {
                try {
                    $this->updateAppSettings($request);
                    $updated = true;
                } catch (\Exception $e) {
                    // Skip jika tidak ada data yang valid
                }
            }

            // Clear semua cache untuk memastikan data terbaru tampil
            \Illuminate\Support\Facades\Cache::flush();
            
            // Clear config, view, dan route cache untuk memastikan AppServiceProvider reload data
            try {
                \Illuminate\Support\Facades\Artisan::call('config:clear');
                \Illuminate\Support\Facades\Artisan::call('view:clear');
                \Illuminate\Support\Facades\Artisan::call('cache:clear');
                \Illuminate\Support\Facades\Artisan::call('route:clear');
            } catch (\Exception $e) {
                // Ignore cache errors
            }

            // Force refresh model instances untuk memastikan data terbaru
            if ($request->has('profil_pondok')) {
                ProfilPondok::getInstance()->refresh();
            }
            if ($request->has('info_aplikasi')) {
                InfoAplikasi::getInstance()->refresh();
            }
            if ($request->has('app_settings')) {
                // Clear cache app settings
                \Illuminate\Support\Facades\Cache::forget('app_settings');
                \Illuminate\Support\Facades\Cache::forget('app_settings_grouped');
            }

            if ($updated) {
                return redirect()->route('admin.unified-edit.index')
                    ->with('success', 'Semua perubahan berhasil disimpan! Data akan langsung tampil setelah halaman di-refresh.')
                    ->withHeaders([
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0'
                    ]);
            } else {
                return redirect()->route('admin.unified-edit.index')
                    ->with('info', 'Tidak ada perubahan yang disimpan.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Update Profil Pondok
     */
    private function updateProfilPondok(Request $request)
    {
        // Cek apakah ada data profil_pondok
        if (!$request->has('profil_pondok')) {
            return;
        }

        $data = $request->input('profil_pondok', []);
        
        $validated = validator($data, [
            'nama_pondok' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'tentang' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'program_unggulan' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ])->validate();

        $profil = ProfilPondok::getInstance();

        // Handle logo upload
        if ($request->hasFile('profil_pondok.logo')) {
            if ($profil->logo && Storage::disk('public')->exists($profil->logo)) {
                Storage::disk('public')->delete($profil->logo);
            }
            $validated['logo'] = $request->file('profil_pondok.logo')->store('profil-pondok', 'public');
        } else {
            // Jika tidak ada file baru, jangan update logo
            unset($validated['logo']);
        }

        // Update semua field
        $profil->update($validated);
        
        // Refresh instance untuk memastikan data terbaru
        $profil->refresh();
    }

    /**
     * Update Info Aplikasi
     */
    private function updateInfoAplikasi(Request $request)
    {
        // Cek apakah ada data info_aplikasi
        if (!$request->has('info_aplikasi')) {
            return;
        }

        $data = $request->input('info_aplikasi', []);
        
        $validated = validator($data, [
            'judul' => 'required|string|max:255',
            'tentang' => 'nullable|string',
            'fitur' => 'nullable|string',
            'keamanan' => 'nullable|string',
            'bantuan' => 'nullable|string',
            'versi' => 'required|string|max:50',
            'framework' => 'required|string|max:100',
            'database' => 'required|string|max:100',
        ])->validate();

        $info = InfoAplikasi::getInstance();
        // Update semua field (termasuk yang null)
        $info->update($validated);
        
        // Refresh instance untuk memastikan data terbaru
        $info->refresh();
    }

    /**
     * Update App Settings
     */
    private function updateAppSettings(Request $request)
    {
        // Cek apakah ada data app_settings
        if (!$request->has('app_settings')) {
            return;
        }

        $data = $request->input('app_settings', []);

        foreach ($data as $key => $value) {
            // Handle file uploads
            if ($request->hasFile("app_settings.{$key}")) {
                $file = $request->file("app_settings.{$key}");
                $path = $file->store('app-settings', 'public');
                
                // Delete old file if exists
                $oldSetting = AppSetting::where('key', $key)->first();
                if ($oldSetting && $oldSetting->value) {
                    Storage::disk('public')->delete($oldSetting->value);
                }
                
                AppSetting::set($key, $path, 'image', 'appearance');
            } elseif ($value !== null && $value !== '') {
                // Update text/color values (skip empty strings)
                $setting = AppSetting::where('key', $key)->first();
                if ($setting) {
                    $setting->value = $value;
                    $setting->save();
                    // Clear cache setelah update
                    \Illuminate\Support\Facades\Cache::forget('app_settings');
                    \Illuminate\Support\Facades\Cache::forget('app_settings_grouped');
                } else {
                    // Tentukan type dan group berdasarkan key
                    $type = in_array($key, ['primary_color', 'secondary_color']) ? 'color' : 'text';
                    $group = in_array($key, ['primary_color', 'secondary_color', 'app_logo', 'app_favicon']) ? 'appearance' : 'general';
                    
                    // Untuk footer_text, pastikan group adalah 'general'
                    if ($key === 'footer_text') {
                        $group = 'general';
                        $type = 'text';
                    }
                    
                    AppSetting::set($key, $value, $type, $group);
                }
            }
            
            // Clear cache setelah semua update
            \Illuminate\Support\Facades\Cache::forget('app_settings');
            \Illuminate\Support\Facades\Cache::forget('app_settings_grouped');
        }
    }
}

