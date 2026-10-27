/* Light network background (dots + thin lines) for every .dzr dark section. */
(function () {
  var secs = [].slice.call(document.querySelectorAll('.dzr'));
  if (!secs.length) return;

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var rgb = '154,123,242', dot = '210,196,255';
  var running = false;

  function clamp(v, a, b) { return Math.max(a, Math.min(b, v)); }

  function readColor() {
    var v = getComputedStyle(document.documentElement).getPropertyValue('--nm-b').trim();
    var m = /^#([0-9a-f]{6})$/i.exec(v);
    if (!m) return;
    var n = parseInt(m[1], 16), r = n >> 16, g = (n >> 8) & 255, b = n & 255;
    rgb = r + ',' + g + ',' + b;
    dot = Math.round(r + (255 - r) * .5) + ',' + Math.round(g + (255 - g) * .5) + ',' + Math.round(b + (255 - b) * .5);
  }

  function build(it) {
    var d = Math.min(window.devicePixelRatio || 1, 2);
    it.W = it.sec.clientWidth;
    it.H = it.sec.clientHeight;
    it.cv.width = it.W * d;
    it.cv.height = it.H * d;
    it.cv.style.width = it.W + 'px';
    it.cv.style.height = it.H + 'px';
    it.ctx.setTransform(d, 0, 0, d, 0, 0);
    it.link = it.W < 700 ? 90 : 120;
    var count = Math.round(clamp(it.W * it.H / 24000, 20, 60));
    it.nodes = [];
    for (var i = 0; i < count; i++) {
      it.nodes.push({
        x: Math.random(), y: Math.random(), z: .3 + Math.random() * .7,
        vx: (Math.random() - .5) * .00006, vy: (Math.random() - .5) * .00006
      });
    }
  }

  function draw(it) {
    var ctx = it.ctx, W = it.W, H = it.H;
    ctx.clearRect(0, 0, W, H);
    ctx.lineWidth = 1;
    var P = it.nodes.map(function (n) {
      if (!reduce) { n.x = (n.x + n.vx * 16 + 1) % 1; n.y = (n.y + n.vy * 16 + 1) % 1; }
      return { x: n.x * W, y: n.y * H, z: n.z };
    });
    for (var i = 0; i < P.length; i++) {
      for (var j = i + 1; j < P.length; j++) {
        var d = Math.hypot(P[i].x - P[j].x, P[i].y - P[j].y);
        if (d < it.link) {
          ctx.strokeStyle = 'rgba(' + rgb + ',' + (.38 * (1 - d / it.link)).toFixed(3) + ')';
          ctx.beginPath(); ctx.moveTo(P[i].x, P[i].y); ctx.lineTo(P[j].x, P[j].y); ctx.stroke();
        }
      }
    }
    P.forEach(function (q) {
      ctx.fillStyle = 'rgba(' + dot + ',' + (.3 + q.z * .45).toFixed(2) + ')';
      ctx.beginPath(); ctx.arc(q.x, q.y, 1 + q.z * 1.4, 0, 6.2832); ctx.fill();
    });
  }

  readColor();

  var items = secs.map(function (sec) {
    var cv = document.createElement('canvas');
    cv.className = 'net-cv';
    cv.setAttribute('aria-hidden', 'true');
    sec.insertBefore(cv, sec.firstChild);
    var it = { sec: sec, cv: cv, ctx: cv.getContext('2d'), W: 0, H: 0, nodes: [], link: 110, visible: false };
    build(it);
    draw(it);
    return it;
  });

  function frame() {
    var any = false;
    items.forEach(function (it) { if (it.visible) { draw(it); any = true; } });
    if (any) { requestAnimationFrame(frame); } else { running = false; }
  }
  function start() { if (!running && !reduce) { running = true; requestAnimationFrame(frame); } }

  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        var it = items.filter(function (x) { return x.sec === e.target; })[0];
        if (it) it.visible = e.isIntersecting;
      });
      start();
    });
    items.forEach(function (it) { io.observe(it.sec); });
  } else {
    items.forEach(function (it) { it.visible = true; });
    start();
  }

  var t;
  window.addEventListener('resize', function () {
    clearTimeout(t);
    t = setTimeout(function () { items.forEach(function (it) { build(it); draw(it); }); }, 150);
  });
  window.addEventListener('load', function () {
    items.forEach(function (it) { build(it); draw(it); });
  });
})();