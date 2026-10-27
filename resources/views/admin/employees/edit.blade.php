@extends('admin.layout')

@section('title', 'Edit Team Member')

@section('content')
<div class="page-head">
  <h1>Edit Team Member</h1>
</div>

<form class="card" method="POST" action="{{ route('admin.employees.update', $employee) }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="field">
    <label>Name</label>
    <input type="text" name="name" value="{{ old('name', $employee->name) }}" required>
    @error('name')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Role</label>
    <input type="text" name="role" value="{{ old('role', $employee->role) }}" required>
    @error('role')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Bio</label>
    <textarea name="bio">{{ old('bio', $employee->bio) }}</textarea>
    @error('bio')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Photo</label>
    <img src="{{ $employee->photoUrl() }}" class="cur-img">
    <input type="file" name="photo" accept="image/*">
    <div class="field-note">Leave blank to keep the current photo.</div>
    @error('photo')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>LinkedIn URL</label>
    <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $employee->linkedin_url) }}">
    @error('linkedin_url')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email', $employee->email) }}">
    @error('email')<div class="err">{{ $message }}</div>@enderror
  </div>

  <div class="field">
    <label>Display order</label>
    <input type="number" name="sort_order" value="{{ old('sort_order', $employee->sort_order) }}" min="0">
    @error('sort_order')<div class="err">{{ $message }}</div>@enderror
  </div>

  <button type="submit" class="btn btn-primary">Save Changes</button>
  <a href="{{ route('admin.employees.index') }}" class="btn btn-outline">Cancel</a>
</form>
@endsection