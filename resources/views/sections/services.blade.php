@push('styles')
<link rel="stylesheet" href="{{ asset('css/sections/services.css') }}?v={{ @filemtime(public_path('css/sections/services.css')) }}">
<link rel="stylesheet" href="{{ asset('css/sections/services-dark.css') }}?v={{ @filemtime(public_path('css/sections/services-dark.css')) }}">
@endpush

@push('vendor-scripts')
<script src="{{ asset('js/services3d.js') }}?v={{ @filemtime(public_path('js/services3d.js')) }}" defer></script>
@endpush

@php
$svg = fn($d) => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$d.'</svg>';
$icons = [
  'code' => $svg('<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>'),
  'globe' => $svg('<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>'),
  'phone' => $svg('<rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>'),
  'compass' => $svg('<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>'),
  'chart' => $svg('<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>'),
  'cloud' => $svg('<path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/>'),
  'cpu' => $svg('<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/>'),
  'gear' => $svg('<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>'),
];

$services = [
  ['icon' => 'code', 'title' => 'Custom Software Development', 'desc' => 'Tailored software solutions designed to meet your specific business needs and requirements.',
   'points' => ['Custom application development', 'Legacy system modernization', 'Software integration services', 'Quality assurance and testing']],
  ['icon' => 'globe', 'title' => 'Web Development', 'desc' => 'Modern, responsive web applications built with cutting-edge technologies and best practices.',
   'points' => ['Responsive websites', 'Ecommerce solutions', 'Progressive web apps', 'API integration']],
  ['icon' => 'phone', 'title' => 'Mobile App Development', 'desc' => 'Native and cross-platform mobile applications that deliver exceptional user experiences.',
   'points' => ['iOS / Android apps', 'Cross-platform apps', 'UI/UX implementation', 'App maintenance and support']],
  ['icon' => 'compass', 'title' => 'IT Consulting', 'desc' => 'Expert guidance and strategic planning to help you make informed technology decisions.',
   'points' => ['Technology strategy consulting', 'Digital transformation', 'IT infrastructure planning', 'Security assessment']],
  ['icon' => 'chart', 'title' => 'Data Analytics & BI', 'desc' => 'Transform your data into actionable insights with our advanced analytics solutions.',
   'points' => ['Business intelligence solutions', 'Data visualization', 'Predictive analytics', 'Real-time analytics dashboards']],
  ['icon' => 'cloud', 'title' => 'Cloud Solutions', 'desc' => 'Scalable and secure cloud infrastructure solutions for modern businesses.',
   'points' => ['Cloud migration services', 'Cloud-native development', 'DevOps implementation', 'Cloud optimization']],
  ['icon' => 'cpu', 'title' => 'AI & Machine Learning', 'desc' => 'Leverage the power of artificial intelligence to automate and optimize your business processes.',
   'points' => ['Machine learning models', 'Natural Language Processing', 'Computer Vision solutions', 'AI-powered automation']],
  ['icon' => 'gear', 'title' => 'Robotics & Automation', 'desc' => 'Comprehensive industrial automation solutions integrating robotics and PLC systems for enhanced manufacturing efficiency.',
   'points' => ['PLC programming and integration', 'Industrial robotics control', 'SCADA system development', 'Process automation solutions']],
];

/* neural-network drawing: 3 input nodes -> 4 hidden -> 2 output */
$in = [[30,30],[30,75],[30,120]]; $hid = [[100,20],[100,55],[100,95],[100,130]]; $out = [[170,45],[170,105]];
$lines = '';
foreach ($in as $a) foreach ($hid as $b) $lines .= "<line x1=\"$a[0]\" y1=\"$a[1]\" x2=\"$b[0]\" y2=\"$b[1]\"/>";
foreach ($hid as $b) foreach ($out as $c) $lines .= "<line x1=\"$b[0]\" y1=\"$b[1]\" x2=\"$c[0]\" y2=\"$c[1]\"/>";
$nodes = '';
foreach (array_merge($in, $hid, $out) as $k => $n) $nodes .= "<circle cx=\"$n[0]\" cy=\"$n[1]\" r=\"7\" style=\"animation-delay:" . (($k % 5) * 0.3) . "s\"/>";

