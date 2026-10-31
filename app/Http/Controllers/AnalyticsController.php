<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent as E;
use App\Models\ContactMessage;
use App\Models\Employee;
use App\Models\Post;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnalyticsController extends Controller
{
    private const KEEP_DAYS = 90;

    private const KIND_LABELS = [
        'nav'     => 'Navigation',
        'cta'     => 'Call-to-action buttons',
        'contact' => 'Contact (form, email, phone)',
        'social'  => 'Social links',
        'blog'    => 'Blog',
        'team'    => 'Team',
        'other'   => 'Other',
    ];

    private const SECTION_LABELS = [
        'header'       => 'Header / menu',
        'top'          => 'Hero (top)',
        'about'        => 'About',
        'what-we-do'   => 'What we do',
        'services'     => 'Services',
        'portfolio'    => 'Portfolio',
        'testimonials' => 'Testimonials',
        'team'         => 'Team',
        'blog'         => 'Blog',
        'contact'      => 'Contact',
        'site-footer'  => 'Footer',
        'other'        => 'Other',
    ];

    // ---------- 1) Receives events from public/js/track.js ----------
    public function track(Request $r)
    {
        $d = $r->validate([
            'v' => 'required|string|max:40',
            't' => 'required|in:view,scroll,click',
            'p' => 'required|string|max:255',
            'l' => 'nullable|string|max:150',
            's' => 'nullable|string|max:40',
            'k' => 'nullable|in:' . implode(',', array_keys(self::KIND_LABELS)),
            'n' => 'nullable|integer|min:0|max:100',
            'r' => 'nullable|string|max:255',
        ]);

        $ua = (string) $r->userAgent();
        if (preg_match('/bot|crawl|spider|slurp|preview|headless/i', $ua)) {
            return response()->noContent();
        }

        $device = preg_match('/ipad|tablet/i', $ua) ? 'tablet'
            : (preg_match('/mobi|android|iphone/i', $ua) ? 'mobile' : 'desktop');

        $ref = null;
        if (! empty($d['r'])) {
            $host = parse_url($d['r'], PHP_URL_HOST);
            if ($host && $host !== $r->getHost()) {
                $ref = substr($host, 0, 190);
            }
        }

        E::create([
            'visitor_id' => $d['v'],
            'type'       => $d['t'],
            'path'       => $d['p'],
            'label'      => $d['l'] ?? null,
            'section'    => $d['s'] ?? null,
            'kind'       => $d['k'] ?? null,
            'value'      => $d['n'] ?? null,
            'device'     => $device,
            'browser'    => $this->browser($ua),
            'os'         => $this->os($ua),
            'referrer'   => $ref,
        ]);

        return response()->noContent();
    }

    private function browser(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/edg(e|a|ios)?\//i', $ua)       => 'Edge',
            (bool) preg_match('/opr\/|opera/i', $ua)           => 'Opera',
            (bool) preg_match('/samsungbrowser/i', $ua)        => 'Samsung',
            (bool) preg_match('/firefox|fxios/i', $ua)         => 'Firefox',
            (bool) preg_match('/chrome|crios|chromium/i', $ua) => 'Chrome',
            (bool) preg_match('/safari/i', $ua)                => 'Safari',
            default                                            => 'Other',
        };
    }

    private function os(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/windows/i', $ua)            => 'Windows',
            (bool) preg_match('/android/i', $ua)            => 'Android',
            (bool) preg_match('/iphone|ipad|ipod/i', $ua)   => 'iOS',
            (bool) preg_match('/mac os x|macintosh/i', $ua) => 'macOS',
            (bool) preg_match('/cros/i', $ua)               => 'ChromeOS',
            (bool) preg_match('/linux|x11/i', $ua)          => 'Linux',
            default                                         => 'Other',
        };
    }

    // ---------- 2) Admin dashboard ----------
    public function dashboard(Request $r)
    {
        [$from, $to, $span, $custom] = $this->range($r);
        $prevTo   = $from->copy()->subSecond();
        $prevFrom = $from->copy()->subDays($span);

        $cur  = $this->totals($from, $to);
        $prev = $this->totals($prevFrom, $prevTo);

        $between = fn (Carbon $f, Carbon $t) => E::whereBetween('created_at', [$f, $t]);
        $q       = fn () => $between($from, $to);

        $cards = [
            ['label' => 'Page views',      'value' => number_format($cur['views']),    'delta' => $this->delta($cur['views'], $prev['views'])],
            ['label' => 'Unique visitors', 'value' => number_format($cur['visitors']), 'delta' => $this->delta($cur['visitors'], $prev['visitors'])],
            ['label' => 'Clicks',          'value' => number_format($cur['clicks']),   'delta' => $this->delta($cur['clicks'], $prev['clicks'])],
            ['label' => 'Clicks per view', 'value' => number_format($cur['cpv'], 1),   'delta' => $this->delta($cur['cpv'], $prev['cpv'])],
        ];

        // chart: views + visitors per day
        $raw = $q()->where('type', 'view')
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c, COUNT(DISTINCT visitor_id) as u')
            ->groupBy('d')->get()->keyBy('d');

        $chart = ['labels' => [], 'views' => [], 'visitors' => []];
        for ($i = 0; $i < $span; $i++) {
            $day = $from->copy()->addDays($i);
            $key = $day->toDateString();
            $chart['labels'][]   = $day->format('D, j M');
            $chart['views'][]    = (int) ($raw[$key]->c ?? 0);
            $chart['visitors'][] = (int) ($raw[$key]->u ?? 0);
        }

        // scroll depth
        $scroll = [];
        foreach ([25, 50, 75, 100] as $m) {
            $scroll[$m] = $q()->where('type', 'scroll')->where('value', $m)->count();
        }

        // click analysis
        $byKind = $q()->where('type', 'click')->selectRaw('kind, COUNT(*) as c')
            ->groupBy('kind')->pluck('c', 'kind');
        $clickKinds = [];
        foreach ($byKind as $k => $c) {
            $key = $k ?: 'other';
            $clickKinds[$key] = ($clickKinds[$key] ?? 0) + (int) $c;
        }
        arsort($clickKinds);

        $bySection = $q()->where('type', 'click')->selectRaw('section, COUNT(*) as c')
            ->groupBy('section')->pluck('c', 'section');
        $clickSections = [];
        foreach ($bySection as $s => $c) {
            $name = $s ? (self::SECTION_LABELS[$s] ?? Str::headline($s)) : 'Not recorded (older data)';
            $clickSections[$name] = ($clickSections[$name] ?? 0) + (int) $c;
        }
        arsort($clickSections);

        $topClicks = [];
        $rows = $q()->where('type', 'click')->selectRaw('label, COUNT(*) as c')
            ->groupBy('label')->orderByDesc('c')->limit(300)->get();
        foreach ($rows as $row) {
            $name = trim(Str::before((string) $row->label, ' → ')) ?: '(no name)';
            $topClicks[$name] = ($topClicks[$name] ?? 0) + (int) $row->c;
        }
        arsort($topClicks);
        $topClicks = array_slice($topClicks, 0, 10, true);

        // conversion funnel
        $scrolled50 = $q()->where('type', 'scroll')->where('value', '>=', 50)->distinct()->count('visitor_id');
        $clickedCta = $q()->where('type', 'click')->where('kind', 'cta')->distinct()->count('visitor_id');
        $messages   = ContactMessage::whereBetween('created_at', [$from, $to])->count();
        $funnel = [
            ['label' => 'Visited the site',       'count' => $cur['visitors']],
            ['label' => 'Scrolled past 50%',      'count' => min($scrolled50, $cur['visitors'])],
            ['label' => 'Clicked a main button',  'count' => min($clickedCta, $cur['visitors'])],
            ['label' => 'Sent a contact message', 'count' => $messages],
        ];

        // busiest times (weekday x hour)
        [$dowSql, $hourSql] = DB::connection()->getDriverName() === 'sqlite'
            ? ["CAST(strftime('%w', created_at) AS INTEGER)", "CAST(strftime('%H', created_at) AS INTEGER)"]
            : ['DAYOFWEEK(created_at) - 1', 'HOUR(created_at)'];

        $heat = array_fill(0, 7, array_fill(0, 24, 0));
        $q()->where('type', 'view')->selectRaw("$dowSql as dw, $hourSql as hr, COUNT(*) as c")
            ->groupBy('dw', 'hr')->get()
            ->each(function ($row) use (&$heat) {
                $heat[(int) $row->dw][(int) $row->hr] = (int) $row->c;
            });
        $heatMax = max(1, max(array_map('max', $heat)));

        // audience
        $devices  = $q()->where('type', 'view')->selectRaw('device, COUNT(*) as c')->groupBy('device')->pluck('c', 'device');
        $browsers = $this->groupCount($q()->where('type', 'view'), 'browser');
        $systems  = $this->groupCount($q()->where('type', 'view'), 'os');

        $returning = E::where('created_at', '<', $from)
            ->whereIn('visitor_id', $q()->where('type', 'view')->select('visitor_id'))
            ->distinct()->count('visitor_id');
        $returning   = min($returning, $cur['visitors']);
        $newVisitors = max(0, $cur['visitors'] - $returning);

        $sources = $q()->where('type', 'view')->whereNotNull('referrer')
            ->selectRaw('referrer, COUNT(*) as c')->groupBy('referrer')->orderByDesc('c')->limit(6)->get();

        // pages with readable titles
        $pageRows = $q()->where('type', 'view')->selectRaw('path, COUNT(*) as c')
            ->groupBy('path')->orderByDesc('c')->limit(30)->get();
        $slugs  = $pageRows->map(fn ($p) => Str::startsWith($p->path, '/blog/') ? Str::after($p->path, '/blog/') : null)
            ->filter()->unique()->values();
        $titles = $slugs->isEmpty() ? collect() : Post::whereIn('slug', $slugs)->pluck('title', 'slug');

        $topPages = [];
        $topPosts = [];
        foreach ($pageRows as $p) {
            if ($p->path === '/') {
                $name = 'Home';
            } elseif ($p->path === '/blog') {
                $name = 'Blog list';
            } elseif (Str::startsWith($p->path, '/blog/')) {
                $slug = Str::after($p->path, '/blog/');
                $name = $titles[$slug] ?? $p->path;
                $topPosts[$name] = ($topPosts[$name] ?? 0) + (int) $p->c;
            } else {
                $name = $p->path;
            }
            $topPages[$name] = ($topPages[$name] ?? 0) + (int) $p->c;
        }
        arsort($topPages);
        arsort($topPosts);

        return view('admin.analytics', [
            'postCount' => Post::count(), 'employeeCount' => Employee::count(), 'projectCount' => Project::count(),

            'days' => $span, 'custom' => $custom,
            'from' => $from->toDateString(), 'to' => $to->toDateString(),
            'periodLabel' => $from->format('j M Y') . ' – ' . $to->format('j M Y'),

            'cards' => $cards, 'chart' => $chart, 'scroll' => $scroll, 'views' => $cur['views'],
            'clickKinds' => $clickKinds, 'kindLabels' => self::KIND_LABELS,
            'clickSections' => $clickSections, 'topClicks' => $topClicks,
            'funnel' => $funnel, 'heat' => $heat, 'heatMax' => $heatMax,
            'devices' => $devices, 'browsers' => $browsers, 'systems' => $systems,
            'newVisitors' => $newVisitors, 'returning' => $returning,
            'topPages' => array_slice($topPages, 0, 8, true), 'topPosts' => array_slice($topPosts, 0, 6, true),
            'sources' => $sources,

            'active' => $this->activeNow(),
            'keepDays' => self::KEEP_DAYS,
            'oldCount' => E::where('created_at', '<', now()->subDays(self::KEEP_DAYS))->count(),
        ]);
    }

    // ---------- 3) Live "active now" ----------
    public function live()
    {
        return response()->json(['active' => $this->activeNow()]);
    }

    private function activeNow(): int
    {
        return E::where('created_at', '>=', now()->subMinutes(5))->distinct()->count('visitor_id');
    }

    // ---------- 4) CSV export ----------
    public function export(Request $r)
    {
        [$from, $to] = $this->range($r);
        $name = 'analytics-' . $from->toDateString() . '_to_' . $to->toDateString() . '.csv';

        return response()->streamDownload(function () use ($from, $to) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['time', 'visitor', 'type', 'page', 'label', 'section', 'click_type',
                'scroll_percent', 'device', 'browser', 'os', 'referrer'], ',', '"', '\\');

            E::whereBetween('created_at', [$from, $to])->orderBy('id')->chunkById(1000, function ($rows) use ($out) {
                foreach ($rows as $e) {
                    fputcsv($out, [
                        $e->created_at, $e->visitor_id, $e->type, $e->path,
                        $this->csvSafe($e->label), $e->section, $e->kind, $e->value,
                        $e->device, $e->browser, $e->os, $e->referrer,
                    ], ',', '"', '\\');
                }
            });
            fclose($out);
        }, $name, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function csvSafe(?string $v): ?string
    {
        return ($v !== null && preg_match('/^[=+\-@\t\r]/', $v)) ? "'" . $v : $v;
    }

    // ---------- 5) Delete data older than 90 days ----------
    public function purge()
    {
        $n = E::where('created_at', '<', now()->subDays(self::KEEP_DAYS))->delete();

        return redirect()->route('admin.analytics')
            ->with('status', "Deleted {$n} analytics events older than " . self::KEEP_DAYS . ' days.');
    }

    // ---------- Helpers ----------
    private function range(Request $r): array
    {
        if ($r->filled('from') && $r->filled('to')) {
            try {
                $f = Carbon::parse($r->query('from'))->startOfDay();
                $t = Carbon::parse($r->query('to'))->endOfDay();
                if ($t->isFuture()) {
                    $t = now()->endOfDay();
                }
                if ($f <= $t) {
                    if ($f->copy()->diffInDays($t) > 364) {
                        $f = $t->copy()->subDays(364)->startOfDay();
                    }
                    $span = (int) $f->copy()->startOfDay()->diffInDays($t->copy()->startOfDay()) + 1;
                    return [$f, $t, $span, true];
                }
            } catch (\Throwable $e) {
                // bad dates: fall back to 7/30/90
            }
        }

        $days = in_array((int) $r->query('days'), [7, 30, 90], true) ? (int) $r->query('days') : 7;

        return [now()->subDays($days - 1)->startOfDay(), now()->endOfDay(), $days, false];
    }

    private function totals(Carbon $f, Carbon $t): array
    {
        $views = E::whereBetween('created_at', [$f, $t])->where('type', 'view');

        $v      = (clone $views)->count();
        $unique = (clone $views)->distinct()->count('visitor_id');
        $clicks = E::whereBetween('created_at', [$f, $t])->where('type', 'click')->count();

        return ['views' => $v, 'visitors' => $unique, 'clicks' => $clicks, 'cpv' => $v ? $clicks / $v : 0.0];
    }

    private function delta(float|int $cur, float|int $prev): array
    {
        if ($prev == 0) {
            return $cur == 0 ? ['dir' => 'flat', 'text' => 'no change'] : ['dir' => 'new', 'text' => 'new'];
        }
        $p = (int) round((($cur - $prev) / $prev) * 100);

        return [
            'dir'  => $p > 0 ? 'up' : ($p < 0 ? 'down' : 'flat'),
            'text' => ($p > 0 ? '+' : '') . $p . '%',
        ];
    }

    private function groupCount($query, string $col): array
    {
        $out = [];
        foreach ($query->selectRaw("$col as k, COUNT(*) as c")->groupBy('k')->pluck('c', 'k') as $k => $c) {
            $name = $k ?: 'Unknown';
            $out[$name] = ($out[$name] ?? 0) + (int) $c;
        }
        arsort($out);

        return $out;
    }
}