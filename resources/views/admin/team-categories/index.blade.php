@extends('admin.layout')

@section('title', 'Team categories')

@section('content')
<div class="page-head">
  <h1>Team categories</h1>
  <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">+ Add member</a>
</div>

<form class="card" method="POST" action="{{ route('admin.team-categories.store') }}" style="margin-bottom:22px;">
  @csrf
  <div class="inline-form">
    <div class="field" style="margin:0;">
      <label>New category name</label>
      <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Back end team" required>
      @error('name')<div class="err">{{ $message }}</div>@enderror
    </div>
    <div class="field" style="margin:0;">
      <label>Display order</label>
      <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
    </div>
    <button type="submit" class="btn btn-primary">Add category</button>
  </div>
  <div class="check-row">
    <label><input type="checkbox" name="is_visible" value="1" {{ old('is_visible', true) ? 'checked' : '' }}> Show on the website</label>
    <label><input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}> Featured (first member shown as a large card)</label>
  </div>
</form>

<table>
  <thead>
    <tr>
      <th>Category</th>
      <th>Members</th>
      <th>Featured</th>
      <th>Visible</th>
      <th>Order</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @forelse($categories as $c)
      <tr>
        <td>{{ $c->name }}</td>
        <td><a href="{{ route('admin.employees.index', ['category' => $c->id]) }}">{{ $c->employees_count }}</a></td>
        <td>{{ $c->is_featured ? 'Yes' : 'No' }}</td>
        <td>{!! $c->is_visible ? 'Yes' : '<span class="cat-tag muted">Hidden</span>' !!}</td>
        <td>{{ $c->sort_order }}</td>
        <td>
          <div class="actions">
            <a href="{{ route('admin.team-categories.edit', $c) }}" class="btn-outline">Edit</a>
            <form method="POST" action="{{ route('admin.team-categories.destroy', $c) }}" onsubmit="return confirm('Delete this category? Its members will stay, but become uncategorized.');">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger">Delete</button>
            </form>
          </div>
        </td>
      </tr>
    @empty
      <tr><td colspan="6" class="cell-empty">No categories yet. Add your first one above.</td></tr>
    @endforelse
  </tbody>
</table>
@endsection