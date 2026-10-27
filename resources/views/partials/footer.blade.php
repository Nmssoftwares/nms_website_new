<footer class="nmf" id="site-footer">
  <div class="nmf-wrap">

    <div class="nmf-grid">
      <div class="nmf-brand">
        <p class="nmf-logo">
          @if($siteLogo)
            <img src="{{ $siteLogo }}" alt="NMS Softwares" style="height:44px;width:auto;display:block;">
          @else
            NMS <span>Softwares</span>
          @endif
        </p>
        <p class="nmf-about">Delivering modern, powerful &amp; intelligent software solutions that help businesses grow in the digital world.</p>
        <div class="nmf-social">
          <a href="https://www.instagram.com/nmssoftwares" target="_blank" rel="noopener" aria-label="Instagram">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" class="fill"/></svg>
          </a>
          <a href="https://www.linkedin.com/company/nms-softwares-and-innovation-india-pvt-ltd/" target="_blank" rel="noopener" aria-label="LinkedIn">
            <svg viewBox="0 0 24 24"><rect x="3" y="9" width="4" height="12" class="fill"/><circle cx="5" cy="4.6" r="2.2" class="fill"/><path class="fill" d="M10 9h3.8v1.8c.6-1.1 2-2.1 4-2.1 3.6 0 4.2 2.4 4.2 5.4V21h-4v-5.8c0-1.4 0-3.1-1.9-3.1s-2.1 1.5-2.1 3V21h-4z"/></svg>
          </a>
        </div>
      </div>

      <div>
        <h4>Quick Links</h4>
        <ul class="nmf-links">
          <li><a href="{{ url('/') }}">Home</a></li>
          <li><a href="{{ url('/') }}#services">Services</a></li>
          <li><a href="{{ url('/') }}#about">About Us</a></li>
          <li><a href="{{ url('/') }}#testimonials">Testimonials</a></li>
        </ul>
      </div>

      <div>
        <h4>Contact</h4>
        <ul class="nmf-contact">
          <li><a href="mailto:info@nmssoftwares.com">
            <span class="nmf-chip"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="3"/><path d="m4 7 8 6 8-6"/></svg></span>
            info@nmssoftwares.com</a></li>
          <li><a href="tel:+919558466409">
            <span class="nmf-chip"><svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg></span>
            +91 95584 66409</a></li>
        </ul>
      </div>
    </div>

    <div class="nmf-bottom">
      <span>&copy; {{ date('Y') }} NMS Softwares and Innovation India Pvt Ltd. All rights reserved.</span>
      <span style="display:flex;gap:14px;align-items:center;flex-wrap:wrap;">
        <a class="nmf-top" href="#top" aria-label="Back to top">Back to top
          <svg viewBox="0 0 24 24"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
        </a>
      </span>
    </div>

  </div>
</footer>a