$visuals = [
  'code' => '<div class="v-code"><div class="v-dots"><i></i><i></i><i></i><em class="v-file">index.html</em></div><p style="--n:15;--d:0s"><span class="k">&lt;!DOCTYPE html&gt;</span></p><p style="--n:6;--d:.25s"><span class="t">&lt;html&gt;</span></p><p style="--n:8;--d:.5s"><span class="t">  &lt;body&gt;</span></p><p style="--n:24;--d:.75s"><span class="t">    &lt;h1&gt;</span><span class="x">Hello World</span><span class="t">&lt;/h1&gt;</span></p><p style="--n:9;--d:1s"><span class="t">  &lt;/body&gt;</span></p><p style="--n:7;--d:1.25s"><span class="t">&lt;/html&gt;</span></p><div class="v-out"><small>OUTPUT</small><b>Hello World</b></div></div>',
  'globe' => '<div class="v-web"><div class="v-bar"><i></i><i></i><i></i><span>nmssoftwares.com</span></div><div class="v-hero"><strong>Build your website</strong><small>Fast. Responsive. Secure.</small><em>Get Started</em></div><div class="v-row"><u><i></i><span></span><span class="s"></span></u><u><i></i><span></span><span class="s"></span></u><u><i></i><span></span><span class="s"></span></u></div></div>',
  'phone' => '<div class="v-phone"><div class="v-notch"></div><div class="v-top"><i class="v-av"></i><div><b></b><b></b></div></div><div class="v-banner">Hello World<small>Welcome to our app</small></div><s><i></i><span></span></s><s><i></i><span></span></s><s><i></i><span></span></s><div class="v-tab"><i></i><i></i><i></i><i></i></div></div>',
  'compass' => '<div class="v-road"><i></i><i></i><i></i><i></i><em class="v-dot"></em></div>',
  'chart' => '<div class="v-chart"><i style="--h:42%"></i><i style="--h:66%"></i><i style="--h:52%"></i><i style="--h:86%"></i><i style="--h:72%"></i><i style="--h:96%"></i></div>',
  'cloud' => '<div class="v-cloud"><svg viewBox="0 0 120 80" aria-hidden="true"><path d="M92 62H32a20 20 0 0 1-4-39.6A28 28 0 0 1 81 21a24 24 0 0 1 11 41z"/></svg><b></b><b></b><b></b></div>',
  'cpu' => '<svg class="v-net" viewBox="0 0 200 150" aria-hidden="true"><g class="v-nl">' . $lines . '</g><g class="v-nn">' . $nodes . '</g></svg>',
  'gear' => '<svg class="v-gear" viewBox="0 0 200 150" aria-hidden="true"><g class="g1"><circle class="t" cx="78" cy="82" r="30" stroke-dasharray="9.42 9.42"/><circle class="r" cx="78" cy="82" r="21"/><circle class="h" cx="78" cy="82" r="7"/></g><g class="g2"><circle class="t" cx="142" cy="50" r="20" stroke-dasharray="7.85 7.85"/><circle class="r" cx="142" cy="50" r="13"/><circle class="h" cx="142" cy="50" r="5"/></g><g class="g3"><circle class="t" cx="148" cy="112" r="16" stroke-dasharray="6.28 6.28"/><circle class="r" cx="148" cy="112" r="10"/><circle class="h" cx="148" cy="112" r="4"/></g></svg>',
];
@endphp

