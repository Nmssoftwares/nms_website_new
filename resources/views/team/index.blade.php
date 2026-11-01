@extends('layouts.app')

@section('title', 'Our team — NMS Softwares')
@section('meta_description', 'Meet the engineers, designers and testers behind NMS Softwares.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dark-zone.css') }}?v={{ @filemtime(public_path('css/dark-zone.css')) }}">
<link rel="stylesheet" href="{{ asset('css/dark-rest.css') }}?v={{ @filemtime(public_path('css/dark-rest.css')) }}">
<link rel="stylesheet" href="{{ asset('css/sections/team.css') }}?v={{ @filemtime(public_path('css/sections/team.css')) }}">
<link rel="stylesheet" href="{{ asset('css/sections/team-page.css') }}?v={{ @filemtime(public_path('css/sections/team-page.css')) }}">
@endpush

@section('content')
@php
  $groups = $categories->map(fn ($c) => [
      'key' => $c->slug, 'name' => $c->name, 'featured' => $c->is_featured, 'members' => $c->employees,
  ]);
  if ($others->isNotEmpty()) {
      $groups->push([
          'key' => 'others',
          'name' => $categories->isEmpty() ? 'Our team' : 'More of our team',
          'featured' => false,
          'members' => $others,
      ]);
  }
@endphp

<section class="section reveal post-hero blog-index-hero">
  <div class="wrap">
    <div class="index">Our team</div>
    <h1 class="grad-text blog-index-title">Meet the team behind NMS Softwares</h1>
    <p class="blog-index-lead">Engineers, designers, and testers who turn ideas into working software.</p>

    @if($groups->count() > 1)
      <div class="tp-filter" id="tpFilter">
        <button type="button" class="tp-pill is-on" data-f="all">All</button>
        @foreach($groups as $g)
          <button type="button" class="tp-pill" data-f="{{ $g['key'] }}">{{ $g['name'] }}</button>
        @endforeach
      </div>
    @endif
  </div>
</section>

@forelse($groups as $g)
  @php
    $members = $g['members'];
    $lead    = $g['featured'] ? $members->first() : null;
    $rest    = $lead ? $members->slice(1) : $members;
  @endphp
  <section class="section reveal dz dzr tp-group" id="team-{{ $g['key'] }}" data-group="{{ $g['key'] }}">
    <div class="wrap">
      <div class="sec-head"><h2>{{ $g['name'] }}</h2></div>

      @if($lead)
        @php $initials = collect(explode(' ', $lead->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->join(''); @endphp
        <div class="tp-lead">
          <div class="tm-photo">
            <div class="tm-inner">
              @if($lead->photo_path)
                <img src="{{ $lead->photoUrl() }}" alt="{{ $lead->name }}">
              @else
                {{ strtoupper($initials) }}
              @endif
            </div>
          </div>
          <div class="tp-lead-body">
            <h3>{{ $lead->name }}</h3>
            <div class="tm-role">{{ $lead->role }}</div>
            @if($lead->bio)<p class="tm-bio">{{ $lead->bio }}</p>@endif
            @include('team._social', ['m' => $lead])
          </div>
        </div>
      @endif

      @if($rest->isNotEmpty())
        <div class="tm-grid">
          @foreach($rest as $m)
            @include('team._card', ['m' => $m])
          @endforeach
        </div>
      @endif
    </div>
  </section>
@empty
  <section class="section reveal">
    <div class="wrap"><p class="blog-index-lead">Our team page is being updated. Please check back soon.</p></div>
  </section>
@endforelse
@endsection

@section('scripts')
<script>
  (function () {
    var bar = document.getElementById('tpFilter');
    if (!bar) return;
    bar.addEventListener('click', function (e) {
      var b = e.target.closest('.tp-pill');
      if (!b) return;
      bar.querySelectorAll('.tp-pill').forEach(function (x) { x.classList.remove('is-on'); });
      b.classList.add('is-on');
      var f = b.getAttribute('data-f');
      document.querySelectorAll('.tp-group').forEach(function (g) {
        g.style.display = (f === 'all' || g.getAttribute('data-group') === f) ? '' : 'none';
      });
    });
  })();
</script>
@endsection