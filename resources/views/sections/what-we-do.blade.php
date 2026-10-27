@push('styles')
<link rel="stylesheet" href="{{ asset('css/sections/what-we-do.css') }}?v={{ @filemtime(public_path('css/sections/what-we-do.css')) }}">
@endpush

@push('vendor-scripts')
<script src="{{ asset('js/what-we-do.js') }}?v={{ @filemtime(public_path('js/what-we-do.js')) }}" defer></script>
@endpush

@php
$cards = [
  ['title' => 'End-to-end Product Engineering', 'text' => 'Building next-gen products & platforms with user-first experiences.',
   'icon' => '<svg class="ly" viewBox="0 0 24 24" aria-hidden="true"><polygon class="ly-top" points="12 2 2 7 12 12 22 7 12 2"/><polyline class="ly-mid" points="2 12 12 17 22 12"/><polyline class="ly-bot" points="2 17 12 22 22 17"/></svg>'],
  ['title' => 'Enterprise Digital Transformation', 'text' => 'Transforming enterprises with cloud, mobility & automation.',
   'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><g class="sy" style="transform-box:fill-box;transform-origin:center"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></g></svg>'],
  ['title' => 'Team Augmentation', 'text' => 'Dedicated engineers working seamlessly with your team.',
   'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><g class="us-a"><circle cx="9" cy="7" r="4"/><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/></g><g class="us-b"><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></g></svg>'],
];
$chips = ['Enterprise Mobility', 'AI & ML', 'Blockchain', 'DevOps', 'UI/UX Design', 'Web Development', 'Cloud Computing', 'Data Science', 'IoT Solutions'];
@endphp

<section class="section reveal wwd-sec dz" id="what-we-do">
  <canvas id="wwd-cv" aria-hidden="true"></canvas>
  <div class="wwd-glow" aria-hidden="true"></div>
  <div class="wrap">
    <div class="sec-head"><h2>What we do</h2><p>From first prototype to enterprise-scale platforms.</p></div>

    <div class="wwd" id="wwd">
      <div class="wwd-grid">
        @foreach($cards as $i => $c)
          <article class="wwd-card{{ $i === 1 ? ' is-feat' : '' }}" style="--i:{{ $i }}">
            <div class="wwd-fx" aria-hidden="true"><span class="wwd-shine"></span><span class="wwd-line"></span></div>
            <span class="wwd-n" aria-hidden="true">{{ sprintf('%02d', $i + 1) }}</span>
            <div class="wwd-ico">{!! $c['icon'] !!}</div>
            <h3>{{ $c['title'] }}</h3>
            <p>{{ $c['text'] }}</p>
            <a class="wwd-go" href="#contact">Talk to us <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
          </article>
        @endforeach
      </div>

      <ul class="wwd-chips">
        @foreach($chips as $i => $chip)
          <li class="wwd-chip" style="--i:{{ $i }}"><span>{{ $chip }}</span></li>
        @endforeach
      </ul>
    </div>
  </div>
</section>

<script>
(function(){
  var r=document.getElementById('wwd');
  if(!r)return;
  r.classList.add('js');
  function show(){r.classList.add('in');}
  if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(es){
      if(es[0].isIntersecting){show();io.disconnect();}
    },{threshold:.2});
    io.observe(r);
  }else{show();}
})();
</script>