@extends('admin.layout')

@section('title', 'Team members')

@section('content')
<div class="page-head">
  <h1>Team members</h1>
  <div class="head-actions">
    <a href="{{ route('admin.team-categories.index') }}" class="btn btn-outline">+ Add category</a>
    <a href="{{ route('admin.employees.create', is_numeric($active) ? ['category' => $active] : []) }}" class="btn btn-primary">+ Add member</a>
  </div>
</div>

<div class="chips">
  <a href="{{ route('admin.employees.index') }}" class="chip {{ ! $active ? 'on' : '' }}">All {{ $total }}</a>
  @foreach($categories as $c)
    <a href="{{ route('admin.employees.index', ['category' => $c->id]) }}"
       class="chip {{ (string) $active === (string) $c->id ? 'on' : '' }}">{{ $c->name }} {{ $c->employees_count }}</a>
  @endforeach
  @if($uncategorized > 0)
    <a href="{{ route('admin.employees.index', ['category' => 'none']) }}" class="chip {{ $active === 'none' ? 'on' : '' }}">Uncategorized {{ $uncategorized }}</a>
  @endif
</div>

<table>
  <thead>
    <tr>
      <th></th>
      <th>Name</th>
      <th>Role</th>
      <th>Category</th>
      <th>Order</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @forelse($employees as $employee)
      <tr>
        <td><img src="{{ $employee->photoUrl() }}" class="thumb" alt=""></td>
        <td>{{ $employee->name }}</td>
        <td>{{ $employee->role }}</td>
        <td>
          @if($employee->category)
            <span class="cat-tag">{{ $employee->category->name }}</span>
          @else
            <span class="cat-tag muted">Uncategorized</span>
          @endif
        </td>
        <td>{{ $employee->sort_order }}</td>
        <td>
          <div class="actions">
            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn-outline">Edit</a>
            <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Remove this team member?');">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger">Delete</button>
            </form>
          </div>
        </td>
      </tr>
    @empty
      <tr><td colspan="6" class="cell-empty">No team members here yet. Add your first one.</td></tr>
    @endforelse
  </tbody>
</table>
@endsection