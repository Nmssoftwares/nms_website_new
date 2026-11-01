@extends('admin.layout')

@section('title', 'New Project')

@section('content')
<div class="page-head">
  <h1>New Portfolio Project</h1>
</div>

<form class="card" method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
  @csrf

  <div class="field">
    <label>Title</label>
    <input type="text" name="title" value="{{ old('title') }}" required>
    @error('title')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Category (short tag shown above the title, e.g. "ERP · SaaS")</label>
    <input type="text" name="category" value="{{ old('category') }}">
    @error('category')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Type</label>
    <select name="type" required>
      @foreach(\App\Models\Project::TYPES as $value => $label)
        <option value="{{ $value }}" @selected(old('type', 'software') === $value)>{{ $label }}</option>
      @endforeach
    </select>
    @error('type')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Description</label>
    <textarea name="description">{{ old('description') }}</textarea>
    @error('description')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Image</label>
    <input type="file" name="image" accept="image/*">
    @error('image')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Tech stack (comma-separated, e.g. "React, Node.js, Python, TensorFlow")</label>
    <input type="text" name="tech_stack" value="{{ old('tech_stack') }}">
    @error('tech_stack')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Website link (optional, e.g. https://offnexa.com)</label>
    <input type="url" name="website_url" value="{{ old('website_url') }}">
    @error('website_url')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Display order (lower numbers show first)</label>
    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
    @error('sort_order')<div class="err">{{ $message }}</div>@enderror
  </div>

  <button type="submit" class="btn btn-primary">Add Project</button>
  <a href="{{ route('admin.projects.index') }}" class="btn btn-outline">Cancel</a>
</form>
@endsection