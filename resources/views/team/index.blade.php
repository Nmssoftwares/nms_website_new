@extends('layouts.app')

@section('title', 'Our team — NMS Softwares')
@section('meta_description', 'Meet the professional team members behind NMS Softwares.')

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
          'name' => $categories->isEmpty() ? 'Our Team' : 'More of Our Team',
          'featured' => false,
          'members' => $others,
      ]);
  }
@endphp

{{-- ===== Hero ===== --}}
<section class="section reveal post-hero kt-hero">
  <div class="wrap">
    <nav class="kt-crumb" aria-label="Breadcrumb">
      <a href="{{ route('home') }}">Home</a><span>/</span><span>Our Team</span>
    </nav>
    <h1 class="grad-text kt-title">Meet our Professional Team Members</h1>
    <p class="kt-intro">Our team comprises dynamic individuals who bring diverse expertise and unwavering dedication to every project. Each member is a vital piece of our collective success, contributing unique skills and perspectives to drive innovation and excellence.</p>
    <div class="kt-hero-actions">
      <a href="#ourExperts" class="btn btn-solid">Our Experts</a>
      <a href="#nextSection" class="kt-scroll">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4v14m0 0l-6-6m6 6l6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <span>Scroll Down</span>
      </a>
    </div>
  </div>
</section>

<div id="ourExperts" class="kt-anchor"></div>
<div id="nextSection" class="kt-anchor"></div>

{{-- ===== Groups ===== --}}
@forelse($groups as $g)
  @php
    $members = $g['members'];
    $lead    = $g['featured'] ? $members->first() : null;
    $rest    = $lead ? $members->slice(1) : $members;
  @endphp

  @if($lead)
    @php $leadInitials = collect(explode(' ', $lead->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->join(''); @endphp
    <section class="section reveal dz dzr kt-sec" id="team-{{ $g['key'] }}">
      <div class="wrap">
        <div class="kt-featured">
          <div class="kt-featured-text">
            <span class="kt-chip">{{ $g['name'] }}</span>
            <h2>{{ $lead->name }}</h2>
            <div class="tm-role">{{ $lead->role }}</div>
            @if($lead->bio)
              <p class="tm-bio">{{ $lead->bio }}</p>
            @endif
            @include('team._social', ['m' => $lead])
          </div>
          <div class="kt-featured-photo">
            @if($lead->photo_path)
              <img src="{{ $lead->photoUrl() }}" alt="{{ $lead->name }}">
            @else
              <div class="kt-initials">{{ strtoupper($leadInitials) }}</div>
            @endif
          </div>
        </div>
      </div>
    </section>
  @endif

  @if($rest->isNotEmpty())
    <section class="section reveal dz dzr kt-sec" id="team-{{ $g['key'] }}-members">
      <div class="wrap">
        <div class="kt-head"><h2>{{ $g['name'] }}</h2></div>
        <div class="kt-grid">
          @foreach($rest as $m)
            @include('team._card', ['m' => $m])
          @endforeach
        </div>
      </div>
    </section>
  @endif
@empty
  <section class="section reveal">
    <div class="wrap"><p class="blog-index-lead">Our team page is being updated. Please check back soon.</p></div>
  </section>
@endforelse

{{-- ===== Call to action ===== --}}
<section class="section reveal kt-cta-sec">
  <div class="wrap">
    <div class="kt-cta">
      <div class="kt-cta-text">
        <span>Don’t worry, our expert team will get in touch</span>
        <h2>Still Confused?</h2>
        <p>Contact our skilled IT staff to know more.</p>
      </div>
      <div class="kt-cta-actions">
        <a href="{{ route('home') }}#contact" class="kt-btn-light">Contact Us</a>
        <a href="mailto:info@nmssoftwares.com" class="kt-btn-ghost">info@nmssoftwares.com</a>
      </div>
    </div>
  </div>
</section>
@endsection