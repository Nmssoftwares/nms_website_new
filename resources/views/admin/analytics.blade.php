@extends('admin.layout')

@section('title', 'Analytics')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-analytics.css') }}?v={{ @filemtime(public_path('css/admin-analytics.css')) }}">
@endpush

@php
  $totalClicks = array_sum($clickKinds);
  $palette     = ['var(--nm-a)', 'var(--nm-b)', '#8B5CF6', '#F59E0B', '#10B981'];
  $weekdays    = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 0 => 'Sun'];
@endphp

<div class="an-wrap">

  {{-- Header: title, live count, range, export --}}
  <div class="page-head">
    <div>
      <h1>Analytics</h1>
      <div class="an-period">{{ $periodLabel }}</div>
    </div>

    <div class="an-head-right">
      <span class="an-live" title="Visitors with activity in the last 5 minutes">
        <i class="an-dot"></i><b id="an-live-n">{{ $active }}</b> active now
      </span>

      <div class="an-range">
        @foreach([7 => '7 days', 30 => '30 days', 90 => '90 days'] as $d => $t)
          <a href="{{ route('admin.analytics', ['days' => $d]) }}"
             class="btn {{ ! $custom && $days == $d ? 'btn-primary' : 'btn-outline' }}">{{ $t }}</a>
        @endforeach
        <a href="{{ route('admin.analytics.export', $custom ? ['from' => $from, 'to' => $to] : ['days' => $days]) }}"
           class="btn btn-outline" title="Download every event in this period as a CSV file">⬇ CSV</a>
      </div>
    </div>
  </div>

  <form method="GET" action="{{ route('admin.analytics') }}" class="an-custom">
    <span>Custom range</span>
    <input type="date" name="from" value="{{ $from }}" max="{{ now()->toDateString() }}" required>
    <span>to</span>
    <input type="date" name="to" value="{{ $to }}" max="{{ now()->toDateString() }}" required>
    <button type="submit" class="btn btn-outline">Apply</button>
  </form>

  {{-- 1. Number cards with comparison --}}
  <div class="an-grid">
    @foreach($cards as $c)
      <div class="an-card">
        <div class="an-num">{{ $c['value'] }}</div>
        <div class="an-lbl">{{ $c['label'] }}</div>
        <div class="an-delta an-delta--{{ $c['delta']['dir'] }}">
          @if($c['delta']['dir'] === 'up')▲@elseif($c['delta']['dir'] === 'down')▼@elseif($c['delta']['dir'] === 'new')★@else–@endif
          {{ $c['delta']['text'] }}
          <span>vs previous {{ $days }} {{ $days == 1 ? 'day' : 'days' }}</span>
        </div>
      </div>
    @endforeach
  </div>

  {{-- 2. Interactive line chart --}}
  <div class="an-card an-card--spaced">
    <div class="an-chart-head">
      <h3>Views and visitors per day</h3>
      <div class="an-legend">
        <span><i style="background:var(--nm-a)"></i>Page views</span>
        <span><i style="background:var(--nm-b)"></i>Unique visitors</span>
      </div>
    </div>
    <div class="an-chart" id="an-chart"></div>
  </div>

  {{-- 3. Click analysis --}}
  <div class="an-grid an-grid--wide">
    <div class="an-card">
      <h3>Clicks by type</h3>
      @forelse($clickKinds as $k => $c)
        @php $p = $totalClicks ? round($c / $totalClicks * 100) : 0; @endphp
        <div class="an-meter">
          <div class="an-row an-row--flush"><span>{{ $kindLabels[$k] ?? ucfirst($k) }}</span><b>{{ $c }} · {{ $p }}%</b></div>
          <div class="an-track"><div class="an-fill" style="width:{{ $p }}%"></div></div>
        </div>
      @empty <div class="an-empty">No clicks yet.</div> @endforelse
    </div>

    <div class="an-card">
      <h3>Clicks by section of the page</h3>
      @forelse($clickSections as $name => $c)
        @php $p = $totalClicks ? round($c / $totalClicks * 100) : 0; @endphp
        <div class="an-meter">
          <div class="an-row an-row--flush"><span>{{ $name }}</span><b>{{ $c }} · {{ $p }}%</b></div>
          <div class="an-track"><div class="an-fill" style="width:{{ $p }}%"></div></div>
        </div>
      @empty <div class="an-empty">No clicks yet.</div> @endforelse
    </div>

    <div class="an-card">
      <h3>Most clicked</h3>
      @forelse($topClicks as $name => $c)
        <div class="an-row"><span title="{{ $name }}">{{ $name }}</span><b>{{ $c }}</b></div>
      @empty <div class="an-empty">No clicks yet.</div> @endforelse
    </div>
  </div>

  {{-- 4. Funnel + scroll depth --}}
  <div class="an-grid an-grid--wide">
    <div class="an-card">
      <h3>Conversion funnel</h3>
      @php $top = max(1, $funnel[0]['count']); @endphp
      @foreach($funnel as $i => $step)
        @php
          $ofAll  = round(min(100, $step['count'] / $top * 100), 1);
          $before = $i ? $funnel[$i - 1]['count'] : 0;
          $stepP  = $i && $before ? round(min(100, $step['count'] / $before * 100)) : null;
        @endphp
        <div class="an-meter">
          <div class="an-row an-row--flush">
            <span>{{ $step['label'] }}</span>
            <b>{{ number_format($step['count']) }} · {{ $ofAll }}%</b>
          </div>
          <div class="an-track an-track--tall"><div class="an-fill" style="width:{{ max(1, $ofAll) }}%"></div></div>
          @if($stepP !== null)<div class="an-step">{{ $stepP }}% of the previous step</div>@endif
        </div>
      @endforeach
      <div class="an-note">Messages come from your Messages inbox. They can't be matched to a single visitor, so the last step is a total, not an exact path.</div>
    </div>

    <div class="an-card">
      <h3>Scroll depth</h3>
      @foreach($scroll as $m => $c)
        @php $p = $views ? min(100, round($c / $views * 100)) : 0; @endphp
        <div class="an-meter">
          <div class="an-row an-row--flush"><span>Reached {{ $m }}% of page</span><b>{{ $p }}% ({{ $c }})</b></div>
          <div class="an-track"><div class="an-fill" style="width:{{ $p }}%"></div></div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- 5. Busiest times --}}
  <div class="an-card an-card--spaced">
    <h3>Busiest times <small>(page views by weekday and hour)</small></h3>
    <div class="an-scroll-x">
      <div class="an-heat">
        <div class="an-heat-corner"></div>
        @for($h = 0; $h < 24; $h++)<div class="an-heat-h">{{ $h % 3 === 0 ? sprintf('%02d', $h) : '' }}</div>@endfor

        @foreach($weekdays as $dw => $dayName)
          <div class="an-heat-d">{{ $dayName }}</div>
          @for($h = 0; $h < 24; $h++)
            @php $c = $heat[$dw][$h]; $i = $c ? max(.14, $c / $heatMax) : 0; @endphp
            <div class="an-heat-c" style="--i:{{ round($i, 2) }}"
                 title="{{ $dayName }} {{ sprintf('%02d:00', $h) }} — {{ $c }} {{ $c == 1 ? 'view' : 'views' }}"></div>
          @endfor
        @endforeach
      </div>
    </div>
  </div>

  {{-- 6. Audience --}}
  <div class="an-grid an-grid--wide">
    <div class="an-card">
      <h3>Devices</h3>
      @php
        $devTotal = max(1, $devices->sum());
        $acc = 0; $stops = []; $i = 0;
        foreach ($devices as $name => $c) {
            $start = $acc / $devTotal * 100; $acc += $c; $end = $acc / $devTotal * 100;
            $stops[] = $palette[$i % count($palette)] . " {$start}% {$end}%"; $i++;
        }
      @endphp
      @if($devices->isEmpty())
        <div class="an-empty">No data yet.</div>
      @else
        <div class="an-donut-wrap">
          <div class="an-donut" style="background:conic-gradient({{ implode(',', $stops) }})"><span>{{ number_format($devices->sum()) }}<small>views</small></span></div>
          <div class="an-donut-legend">
            @php $i = 0; @endphp
            @foreach($devices as $name => $c)
              <div><i style="background:{{ $palette[$i % count($palette)] }}"></i>{{ ucfirst($name) }} <b>{{ round($c / $devTotal * 100) }}%</b></div>
              @php $i++; @endphp
            @endforeach
          </div>
        </div>
      @endif
    </div>

    <div class="an-card">
      <h3>New vs returning visitors</h3>
      @php $visTotal = max(1, $newVisitors + $returning); @endphp
      @if($newVisitors + $returning === 0)
        <div class="an-empty">No data yet.</div>
      @else
        <div class="an-meter">
          <div class="an-row an-row--flush"><span>New</span><b>{{ $newVisitors }} · {{ round($newVisitors / $visTotal * 100) }}%</b></div>
          <div class="an-track"><div class="an-fill" style="width:{{ round($newVisitors / $visTotal * 100) }}%"></div></div>
        </div>
        <div class="an-meter">
          <div class="an-row an-row--flush"><span>Returning</span><b>{{ $returning }} · {{ round($returning / $visTotal * 100) }}%</b></div>
          <div class="an-track"><div class="an-fill" style="width:{{ round($returning / $visTotal * 100) }}%"></div></div>
        </div>
      @endif

      <h3 class="an-sub">Traffic sources</h3>
      @forelse($sources as $row)
        <div class="an-row"><span>{{ $row->referrer }}</span><b>{{ $row->c }}</b></div>
      @empty <div class="an-empty">No external sources yet.</div> @endforelse
    </div>

    <div class="an-card">
      <h3>Browsers</h3>
      @forelse($browsers as $name => $c)
        <div class="an-row"><span>{{ $name }}</span><b>{{ $c }}</b></div>
      @empty <div class="an-empty">No data yet. (Collected from new visits only.)</div> @endforelse

      <h3 class="an-sub">Operating systems</h3>
      @forelse($systems as $name => $c)
        <div class="an-row"><span>{{ $name }}</span><b>{{ $c }}</b></div>
      @empty <div class="an-empty">No data yet. (Collected from new visits only.)</div> @endforelse
    </div>

    <div class="an-card">
      <h3>Top pages</h3>
      @forelse($topPages as $name => $c)
        <div class="an-row"><span title="{{ $name }}">{{ $name }}</span><b>{{ $c }}</b></div>
      @empty <div class="an-empty">No views yet.</div> @endforelse

      <h3 class="an-sub">Top blog posts</h3>
      @forelse($topPosts as $name => $c)
        <div class="an-row"><span title="{{ $name }}">{{ $name }}</span><b>{{ $c }}</b></div>
      @empty <div class="an-empty">No blog post views yet.</div> @endforelse
    </div>
  </div>

  {{-- 7. Housekeeping --}}
  <div class="an-card an-card--spaced an-house">
    <div>
      <h3>Housekeeping</h3>
      <div class="an-empty">
        {{ number_format($oldCount) }} {{ $oldCount == 1 ? 'event is' : 'events are' }} older than {{ $keepDays }} days.
        Deleting them keeps this page fast. Numbers inside the last {{ $keepDays }} days are not affected.
      </div>
    </div>
    <form method="POST" action="{{ route('admin.analytics.purge') }}"
          onsubmit="return confirm('Delete all analytics events older than {{ $keepDays }} days? This cannot be undone.');">
      @csrf @method('DELETE')
      <button type="submit" class="btn btn-danger" @disabled($oldCount === 0)>Delete data older than {{ $keepDays }} days</button>
    </form>
  </div>

  <div class="an-actions">
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ New Blog Post</a>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-outline">+ Add Team Member</a>
  </div>
  <p class="an-empty an-empty--note">Content: {{ $postCount }} blog posts · {{ $employeeCount }} team members · {{ $projectCount }} portfolio projects</p>
  <p class="an-empty an-empty--note">Visitor numbers are approximate: a visitor is a browser, so the same person on two devices counts twice.</p>
