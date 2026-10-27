<header class="hdr-{{ $headerDesign ?? 'classic' }}">
  <nav>
    <a href="{{ route('home') }}" class="brand">
      @if($siteLogo)
        <img src="{{ $siteLogo }}" alt="NMS Softwares">
      @else
        NMS <span>Softwares</span>
      @endif
    </a>
    <div class="navlinks" id="navlinks">
      <a href="{{ route('home') }}#about">About</a>
      <a href="{{ route('home') }}#services">Services</a>
      <a href="{{ route('home') }}#portfolio">Portfolio</a>
      <a href="{{ route('home') }}#team">Team</a>
      <a href="{{ route('home') }}#blog">Blog</a>
      <a href="{{ route('home') }}#contact" class="nav-cta">Contact Us</a>
    </div>
    <button class="burger" aria-label="Toggle menu">☰</button>
  </nav>
</header>