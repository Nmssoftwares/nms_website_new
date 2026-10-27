@extends('admin.layout')

@section('title', 'Messages')

@section('content')
<div class="page-head">
  <h1>Contact Messages</h1>
</div>

<table>
  <thead>
    <tr>
      <th>From</th>
      <th>Email</th>
      <th>Service</th>
      <th>Received</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @forelse($messages as $m)
      <tr class="{{ $m->isUnread() ? 'unread' : '' }}">
        <td>
          @if($m->isUnread())<span class="dot" title="Unread"></span>@endif
          <a href="{{ route('admin.messages.show', $m) }}" class="row-link">{{ $m->name }}</a>
        </td>
        <td>{{ $m->email }}</td>
        <td>{{ $m->service ?: '—' }}</td>
        <td title="{{ $m->created_at->format('M j, Y g:i A') }}">{{ $m->created_at->diffForHumans() }}</td>
        <td>
          <div class="actions">
            <a href="{{ route('admin.messages.show', $m) }}" class="btn-outline">View</a>
            <form method="POST" action="{{ route('admin.messages.destroy', $m) }}" onsubmit="return confirm('Delete this message?');">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger">Delete</button>
            </form>
          </div>
        </td>
      </tr>
    @empty
      <tr><td colspan="5" class="empty-row">No messages yet. They will appear here when someone uses the contact form.</td></tr>
    @endforelse
  </tbody>
</table>

@if($messages->hasPages())
  <div class="pager">
    <span>Page {{ $messages->currentPage() }} of {{ $messages->lastPage() }}</span>
    <span>
      @if($messages->previousPageUrl())<a href="{{ $messages->previousPageUrl() }}">← Newer</a>@endif
      @if($messages->previousPageUrl() && $messages->nextPageUrl()) &nbsp;·&nbsp; @endif
      @if($messages->nextPageUrl())<a href="{{ $messages->nextPageUrl() }}">Older →</a>@endif
    </span>
  </div>
@endif
@endsection