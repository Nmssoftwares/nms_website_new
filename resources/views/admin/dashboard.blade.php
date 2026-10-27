@extends('admin.layout')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v={{ @filemtime(public_path('css/admin-dashboard.css')) }}">
@endpush

@section('content')
<div class="page-head">
  <h1>Dashboard</h1>
</div>

<div class="dash-grid">
  <a href="{{ route('admin.posts.index') }}" class="dash-link">
    <div class="dash-card">
      <div class="dash-num">{{ $postCount }}</div>
      <div class="dash-lbl">Blog Posts</div>
    </div>
  </a>
  <a href="{{ route('admin.employees.index') }}" class="dash-link">
    <div class="dash-card">
      <div class="dash-num">{{ $employeeCount }}</div>
      <div class="dash-lbl">Team Members</div>
    </div>
  </a>
  <a href="{{ route('admin.projects.index') }}" class="dash-link">
    <div class="dash-card">
      <div class="dash-num">{{ $projectCount }}</div>
      <div class="dash-lbl">Portfolio Projects</div>
    </div>
  </a>
</div>

<div class="dash-actions">
  <a href="{{ route('admin.analytics') }}" class="btn btn-primary">View Analytics</a>
  <a href="{{ route('admin.posts.create') }}" class="btn btn-outline">+ New Blog Post</a>
  <a href="{{ route('admin.employees.create') }}" class="btn btn-outline">+ Add Team Member</a>
</div>
@endsection