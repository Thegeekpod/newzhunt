@extends('layouts.admin')

@section('title', 'Newsletter Subscribers')
@section('header_title', 'Newsletter Subscribers')

@section('content')


<div class="admin-card">
  <div class="card-header-flex">
    <h2 class="card-title">Subscribers List</h2>
    <span style="font-size: 13px; color: var(--text-muted);">Total: {{ $subscribers->total() }} subscribers</span>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Subscriber Email</th>
          <th>Date Joined</th>
        </tr>
      </thead>
      <tbody>
        @forelse($subscribers as $index => $sub)
          <tr>
            <td>{{ $subscribers->firstItem() + $index }}</td>
            <td style="font-weight: 600; color: #ffffff;">{{ $sub->email }}</td>
            <td>{{ $sub->created_at->format('M d, Y') }} ({{ $sub->created_at->format('H:i') }})</td>
          </tr>
        @empty
          <tr>
            <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 30px;">No subscribers found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  @if($subscribers->hasPages())
    <div class="pagination" role="navigation" aria-label="Page navigation" style="margin-top: 24px; display: flex; justify-content: center; gap: 6px;">
        @if ($subscribers->onFirstPage())
            <button class="page-btn" aria-label="Previous Page" style="opacity: 0.5; background: rgba(255,255,255,0.05); color: var(--text-muted); padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px;" disabled><i class="fas fa-chevron-left"></i></button>
        @else
            <a href="{{ $subscribers->previousPageUrl() }}" class="page-btn" aria-label="Previous Page" style="color: var(--text-main); background: rgba(255,255,255,0.05); padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; text-decoration: none;"><i class="fas fa-chevron-left"></i></a>
        @endif
 
        @foreach ($subscribers->getUrlRange(1, $subscribers->lastPage()) as $page => $url)
            @if ($page == $subscribers->currentPage())
                <button class="page-btn active" aria-label="Page {{ $page }}" style="background: var(--primary); color: #ffffff; padding: 8px 12px; border: 1px solid var(--primary); border-radius: 4px; font-weight: 600;">{{ $page }}</button>
            @else
                <a href="{{ $url }}" class="page-btn" aria-label="Page {{ $page }}" style="color: var(--text-main); background: rgba(255,255,255,0.05); padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; text-decoration: none;">{{ $page }}</a>
            @endif
        @endforeach
 
        @if ($subscribers->hasMorePages())
            <a href="{{ $subscribers->nextPageUrl() }}" class="page-btn" aria-label="Next Page" style="color: var(--text-main); background: rgba(255,255,255,0.05); padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; text-decoration: none;"><i class="fas fa-chevron-right"></i></a>
        @else
            <button class="page-btn" aria-label="Next Page" style="opacity: 0.5; background: rgba(255,255,255,0.05); color: var(--text-muted); padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px;" disabled><i class="fas fa-chevron-right"></i></button>
        @endif
    </div>
  @endif
</div>
@endsection
