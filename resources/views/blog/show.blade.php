@extends('layouts.app')

@section('title', $post->title . ' — NMS Softwares Blog')
@section('meta_description', $post->excerpt)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/post.css') }}?v={{ @filemtime(public_path('css/post.css')) }}">
@endpush

@section('content')

<section class="section reveal post-hero">
  <div class="wrap">
    <a href="{{ route('home') }}" class="back-link">← Back to home</a>

    <div class="post-card {{ $post->featuredImageUrl() ? 'has-image' : '' }}">
      @if($post->featuredImageUrl())
        <img class="post-card-img" src="{{ $post->featuredImageUrl() }}" alt="{{ $post->title }}">
      @endif
      <div>
        <div class="post-meta">{{ $post->published_at?->format('F j, Y') }} · by {{ $post->author ?? 'NMS Softwares' }}</div>
        <h1 class="grad-text post-title">{{ $post->title }}</h1>
      </div>
    </div>
  </div>
</section>

<section class="section reveal post-content">
  <div class="wrap">
    <div class="post-body">
      {!! $post->body_html !!}
    </div>
  </div>
</section>

@if($relatedPosts->isNotEmpty())
<section class="section alt reveal">
  <div class="wrap">
    <div class="sec-head"><h2>More posts</h2></div>
    <div class="blog-grid">
      @foreach($relatedPosts as $related)
        <a href="{{ route('blog.show', $related->slug) }}" class="blog-card">
          @if($related->featuredImageUrl())
            <img class="related-img" src="{{ $related->featuredImageUrl() }}" alt="{{ $related->title }}" loading="lazy">
          @endif
          <div class="date">{{ $related->published_at?->format('M j, Y') }}</div>
          <h3>{{ $related->title }}</h3>
          <p>{{ $related->excerpt }}</p>
          <div class="read">Read post →</div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

@endsection