@push('styles')
<link rel="stylesheet" href="{{ asset('css/sections/portfolio.css') }}?v={{ @filemtime(public_path('css/sections/portfolio.css')) }}">
@endpush

<section class="section reveal dz dzr" id="portfolio">
  <div class="wrap">
    <div class="sec-head"><h2>Projects</h2><p>A few of the systems we've designed and shipped.</p></div>

    <div class="fp-grid">
      @if($projects->isEmpty())
        {{-- No projects added yet in admin — show styled skeleton placeholder cards --}}
        @for($i = 0; $i < 4; $i++)
          <article class="fp-card">
            <div class="fp-media fp-media--skel">
              <svg class="fp-skel-icon" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
              <div class="fp-num">{{ sprintf('%02d', $i + 1) }}</div>
            </div>
            <div class="fp-body">
              <div class="sk-title"></div>
              <div class="sk-line"></div>
              <div class="sk-line sk-line--short"></div>
              <div class="sk-pills">
                <div class="sk-pill"></div>
                <div class="sk-pill"></div>
                <div class="sk-pill"></div>
              </div>
            </div>
          </article>
        @endfor
      @else
        {{-- Projects added from the admin panel --}}
        @foreach($projects as $i => $project)
          <article class="fp-card">
            <div class="fp-media">
              @if($project->imageUrl())
                <img src="{{ $project->imageUrl() }}" alt="{{ $project->title }}" loading="lazy">
              @endif
              <div class="fp-num">{{ sprintf('%02d', $i + 1) }}</div>
              @if($project->category)
                <div class="fp-cat">{{ $project->category }}</div>
              @endif
            </div>
            <div class="fp-body">
              <h3>{{ $project->title }}</h3>
              @if($project->description)
                <p>{{ $project->description }}</p>
              @endif
              @if($project->techStackList())
                <div class="fp-stack">@foreach($project->techStackList() as $tech)<span>{{ $tech }}</span>@endforeach</div>
              @endif
              <a href="#contact" class="fp-link">Discuss a similar project <span>→</span></a>
            </div>
          </article>
        @endforeach
      @endif
    </div>
  </div>
</section>