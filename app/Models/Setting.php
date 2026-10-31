<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    private const CACHE_KEY = 'site.settings';

    protected $fillable = [
        'logo_path', 'logo_dark_path', 'logo_light_path',
        'logo_header_h', 'logo_footer_h', 'logo_admin_h',
        'bg_image_path', 'bg_mode', 'bg_demo', 'bg_dark_demo', 'bg_light_demo',
        'bg_fit', 'bg_position', 'bg_blur',
        'bg_veil', 'bg_brightness', 'bg_saturation',
        'theme_a', 'theme_b', 'font_body', 'font_heading',
        'hero_text', 'hero_text_color', 'header_style', 'site_mode', 'header_shade', 'header_design',
    ];

    /** Defaults used when a value has never been saved. */
    public const DEFAULTS = [
        'bg_fit' => 'cover', 'bg_position' => 'center', 'bg_blur' => 3,
        'bg_veil' => 55, 'bg_brightness' => 105, 'bg_saturation' => 110,
    ];

    /** The look the site has before anyone changes anything. */
    public const DEFAULT_THEME = ['a' => '#2563EB', 'b' => '#0EA5E9'];
    public const DEFAULT_FONTS = ['body' => 'Inter', 'heading' => 'Space Grotesk'];

    /** Hero text colour modes and header styles. */
    public const HERO_TEXT = ['auto', 'dark', 'light', 'custom'];

    /** Logo height in pixels for each place: [min, max, default]. */
    public const LOGO_SIZES = [
        'header' => [20, 120, 36],   // website header
        'footer' => [20, 140, 44],   // website footer
        'admin'  => [20, 80, 40],    // admin sidebar + login card
    ];
    public const HEADER_STYLES = ['light', 'dark'];
    public const SITE_MODES = ['dark', 'light'];
    public const BG_MODES = ['demo', 'upload', 'plain'];
    public const DEMO_DIR = 'images/backgrounds';
    public const HEADER_DESIGNS = ['classic', 'glass', 'aurora', 'spotlight'];
    public const DEFAULT_HERO_CUSTOM = '#FFFFFF';

    /** Fonts the admin can choose from (all free Google Fonts that support weights 400-700). */
    public const FONTS = [
        'Inter', 'Poppins', 'Open Sans', 'Montserrat', 'Nunito', 'DM Sans', 'Manrope',
        'Plus Jakarta Sans', 'Work Sans', 'Rubik', 'Space Grotesk', 'Sora', 'Outfit', 'Playfair Display',
    ];

    protected static function booted(): void
    {
        // Whenever the settings are saved or deleted, throw away the cached copy.
        static::saved(fn () => static::forgetCache());
        static::deleted(fn () => static::forgetCache());
    }

    /** There's only ever one settings row; create it the first time. */
    public static function current(): self
    {
        return static::firstOrCreate([]);
    }

    /**
     * Settings for the public pages. Read from the database once, then served from cache.
     * Falls back to a plain query if the cache is unavailable.
     */
    public static function cached(): ?self
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, fn () => static::query()->first());
        } catch (\Throwable $e) {
            return static::query()->first();
        }
    }

    private static function forgetCache(): void
    {
        try {
            Cache::forget(self::CACHE_KEY);
        } catch (\Throwable $e) {
            // cache not available: nothing to clear
        }
    }

    public function opt(string $key)
    {
        return $this->{$key} ?? self::DEFAULTS[$key];
    }

    /** Seeded files live in public/ ; uploaded files live in storage/. */
    private static function fileUrl(string $path): string
    {
        return is_file(public_path($path)) ? asset($path) : asset('storage/' . $path);
    }

    private function modeOrCurrent(?string $mode): string
    {
        return in_array($mode, self::SITE_MODES, true) ? $mode : $this->siteMode();
    }

    /** Logo for 'dark' or 'light' (defaults to the current site mode). */
    public function logoUrl(?string $mode = null): ?string
    {
        $mode = $this->modeOrCurrent($mode);
        $path = $this->{'logo_' . $mode . '_path'} ?: $this->logo_path;

        if ($path) {
            return self::fileUrl($path);
        }

        return is_file(public_path('logo.png')) ? asset('logo.png') : null;
    }

    /** Logo height in px for 'header', 'footer' or 'admin' (saved value, kept inside its limits). */
    public function logoSize(string $where): int
    {
        [$min, $max, $default] = self::LOGO_SIZES[$where];
        $value = $this->{'logo_' . $where . '_h'} ?? null;

        return is_numeric($value) ? max($min, min($max, (int) $value)) : $default;
    }

    /** CSS for the logo sizes. Only printed for places where a size was saved. */
    private function logoCss(): string
    {
        $css = '';

        if ($this->logo_header_h !== null) {
            $h = $this->logoSize('header');
            $css .= ".brand img{height:{$h}px !important;width:auto !important;}"
                  . '@media(max-width:860px){.brand img{height:' . min($h, 48) . 'px !important;}}';
        }

        if ($this->logo_footer_h !== null) {
            $h = $this->logoSize('footer');
            $css .= ".nmf-logo img{height:{$h}px !important;width:auto !important;}";
        }

        if ($this->logo_admin_h !== null) {
            $h = $this->logoSize('admin');
            $css .= ".sidebar .brand-row img{height:{$h}px !important;width:auto !important;max-width:150px !important;}"
                  . ".stage .logo img{height:{$h}px !important;width:auto !important;max-width:260px !important;}";
        }

        return $css;
    }

    /** Gallery pictures found in public/images/backgrounds  =>  ['day-office.jpg' => 'Day Office', ...] */
    public static function demoImages(): array
    {
        $dir = public_path(self::DEMO_DIR);
        if (! is_dir($dir)) {
            return [];
        }

        $out = [];
        foreach (scandir($dir) ?: [] as $file) {
            if (preg_match('/\.(jpe?g|png|webp)$/i', $file)) {
                $out[$file] = ucwords(str_replace(['-', '_'], ' ', pathinfo($file, PATHINFO_FILENAME)));
            }
        }

        return $out;
    }

    /** 'demo' (gallery picture), 'upload' (own photo) or 'plain' (no photo). */
    public function bgMode(): string
    {
        $mode = $this->bg_mode ?? null;

        if (in_array($mode, self::BG_MODES, true)) {
            return $mode;
        }

        return $this->bg_image_path ? 'upload' : 'demo'; // rows saved before this feature
    }

    /** Gallery file chosen for 'dark' or 'light' (defaults to the current mode, then the first picture). */
    public function bgDemo(?string $mode = null): ?string
    {
        $mode  = $this->modeOrCurrent($mode);
        $demos = self::demoImages();

        foreach ([$this->{'bg_' . $mode . '_demo'}, $this->bg_demo] as $pick) {
            if ($pick && isset($demos[$pick])) {
                return $pick;
            }
        }

        return array_key_first($demos);
    }

    public function bgUploadUrl(): ?string
    {
        return $this->bg_image_path ? asset('storage/' . $this->bg_image_path) : null;
    }

    /** The picture the site should show, or null for a plain background. */
    public function bgImageUrl(): ?string
    {
        return match ($this->bgMode()) {
            'plain'  => null,
            'upload' => $this->bgUploadUrl(),
            default  => ($demo = $this->bgDemo()) ? asset(self::DEMO_DIR . '/' . $demo) : null,
        };
    }

    /** How strongly the photo is veiled, 0.00 (clear) to 1.00 (heavily washed). */
    public function bgVeil(): string
    {
        return number_format(max(0, min(100, (int) $this->opt('bg_veil'))) / 100, 2, '.', '');
    }

    /** Inline CSS for the background photo (hero + login page). */
    public function bgStyle(): string
    {
        $size = match ($this->opt('bg_fit')) {
            'contain' => 'contain',
            'stretch' => '100% 100%',
            default   => 'cover',
        };
        $pos = match ($this->opt('bg_position')) {
            'top'    => 'center top',
            'bottom' => 'center bottom',
            default  => 'center center',
        };
        $blur = max(0, min(12, (int) $this->opt('bg_blur')));
        $bri  = max(60, min(140, (int) $this->opt('bg_brightness')));
        $sat  = max(50, min(150, (int) $this->opt('bg_saturation')));

        return "background-size:{$size};background-position:{$pos};"
             . "filter:blur({$blur}px) brightness({$bri}%) saturate({$sat}%);";
    }

    // ------------------------------------------------------------------
    //  Theme colours and fonts
    // ------------------------------------------------------------------

    /** $which is 'a' (main colour) or 'b' (second colour). Always returns a safe #RRGGBB value. */
    public function themeColor(string $which): string
    {
        $value = $this->{'theme_' . $which} ?? null;

        return is_string($value) && preg_match('/^#[0-9A-Fa-f]{6}$/', $value)
            ? strtoupper($value)
            : self::DEFAULT_THEME[$which];
    }

    /** $which is 'body' or 'heading'. Only fonts from the approved list are ever returned. */
    public function fontName(string $which): string
    {
        $value = $this->{'font_' . $which} ?? null;

        return in_array($value, self::FONTS, true) ? $value : self::DEFAULT_FONTS[$which];
    }

    /** What the admin picked: auto, dark, light or custom. */
    public function heroTextSetting(): string
    {
        $value = $this->hero_text ?? null;

        return in_array($value, self::HERO_TEXT, true) ? $value : 'auto';
    }

    /** What is really used: 'auto' = light text in dark mode, dark text in light mode. */
    public function heroText(): string
    {
        $value = $this->heroTextSetting();

        return $value === 'auto'
            ? ($this->siteMode() === 'dark' ? 'light' : 'dark')
            : $value;
    }

    /** The colour used when heroText() is 'custom'. */
    public function heroTextColor(): string
    {
        $value = $this->hero_text_color ?? null;

        return is_string($value) && preg_match('/^#[0-9A-Fa-f]{6}$/', $value)
            ? strtoupper($value)
            : self::DEFAULT_HERO_CUSTOM;
    }

    /** Kept only so older code does not break. The header now follows the site mode. */
    public function headerStyle(): string
    {
        $value = $this->header_style ?? null;

        return in_array($value, self::HEADER_STYLES, true) ? $value : 'light';
    }

    /** 'dark' (default) or 'light': the look of every section below the hero. */
    public function siteMode(): string
    {
        $value = $this->site_mode ?? null;

        return in_array($value, self::SITE_MODES, true) ? $value : 'dark';
    }

    /** Header shade as #RRGGBB, or null for the default look. */
    public function headerShade(): ?string
    {
        $value = $this->header_shade ?? null;

        return is_string($value) && preg_match('/^#[0-9A-Fa-f]{6}$/', $value)
            ? strtoupper($value)
            : null;
    }

    /** Header design: classic (default), glass, aurora or spotlight. */
    public function headerDesign(): string
    {
        $value = $this->header_design ?? null;

        return in_array($value, self::HEADER_DESIGNS, true) ? $value : 'classic';
    }

    /** True when the hero text is light, so the photo needs a DARK wash behind it. */
    private function heroTextIsLight(): bool
    {
        if ($this->heroText() === 'light') {
            return true;
        }
        if ($this->heroText() !== 'custom') {
            return false;
        }

        [$r, $g, $b] = sscanf($this->heroTextColor(), '#%02x%02x%02x');

        return (0.299 * $r + 0.587 * $g + 0.114 * $b) > 150;
    }

    private function hasCustomColors(): bool
    {
        return $this->themeColor('a') !== self::DEFAULT_THEME['a']
            || $this->themeColor('b') !== self::DEFAULT_THEME['b'];
    }

    private function hasCustomFonts(): bool
    {
        return $this->fontName('body') !== self::DEFAULT_FONTS['body']
            || $this->fontName('heading') !== self::DEFAULT_FONTS['heading'];
    }

    /** Google Fonts stylesheet for the chosen fonts, or null while the default fonts are in use. */
    public function themeFontsUrl(): ?string
    {
        if (! $this->hasCustomFonts()) {
            return null;
        }

        $families = collect([$this->fontName('body'), $this->fontName('heading')])
            ->unique()
            ->map(fn ($name) => 'family=' . str_replace(' ', '+', $name) . ':wght@400;500;600;700')
            ->implode('&');

        return "https://fonts.googleapis.com/css2?{$families}&display=swap";
    }

    /** CSS that re-colours / re-fonts the whole site. Empty while nothing was changed. */
    public function themeCss(): string
    {
        $css = '';

        if ($this->hasCustomColors()) {
            $css .= ':root{'
                . '--nm-a:' . $this->themeColor('a') . ';'
                . '--nm-b:' . $this->themeColor('b') . ';'
                . '--nm-deep:color-mix(in srgb,var(--nm-a) 78%,#000);'
                . '--ring:color-mix(in srgb,var(--nm-b) 28%,transparent);'
                . '}'
                . '.nmf{--a:var(--nm-a);--b:var(--nm-b);}';
        }

        if ($this->hasCustomFonts()) {
            $body    = $this->fontName('body');
            $heading = $this->fontName('heading');

            $css .= ":root{--font-body:'{$body}',system-ui,sans-serif;--font-heading:'{$heading}',system-ui,sans-serif;}"
                . 'body,.nmf{font-family:var(--font-body);}'
                . 'h1,h2,h3,h4,.brand,.stat-num,.brand-text,.page-head h1,.st-card h2,.logo b,.mark,'
                . '.nmf-logo,.nmf h4,.abt h2.abt-title,.abt h3.abt-sub,.wwd .wwd-n,.wwd .wwd-card h3,'
                . '.sx-count b,.sx-mark,.v-out b,.v-hero strong,.v-banner{font-family:var(--font-heading);}'
                . '.v-out small,.v-banner small,.brand-text small{font-family:var(--font-body);}';
        }

        // ---- Hero text colour (readable on top of the background photo) ----
        $mode = $this->heroText();

        if ($this->heroTextIsLight()) {
            // Light text needs a dark wash over the photo.
            $css .= '.hero-veil{background:linear-gradient(90deg,rgba(6,11,36,.92) 0%,rgba(6,11,36,.78) 45%,rgba(6,11,36,.55) 100%);}';
        }

        if ($mode === 'light') {
            $css .= '.hero .eyebrow-line{color:#DBEAFE;}'
                . '.hero h1.grad-text{background:none;color:#fff;-webkit-text-fill-color:#fff;}'
                . '.hero p{color:rgba(255,255,255,.9);}';
        } elseif ($mode === 'custom') {
            $c = $this->heroTextColor();
            $css .= ".hero .eyebrow-line{color:{$c};}"
                . ".hero h1.grad-text{background:none;color:{$c};-webkit-text-fill-color:{$c};}"
                . ".hero p{color:{$c};}";
        }

        // ---- Header shade (theme.css decides how light / dark it looks per mode and design) ----
        if ($shade = $this->headerShade()) {
            $css .= ":root{--hdr-shade:{$shade};}";
        }

        // ---- Logo sizes ----
        $css .= $this->logoCss();

        return $css;
    }
}