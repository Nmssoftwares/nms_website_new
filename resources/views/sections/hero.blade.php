<section class="hero" id="top" style="--veil:{{ $bgVeil }};">
  <div class="hero-bg" style="background-image:url('{{ $bgImage ?: asset('images/hero-building.jpg') }}');{{ $bgStyle }}" aria-hidden="true"></div>
  <div class="hero-veil" aria-hidden="true"></div>
  <canvas id="hero3d"></canvas>
  <div class="wrap hero-inner">
    <div>
      <div class="eyebrow-line">Cloud · AI · Engineering</div>
      <h1 class="grad-text">Transforming Ideas Into Digital Reality</h1>
      <p>We help businesses turn data into insights with survey reports, enhancing their power through
        data science and artificial intelligence.</p>
      <div class="hero-ctas">
        <a href="#contact" class="btn btn-solid">Start a project</a>
        <a href="#services" class="btn btn-outline">View our services</a>
        <a href="#portfolio" class="btn btn-outline">See our work</a>
      </div>
    </div>
    <div class="hx" id="hx" aria-label="NMS at a glance">
      <div class="hx-stage">
        <div class="hx-ring" aria-hidden="true"></div>
        <div class="hx-card">
          <div class="hx-bar"><i></i><i></i><i></i><span><b></b>nms://status</span></div>
          <div class="hx-stats">
            <div class="hx-stat"><div class="hx-num">2024</div><div class="hx-lbl">Year established</div></div>
            <div class="hx-stat"><div class="hx-num">15+</div><div class="hx-lbl">Projects delivered</div></div>
            <div class="hx-stat"><div class="hx-num">20+</div><div class="hx-lbl">Happy clients</div></div>
            <div class="hx-stat"><div class="hx-num">5+</div><div class="hx-lbl">Global partnerships</div></div>
          </div>
          <div class="hx-code">&gt; <em>deploy</em> --cloud --ai <u></u></div>
        </div>
        <div class="hx-chip hx-c3" aria-hidden="true"><div><svg viewBox="0 0 24 24"><path d="M8 7l-5 5 5 5M16 7l5 5-5 5M14 4l-4 16"/></svg>Code</div></div>
        <div class="hx-chip hx-c4" aria-hidden="true"><div><svg viewBox="0 0 24 24"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/></svg>Secure</div></div>
      </div>
    </div>
  </div>
</section>