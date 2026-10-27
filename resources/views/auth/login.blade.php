<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign in — NMS Softwares</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/logo.css') }}?v={{ @filemtime(public_path('css/logo.css')) }}">
<link rel="stylesheet" href="{{ asset('css/login.css') }}?v={{ @filemtime(public_path('css/login.css')) }}">
@include('partials.theme')
</head>
<body>

<div class="bg" aria-hidden="true">
  <div class="photo" style="background-image:url('{{ $bgImage ?: asset('images/login-bg.jpg') }}');--veil:{{ $bgVeil }};{{ $bgStyle }}"></div>
  <div class="glow g1"></div>
  <div class="glow g2"></div>
  <div class="floor"></div>
  <canvas id="net"></canvas>
  <div class="vignette"></div>
</div>

<main class="stage">
  <section class="card">
    <div class="logo">
      @if($siteLogo)
        <img src="{{ $siteLogo }}" alt="NMS Softwares" style="height:44px;width:auto;max-width:140px;object-fit:contain;">
      @else
        <span class="mark">N</span>
      @endif
      <span><b>NMS Softwares</b><small>Content management</small></span>
    </div>

    <h1>Welcome back</h1>
    <p class="sub">Sign in to manage your blog posts and team.</p>

    <form method="POST" action="{{ route('login.attempt') }}" id="loginForm" novalidate>
      @csrf

      <div class="field">
        <label class="lbl" for="email">Email</label>
        <div class="control">
          <svg class="ico" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="3"/><path d="m4 7 8 6 8-6"/></svg>
          <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@nmssoftwares.com"
                 autocomplete="username" class="{{ $errors->has('email') ? 'invalid' : '' }}" required autofocus>
        </div>
        @error('email')<div class="err" role="alert">{{ $message }}</div>@enderror
      </div>

      <div class="field">
        <label class="lbl" for="password">Password</label>
        <div class="control pw">
          <svg class="ico" viewBox="0 0 24 24"><rect x="4" y="10" width="16" height="10" rx="3"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
          <input id="password" type="password" name="password" placeholder="Enter your password"
                 autocomplete="current-password" class="{{ $errors->has('password') ? 'invalid' : '' }}" required>
          <button type="button" class="toggle" id="togglePw" aria-label="Show password" aria-pressed="false">Show</button>
        </div>
        @error('password')<div class="err" role="alert">{{ $message }}</div>@enderror
      </div>

      <label class="remember">
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Keep me signed in
      </label>

      <button type="submit" class="btn" id="submitBtn">Sign in</button>
    </form>
  </section>

  <div class="foot">&copy; {{ date('Y') }} NMS Softwares. Authorized staff only.</div>
</main>

<script>
  // Show / hide password
  const pw = document.getElementById('password'), tg = document.getElementById('togglePw');
  tg.addEventListener('click', () => {
    const show = pw.type === 'password';
    pw.type = show ? 'text' : 'password';
    tg.textContent = show ? 'Hide' : 'Show';
    tg.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    tg.setAttribute('aria-pressed', show);
  });

  // Loading state
  document.getElementById('loginForm').addEventListener('submit', () => {
    const b = document.getElementById('submitBtn'); b.disabled = true; b.textContent = 'Signing in…';
  });

  // Connected-particles background
  (() => {
    const cv = document.getElementById('net'), ctx = cv.getContext('2d');
    const still = matchMedia('(prefers-reduced-motion: reduce)').matches;
    let w, h, dpr, pts = [];
    const LINK = 140;
    function size() {
      dpr = Math.min(devicePixelRatio || 1, 2);
      w = cv.clientWidth; h = cv.clientHeight;
      cv.width = w * dpr; cv.height = h * dpr; ctx.setTransform(dpr,0,0,dpr,0,0);
      const n = Math.max(28, Math.min(80, Math.round(w * h / 22000)));
      pts = Array.from({length:n}, () => ({x:Math.random()*w, y:Math.random()*h, vx:(Math.random()-.5)*.35, vy:(Math.random()-.5)*.35, r:Math.random()*1.6+.6}));
    }
    function frame() {
      ctx.clearRect(0,0,w,h);
      for (const p of pts) {
        if (!still) { p.x += p.vx; p.y += p.vy; if (p.x<0||p.x>w) p.vx*=-1; if (p.y<0||p.y>h) p.vy*=-1; }
        ctx.beginPath(); ctx.arc(p.x,p.y,p.r,0,6.283); ctx.fillStyle='rgba(186,230,253,.85)'; ctx.fill();
      }
      for (let i=0;i<pts.length;i++) for (let j=i+1;j<pts.length;j++) {
        const dx=pts[i].x-pts[j].x, dy=pts[i].y-pts[j].y, d=Math.hypot(dx,dy);
        if (d<LINK) { ctx.strokeStyle='rgba(125,211,252,'+(0.28*(1-d/LINK))+')'; ctx.lineWidth=1; ctx.beginPath(); ctx.moveTo(pts[i].x,pts[i].y); ctx.lineTo(pts[j].x,pts[j].y); ctx.stroke(); }
      }
      if (!still) requestAnimationFrame(frame);
    }
    addEventListener('resize', () => { size(); if (still) frame(); });
    size(); frame();
  })();
</script>
</body>
</html>