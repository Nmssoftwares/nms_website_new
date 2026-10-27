{{-- Site-wide colours and fonts chosen in Admin → Settings. Prints nothing until something is changed. --}}
@if(!empty($themeFontsUrl))
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="{{ $themeFontsUrl }}">
@endif
@if(($siteMode ?? 'dark') === 'light')
<link rel="stylesheet" href="{{ asset('css/light-mode.css') }}?v={{ @filemtime(public_path('css/light-mode.css')) }}">
<script>document.documentElement.classList.add('mode-light');</script>
@endif
@if(!empty($themeCss))
<style id="site-theme">{!! $themeCss !!}</style>
@endif