/* Services: network background, 3D laptop scene, tilting service card. */
(function () {
  var sec = document.getElementById('services');
  var cv = document.getElementById('svc-cv');
  if (!sec || !cv) return;
  var scene = document.getElementById('sv3-stage');
  var card = sec.querySelector('.sx-stage');
  var ctx = cv.getContext('2d');
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

  var W = 0, H = 0, nodes = [], link = 110, rgb = '154,123,242', dot = '210,196,255';
  var tx = 0, ty = 0, cx = 0, cy = 0, idle = true, visible = true, running = false, t0 = performance.now();
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
    var d = Math.min(window.devicePixelRatio || 1, 2);
    W = sec.clientWidth; H = sec.clientHeight;
    cv.width = W * d; cv.height = H * d;
    cv.style.width = W + 'px'; cv.style.height = H + 'px';
    ctx.setTransform(d, 0, 0, d, 0, 0);
    link = W < 700 ? 90 : 110;
    var count = Math.round(clamp(W * H / 24000, 22, 64));
    nodes = [];
    for (var i = 0; i < count; i++) {
      nodes.push({ x: Math.random(), y: Math.random(), z: .3 + Math.random() * .7,
        vx: (Math.random() - .5) * .00006, vy: (Math.random() - .5) * .00006 });
    }
  }

  function drawNet() {
    ctx.clearRect(0, 0, W, H);
    ctx.lineWidth = 1;
    var P = nodes.map(function (n) {
      if (!reduce) { n.x = (n.x + n.vx * 16 + 1) % 1; n.y = (n.y + n.vy * 16 + 1) % 1; }
      return { x: n.x * W + cy * n.z * 1.2, y: n.y * H, z: n.z };
    });
    for (var i = 0; i < P.length; i++) {
      for (var j = i + 1; j < P.length; j++) {
        var d = Math.hypot(P[i].x - P[j].x, P[i].y - P[j].y);
        if (d < link) {
          ctx.strokeStyle = 'rgba(' + rgb + ',' + (.34 * (1 - d / link)).toFixed(3) + ')';
          ctx.beginPath(); ctx.moveTo(P[i].x, P[i].y); ctx.lineTo(P[j].x, P[j].y); ctx.stroke();
        }
      }
    }
    P.forEach(function (q) {
      ctx.fillStyle = 'rgba(' + dot + ',' + (.3 + q.z * .45).toFixed(2) + ')';
      ctx.beginPath(); ctx.arc(q.x, q.y, 1 + q.z * 1.4, 0, 6.2832); ctx.fill();
    });
  }

  function frame(t) {
    if (!visible) { running = false; return; }
    if (scene) {
      var swayY = idle ? Math.sin((t - t0) / 1700) * 6 : 0;
      var swayX = idle ? Math.cos((t - t0) / 2300) * 2.5 : 0;
      cx += (tx + swayX - cx) * .08;
      cy += (ty + swayY - cy) * .08;
      scene.style.setProperty('--rx', cx.toFixed(2) + 'deg');
      scene.style.setProperty('--ry', cy.toFixed(2) + 'deg');
    }
    drawNet();
    requestAnimationFrame(frame);
  }
  function start() { if (!running && visible) { running = true; requestAnimationFrame(frame); } }

  readColor();
  build();
  window.addEventListener('resize', function () { build(); if (reduce) drawNet(); });
  if (reduce) { drawNet(); return; }

  if (fine && scene) {
    sec.addEventListener('pointermove', function (e) {
      var r = scene.getBoundingClientRect();
      var nx = (e.clientX - (r.left + r.width / 2)) / (window.innerWidth / 2);
      var ny = (e.clientY - (r.top + r.height / 2)) / (window.innerHeight / 2);
      tx = clamp(-ny * 10, -10, 10);
      ty = clamp(nx * 18, -18, 18);
      idle = false;
    });
    sec.addEventListener('pointerleave', function () { tx = 0; ty = 0; idle = true; });
  }

  /* the service card tilts toward the cursor */
  if (fine && card) {
    card.addEventListener('pointermove', function (e) {
      var r = card.getBoundingClientRect();
      var px = (e.clientX - r.left) / r.width, py = (e.clientY - r.top) / r.height;
      card.style.setProperty('--ry', clamp((px - .5) * 10, -5, 5).toFixed(2) + 'deg');
      card.style.setProperty('--rx', clamp((.5 - py) * 8, -4, 4).toFixed(2) + 'deg');
    });
    card.addEventListener('pointerleave', function () {
      card.style.setProperty('--rx', '0deg');
      card.style.setProperty('--ry', '0deg');
    });
  }

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (en) { visible = en[0].isIntersecting; if (visible) start(); }).observe(sec);
  }
  start();
})();