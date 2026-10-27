@push('styles')
<link rel="stylesheet" href="{{ asset('css/sections/team.css') }}?v={{ @filemtime(public_path('css/sections/team.css')) }}">
@endpush

<section class="section reveal dz dzr" id="team">
  <div class="wrap">
    @unless($hideHead ?? false)
      <div class="sec-head"><h2>Meet our team</h2><p>The engineers, designers and strategists behind our products.</p></div>
    @endunless

    <div class="tm-grid">
      @if($employees->isEmpty())
        @php
          $placeholders = [
            ['label' => 'CEO', 'role' => 'Founder & CEO'],
            ['label' => 'AI', 'role' => 'Lead AI Engineer'],
            ['label' => 'ENG', 'role' => 'Head of Engineering'],
            ['label' => 'UX', 'role' => 'Product Designer'],
          ];
        @endphp
        @foreach($placeholders as $p)
          <div class="tm-card">
            <div class="tm-photo">
              <div class="tm-inner">{{ $p['label'] }}</div>
            </div>
            <h3>Name Surname</h3>
            <div class="tm-role">{{ $p['role'] }}</div>
            <p class="tm-bio">Add a short line on their background.</p>
            <div class="tm-social">
              <a href="https://www.linkedin.com/company/nms-softwares-and-innovation-india-pvt-ltd/" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 11-.01 5 2.5 2.5 0 01.01-5zM3 9.75h4V21H3V9.75zm7 0h3.83v1.54h.06c.53-.95 1.84-1.95 3.78-1.95 4.04 0 4.78 2.66 4.78 6.11V21h-4v-4.9c0-1.17-.02-2.67-1.63-2.67-1.63 0-1.88 1.27-1.88 2.59V21h-4V9.75z"/></svg></a>
              <a href="mailto:info@nmssoftwares.com" aria-label="Email"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 5h18a1 1 0 011 1v12a1 1 0 01-1 1H3a1 1 0 01-1-1V6a1 1 0 011-1zm9 7.2L4.6 7 4 8.2l8 5.6 8-5.6-.6-1.2L12 12.2z"/></svg></a>
            </div>
          </div>
        @endforeach
      @else
        @foreach($employees as $m)
          @php
            $initials = collect(explode(' ', $m->name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join('');
          @endphp
          <div class="tm-card">
            <div class="tm-photo">
              <div class="tm-inner">
                @if($m->photo_path)
                  <img src="{{ $m->photoUrl() }}" alt="{{ $m->name }}">
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
            @if($m->linkedin_url || $m->email)
              <div class="tm-social">
                @if($m->linkedin_url)
                  <a href="{{ $m->linkedin_url }}" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 11-.01 5 2.5 2.5 0 01.01-5zM3 9.75h4V21H3V9.75zm7 0h3.83v1.54h.06c.53-.95 1.84-1.95 3.78-1.95 4.04 0 4.78 2.66 4.78 6.11V21h-4v-4.9c0-1.17-.02-2.67-1.63-2.67-1.63 0-1.88 1.27-1.88 2.59V21h-4V9.75z"/></svg></a>
                @endif
                @if($m->email)
                  <a href="mailto:{{ $m->email }}" aria-label="Email"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 5h18a1 1 0 011 1v12a1 1 0 01-1 1H3a1 1 0 01-1-1V6a1 1 0 011-1zm9 7.2L4.6 7 4 8.2l8 5.6 8-5.6-.6-1.2L12 12.2z"/></svg></a>
                @endif
              </div>
            @endif
          </div>
        @endforeach
      @endif
    </div>
  </div>
</section>