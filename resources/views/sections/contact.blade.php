@push('styles')
<link rel="stylesheet" href="{{ asset('css/sections/contact.css') }}?v={{ @filemtime(public_path('css/sections/contact.css')) }}">
<link rel="stylesheet" href="{{ asset('css/sections/contact3d.css') }}?v={{ @filemtime(public_path('css/sections/contact3d.css')) }}">
@endpush

<section class="section reveal dz dzr" id="contact">
  <div class="wrap">
    <div class="sec-head">
      <h2>Contact NMS Softwares</h2>
      <p>We're here to help you build scalable, modern and intelligent digital solutions.</p>
    </div>

    <div class="contact-grid">
      {{-- ============ LEFT: 3D picture + contact details ============ --}}
      <div class="contact-info">

        <div class="ct-art" role="img" aria-label="Contact NMS Softwares">
          <div class="ct-persp">
            <div class="ct-stage" id="ct-stage">

              <div class="ct-ring" aria-hidden="true"></div>

              <div class="ct-ly" style="--z:-60px" aria-hidden="true">
                <div class="ct-glow"></div>
                <div class="ct-floor"></div>
              </div>

              <div class="ct-ly" style="--z:-30px" aria-hidden="true">
                <img class="ct-img ct-drift" src="{{ asset('images/contact3d/ct-back.png') }}" alt="">
              </div>

              <div class="ct-ly" style="--z:30px">
                <img class="ct-img ct-breathe" src="{{ asset('images/contact3d/ct-boy.png') }}" alt="">
              </div>

              <div class="ct-ly" style="--z:95px" aria-hidden="true">
                <img class="ct-img ct-bob" style="--o:20.6% 25.2%;--y:-14px;--t:5s" src="{{ asset('images/contact3d/ct-phone.png') }}" alt="">
              </div>

              <div class="ct-ly" style="--z:80px" aria-hidden="true">
                <img class="ct-img ct-bob" style="--o:81.8% 33.4%;--y:-12px;--t:6s;--d:-2s" src="{{ asset('images/contact3d/ct-plane.png') }}" alt="">
              </div>

              <div class="ct-ly" style="--z:65px" aria-hidden="true">
                <img class="ct-img ct-bob" style="--o:77.6% 81.2%;--y:-8px;--t:4.5s;--d:-1s" src="{{ asset('images/contact3d/ct-mail.png') }}" alt="">
              </div>

            </div>
          </div>
        </div>

        <a class="contact-item" href="mailto:info@nmssoftwares.com">
          <span class="ci-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="22 6 12 13 2 6"/></svg></span>
          <span class="ci-text"><span class="c-label">Mail us</span><strong>info@nmssoftwares.com</strong></span>
          <span class="ci-arrow">↗</span>
        </a>

        <a class="contact-item" href="tel:+919558466409">
          <span class="ci-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
          <span class="ci-text"><span class="c-label">Call us 24/7</span><strong>+91 95584 66409</strong></span>
          <span class="ci-arrow">↗</span>
        </a>

        <a class="contact-item" href="https://www.instagram.com/nmssoftwares" target="_blank" rel="noopener">
          <span class="ci-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg></span>
          <span class="ci-text"><span class="c-label">Instagram</span><strong>@nmssoftwares</strong></span>
          <span class="ci-arrow">↗</span>
        </a>

        <a class="contact-item" href="https://www.linkedin.com/company/nms-softwares-and-innovation-india-pvt-ltd/" target="_blank" rel="noopener">
          <span class="ci-icon"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 11-.01 5 2.5 2.5 0 01.01-5zM3 9.75h4V21H3V9.75zm7 0h3.83v1.54h.06c.53-.95 1.84-1.95 3.78-1.95 4.04 0 4.78 2.66 4.78 6.11V21h-4v-4.9c0-1.17-.02-2.67-1.63-2.67-1.63 0-1.88 1.27-1.88 2.59V21h-4V9.75z"/></svg></span>
          <span class="ci-text"><span class="c-label">LinkedIn</span><strong>NMS Softwares and Innovation India Pvt Ltd</strong></span>
          <span class="ci-arrow">↗</span>
        </a>
      </div>

      {{-- ============ RIGHT: form ============ --}}
      <form class="contact-form" id="contact-form" method="POST" action="{{ route('contact.store') }}">
        @csrf
        <h3 class="cf-title">Ready to get started?</h3>
        <p class="cf-sub">Tell us about your project and we'll get back to you.</p>

        @if(session('contact_success'))
          <div class="contact-ok">{{ session('contact_success') }}</div>
        @endif

        {{-- Filled in by main.js when the form is sent without reloading the page --}}
        <div class="contact-ok" id="cf-status" role="status" aria-live="polite" hidden></div>

        <input type="text" name="company_site_check" class="contact-hp" tabindex="-1" autocomplete="off" aria-hidden="true">

        <div class="row2">
          <div class="field">
            <label for="c-name">Name</label>
            <input id="c-name" type="text" name="name" value="{{ old('name') }}" placeholder="Your name" required>
            @error('name')<div class="err">{{ $message }}</div>@enderror
          </div>
          <div class="field">
            <label for="c-email">Email</label>
            <input id="c-email" type="email" name="email" value="{{ old('email') }}" placeholder="you@company.com" required>
            @error('email')<div class="err">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="row2">
          <div class="field">
            <label for="c-phone">Phone (optional)</label>
            <input id="c-phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+91 ...">
            @error('phone')<div class="err">{{ $message }}</div>@enderror
          </div>
          <div class="field">
            <label for="c-service">Service</label>
            <select id="c-service" name="service">
              <option value="">Select a service</option>
              @foreach(['Custom Software Development','Web Development','Mobile App Development','IT Consulting','Data Analytics & BI','Cloud Solutions','AI & Machine Learning','Robotics & Automation'] as $svc)
                <option value="{{ $svc }}" @selected(old('service') === $svc)>{{ $svc }}</option>
              @endforeach
            </select>
            @error('service')<div class="err">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="field">
          <label for="c-message">Message</label>
          <textarea id="c-message" name="message" placeholder="Tell us about your idea..." required>{{ old('message') }}</textarea>
          @error('message')<div class="err">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-solid cf-btn">Send message</button>
      </form>
    </div>
  </div>
</section>