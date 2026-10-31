<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    use HandlesUploads;

    public function edit()
    {
        $setting = Setting::current();
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $demos = array_keys(Setting::demoImages());

        $request->validate([
            'logo_dark'     => 'nullable|image|max:2048',
            'logo_light'    => 'nullable|image|max:2048',
            'logo_header_h' => 'nullable|integer|min:20|max:120',
            'logo_footer_h' => 'nullable|integer|min:20|max:140',
            'logo_admin_h'  => 'nullable|integer|min:20|max:80',
            'bg_image'      => 'nullable|image|max:6144',
            'remove_bg'     => 'nullable|boolean',
            'bg_mode'       => ['required', Rule::in(Setting::BG_MODES)],
            'bg_dark_demo'  => ['nullable', Rule::in($demos)],
            'bg_light_demo' => ['nullable', Rule::in($demos)],
            'bg_fit'        => 'required|in:cover,contain,stretch',
            'bg_position'   => 'required|in:top,center,bottom',
            'bg_blur'       => 'required|integer|min:0|max:12',
            'bg_veil'       => 'required|integer|min:0|max:100',
            'bg_brightness' => 'required|integer|min:60|max:140',
            'bg_saturation' => 'required|integer|min:50|max:150',
            'theme_a'       => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme_b'       => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'font_body'     => ['required', Rule::in(Setting::FONTS)],
            'font_heading'  => ['required', Rule::in(Setting::FONTS)],
            'hero_text'       => ['required', Rule::in(Setting::HERO_TEXT)],
            'hero_text_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'header_shade'    => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'header_design'   => ['required', Rule::in(Setting::HEADER_DESIGNS)],
        ]);

        $setting = Setting::current();

        // ---- Logos: one per mode ----
        foreach (['dark', 'light'] as $m) {
            $col = "logo_{$m}_path";
            if ($path = $this->replaceUpload($request, "logo_{$m}", 'settings', $setting->$col)) {
                $setting->$col = $path;
            }
        }

        // ---- Logo sizes (height in px) ----
        foreach (['header', 'footer', 'admin'] as $where) {
            $col = "logo_{$where}_h";
            $setting->$col = $request->filled($col) ? (int) $request->input($col) : null;
        }

        // ---- Background: gallery photo per mode, or one uploaded photo, or none ----
        $mode = $request->input('bg_mode');

        if ($path = $this->replaceUpload($request, 'bg_image', 'settings', $setting->bg_image_path)) {
            $setting->bg_image_path = $path;
            $mode = 'upload'; // a freshly chosen photo always wins
        } elseif ($request->boolean('remove_bg')) {
            $this->deleteUpload($setting->bg_image_path);
            $setting->bg_image_path = null;
        }

        if ($mode === 'upload' && ! $setting->bg_image_path) {
            $mode = 'demo'; // nothing uploaded, so use the gallery
        }

        $setting->bg_mode       = $mode;
        $setting->bg_dark_demo  = $request->input('bg_dark_demo')  ?: $setting->bg_dark_demo;
        $setting->bg_light_demo = $request->input('bg_light_demo') ?: $setting->bg_light_demo;

        $setting->bg_fit        = $request->input('bg_fit');
        $setting->bg_position   = $request->input('bg_position');
        $setting->bg_blur       = (int) $request->input('bg_blur');
        $setting->bg_veil       = (int) $request->input('bg_veil');
        $setting->bg_brightness = (int) $request->input('bg_brightness');
        $setting->bg_saturation = (int) $request->input('bg_saturation');

        $setting->theme_a       = strtoupper($request->input('theme_a'));
        $setting->theme_b       = strtoupper($request->input('theme_b'));
        $setting->font_body     = $request->input('font_body');
        $setting->font_heading  = $request->input('font_heading');

        $setting->hero_text       = $request->input('hero_text');
        $setting->hero_text_color = strtoupper($request->input('hero_text_color') ?: Setting::DEFAULT_HERO_CUSTOM);

        // Header shade: null = default look
        if ($request->boolean('header_shade_default') || ! $request->filled('header_shade')) {
            $setting->header_shade = null;
        } else {
            $setting->header_shade = strtoupper($request->input('header_shade'));
        }

        $setting->header_design = $request->input('header_design');

        $setting->save(); // saving also clears the cached copy used by the public pages

        return redirect()->route('admin.settings.edit')->with('status', 'Settings saved.');
    }

    /** Quick dark / light switch used by the admin sidebar (no full settings form needed). */
    public function mode(Request $request)
    {
        $data = $request->validate([
            'site_mode' => ['required', Rule::in(Setting::SITE_MODES)],
        ]);

        $setting = Setting::current();
        $setting->site_mode = $data['site_mode'];
        $setting->save(); // clears the cached copy the public pages read

        return response()->json(['site_mode' => $setting->siteMode()]);
    }
}