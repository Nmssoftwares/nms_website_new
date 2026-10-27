@push('styles')
<link rel="stylesheet" href="{{ asset('css/sections/testimonials.css') }}?v={{ @filemtime(public_path('css/sections/testimonials.css')) }}">
@endpush

@php
$testimonials = [
  ['name' => 'John Smith', 'initials' => 'JS', 'role' => 'Tech Innovations Ltd', 'badge' => null, 'featured' => false,
   'text' => 'NMS Softwares transformed our business with their innovative data solutions. Their expertise in AI and machine learning helped us make better decisions.'],
  ['name' => 'Sarah Johnson', 'initials' => 'SJ', 'role' => 'Digital Solutions Inc', 'badge' => '+40% productivity', 'featured' => true,
   'text' => 'Working with NMS Softwares was a game-changer. Their custom solutions streamlined our operations and boosted productivity by 40%.'],
  ['name' => 'Michael Chen', 'initials' => 'MC', 'role' => 'Global Systems Corp', 'badge' => null, 'featured' => false,
   'text' => 'The team at NMS Softwares is exceptional. Their attention to detail and commitment to delivering quality solutions is outstanding.'],
];
@endphp

<section class="section reveal dz dz2 dzr" id="testimonials">
  <div class="wrap">
    <div class="sec-head"><h2>What our clients say</h2><p>Trusted by teams building the next generation of digital products.</p></div>

    <div class="nmt-grid">
      @foreach($testimonials as $t)
        <article class="nmt-card {{ $t['featured'] ? 'is-featured' : '' }}">
          <span class="nmt-q" aria-hidden="true">&ldquo;</span>
          <div class="nmt-stars" role="img" aria-label="5 out of 5 stars">★★★★★</div>
          @if($t['badge'])<span class="nmt-badge">{{ $t['badge'] }}</span>@endif
          <p class="nmt-text">{{ $t['text'] }}</p>
          <div class="nmt-who">
            <span class="nmt-av" aria-hidden="true">{{ $t['initials'] }}</span>
            <div class="nmt-info">
              <span class="nmt-name">{{ $t['name'] }}</span>
              <span class="nmt-role">{{ $t['role'] }}</span>
            </div>
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>