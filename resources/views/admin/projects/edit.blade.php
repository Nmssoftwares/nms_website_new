@extends('admin.layout')

@section('title', 'Edit Project')

@section('content')
<div class="page-head">
  <h1>Edit Project</h1>
</div>

<form class="card" method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="field">
    <label>Title</label>
    <input type="text" name="title" value="{{ old('title', $project->title) }}" required>
    @error('title')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Category</label>
    <input type="text" name="category" value="{{ old('category', $project->category) }}">
    @error('category')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Description</label>
    <textarea name="description">{{ old('description', $project->description) }}</textarea>
    @error('description')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Image</label>
    @if($project->imageUrl())
      <img src="{{ $project->imageUrl() }}" class="cur-img">
    @endif
    <input type="file" name="image" accept="image/*">
    <div class="field-note">Leave blank to keep the current image.</div>
    @error('image')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Tech stack (comma-separated)</label>
    <input type="text" name="tech_stack" value="{{ old('tech_stack', $project->tech_stack) }}">
    @error('tech_stack')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Display order</label>
    <input type="number" name="sort_order" value="{{ old('sort_order', $project->sort_order) }}" min="0">
    @error('sort_order')<div class="err">{{ $message }}</div>@enderror
  </div>

  <button type="submit" class="btn btn-primary">Save Changes</button>
  <a href="{{ route('admin.projects.index') }}" class="btn btn-outline">Cancel</a>
</form>
@endsection