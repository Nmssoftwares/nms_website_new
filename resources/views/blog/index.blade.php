@extends('layouts.app')

@section('title', 'Blog — NMS Softwares')
@section('meta_description', 'Notes on AI, cloud engineering and robotics from the NMS Softwares team.')

@section('content')

<section class="section reveal post-hero blog-index-hero">
  <div class="wrap">
    <div class="index">Blog</div>
    <h1 class="grad-text blog-index-title">From the blog</h1>
    <p class="blog-index-lead">Writing on AI, cloud engineering and robotics from the NMS Softwares team.</p>
  </div>
</section>

<section class="section reveal blog-index-list">
  <div class="wrap">
    <div class="blog-grid">
      @forelse($posts as $post)
        <a href="{{ route('blog.show', $post->slug) }}" class="blog-card">
          @if($post->featuredImageUrl())
            <img class="blog-card-img" src="{{ $post->featuredImageUrl() }}" alt="{{ $post->title }}" loading="lazy">
          @endif
          <div class="date">{{ $post->published_at?->format('M j, Y') }}</div>
          <h3>{{ $post->title }}</h3>
          <p>{{ $post->excerpt }}</p>
          <div class="read">Read post →</div>
        </a>
      @empty
        @for($i = 0; $i < 3; $i++)
          <div class="blog-card">
            <div class="blog-card-img blog-card-img--skel">
              <svg class="fp-skel-icon" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </div>
            <div class="sk-date"></div>
            <div class="sk-heading"></div>
            <div class="sk-line"></div>
            <div class="sk-line sk-line--70"></div>
          </div>
        @endfor
      @endforelse
    </div>

    <div class="pagination">
      {{ $posts->links() }}
    </div>
  </div>
</section>

@endsection