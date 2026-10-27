@extends('admin.layout')

@section('title', 'Portfolio Projects')

@section('content')
<div class="page-head">
  <h1>Portfolio Projects</h1>
  <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">+ New Project</a>
</div>

<table>
  <thead>
    <tr>
      <th></th>
      <th>Title</th>
      <th>Category</th>
      <th>Order</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @forelse($projects as $project)
      <tr>
        <td>
          @if($project->imageUrl())
            <img src="{{ $project->imageUrl() }}" class="thumb">
          @else
            <div class="thumb"></div>
          @endif
        </td>
        <td>{{ $project->title }}</td>
        <td>{{ $project->category }}</td>
        <td>{{ $project->sort_order }}</td>
        <td>
          <div class="actions">
            <a href="{{ route('admin.projects.edit', $project) }}" class="btn-outline">Edit</a>
            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?');">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger">Delete</button>
            </form>
          </div>
        </td>
      </tr>
    @empty
      <tr><td colspan="5" class="cell-empty">No projects yet — add your first one.</td></tr>
    @endforelse
  </tbody>
</table>
@endsection