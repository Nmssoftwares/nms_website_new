@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dark-zone.css') }}?v={{ @filemtime(public_path('css/dark-zone.css')) }}">
<link rel="stylesheet" href="{{ asset('css/hero3d.css') }}?v={{ @filemtime(public_path('css/hero3d.css')) }}">
<link rel="stylesheet" href="{{ asset('css/dark-rest.css') }}?v={{ @filemtime(public_path('css/dark-rest.css')) }}">
@endpush

@push('vendor-scripts')
<script src="{{ asset('js/hero3d.js') }}?v={{ @filemtime(public_path('js/hero3d.js')) }}" defer></script>
<script src="{{ asset('js/contact3d.js') }}?v={{ @filemtime(public_path('js/contact3d.js')) }}" defer></script>
<script src="{{ asset('js/net-bg.js') }}?v={{ @filemtime(public_path('js/net-bg.js')) }}" defer></script>
@endpush

@section('content')

@include('sections.hero')
@include('sections.about')
@include('sections.what-we-do')
@include('sections.services')
@include('sections.portfolio')
@include('sections.testimonials')
@include('sections.team')
@include('sections.blog')
@include('sections.contact')

@endsection