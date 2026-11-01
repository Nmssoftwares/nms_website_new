@extends('admin.layout')

@section('title', 'Edit Team Member')

@section('content')
<div class="page-head">
  <h1>Edit Team Member</h1>
</div>

<form class="card" method="POST" action="{{ route('admin.employees.update', $employee) }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  @include('admin.employees._fields')

  <button type="submit" class="btn btn-primary">Save Changes</button>
  <a href="{{ route('admin.employees.index') }}" class="btn btn-outline">Cancel</a>
</form>
@endsection