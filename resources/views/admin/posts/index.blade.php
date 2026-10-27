@extends('admin.layout')

@section('title', 'Blog Posts')

@section('content')
<div class="page-head">
  <h1>Blog Posts</h1>
  <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ New Post</a>
</div>

<table>
  <thead>
    <tr>
      <th></th>
      <th>Title</th>
      <th>Status</th>
      <th>Published</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @forelse($posts as $post)
      <tr>
        <td>
          @if($post->featuredImageUrl())
            <img src="{{ $post->featuredImageUrl() }}" class="thumb">
          @else
            <div class="thumb"></div>
          @endif
        </td>
        <td>{{ $post->title }}</td>
        <td>
          @if($post->published_at && $post->published_at <= now())
            <span class="status status-ok">Published</span>
          @else
            <span class="status status-draft">Draft</span>
          @endif
        </td>
        <td>{{ $post->published_at?->format('M j, Y') ?? '—' }}</td>
        <td>
          <div class="actions">
            <a href="{{ route('admin.posts.edit', $post) }}" class="btn-outline">Edit</a>
            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Delete this post?');">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger">Delete</button>
            </form>
          </div>
        </td>
      </tr>
    @empty
      <tr><td colspan="5" class="cell-empty">No posts yet — create your first one.</td></tr>
    @endforelse
  </tbody>
</table>
@endsection