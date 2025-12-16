<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = AppSetting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        
        // Default settings jika belum ada
        $defaultSettings = [
            'app_name' => 'Managemen Data Santri',
            'app_title' => 'PP HS AL-FAKKAR',
            'app_description' => 'Sistem Manajemen Data Santri',
            'app_logo' => null,
            'app_favicon' => null,
            'primary_color' => '#28a745',
            'secondary_color' => '#20c997',
            'footer_text' => '© 2025 PP HS AL-FAKKAR. All rights reserved.',
        ];

        // Merge dengan settings yang ada
        foreach ($defaultSettings as $key => $defaultValue) {
            if (!AppSetting::where('key', $key)->exists()) {
                $type = in_array($key, ['app_logo', 'app_favicon']) ? 'image' : 
                       (in_array($key, ['primary_color', 'secondary_color']) ? 'color' : 'text');
                $group = in_array($key, ['app_logo', 'app_favicon', 'primary_color', 'secondary_color']) ? 'appearance' : 'general';
                
                AppSetting::set($key, $defaultValue, $type, $group);
            }
        }

        $settings = AppSetting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        
        return view('admin.app-settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        try {
            $data = $request->except(['_token', '_method']);

            foreach ($data as $key => $value) {
                // Handle file uploads
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $path = $file->store('app-settings', 'public');
                    
                    // Delete old file if exists
                    $oldSetting = AppSetting::where('key', $key)->first();
                    if ($oldSetting && $oldSetting->value) {
                        Storage::disk('public')->delete($oldSetting->value);
                    }
                    
                    AppSetting::set($key, $path, 'image', 'appearance');
                } elseif ($value !== null) {
                    // Update text/color values
                    $setting = AppSetting::where('key', $key)->first();
                    if ($setting) {
                        $setting->value = $value;
                        $setting->save();
                        // Clear cache setelah update
                        \Illuminate\Support\Facades\Cache::forget('app_settings');
                        \Illuminate\Support\Facades\Cache::forget('app_settings_grouped');
                    } else {
                        $type = in_array($key, ['primary_color', 'secondary_color']) ? 'color' : 'text';
                        $group = in_array($key, ['primary_color', 'secondary_color']) ? 'appearance' : 'general';
                        AppSetting::set($key, $value, $type, $group);
                    }
                }
            }

            // Clear config dan view cache untuk memastikan perubahan langsung tampil
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');

            return redirect()->route('admin.app-settings.index')
                ->with('success', 'Pengaturan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }
}

