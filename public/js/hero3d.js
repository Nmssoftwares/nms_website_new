/* Hero: 3D console (cursor tilt + scroll) and the network background (canvas #hero3d). */
(function () {
  var root = document.getElementById('hx');
  if (!root) return;
  var stage = root.querySelector('.hx-stage');
  var card = root.querySelector('.hx-card');
  var hero = root.closest('.hero') || root;
  var cv = document.getElementById('hero3d');
  var ctx = cv ? cv.getContext('2d') : null;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

  var W = 0, H = 0, nodes = [], linkDist = 120, rgb = '154,123,242', dot = '210,196,255';
  var tRX = 0, tRY = 0, cRX = 0, cRY = 0, tS = 0, cS = 0, visible = true, running = false;
  function clamp(v, a, b) { return Math.max(a, Math.min(b, v)); }

  function readColor() {
    var v = getComputedStyle(document.documentElement).getPropertyValue('--nm-b').trim();
    var m = /^#([0-9a-f]{6})$/i.exec(v);
    if (!m) return;
    var n = parseInt(m[1], 16), r = n >> 16, g = (n >> 8) & 255, b = n & 255;
    rgb = r + ',' + g + ',' + b;
    dot = Math.round(r + (255 - r) * .5) + ',' + Math.round(g + (255 - g) * .5) + ',' + Math.round(b + (255 - b) * .5);
  }

  function build() {
    if (!cv) return;
    var d = Math.min(window.devicePixelRatio || 1, 2);
    W = hero.clientWidth; H = hero.clientHeight;
    cv.width = W * d; cv.height = H * d;
    cv.style.width = W + 'px'; cv.style.height = H + 'px';
    ctx.setTransform(d, 0, 0, d, 0, 0);
    linkDist = W < 700 ? 90 : 120;
    var count = Math.round(clamp(W * H / 20000, 24, 70));
    nodes = [];
    for (var i = 0; i < count; i++) {
      nodes.push({ x: Math.random(), y: Math.random(), z: .3 + Math.random() * .7,
        vx: (Math.random() - .5) * .00006, vy: (Math.random() - .5) * .00006 });
    }
  }

  function drawNetwork() {
    if (!ctx) return;
    ctx.clearRect(0, 0, W, H);
    ctx.lineWidth = 1;
    var pts = nodes.map(function (n) {
      if (!reduce) { n.x = (n.x + n.vx * 16 + 1) % 1; n.y = (n.y + n.vy * 16 + 1) % 1; }
      return { x: n.x * W + cRY * n.z * 1.5,
               y: (((n.y * H - cS * H * .25 * n.z) % H) + H) % H, z: n.z };
    });
    for (var i = 0; i < pts.length; i++) {
      for (var j = i + 1; j < pts.length; j++) {
        var d = Math.hypot(pts[i].x - pts[j].x, pts[i].y - pts[j].y);
        if (d < linkDist) {
          ctx.strokeStyle = 'rgba(' + rgb + ',' + (.42 * (1 - d / linkDist)).toFixed(3) + ')';
          ctx.beginPath(); ctx.moveTo(pts[i].x, pts[i].y); ctx.lineTo(pts[j].x, pts[j].y); ctx.stroke();
        }
      }
    }
    pts.forEach(function (q) {
      ctx.fillStyle = 'rgba(' + dot + ',' + (.35 + q.z * .5).toFixed(2) + ')';
      ctx.beginPath(); ctx.arc(q.x, q.y, 1 + q.z * 1.6, 0, 6.2832); ctx.fill();
    });
  }

  function step() {
    cRX += (tRX - cRX) * .08;
    cRY += (tRY - cRY) * .08;
    cS += (tS - cS) * .08;
    stage.style.setProperty('--rx', (cRX + cS * 14).toFixed(2) + 'deg');
    stage.style.setProperty('--ry', (cRY - cS * 18).toFixed(2) + 'deg');
    stage.style.setProperty('--ty', (-cS * 30).toFixed(1) + 'px');
    stage.style.setProperty('--sp', (1 + cS * .8).toFixed(3));
    drawNetwork();
  }

  function frame() {
    if (!visible) { running = false; return; }
    step();
    requestAnimationFrame(frame);
  }
  function start() { if (!running && visible) { running = true; requestAnimationFrame(frame); } }

  readColor();
  build();
  window.addEventListener('resize', function () { build(); if (reduce) drawNetwork(); });

  if (reduce) { drawNetwork(); return; }

  if (fine) {
    hero.addEventListener('pointermove', function (e) {
      var r = root.getBoundingClientRect();
      var nx = (e.clientX - (r.left + r.width / 2)) / (window.innerWidth / 2);
      var ny = (e.clientY - (r.top + r.height / 2)) / (window.innerHeight / 2);
      tRX = clamp(-ny * 6, -6, 6);
      tRY = clamp(nx * 9, -9, 9);
      var c = card.getBoundingClientRect();
      card.style.setProperty('--mx', clamp((e.clientX - c.left) / c.width * 100, 0, 100) + '%');
      card.style.setProperty('--my', clamp((e.clientY - c.top) / c.height * 100, 0, 100) + '%');
    });
    hero.addEventListener('pointerleave', function () { tRX = 0; tRY = 0; });
  }

  window.addEventListener('scroll', function () {
    tS = clamp(window.scrollY / (hero.offsetHeight || 1), 0, 1);
  }, { passive: true });
  tS = clamp(window.scrollY / (hero.offsetHeight || 1), 0, 1);

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (en) { visible = en[0].isIntersecting; if (visible) start(); }).observe(hero);
  }
  start();
})();