/* First-party analytics: page view, scroll depth, clicks (with section + type). */
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

  function send(type, label, value, section, kind) {
    var fd = new FormData();
    fd.append('_token', cfg.token);
    fd.append('v', vid);
    fd.append('t', type);
    fd.append('p', location.pathname);
    if (label)   fd.append('l', label.slice(0, 150));
    if (section) fd.append('s', section);
    if (kind)    fd.append('k', kind);
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
  var SOCIAL = /(facebook|instagram|linkedin|twitter|x\.com|youtube|github|wa\.me|whatsapp|t\.me|telegram|tiktok)/i;

  function sectionOf(el) {
    var s = el.closest('section[id],footer[id]');
    if (s) return s.id.slice(0, 40);
    if (el.closest('header,nav')) return 'header';
    return 'other';
  }

  function kindOf(el, href, section) {
    if (/^(mailto|tel):/i.test(href)) return 'contact';
    if (SOCIAL.test(href)) return 'social';
    if (el.closest('header,nav')) return 'nav';
    if (/\/blog(\/|$|#)/i.test(href) || section === 'blog') return 'blog';
    if (section === 'team') return 'team';
    var isButton = el.tagName === 'BUTTON' || /(^|\s)(btn|button|cta)/i.test(String(el.className || ''));
    if (isButton) return (section === 'contact' && el.type === 'submit') ? 'contact' : 'cta';
    if (section === 'contact') return 'contact';
    return 'other';
  }

  function nameOf(el, href) {
    var t = (el.getAttribute('data-track') || el.getAttribute('aria-label') || el.innerText || el.title || '')
      .replace(/\s+/g, ' ').trim();
    if (!t && href) {
      try {
        if (/^(mailto|tel):/i.test(href)) {
          t = href.replace(/^(mailto|tel):/i, '');
        } else {
          var u = new URL(href, location.href);
          t = u.host !== location.host ? u.host.replace(/^www\./, '') : (u.hash || u.pathname);
        }
      } catch (e) { t = href; }
    }
    return (t || el.tagName.toLowerCase()).slice(0, 60);
  }

  document.addEventListener('click', function (e) {
    var el = e.target.closest && e.target.closest('a,button');
    if (!el) return;
    var href    = el.getAttribute('href') || '';
    var section = sectionOf(el);
    send('click', nameOf(el, href), null, section, kindOf(el, href, section));
  }, true);
})();