<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // Worked out once per request, then shared by every view below.
        $shared = null;

        View::composer(
            ['partials.header', 'partials.footer', 'partials.theme', 'admin.layout', 'auth.login', 'sections.hero'],
            function ($view) use (&$shared) {
                $shared ??= $this->siteSettings();
                $view->with($shared);
            }
        );
    }

    private function siteSettings(): array
    {
        try {
            $setting = Setting::cached();
        } catch (\Throwable $e) {
            $setting = null; // settings table not migrated yet
        }

        return [
            'siteLogo'      => $setting?->logoUrl(),
            'bgImage'       => $setting?->bgImageUrl(),
            'bgStyle'       => $setting?->bgStyle() ?? '',
            'bgVeil'        => $setting?->bgVeil() ?? '0.55',
            'themeCss'      => $setting?->themeCss() ?? '',
            'themeFontsUrl' => $setting?->themeFontsUrl(),
            'siteMode'      => $setting?->siteMode() ?? 'dark',
            'headerDesign'  => $setting?->headerDesign() ?? 'classic',
        ];
    }
}