<section class="section reveal dz" id="services">
  <canvas id="svc-cv" aria-hidden="true"></canvas>
  <div class="svc-glow" aria-hidden="true"></div>
  <div class="wrap">
    <div class="sec-head"><h2>Our premium services</h2><p>End-to-end engineering, design and product innovation — built with quality and precision.</p></div>
    <div class="sv-intro">
      <div>
        <p>We offer a comprehensive range of software development and IT consulting services to help businesses thrive in the digital age.</p>
        <div class="sv-badges"><span>8 core services</span><span>Cloud · AI · Robotics</span><span>Built with precision</span></div>
      </div>
      <div class="sv-art">
        <div class="sv3" role="img" aria-label="Laptop showing code">
          <div class="sv3-stage" id="sv3-stage">
            <div class="sv3-ring"></div>
            <div class="sv3-ly" style="--z:-10px"><svg viewBox="0 0 300 260" aria-hidden="true">
              <defs><linearGradient id="svBlob" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#4B4FD0" stop-opacity=".55"/><stop offset="1" stop-color="#8F7CFF" stop-opacity=".95"/></linearGradient></defs>
              <g class="sv3-fl" style="--y:-4px;--t:8s"><path d="M30 130C18 70 70 22 135 20 200 18 266 46 278 112 290 178 240 238 168 244 96 250 42 192 30 130Z" fill="url(#svBlob)"/></g>
            </svg></div>
            <div class="sv3-ly" style="--z:30px"><svg viewBox="0 0 300 260" aria-hidden="true">
              <defs><radialGradient id="svOrb" cx=".35" cy=".3"><stop offset="0" stop-color="#C4B5FD"/><stop offset="1" stop-color="#5B4CDB"/></radialGradient></defs>
              <g class="sv3-fl" style="--y:-10px;--t:5s"><circle cx="62" cy="52" r="18" fill="url(#svOrb)"/></g>
              <g class="sv3-fl" style="--y:-8px;--t:6s;--d:-2s"><circle cx="258" cy="196" r="11" fill="url(#svOrb)"/></g>
            </svg></div>
            <div class="sv3-ly" style="--z:66px"><svg viewBox="0 0 300 260" aria-hidden="true">
              <defs>
                <linearGradient id="svScr" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#3B3FD8"/><stop offset="1" stop-color="#6B5CE7"/></linearGradient>
                <linearGradient id="svBase" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#D9DEFF"/><stop offset="1" stop-color="#9FA8F0"/></linearGradient>
              </defs>
              <g class="sv3-fl" style="--y:-7px;--t:6s">
                <rect x="62" y="62" width="176" height="116" rx="12" fill="#B9C2FF"/>
                <rect x="70" y="70" width="160" height="100" rx="7" fill="url(#svScr)"/>
                <text x="150" y="132" text-anchor="middle" font-size="40" font-weight="700" fill="#E8EAFF" font-family="monospace">&lt;/&gt;</text>
                <path d="M40 182h220l-14 20H54z" fill="url(#svBase)"/>
                <rect x="128" y="184" width="44" height="5" rx="2.5" fill="#8A93E0"/>
              </g>
            </svg></div>
            <div class="sv3-ly" style="--z:110px">
              <span class="sv3-chip c1 sv3-fl">{ API }</span>
              <span class="sv3-chip c2 sv3-fl">⚙ DevOps</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ====== Service explorer: scrolling tabs on top, full-width details below ====== --}}
    <div class="sx" id="sx">
      <div class="sx-tabs">
        <div class="sx-list" role="tablist" aria-label="Our services">
          @foreach($services as $i => $s)
            <button class="sx-tab" type="button" role="tab" id="sx-t-{{ $i }}" aria-controls="sx-p-{{ $i }}" aria-selected="{{ $i === 0 ? 'true' : 'false' }}" tabindex="{{ $i === 0 ? 0 : -1 }}">
              <span class="sx-ti">{!! $icons[$s['icon']] !!}</span>
              <span class="sx-tt">{{ $s['title'] }}</span>
              <span class="sx-bar"></span>
            </button>
          @endforeach
        </div>
        <div class="sx-nav">
          <button type="button" class="sx-prev" aria-label="Previous service">‹</button>
          <span class="sx-count"><b id="sxNow">01</b> / {{ sprintf('%02d', count($services)) }}</span>
          <button type="button" class="sx-next" aria-label="Next service">›</button>
        </div>
      </div>

      <div class="sx-stage">
        <div class="sx-fx"><div class="sx-glow"></div></div>
        @foreach($services as $i => $s)
          <div class="sx-panel {{ $i === 0 ? 'is-active' : '' }}" id="sx-p-{{ $i }}" role="tabpanel" aria-labelledby="sx-t-{{ $i }}" @if($i !== 0) hidden @endif>
            <div class="sx-mark">{{ sprintf('%02d', $i + 1) }}</div>
            <div class="sx-main">
              <div class="sx-head">
                <div class="sx-ico">{!! $icons[$s['icon']] !!}</div>
                <div>
                  <span class="sx-kicker">Service {{ sprintf('%02d', $i + 1) }} / {{ sprintf('%02d', count($services)) }}</span>
                  <h3>{{ $s['title'] }}</h3>
                </div>
              </div>
              <p class="sx-desc">{{ $s['desc'] }}</p>
              <div class="sx-sub">What's included</div>
              <ul class="sx-points">
                @foreach($s['points'] as $pt)<li><i>✓</i>{{ $pt }}</li>@endforeach
              </ul>
              <div class="sx-actions">
                <a href="#contact" class="sx-btn primary">Get Started <span>→</span></a>
                <a href="tel:+919558466409" class="sx-btn ghost">Talk to us</a>
              </div>
            </div>
            <div class="sx-vis" aria-hidden="true">{!! $visuals[$s['icon']] !!}</div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="sv-cta">
      <div class="cta-text">
        <span class="cta-pulse"></span>
        <div>
          <h3>Ready to build something great?</h3>
          <p>Let's turn your idea into a successful digital product.</p>
        </div>
      </div>
      <div class="cta-actions">
        <a href="#contact" class="cta-btn primary">Contact us <svg viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
        <a href="tel:+919558466409" class="cta-btn ghost"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg> +91 95584 66409</a>
      </div>
    </div>
  </div>