</div>

{{-- Chart + live counter (no external library) --}}
<script>
(function () {
  var data = @json($chart);
  var box  = document.getElementById('an-chart');
  var NS   = 'http://www.w3.org/2000/svg';

  function el(name, attrs, parent) {
    var n = document.createElementNS(NS, name);
    for (var k in attrs) n.setAttribute(k, attrs[k]);
    if (parent) parent.appendChild(n);
    return n;
  }

  function niceMax(v) {
    if (v <= 4) return 4;
    var p = Math.pow(10, Math.floor(Math.log10(v)));
    var f = v / p;
    return (f <= 1 ? 1 : f <= 2 ? 2 : f <= 5 ? 5 : 10) * p;
  }

  function draw() {
    box.innerHTML = '';
    var n = data.labels.length;
    if (!n) return;

    var W = Math.max(320, box.clientWidth), H = 280;
    var m = { l: 40, r: 14, t: 14, b: 30 };
    var iw = W - m.l - m.r, ih = H - m.t - m.b;
    var max = niceMax(Math.max.apply(null, data.views.concat(data.visitors, [1])));

    var x = function (i) { return m.l + (n === 1 ? iw / 2 : (i / (n - 1)) * iw); };
    var y = function (v) { return m.t + ih - (v / max) * ih; };

    var svg = el('svg', { width: W, height: H, viewBox: '0 0 ' + W + ' ' + H, role: 'img',
      'aria-label': 'Line chart of page views and unique visitors per day' }, box);

    for (var g = 0; g <= 4; g++) {
      var gv = (max / 4) * g, gy = y(gv);
      el('line', { x1: m.l, x2: W - m.r, y1: gy, y2: gy, class: 'an-grid-line' }, svg);
      var t = el('text', { x: m.l - 8, y: gy + 4, 'text-anchor': 'end', class: 'an-axis' }, svg);
      t.textContent = Math.round(gv);
    }

    var step = Math.max(1, Math.ceil(n / 7));
    for (var i = 0; i < n; i += step) {
      var xt = el('text', { x: x(i), y: H - 8, 'text-anchor': 'middle', class: 'an-axis' }, svg);
      xt.textContent = data.labels[i].replace(/^[A-Za-z]+, /, '');
    }

    function path(values) {
      return values.map(function (v, i) { return (i ? 'L' : 'M') + x(i).toFixed(1) + ' ' + y(v).toFixed(1); }).join(' ');
    }

    el('path', { d: path(data.views) + ' L' + x(n - 1) + ' ' + y(0) + ' L' + x(0) + ' ' + y(0) + ' Z', class: 'an-area' }, svg);
    el('path', { d: path(data.visitors), class: 'an-line an-line--b' }, svg);
    el('path', { d: path(data.views),    class: 'an-line an-line--a' }, svg);

    var vline = el('line', { y1: m.t, y2: m.t + ih, class: 'an-vline', visibility: 'hidden' }, svg);
    var dotA  = el('circle', { r: 5, class: 'an-pt an-pt--a', visibility: 'hidden' }, svg);
    var dotB  = el('circle', { r: 5, class: 'an-pt an-pt--b', visibility: 'hidden' }, svg);
    var tip   = document.createElement('div');
    tip.className = 'an-tip'; tip.hidden = true; box.appendChild(tip);

    function show(evt) {
      var rect = svg.getBoundingClientRect();
      var px   = evt.clientX - rect.left;
      var idx  = Math.round(((px - m.l) / iw) * (n - 1));
      idx = Math.max(0, Math.min(n - 1, n === 1 ? 0 : idx));

      var cx = x(idx);
      vline.setAttribute('x1', cx); vline.setAttribute('x2', cx); vline.setAttribute('visibility', 'visible');
      dotA.setAttribute('cx', cx); dotA.setAttribute('cy', y(data.views[idx]));    dotA.setAttribute('visibility', 'visible');
      dotB.setAttribute('cx', cx); dotB.setAttribute('cy', y(data.visitors[idx])); dotB.setAttribute('visibility', 'visible');

      tip.innerHTML = '<b></b><span><i class="a"></i>Page views: <strong></strong></span><span><i class="b"></i>Visitors: <strong></strong></span>';
      tip.querySelector('b').textContent = data.labels[idx];
      var s = tip.querySelectorAll('strong');
      s[0].textContent = data.views[idx]; s[1].textContent = data.visitors[idx];
      tip.hidden = false;

      var left = cx + 14;
      if (left + tip.offsetWidth > W - 4) left = cx - tip.offsetWidth - 14;
      tip.style.left = Math.max(4, left) + 'px';
      tip.style.top  = '10px';
    }
    function hide() {
      tip.hidden = true;
      [vline, dotA, dotB].forEach(function (o) { o.setAttribute('visibility', 'hidden'); });
    }

    svg.addEventListener('mousemove', show);
    svg.addEventListener('mouseleave', hide);
    svg.addEventListener('touchstart', function (e) { show(e.touches[0]); }, { passive: true });
    svg.addEventListener('touchmove',  function (e) { show(e.touches[0]); }, { passive: true });
  }

  draw();
  var timer;
  window.addEventListener('resize', function () { clearTimeout(timer); timer = setTimeout(draw, 120); });

  var liveEl = document.getElementById('an-live-n');
  function refreshLive() {
    if (document.hidden) return;
    fetch(@json(route('admin.analytics.live')), { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
      .then(function (r) { return r.ok ? r.json() : null; })
      .then(function (j) { if (j && liveEl) liveEl.textContent = j.active; })
      .catch(function () {});
  }
  setInterval(refreshLive, 30000);
})();
</script>
@endsection