@php $e = $employee ?? null; @endphp

<div class="field">
  <label>Name</label>
  <input type="text" name="name" value="{{ old('name', $e?->name) }}" required>
  @error('name')<div class="err">{{ $message }}</div>@enderror
</div>

<div class="field">
  <label>Role</label>
  <input type="text" name="role" value="{{ old('role', $e?->role) }}" placeholder="e.g. Software engineer" required>
  @error('role')<div class="err">{{ $message }}</div>@enderror
</div>

<div class="field">
  <label>Category</label>
  <select name="team_category_id">
    <option value="">Uncategorized</option>
    @foreach($categories as $c)
      <option value="{{ $c->id }}" @selected((string) old('team_category_id', $e?->team_category_id ?? request('category')) === (string) $c->id)>{{ $c->name }}</option>
    @endforeach
  </select>
  <div class="field-note">Don't see the right one? <a href="{{ route('admin.team-categories.index') }}">Add a category</a> first.</div>
  @error('team_category_id')<div class="err">{{ $message }}</div>@enderror
</div>

<div class="field">
  <label>Bio</label>
  <textarea name="bio">{{ old('bio', $e?->bio) }}</textarea>
  <div class="field-note">Shown on the card. In a featured category, it shows on the large card.</div>
  @error('bio')<div class="err">{{ $message }}</div>@enderror
</div>

<div class="field">
  <label>Photo</label>
  @if($e)
    <img src="{{ $e->photoUrl() }}" class="cur-img" alt="">
  @endif
  <input type="file" name="photo" accept="image/*">
  @if($e)<div class="field-note">Leave blank to keep the current photo.</div>@endif
  @error('photo')<div class="err">{{ $message }}</div>@enderror
</div>

<div class="field">
  <label>LinkedIn URL (optional)</label>
  <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $e?->linkedin_url) }}" placeholder="https://linkedin.com/in/...">
  @error('linkedin_url')<div class="err">{{ $message }}</div>@enderror
</div>

<div class="field">
  <label>X (Twitter) URL (optional)</label>
  <input type="url" name="x_url" value="{{ old('x_url', $e?->x_url) }}" placeholder="https://x.com/...">
  @error('x_url')<div class="err">{{ $message }}</div>@enderror
</div>

<div class="field">
  <label>Email (optional)</label>
  <input type="email" name="email" value="{{ old('email', $e?->email) }}">
  @error('email')<div class="err">{{ $message }}</div>@enderror
</div>

<div class="field">
  <label>Display order (lower numbers show first)</label>
  <input type="number" name="sort_order" value="{{ old('sort_order', $e?->sort_order ?? 0) }}" min="0">
  @error('sort_order')<div class="err">{{ $message }}</div>@enderror
</div>