</section>

<script>
(function(){
  var root=document.getElementById('sx');
  if(!root)return;
  var tabs=[].slice.call(root.querySelectorAll('.sx-tab'));
  var panels=[].slice.call(root.querySelectorAll('.sx-panel'));
  var list=root.querySelector('.sx-list');
  var cur=0;

  function select(i,focus){
    cur=(i+tabs.length)%tabs.length;
    tabs.forEach(function(t,k){
      var on=k===cur;
      t.setAttribute('aria-selected',on?'true':'false');
      t.tabIndex=on?0:-1;
      var bar=t.querySelector('.sx-bar');          /* restart the progress line */
      bar.style.animation='none'; void bar.offsetWidth; bar.style.animation='';
    });
    panels.forEach(function(p,k){p.classList.toggle('is-active',k===cur);p.hidden=k!==cur;});
    var tb=tabs[cur];                               /* keep the active tab centred in the scrolling row */
    list.scrollTo({left:tb.offsetLeft-(list.clientWidth-tb.offsetWidth)/2,behavior:'smooth'});
    var now=document.getElementById('sxNow');
    if(now)now.textContent=('0'+(cur+1)).slice(-2);
    if(focus)tabs[cur].focus({preventScroll:true});
  }

  tabs.forEach(function(t,k){
    t.addEventListener('click',function(){select(k);});
    t.addEventListener('keydown',function(e){
      if(e.key==='ArrowRight'||e.key==='ArrowDown'){e.preventDefault();select(cur+1,true);}
      if(e.key==='ArrowLeft'||e.key==='ArrowUp'){e.preventDefault();select(cur-1,true);}
      if(e.key==='Home'){e.preventDefault();select(0,true);}
      if(e.key==='End'){e.preventDefault();select(tabs.length-1,true);}
    });
    /* when the progress line finishes, move to the next service */
    t.querySelector('.sx-bar').addEventListener('animationend',function(){
      if(t.getAttribute('aria-selected')==='true')select(cur+1);
    });
  });

  /* prev / next buttons */
  root.querySelector('.sx-prev').addEventListener('click',function(){select(cur-1);});
  root.querySelector('.sx-next').addEventListener('click',function(){select(cur+1);});

  /* swipe left / right on the panel (touch screens) */
  var stage=root.querySelector('.sx-stage'),sx0=0,sy0=0;
  stage.addEventListener('touchstart',function(e){sx0=e.touches[0].clientX;sy0=e.touches[0].clientY;},{passive:true});
  stage.addEventListener('touchend',function(e){
    var dx=e.changedTouches[0].clientX-sx0,dy=e.changedTouches[0].clientY-sy0;
    if(Math.abs(dx)>50&&Math.abs(dx)>Math.abs(dy)*1.5)select(dx<0?cur+1:cur-1);
  },{passive:true});

  /* soft light that follows the mouse over the panel */
  if(window.matchMedia('(hover:hover)').matches){
    stage.addEventListener('mousemove',function(e){
      var r=stage.getBoundingClientRect();
      stage.style.setProperty('--gx',(e.clientX-r.left)+'px');
      stage.style.setProperty('--gy',(e.clientY-r.top)+'px');
    });
  }

  /* auto-play only while the section is on screen, paused on hover/focus,
     and not at all on touch screens (people tap there) */
  if(window.matchMedia('(hover:none)').matches)root.classList.add('sx-hold');
  root.addEventListener('mouseenter',function(){root.classList.add('sx-hold');});
  root.addEventListener('mouseleave',function(){if(!window.matchMedia('(hover:none)').matches)root.classList.remove('sx-hold');});
  if('IntersectionObserver' in window){
    new IntersectionObserver(function(es){
      es.forEach(function(e){root.classList.toggle('sx-run',e.isIntersecting);});
    },{threshold:.25}).observe(root);
  }else{root.classList.add('sx-run');}
})();
</script>