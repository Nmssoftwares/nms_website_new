@php
  $initials = collect(explode(' ', $m->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->join('');
@endphp
<div class="tm-card">
  <div class="tm-photo">
    <div class="tm-inner">
      @if($m->photo_path)
        <img src="{{ $m->photoUrl() }}" alt="{{ $m->name }}" loading="lazy">
      @else
        {{ strtoupper($initials) }}
      @endif
    </div>
  </div>
  <h3>{{ $m->name }}</h3>
  <div class="tm-role">{{ $m->role }}</div>
  @if($m->bio)
    <p class="tm-bio">{{ $m->bio }}</p>
  @endif
  @include('team._social', ['m' => $m])
</div>