@extends('admin.layout')

@section('title', 'Add Team Member')

@section('content')
<div class="page-head">
  <h1>Add Team Member</h1>
</div>

<form class="card" method="POST" action="{{ route('admin.employees.store') }}" enctype="multipart/form-data">
  @csrf
  @include('admin.employees._fields')

  <button type="submit" class="btn btn-primary">Add Team Member</button>
  <a href="{{ route('admin.employees.index') }}" class="btn btn-outline">Cancel</a>
</form>
@endsection