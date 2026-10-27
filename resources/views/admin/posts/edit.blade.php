@extends('admin.layout')

@section('title', 'Edit Post')

@section('content')
<div class="page-head">
  <h1>Edit Blog Post</h1>
</div>

<form class="card" method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="field">
    <label>Title</label>
    <input type="text" name="title" value="{{ old('title', $post->title) }}" required>
    @error('title')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Slug</label>
    <input type="text" name="slug" value="{{ old('slug', $post->slug) }}">
    @error('slug')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Excerpt</label>
    <textarea name="excerpt" required>{{ old('excerpt', $post->excerpt) }}</textarea>
    @error('excerpt')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Body (HTML allowed)</label>
    <textarea name="body_html" class="textarea-tall" required>{{ old('body_html', $post->body_html) }}</textarea>
    @error('body_html')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Featured Image</label>
    @if($post->featuredImageUrl())
      <img src="{{ $post->featuredImageUrl() }}" class="cur-img">
    @endif
    <input type="file" name="featured_image" accept="image/*">
    <div class="field-note">Leave blank to keep the current image.</div>
    @error('featured_image')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Author</label>
    <input type="text" name="author" value="{{ old('author', $post->author) }}">
  </div>

  <div class="field">
    <label>Publish date</label>
    <input type="datetime-local" name="published_at"
      value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
    @error('published_at')<div class="err">{{ $message }}</div>@enderror
  </div>

  <button type="submit" class="btn btn-primary">Save Changes</button>
  <a href="{{ route('admin.posts.index') }}" class="btn btn-outline">Cancel</a>
</form>
@endsection