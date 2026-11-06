@php
  $initials = collect(explode(' ', $m->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->join('');
@endphp
<article class="kt-card">
  <div class="kt-photo">
    @if($m->photo_path)
      <img src="{{ $m->photoUrl() }}" alt="{{ $m->name }}" loading="lazy">
    @else
      <div class="kt-initials">{{ strtoupper($initials) }}</div>
    @endif
    @include('team._social', ['m' => $m])
  </div>
  <div class="kt-body">
    <h3>{{ $m->name }}</h3>
    <div class="kt-role">({{ $m->role }})</div>
  </div>
</article>