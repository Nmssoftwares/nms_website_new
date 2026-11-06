@push('vendor-scripts')
<script src="{{ asset('js/team-motion.js') }}"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sections/team.css') }}?v={{ @filemtime(public_path('css/sections/team.css')) }}">
<link rel="stylesheet" href="{{ asset('css/sections/team-page.css') }}?v={{ @filemtime(public_path('css/sections/team-page.css')) }}">
@endpush

@php
  // Group members by category (visible categories only), in the order set in Admin → Categories.
  $teamGroups = $employees
      ->groupBy(fn ($e) => $e->category?->id ?? 0)
      ->map(fn ($items) => ['cat' => $items->first()->category, 'members' => $items])
      ->filter(fn ($g) => ! $g['cat'] || $g['cat']->is_visible)
      ->sortBy(fn ($g) => $g['cat']?->sort_order ?? 9999)
      ->values();
@endphp

<section class="section reveal dz dzr" id="team">
  <div class="wrap">
    @unless($hideHead ?? false)
      <div class="sec-head"><h2>Meet our team</h2><p>The engineers, designers and strategists behind our products.</p></div>
    @endunless

    @forelse($teamGroups as $g)
      <div class="kt-group">
        @if($g['cat'])
          <div class="kt-head"><h2>{{ $g['cat']->name }}</h2></div>
        @endif
        <div class="kt-grid">
          @foreach($g['members'] as $m)
            @include('team._card', ['m' => $m])
          @endforeach
        </div>
      </div>
    @empty
      <p class="blog-index-lead">Our team will be listed here soon.</p>
    @endforelse

    <div class="kt-more">
      <a href="{{ route('team') }}" class="btn btn-solid">View full team page</a>
    </div>
  </div>
</section>