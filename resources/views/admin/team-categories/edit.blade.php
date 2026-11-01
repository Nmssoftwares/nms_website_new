@extends('admin.layout')

@section('title', 'Edit category')

@section('content')
<div class="page-head">
  <h1>Edit category</h1>
</div>

<form class="card" method="POST" action="{{ route('admin.team-categories.update', $category) }}">
  @csrf
  @method('PUT')

  <div class="field">
    <label>Name</label>
    <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
    @error('name')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Display order (lower numbers show first)</label>
    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0">
    @error('sort_order')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="check-row" style="margin:0 0 20px;">
    <label><input type="checkbox" name="is_visible" value="1" {{ old('is_visible', $category->is_visible) ? 'checked' : '' }}> Show on the website</label>
    <label><input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}> Featured (first member shown as a large card)</label>
  </div>

  <button type="submit" class="btn btn-primary">Save Changes</button>
  <a href="{{ route('admin.team-categories.index') }}" class="btn btn-outline">Cancel</a>
</form>
@endsection