<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'NMS Softwares — Cloud, AI & Engineering')</title>
<meta name="description" content="@yield('meta_description', 'NMS Softwares and Innovation India Pvt. Ltd. — cloud, AI and engineering solutions for healthcare, robotics and enterprise.')">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/theme.css') }}?v={{ @filemtime(public_path('css/theme.css')) }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}?v={{ @filemtime(public_path('css/footer.css')) }}">
@stack('styles')
@yield('head')
@include('partials.theme')
</head>
<body>

@include('partials.header')

@yield('content')

@include('partials.footer')

@stack('vendor-scripts')
<script src="{{ asset('js/main.js') }}?v={{ @filemtime(public_path('js/main.js')) }}" defer></script>
@guest
<script>window.NMS_TRACK={url:"{{ route('track') }}",token:"{{ csrf_token() }}"};</script>
<script src="{{ asset('js/track.js') }}?v={{ @filemtime(public_path('js/track.js')) }}" defer></script>
@endguest
@yield('scripts')
</body>
</html>