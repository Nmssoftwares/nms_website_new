@extends('admin.layout')

@section('title', 'Add Team Member')

@section('content')
<div class="page-head">
  <h1>Add Team Member</h1>
</div>

<form class="card" method="POST" action="{{ route('admin.employees.store') }}" enctype="multipart/form-data">
  @csrf

  <div class="field">
    <label>Name</label>
    <input type="text" name="name" value="{{ old('name') }}" required>
    @error('name')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Role</label>
    <input type="text" name="role" value="{{ old('role') }}" placeholder="e.g. Lead AI Engineer" required>
    @error('role')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Bio</label>
    <textarea name="bio">{{ old('bio') }}</textarea>
    @error('bio')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Photo</label>
    <input type="file" name="photo" accept="image/*">
    @error('photo')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>LinkedIn URL (optional)</label>
    <input type="url" name="linkedin_url" value="{{ old('linkedin_url') }}" placeholder="https://linkedin.com/in/...">
    @error('linkedin_url')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Email (optional)</label>
    <input type="email" name="email" value="{{ old('email') }}">
    @error('email')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Display order (lower numbers show first)</label>
    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
    @error('sort_order')<div class="err">{{ $message }}</div>@enderror
  </div>

  <button type="submit" class="btn btn-primary">Add Team Member</button>
  <a href="{{ route('admin.employees.index') }}" class="btn btn-outline">Cancel</a>
</form>
@endsection