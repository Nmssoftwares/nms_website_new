<section class="section reveal dz dz2 dzr" id="blog">
  <div class="wrap">
    <div class="sec-head">
      <h2>From the blog</h2>
      <p>Latest posts from our team.</p>
    </div>
    <div class="blog-grid">
      @forelse($latestPosts as $post)
        <a href="{{ route('blog.show', $post->slug) }}" class="blog-card">
          <div class="date">{{ $post->published_at?->format('M j, Y') }}</div>
          <h3>{{ $post->title }}</h3>
          <p>{{ $post->excerpt }}</p>
          <div class="read">Read post →</div>
        </a>
      @empty
        <p class="blog-empty">No posts yet — add posts from the admin panel.</p>
      @endforelse
    </div>

    @if($latestPosts->isNotEmpty())
      <div class="blog-more">
        <a href="{{ route('blog.index') }}">View all posts →</a>
      </div>
    @endif
  </div>
</section>