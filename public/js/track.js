/* Simple first-party analytics: page view, scroll depth, clicks. */
(function () {
  var cfg = window.NMS_TRACK;
  if (!cfg) return;

  var vid;
  try {
    vid = localStorage.getItem('nms_vid');
    if (!vid) {
      vid = (window.crypto && crypto.randomUUID) ? crypto.randomUUID().replace(/-/g, '').slice(0, 32)
          : Math.random().toString(36).slice(2) + Date.now().toString(36);
      localStorage.setItem('nms_vid', vid);
    }
  } catch (e) { vid = 'anon' + Math.random().toString(36).slice(2, 10); }

  function send(type, label, value) {
    var fd = new FormData();
    fd.append('_token', cfg.token);
    fd.append('v', vid);
    fd.append('t', type);
    fd.append('p', location.pathname);
    if (label) fd.append('l', label.slice(0, 150));
    if (value != null) fd.append('n', value);
    if (type === 'view') fd.append('r', document.referrer || '');
    try {
      if (!(navigator.sendBeacon && navigator.sendBeacon(cfg.url, fd))) {
        fetch(cfg.url, { method: 'POST', body: fd, keepalive: true, credentials: 'same-origin' });
      }
    } catch (e) {}
  }

  // 1) page view
  send('view');

  // 2) scroll depth (each milestone sent once per page load)
  var sent = {}, ticking = false;
  function check() {
    ticking = false;
    var doc = document.documentElement;
    var total = doc.scrollHeight - window.innerHeight;
    var pct = total <= 0 ? 100 : Math.round(((window.pageYOffset || doc.scrollTop) / total) * 100);
    [25, 50, 75, 100].forEach(function (m) {
      if (pct >= m && !sent[m]) { sent[m] = 1; send('scroll', null, m); }
    });
  }
  window.addEventListener('scroll', function () {
    if (!ticking) { ticking = true; requestAnimationFrame(check); }
  }, { passive: true });
  setTimeout(check, 1500);

  // 3) clicks on links and buttons
  document.addEventListener('click', function (e) {
    var el = e.target.closest && e.target.closest('a,button');
    if (!el) return;
    var text = (el.getAttribute('aria-label') || el.innerText || el.title || '').replace(/\s+/g, ' ').trim();
    var href = el.getAttribute('href') || '';
    var label = text || href || el.tagName.toLowerCase();
    if (href && text) label = text + ' → ' + href;
    send('click', label);
  }, true);
})();