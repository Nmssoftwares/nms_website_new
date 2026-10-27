@extends('admin.layout')

@section('title', 'New Post')

@section('content')
<div class="page-head">
  <h1>New Blog Post</h1>
</div>

<form class="card" method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
  @csrf

  <div class="field">
    <label>Title</label>
    <input type="text" name="title" value="{{ old('title') }}" required>
    @error('title')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Slug (optional — auto-generated from title if left blank)</label>
    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="my-post-title">
    @error('slug')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Excerpt (short summary shown on the blog listing)</label>
    <textarea name="excerpt" required>{{ old('excerpt') }}</textarea>
    @error('excerpt')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Body (HTML allowed)</label>
    <textarea name="body_html" class="textarea-tall" required>{{ old('body_html') }}</textarea>
    @error('body_html')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Featured Image</label>
    <input type="file" name="featured_image" accept="image/*">
    @error('featured_image')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Author</label>
    <input type="text" name="author" value="{{ old('author', 'NMS Softwares') }}">
  </div>

  <div class="field">
    <label>Publish date (leave blank to save as a draft)</label>
    <input type="datetime-local" name="published_at" value="{{ old('published_at') }}">
    @error('published_at')<div class="err">{{ $message }}</div>@enderror
  </div>

  <button type="submit" class="btn btn-primary">Create Post</button>
  <a href="{{ route('admin.posts.index') }}" class="btn btn-outline">Cancel</a>
</form>
@endsection