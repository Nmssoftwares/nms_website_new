@extends('admin.layout')

@section('title', 'Analytics')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-analytics.css') }}?v={{ @filemtime(public_path('css/admin-analytics.css')) }}">
@endpush

<div class="page-head">
    <h1>Analytics</h1>

  <div class="an-range">
    @foreach([7 => '7 days', 30 => '30 days', 90 => '90 days'] as $d => $t)
      <a href="{{ route('admin.analytics', ['days' => $d]) }}" class="btn {{ $days == $d ? 'btn-primary' : 'btn-outline' }}" style="padding:8px 14px;">{{ $t }}</a>
    @endforeach
  </div>
</div>

<div class="an-grid">
  <div class="an-card"><div class="an-num">{{ number_format($views) }}</div><div class="an-lbl">Page views</div></div>
  <div class="an-card"><div class="an-num">{{ number_format($visitors) }}</div><div class="an-lbl">Unique visitors</div></div>
  <div class="an-card"><div class="an-num">{{ number_format($clicks) }}</div><div class="an-lbl">Clicks</div></div>
  <div class="an-card"><div class="an-num">{{ $views ? number_format($clicks / $views, 1) : '0.0' }}</div><div class="an-lbl">Clicks per view</div></div>
</div>

<div class="an-card an-card--spaced">
  <h3>Views per day (last {{ $days }} days)</h3>
  @php $max = max($daily) ?: 1; @endphp
  <div class="an-bars">
    @foreach($daily as $day => $c)
      <div class="an-bar" style="height:{{ max(2, round($c / $max * 100)) }}%" title="{{ $day }}: {{ $c }} views"></div>
    @endforeach
  </div>
</div>

<div class="an-grid an-grid--wide">
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

  <div class="an-card">
    <h3>Most clicked</h3>
    @forelse($topClicks as $row)
      <div class="an-row"><span title="{{ $row->label }}">{{ $row->label }}</span><b>{{ $row->c }}</b></div>
    @empty <div class="an-empty">No clicks yet.</div> @endforelse
  </div>

  <div class="an-card">
    <h3>Top pages</h3>
    @forelse($topPages as $row)
      <div class="an-row"><span>{{ $row->path }}</span><b>{{ $row->c }}</b></div>
    @empty <div class="an-empty">No views yet.</div> @endforelse
  </div>

  <div class="an-card">
    <h3>Devices</h3>
    @forelse($devices as $name => $c)
      <div class="an-row"><span>{{ ucfirst($name) }}</span><b>{{ $c }}</b></div>
    @empty <div class="an-empty">No data yet.</div> @endforelse
    <h3 class="an-sub">Traffic sources</h3>
    @forelse($sources as $row)
      <div class="an-row"><span>{{ $row->referrer }}</span><b>{{ $row->c }}</b></div>
    @empty <div class="an-empty">No external sources yet.</div> @endforelse
  </div>
</div>

<div class="an-actions">
  <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ New Blog Post</a>
  <a href="{{ route('admin.employees.create') }}" class="btn btn-outline">+ Add Team Member</a>
</div>
<p class="an-empty an-empty--note">Content: {{ $postCount }} blog posts · {{ $employeeCount }} team members · {{ $projectCount }} portfolio projects</p>
@endsection