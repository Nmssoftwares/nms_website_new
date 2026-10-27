@push('styles')
<link rel="stylesheet" href="{{ asset('css/sections/about.css') }}?v={{ @filemtime(public_path('css/sections/about.css')) }}">
@endpush

@push('vendor-scripts')
<script src="{{ asset('js/about3d.js') }}?v={{ @filemtime(public_path('js/about3d.js')) }}" defer></script>
@endpush

@php
$points = [
  'Custom Software Development',
  'Website & Web App Solutions',
  'AI, ML & Data Science Implementations',
  'End-to-End Digital Transformation',
];
@endphp

<section class="section reveal abt-sec dz" id="about">
  <canvas id="abt-cv" aria-hidden="true"></canvas>
  <div class="abt-glow" aria-hidden="true"></div>
  <div class="wrap abt">
    <div class="abt-grid">

      <div class="abt-text">
        <span class="abt-eyebrow">About Us</span>
        <h2 class="abt-title">Innovative software solutions for the <span class="abt-grad">digital age.</span></h2>
        <p class="abt-lead">NMS Softwares and Innovation India Pvt. Ltd. is a leading software development company committed to delivering innovative solutions that help businesses thrive in the digital age. Our expertise in data science and artificial intelligence enables us to transform complex challenges into opportunities for growth.</p>

        <h3 class="abt-sub">Who We Are</h3>
        <p class="abt-txt">We combine innovation, strategy and technology to deliver powerful digital products. Our mission is to build solutions that are scalable, user-friendly and drive measurable business outcomes.</p>

        <ul class="abt-list">
          @foreach($points as $p)
            <li>
              <i class="abt-ck"><svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="5 12.5 10 17.5 19 7.5"/></svg></i>
              {{ $p }}
            </li>
          @endforeach
        </ul>
      </div>

      <div class="abt-art" role="img" aria-label="A consultant presenting analytics on a screen">
        <div class="abt-persp">
          <div class="abt-stage" id="abt-stage">

            <div class="abt-ring" aria-hidden="true"></div>

            {{-- back: soft blob --}}
            <div class="abt-ly" style="--z:-10px"><svg viewBox="0 0 1024 1024" aria-hidden="true">
              <defs>
                <linearGradient id="abtBlobA" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#8FA8FF" stop-opacity=".30"/><stop offset="1" stop-color="#6B5CE7" stop-opacity=".18"/></linearGradient>
                <linearGradient id="abtBlobB" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#4B4FD0" stop-opacity=".45"/><stop offset="1" stop-color="#7FA6FF" stop-opacity=".92"/></linearGradient>
              </defs>
              <g class="abt-fl" style="--y:-6px;--t:9s">
                <path d="M400 480C440 360 600 330 650 200C690 90 800 40 868 108C938 180 912 340 884 470Z" fill="url(#abtBlobA)"/>
                <path d="M80 650C58 566 140 506 232 510C330 446 520 424 704 434C864 444 992 524 976 648C960 770 820 856 642 912C522 950 400 952 300 926C182 896 102 770 80 650Z" fill="url(#abtBlobB)"/>
              </g>
            </svg></div>

            {{-- circles + floor glow --}}
            <div class="abt-ly" style="--z:12px"><svg viewBox="0 0 1024 1024" aria-hidden="true">
              <defs><radialGradient id="abtFloor"><stop offset="0" stop-color="#9A7BF2" stop-opacity=".55"/><stop offset="1" stop-color="#9A7BF2" stop-opacity="0"/></radialGradient></defs>
              <ellipse cx="335" cy="944" rx="230" ry="34" fill="url(#abtFloor)"/>
              <g class="abt-fl" style="--y:-12px;--t:5s"><circle cx="248" cy="238" r="98" fill="#B4C6FF" opacity=".22"/><circle cx="259" cy="258" r="65" fill="#8CA8FF" opacity=".92"/></g>
              <g class="abt-fl" style="--y:-9px;--t:6.5s;--d:-2s"><circle cx="743" cy="222" r="75" fill="#A9B4FF" opacity=".75"/></g>
            </svg></div>

            {{-- analytics screen --}}
            <div class="abt-ly" style="--z:60px"><div class="abt-fl" style="--y:-10px;--t:5.5s">
              <div class="abt-scr">
                <div class="abt-scr-head"><span>Revenue</span><em>+24%</em></div>
                <div class="abt-scr-num"><span id="abt-count">248</span>K</div>
                <svg class="abt-spark" viewBox="0 0 100 30" preserveAspectRatio="none" aria-hidden="true"><polyline points="0,25 14,21 28,23 42,13 56,17 70,7 84,10 100,2" pathLength="1"/></svg>
                <div class="abt-bars"><b></b><b></b><b></b><b></b></div>
                <span class="abt-shine"></span>
              </div>
            </div></div>

            {{-- the boy (cut out of your original illustration) --}}
            <div class="abt-ly" style="--z:96px">
              <img class="abt-boy" src="{{ asset('images/about-boy.png') }}" alt="" width="452" height="552" decoding="async">
            </div>

            {{-- circuit chip --}}
            <div class="abt-ly" style="--z:128px"><div class="abt-fl" style="--y:-14px;--t:4.6s;--d:-1s">
              <div class="abt-chip"><svg viewBox="0 0 100 100" fill="none" stroke="#E4E6FF" stroke-width="3.2" stroke-linecap="round" aria-hidden="true">
                <path class="abt-flow" d="M14 70H38L54 50H86"/><path class="abt-flow" d="M14 50H34" style="animation-delay:-.8s"/><path class="abt-flow" d="M38 28H78" style="animation-delay:-1.6s"/>
                <circle class="abt-node" cx="88" cy="50" r="6.5" fill="#EEF0FF" stroke="none"/><circle class="abt-node" cx="82" cy="28" r="6.5" fill="#EEF0FF" stroke="none" style="animation-delay:-.7s"/><circle class="abt-node" cx="12" cy="70" r="5.5" fill="#EEF0FF" stroke="none" style="animation-delay:-1.4s"/>
              </svg></div>
            </div></div>

            {{-- decorations + glass badges --}}
            <div class="abt-ly" style="--z:155px">
              <svg viewBox="0 0 1024 1024" aria-hidden="true">
                <g class="abt-fl" style="--y:-12px;--t:4s"><circle cx="190" cy="398" r="9" fill="#9DB6FF"/><circle cx="106" cy="531" r="8" fill="#B4C6FF"/></g>
                <g class="abt-fl" style="--y:-9px;--t:5s;--d:-1s"><circle cx="931" cy="280" r="10" fill="#A9B4FF"/><circle cx="931" cy="766" r="9" fill="#9DA8FF"/><circle cx="485" cy="199" r="6" fill="#C4B5FD"/></g>
                <g class="abt-fl" style="--y:-10px;--t:6s;--d:-2s"><circle cx="704" cy="926" r="8" fill="#9DB6FF"/><circle cx="581" cy="242" r="3.5" fill="#C4B5FD"/><circle cx="607" cy="242" r="3.5" fill="#C4B5FD"/><circle cx="623" cy="242" r="3.5" fill="#C4B5FD"/></g>
                <g class="abt-fl" style="--y:-9px;--t:6s;--d:-3s" fill="none" stroke="#9DA8FF" stroke-width="7" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M158 323H124L108 352L124 381H158"/><path d="M905 548L934 585L906 622"/><path d="M850 864H822L810 886L822 908H850" opacity=".7"/><path d="M898 376H944" opacity=".8"/>
                </g>
              </svg>
              <div class="abt-fl" style="--y:-8px;--t:5s"><div class="abt-badge abt-b1"><i></i>99.9% Uptime</div></div>
              <div class="abt-fl" style="--y:-10px;--t:6s;--d:-2s"><div class="abt-badge abt-b2"><svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="5 12.5 10 17.5 19 7.5"/></svg>Deployed</div></div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</section>