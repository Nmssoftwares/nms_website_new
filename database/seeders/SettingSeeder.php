<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SettingSeeder extends Seeder
{
    private const LOGO_SOURCE = 'logo.png';              // public/logo.png (transparent NMS logo)
    private const LOGO_STORED = 'settings/nms-logo.png'; // storage/app/public/settings/...
    private const DEFAULT_BG  = 'day-office';            // file name without extension

    public function run(): void
    {
        $setting = Setting::current();

        // Logo: always the NMS logo, saved in the database (logo_path).
        $source = public_path(self::LOGO_SOURCE);
        if (is_file($source)) {
            Storage::disk('public')->put(self::LOGO_STORED, file_get_contents($source));
            $setting->logo_path = self::LOGO_STORED;
        }

        // Background: gallery mode with the day photo, saved in the database (bg_mode, bg_demo).
        $demos   = array_keys(Setting::demoImages());
        $default = collect($demos)->first(fn ($file) => str_starts_with($file, self::DEFAULT_BG))
                   ?? ($demos[0] ?? null);

        $setting->bg_mode = $default ? 'demo' : 'plain';
        $setting->bg_demo = $default;

        $setting->save(); // also clears the cached copy the website reads
    }
}