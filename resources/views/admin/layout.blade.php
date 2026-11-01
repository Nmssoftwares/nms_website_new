<!DOCTYPE html>
<html lang="en" class="{{ ($siteMode ?? 'dark') === 'light' ? 'mode-light' : 'admin-dark' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Admin') — NMS Softwares</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/logo.css') }}?v={{ @filemtime(public_path('css/logo.css')) }}">
<link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}?v={{ @filemtime(public_path('css/admin-layout.css')) }}">
<link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}?v={{ @filemtime(public_path('css/admin-theme.css')) }}">
<link rel="stylesheet" href="{{ asset('css/logo.css') }}?v={{ @filemtime(public_path('css/logo.css')) }}">
@stack('styles')
@include('partials.theme')
</head>
<body>
<div class="shell">
  <aside class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="brand-row" style="text-decoration:none;">
      @if($siteLogo)
        <img src="{{ $siteLogo }}" alt="NMS" style="height:34px;width:auto;max-width:60px;object-fit:contain;flex:none;">
      @else
        <span class="brand-mark">N</span>
      @endif
      <span class="brand-text">NMS Admin<small>Content management</small></span>
    </a>

        <nav>
      <div class="nav-label">Overview</div>
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
        Dashboard
      </a>
      <a href="{{ route('admin.analytics') }}" class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        Analytics
      </a>

      <div class="nav-label">Content</div>
      <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        Blog Posts
      </a>
      <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
        Portfolio
      </a>

      @php $teamOpen = request()->routeIs('admin.employees.*', 'admin.team-categories.*'); @endphp
      <div class="nav-group {{ $teamOpen ? 'open' : '' }}">
        <a href="#" class="nav-toggle {{ $teamOpen ? 'active' : '' }}" aria-expanded="{{ $teamOpen ? 'true' : 'false' }}" aria-controls="teamMenu">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          Team
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
        </a>
        <div class="nav-sub" id="teamMenu">
          <a href="{{ route('admin.employees.index') }}" class="{{ request()->routeIs('admin.employees.index') ? 'on' : '' }}">All members</a>
          <a href="{{ route('admin.team-categories.index') }}" class="{{ request()->routeIs('admin.team-categories.*') ? 'on' : '' }}">Categories</a>
          <a href="{{ route('admin.employees.create') }}" class="{{ request()->routeIs('admin.employees.create') ? 'on' : '' }}">+ Add member</a>
        </div>
      </div>

      <div class="nav-label">Inbox</div>
      @php $unreadMessages = \App\Models\ContactMessage::unread()->count(); @endphp
      <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        Messages
        @if($unreadMessages > 0)<span class="nav-badge">{{ $unreadMessages }}</span>@endif
      </a>

      <div class="nav-label">Site</div>
      <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h0a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h0a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v0a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        Settings
      </a>
      <a href="{{ route('home') }}" target="_blank">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        View site
      </a>
    </nav>

    {{-- Dark / light switch: changes the admin panel AND the public website --}}
    <div class="theme-row">
      <span class="tl">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 0 0 18z" fill="currentColor"/></svg>
        <span id="themeLabel">{{ ($siteMode ?? 'dark') === 'light' ? 'Light mode' : 'Dark mode' }}</span>
      </span>
      <button type="button" class="theme-toggle" id="themeToggle" role="switch" aria-label="Switch between dark and light mode" aria-checked="{{ ($siteMode ?? 'dark') === 'light' ? 'false' : 'true' }}">
        <span class="thumb">
          <svg class="ic-sun" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
          <svg class="ic-moon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
        </span>
      </button>
    </div>
    <div class="side-user">
      <span class="av">{{ strtoupper(mb_substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
      <span class="who">{{ auth()->user()->name ?? 'Admin' }}<small>Administrator</small></span>
    </div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="logout-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Log out
      </button>
    </form>
  </aside>

  <main class="main">
    @if(session('status'))
      <div class="flash">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        {{ session('status') }}
      </div>
    @endif
    @yield('content')
  </main>
</div>
<script>
  // dark / light switch in the sidebar
  (function(){
    var btn=document.getElementById('themeToggle'); if(!btn) return;
    var html=document.documentElement, label=document.getElementById('themeLabel'), busy=false, toast;
    function say(msg,bad){
      if(!toast){ toast=document.createElement('div'); toast.className='theme-toast'; document.body.appendChild(toast); }
      toast.textContent=msg; toast.classList.toggle('err',!!bad); toast.classList.add('show');
      clearTimeout(say.t); say.t=setTimeout(function(){ toast.classList.remove('show'); },3200);
    }
    function apply(mode){
      var dark = mode==='dark';
      html.classList.toggle('admin-dark',dark);
      html.classList.toggle('mode-light',!dark);
      btn.setAttribute('aria-checked',dark?'true':'false');
      label.textContent = dark ? 'Dark mode' : 'Light mode';
      // keep the Settings page radio buttons in step, so saving that form does not undo the switch
      document.querySelectorAll('input[name="site_mode"]').forEach(function(r){ r.checked = (r.value===mode); });
    }
    btn.addEventListener('click',function(){
      if(busy) return;
      var next = btn.getAttribute('aria-checked')==='true' ? 'light' : 'dark', prev = next==='dark' ? 'light' : 'dark';
      busy=true; html.classList.add('theme-anim'); apply(next);
      fetch('{{ route('admin.settings.mode') }}',{
        method:'POST', credentials:'same-origin',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:JSON.stringify({site_mode:next})
      }).then(function(r){
        if(!r.ok) throw new Error('save failed');
        say((next==='dark'?'Dark':'Light')+' mode saved. Refresh the website tab to see it.');
      }).catch(function(){
        apply(prev); say('Could not save the theme. Please try again.',true);
      }).then(function(){ busy=false; setTimeout(function(){ html.classList.remove('theme-anim'); },450); });
    });
  })();
</script>
<script>
  // auto-hide the green success message
  document.querySelectorAll('.flash').forEach(function(f){
    setTimeout(function(){ f.classList.add('hide'); setTimeout(function(){ f.remove(); }, 450); }, 4500);
  });
</script>
<script>
  // Team dropdown in the sidebar
  document.querySelectorAll('.nav-toggle').forEach(function (t) {
    t.addEventListener('click', function (e) {
      e.preventDefault();
      var open = t.closest('.nav-group').classList.toggle('open');
      t.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });
</script>
</body>
</html>