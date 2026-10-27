/* Contact section: 3D tilt for the boy (mouse tilt + gentle idle sway). */
(function () {
  var stage = document.getElementById('ct-stage');
  var sec = document.getElementById('contact');
  if (!stage || !sec) return;

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  var tx = 0, ty = 0, cx = 0, cy = 0, idle = true, visible = true, running = false, t0 = performance.now();

  function clamp(v, a, b) { return Math.max(a, Math.min(b, v)); }

  function frame(t) {
    if (!visible) { running = false; return; }
    var swayY = idle ? Math.sin((t - t0) / 1700) * 6 : 0;
    var swayX = idle ? Math.cos((t - t0) / 2300) * 2.5 : 0;
    cx += (tx + swayX - cx) * .08;
    cy += (ty + swayY - cy) * .08;
    stage.style.setProperty('--rx', cx.toFixed(2) + 'deg');
    stage.style.setProperty('--ry', cy.toFixed(2) + 'deg');
    requestAnimationFrame(frame);
  }
  function start() { if (!running && visible) { running = true; requestAnimationFrame(frame); } }

  if (reduce) return;

  if (fine) {
    sec.addEventListener('pointermove', function (e) {
      var r = stage.getBoundingClientRect();
      var nx = (e.clientX - (r.left + r.width / 2)) / (window.innerWidth / 2);
      var ny = (e.clientY - (r.top + r.height / 2)) / (window.innerHeight / 2);
      tx = clamp(-ny * 10, -10, 10);
      ty = clamp(nx * 18, -18, 18);
      idle = false;
    });
    sec.addEventListener('pointerleave', function () { tx = 0; ty = 0; idle = true; });
  }

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (en) { visible = en[0].isIntersecting; if (visible) start(); }).observe(sec);
  }
  start();
})();