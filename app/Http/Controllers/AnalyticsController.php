<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent as E;
use App\Models\Employee;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /** Receives events from public/js/track.js */
    public function track(Request $r)
    {
        $d = $r->validate([
            'v' => 'required|string|max:40',
            't' => 'required|in:view,scroll,click',
            'p' => 'required|string|max:255',
            'l' => 'nullable|string|max:150',
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
            'value'      => $d['n'] ?? null,
            'device'     => $device,
            'referrer'   => $ref,
        ]);

        return response()->noContent();
    }

    /** Admin dashboard with the analysis */
    public function dashboard(Request $r)
    {
        $days = in_array((int) $r->query('days'), [7, 30, 90], true) ? (int) $r->query('days') : 7;
        $from = now()->subDays($days - 1)->startOfDay();
        $q    = fn () => E::where('created_at', '>=', $from);

        $views    = $q()->where('type', 'view')->count();
        $visitors = $q()->where('type', 'view')->distinct()->count('visitor_id');
        $clicks   = $q()->where('type', 'click')->count();

        // views per day
        $raw   = $q()->where('type', 'view')->selectRaw('DATE(created_at) as d, COUNT(*) as c')->groupBy('d')->pluck('c', 'd');
        $daily = [];
        for ($i = 0; $i < $days; $i++) {
            $day         = $from->copy()->addDays($i)->toDateString();
            $daily[$day] = (int) ($raw[$day] ?? 0);
        }

        // scroll depth: how many reached each milestone
        $scroll = [];
        foreach ([25, 50, 75, 100] as $m) {
            $scroll[$m] = $q()->where('type', 'scroll')->where('value', $m)->count();
        }

        $topPages = $q()->where('type', 'view')->selectRaw('path, COUNT(*) as c')
            ->groupBy('path')->orderByDesc('c')->limit(6)->get();

        $topClicks = $q()->where('type', 'click')->selectRaw('label, COUNT(*) as c')
            ->groupBy('label')->orderByDesc('c')->limit(8)->get();

        $devices = $q()->where('type', 'view')->selectRaw('device, COUNT(*) as c')
            ->groupBy('device')->pluck('c', 'device');

        $sources = $q()->where('type', 'view')->whereNotNull('referrer')->selectRaw('referrer, COUNT(*) as c')
            ->groupBy('referrer')->orderByDesc('c')->limit(5)->get();

        return view('admin.analytics', [
            'postCount'     => Post::count(),
            'employeeCount' => Employee::count(),
            'projectCount'  => Project::count(),
            'days' => $days, 'views' => $views, 'visitors' => $visitors, 'clicks' => $clicks,
            'daily' => $daily, 'scroll' => $scroll, 'topPages' => $topPages,
            'topClicks' => $topClicks, 'devices' => $devices, 'sources' => $sources,
        ]);
    }
}