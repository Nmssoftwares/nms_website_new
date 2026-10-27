@extends('admin.layout')

@section('title', 'Team')

@section('content')
<div class="page-head">
  <h1>Team</h1>
  <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">+ Add Team Member</a>
</div>

<table>
  <thead>
    <tr>
      <th></th>
      <th>Name</th>
      <th>Role</th>
      <th>Order</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @forelse($employees as $employee)
      <tr>
        <td><img src="{{ $employee->photoUrl() }}" class="thumb"></td>
        <td>{{ $employee->name }}</td>
        <td>{{ $employee->role }}</td>
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
      <tr><td colspan="5" class="cell-empty">No team members yet — add your first one.</td></tr>
    @endforelse
  </tbody>
</table>
@endsection