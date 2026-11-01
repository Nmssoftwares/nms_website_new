@if($m->linkedin_url || $m->x_url || $m->email)
  <div class="tm-social">
    @if($m->linkedin_url)
      <a href="{{ $m->linkedin_url }}" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg></a>
    @endif
    @if($m->x_url)
      <a href="{{ $m->x_url }}" target="_blank" rel="noopener" aria-label="X"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
    @endif
    @if($m->email)
      <a href="mailto:{{ $m->email }}" aria-label="Email"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 6a1 1 0 011-1h18a1 1 0 011 1v.4l-10 6.2L2 6.4V6zm0 2.75V18a1 1 0 001 1h18a1 1 0 001-1V8.75l-9.47 5.86a1 1 0 01-1.06 0L2 8.75z"/></svg></a>
    @endif
  </div>
@endif