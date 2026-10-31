<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    // Logos: put these two files in public/images/logos/
    private const LOGO_DARK  = 'images/logos/logo-dark.png';   // light lettering, for dark mode
    private const LOGO_LIGHT = 'images/logos/logo-light.png';  // dark lettering, for light mode
    private const LOGO_ANY   = 'logo.png';                     // fallback: public/logo.png

    // Default gallery photos (files in public/images/backgrounds/)
    private const DEFAULT_DARK_BG  = 'night-neon-planet.jpg';
    private const DEFAULT_LIGHT_BG = 'day-cloud-tech.jpg';

    public function run(): void
    {
        $s = Setting::current();

        // ---- Logos (one per mode) ----
        $any = is_file(public_path(self::LOGO_ANY)) ? self::LOGO_ANY : null;
        $s->logo_dark_path  = is_file(public_path(self::LOGO_DARK))  ? self::LOGO_DARK  : $any;
        $s->logo_light_path = is_file(public_path(self::LOGO_LIGHT)) ? self::LOGO_LIGHT : $any;
        $s->logo_path       = $s->logo_light_path;

        // ---- Logo sizes: empty = use the built-in sizes (header 36, footer 44, admin 40) ----
        $s->logo_header_h = null;
        $s->logo_footer_h = null;
        $s->logo_admin_h  = null;

        // ---- Backgrounds (one gallery photo per mode) ----
        $files = array_keys(Setting::demoImages());
        $pick  = fn (string $prefix) => collect($files)->first(fn ($f) => str_starts_with(strtolower($f), $prefix));

        $dark  = in_array(self::DEFAULT_DARK_BG, $files, true)  ? self::DEFAULT_DARK_BG
               : ($pick('night') ?? $pick('dark')  ?? ($files[0] ?? null));
        $light = in_array(self::DEFAULT_LIGHT_BG, $files, true) ? self::DEFAULT_LIGHT_BG
               : ($pick('day')   ?? $pick('light') ?? ($files[0] ?? null));

        $s->bg_mode       = $files ? 'demo' : 'plain';
        $s->bg_dark_demo  = $dark;
        $s->bg_light_demo = $light;
        $s->bg_demo       = $dark;

        // ---- Look and text defaults ----
        $s->bg_fit        = Setting::DEFAULTS['bg_fit'];
        $s->bg_position   = Setting::DEFAULTS['bg_position'];
        $s->bg_blur       = Setting::DEFAULTS['bg_blur'];
        $s->bg_veil       = Setting::DEFAULTS['bg_veil'];
        $s->bg_brightness = Setting::DEFAULTS['bg_brightness'];
        $s->bg_saturation = Setting::DEFAULTS['bg_saturation'];

        $s->theme_a       = Setting::DEFAULT_THEME['a'];
        $s->theme_b       = Setting::DEFAULT_THEME['b'];
        $s->font_body     = Setting::DEFAULT_FONTS['body'];
        $s->font_heading  = Setting::DEFAULT_FONTS['heading'];

        $s->hero_text       = 'auto';   // light text on the dark photo, dark text on the light photo
        $s->hero_text_color = Setting::DEFAULT_HERO_CUSTOM;
        $s->header_design   = 'classic';
        $s->header_shade    = null;
        $s->site_mode     ??= 'dark';

        $s->save(); // also clears the cached copy the website reads
    }
}