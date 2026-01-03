<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\AppSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share app settings to all views
        View::composer('*', function ($view) {
            try {
                // Ambil data langsung dari database tanpa cache untuk memastikan data terbaru
                $appName = AppSetting::where('key', 'app_name')->value('value') ?? 'MANAGEMEN DATA SANTRI';
                $appSubtitle = AppSetting::where('key', 'app_subtitle')->value('value') ?? AppSetting::where('key', 'app_title')->value('value') ?? 'PP HS AL-FAKKAR';
                $appLogo = AppSetting::where('key', 'app_logo')->value('value');
                $primaryColor = AppSetting::where('key', 'primary_color')->value('value') ?? '#059669';
                $secondaryColor = AppSetting::where('key', 'secondary_color')->value('value') ?? '#d1fae5';
                $footerText = AppSetting::where('key', 'footer_text')->value('value') ?? '© ' . date('Y') . ' PP HS Al-Fakkar. All rights reserved.';
                
                $view->with([
                    'appName' => $appName,
                    'appSubtitle' => $appSubtitle,
                    'appLogo' => $appLogo,
                    'primaryColor' => $primaryColor,
                    'secondaryColor' => $secondaryColor,
                    'footerText' => $footerText,
                ]);
            } catch (\Exception $e) {
                // If table doesn't exist yet, use defaults
                $view->with([
                    'appName' => 'MANAGEMEN DATA SANTRI',
                    'appSubtitle' => 'PP HS AL-FAKKAR',
                    'appLogo' => null,
                    'primaryColor' => '#059669',
                    'secondaryColor' => '#d1fae5',
                    'footerText' => '© ' . date('Y') . ' PP HS Al-Fakkar. All rights reserved.',
                ]);
            }
        });
    }
}
