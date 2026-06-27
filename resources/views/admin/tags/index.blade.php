@extends('layouts.admin')

@section('title', 'Tags')
@section('header_title', 'Tags')


@section('content')


<div class="tag-layout">
  <!-- Left Side: Quick Add Tag -->
  <div class="admin-card">
    <h2 class="card-title" style="margin-bottom: 20px;">Add New Tag</h2>
    
    <form action="{{ route('admin.tags.store') }}" method="POST">
      @csrf
      <div class="form-group">
        <label for="name_bn" class="form-label">Tag Name (Bengali) <span style="color: var(--danger)">*</span></label>
        <input type="text" name="name_bn" id="name_bn" class="form-control" placeholder="e.g. বাজেট" required>
      </div>
      
      <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; justify-content: center;">
        <i class="fas fa-plus"></i> Add Tag
      </button>
    </form>
  </div>

  <!-- Right Side: Tag Cloud -->
  <div class="admin-card">
    <h2 class="card-title">Existing Tags</h2>
    <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">The number next to each tag indicates the count of associated articles.</p>
    
    <div class="tag-pill-container">
      @forelse($tags as $tag)
        <div class="tag-badge-item">
          <span style="font-weight: 500; color: #ffffff;">{{ $tag->name_bn }}</span>
          <span style="font-size: 11px; background: rgba(99, 102, 241, 0.2); color: var(--primary); padding: 2px 6px; border-radius: 10px;">
            {{ $tag->articles_count }}
          </span>
          
          <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tag?');" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="tag-delete-btn" title="Delete">
              <i class="fas fa-times"></i>
            </button>
          </form>
        </div>
      @empty
        <div style="color: var(--text-muted); font-size: 14px; padding: 20px 0;">No tags found.</div>
      @endforelse
    </div>
  </div>
</div>
@endsection
