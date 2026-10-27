@extends('admin.layout')

@section('title', 'Message from ' . $message->name)

@section('content')
<div class="page-head">
  <h1>Message from {{ $message->name }}</h1>
  <a href="{{ route('admin.messages.index') }}" class="btn btn-outline">← All messages</a>
</div>

<div class="msg-card">
  <dl class="msg-meta">
    <dt>Name</dt>
    <dd>{{ $message->name }}</dd>

    <dt>Email</dt>
    <dd><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd>

    @if($message->phone)
      <dt>Phone</dt>
      <dd><a href="tel:{{ $message->phone }}">{{ $message->phone }}</a></dd>
    @endif

    @if($message->service)
      <dt>Service</dt>
      <dd>{{ $message->service }}</dd>
    @endif

    <dt>Received</dt>
    <dd>{{ $message->created_at->format('F j, Y \a\t g:i A') }}</dd>
  </dl>

  <div class="msg-body">{{ $message->message }}</div>

  <div class="actions msg-actions">
    <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: your message to NMS Softwares') }}" class="btn btn-primary">Reply by email</a>
    <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?');">
      @csrf @method('DELETE')
      <button type="submit" class="btn btn-danger">Delete</button>
    </form>
  </div>
</div>
@endsection