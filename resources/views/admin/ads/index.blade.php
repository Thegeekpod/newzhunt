@extends('layouts.admin')

@section('title', 'Advertisements')
@section('header_title', 'Advertisements')

@section('content')
@php
  $slotDescriptions = [
      'homepage_top' => 'Homepage Top (Below Menu, 970x90)',
      'homepage_middle' => 'Homepage Middle (Below Video News, 728x90)',
      'category_top' => 'Category Page Top (Below Header, 970x90)',
      'article_top' => 'Article Page Top (Below Breadcrumbs, 970x90)',
      'article_body' => 'Article Page Body (Inside text content, 728x90)',
      'article_bottom_1' => 'Article Bottom Grid 1 (Below Related News, 728x90)',
      'article_bottom_2' => 'Article Bottom Grid 2 (Below Related News, 728x90)',
      'sidebar_square' => 'Sidebar Box (Sidebar widget area, 300x250)',
  ];

  $slotPageMap = [
      'homepage_top' => 'homepage',
      'homepage_middle' => 'homepage',
      'category_top' => 'category',
      'article_top' => 'article',
      'article_body' => 'article',
      'article_bottom_1' => 'article',
      'article_bottom_2' => 'article',
      'sidebar_square' => 'sidebar',
  ];
@endphp

<div style="display: flex; flex-direction: column; gap: 24px;">

  <!-- Page View Filter Tab System -->
  <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 5px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
    <button class="btn-admin btn-admin-primary ad-tab active" data-tab="all" style="padding: 8px 16px; font-size: 13px;">
      <i class="fas fa-list"></i> All Positions
    </button>
    <button class="btn-admin btn-admin-secondary ad-tab" data-tab="homepage" style="padding: 8px 16px; font-size: 13px;">
      <i class="fas fa-home"></i> Homepage Banners
    </button>
    <button class="btn-admin btn-admin-secondary ad-tab" data-tab="category" style="padding: 8px 16px; font-size: 13px;">
      <i class="fas fa-folder-open"></i> Category Page
    </button>
    <button class="btn-admin btn-admin-secondary ad-tab" data-tab="article" style="padding: 8px 16px; font-size: 13px;">
      <i class="fas fa-file-alt"></i> Article Page
    </button>
    <button class="btn-admin btn-admin-secondary ad-tab" data-tab="sidebar" style="padding: 8px 16px; font-size: 13px;">
      <i class="fas fa-columns"></i> Sidebar Widget
    </button>
  </div>

  <style>
    @media (min-width: 992px) {
      .ads-container {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 24px;
        align-items: start;
      }
    }
    .ad-card-item {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      gap: 14px;
      transition: all 0.2s ease;
    }
  </style>

  <div class="ads-container">
    <!-- Left Column: Add New Advertisement Banner -->
    <div class="admin-card">
      <h2 class="card-title" style="margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
        <i class="fas fa-plus"></i> Post New Banner
      </h2>
      
      <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
          <label for="slot_name" class="form-label">Position / Slot Name <span style="color: var(--danger)">*</span></label>
          <select name="slot_name" id="slot_name" class="form-control" required style="background-color: var(--bg-dark); color: #fff;">
            @foreach($slotDescriptions as $key => $description)
              <option value="{{ $key }}">{{ $key }} ({{ $description }})</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="image" class="form-label">Banner Image <span style="color: var(--danger)">*</span></label>
          <input type="file" name="image" id="image" class="form-control" required style="padding: 6px 10px; font-size: 13px;">
          <small style="color: var(--text-muted); font-size: 11px; margin-top: 4px; display: block;">Supports: jpeg, png, jpg, gif, svg, webp. Max 2MB.</small>
        </div>
        
        <div class="form-group">
          <label for="destination_url" class="form-label">Destination URL</label>
          <input type="url" name="destination_url" id="destination_url" class="form-control" placeholder="e.g., https://example.com">
        </div>

        <div class="form-group">
          <label class="form-check">
            <input type="checkbox" name="is_active" value="1" checked>
            <span>Set banner as active on post</span>
          </label>
        </div>
        
        <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; justify-content: center; margin-top: 10px;">
          <i class="fas fa-upload"></i> Post Banner Ad
        </button>
      </form>
    </div>

    <!-- Right Column: Active and Inactive Banner Posts -->
    <div>
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h2 class="card-title" style="font-size: 18px;" id="current-tab-title">
          All Banner Posts (Multiple Banners Slider Supported)
        </h2>
        <span class="badge badge-primary" style="font-size: 13px;" id="ad-counter-badge">{{ count($ads) }} Banners</span>
      </div>

      <div class="ads-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
        @forelse($ads as $ad)
          @php
            $pageGroup = $slotPageMap[$ad->slot_name] ?? 'other';
          @endphp
          <div class="ad-card-item" data-page="{{ $pageGroup }}">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
              <div>
                <h3 style="font-size: 14px; font-weight: 700; color: #38bdf8; text-transform: uppercase; margin: 0;">
                  <i class="fas fa-rectangle-ad"></i> {{ str_replace('_', ' ', $ad->slot_name) }}
                </h3>
                <span style="font-size: 11px; color: var(--text-muted);">
                  ({{ $slotDescriptions[$ad->slot_name] ?? 'General Position' }})
                </span>
              </div>
              <span class="badge {{ $ad->is_active ? 'badge-success' : 'badge-danger' }}" style="padding: 2px 6px; font-size: 10px;">
                {{ $ad->is_active ? 'Active' : 'Inactive' }}
              </span>
            </div>
            
            <!-- Ad Image Preview -->
            <div class="ad-preview-box" style="height: 120px; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.2); border-radius: 6px; border: 1px dashed var(--border-color);">
              @if($ad->image_url)
                <img src="{{ asset($ad->image_url) }}" alt="Ad Preview" class="ad-preview-img" style="max-height: 100px; max-width: 100%; object-fit: contain;">
              @else
                <span style="font-size: 12px; color: var(--text-muted);">No image uploaded</span>
              @endif
            </div>

            <!-- Edit Details & Replace Banner Form -->
            <form action="{{ route('admin.ads.update', $ad->id) }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 10px;">
              @csrf
              @method('PUT')

              <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 11px; margin-bottom: 2px;">Change Position</label>
                <select name="slot_name" class="form-control" style="padding: 6px 10px; font-size: 12px; background-color: var(--bg-dark); color: #fff;">
                  @foreach($slotDescriptions as $key => $description)
                    <option value="{{ $key }}" {{ $ad->slot_name == $key ? 'selected' : '' }}>{{ $key }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 11px; margin-bottom: 2px;">Destination URL</label>
                <input type="url" name="destination_url" class="form-control" style="padding: 6px 10px; font-size: 12px;" placeholder="https://example.com" value="{{ $ad->destination_url }}">
              </div>

              <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 11px; margin-bottom: 2px;">Replace Image</label>
                <input type="file" name="image" class="form-control" style="padding: 4px 8px; font-size: 11px;">
              </div>

              <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 5px;">
                <label class="form-check" style="margin-top: 0; font-size: 12px;">
                  <input type="checkbox" name="is_active" value="1" {{ $ad->is_active ? 'checked' : '' }}>
                  <span>Show ad as active</span>
                </label>
                
                <button type="submit" class="btn-admin btn-admin-secondary" style="padding: 5px 12px; font-size: 12px;">
                  Save Changes
                </button>
              </div>
            </form>

            <!-- Delete Button -->
            <div style="border-top: 1px solid var(--border-color); padding-top: 10px; margin-top: 5px; display: flex; justify-content: flex-end;">
              <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner post?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-admin btn-admin-danger" style="padding: 4px 10px; font-size: 11px;">
                  <i class="fas fa-trash"></i> Delete Banner
                </button>
              </form>
            </div>
          </div>
        @empty
          <div class="admin-card" id="empty-state-card" style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px;">
            <i class="fas fa-rectangle-ad" style="font-size: 40px; margin-bottom: 12px; opacity: 0.5;"></i>
            <p>No advertisements found. Post a new banner to get started!</p>
          </div>
        @endforelse

        <div class="admin-card" id="filter-empty-state" style="display: none; grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px;">
          <i class="fas fa-rectangle-ad" style="font-size: 40px; margin-bottom: 12px; opacity: 0.5;"></i>
          <p>No ads found in this tab.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.ad-tab');
    const cards = document.querySelectorAll('.ad-card-item');
    const filterEmptyState = document.getElementById('filter-empty-state');
    const adCounterBadge = document.getElementById('ad-counter-badge');
    const tabTitle = document.getElementById('current-tab-title');
    
    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        // Toggle Active tab button style
        tabs.forEach(t => {
          t.classList.remove('active', 'btn-admin-primary');
          t.classList.add('btn-admin-secondary');
        });
        tab.classList.add('active', 'btn-admin-primary');
        tab.classList.remove('btn-admin-secondary');
        
        const filter = tab.dataset.tab;
        
        // Update Title text
        if (filter === 'all') {
          tabTitle.textContent = 'All Banner Posts (Multiple Banners Slider Supported)';
        } else {
          tabTitle.textContent = tab.textContent.trim() + ' Banners';
        }
        
        let visibleCount = 0;
        cards.forEach(card => {
          if (filter === 'all' || card.dataset.page === filter) {
            card.style.display = 'flex';
            visibleCount++;
          } else {
            card.style.display = 'none';
          }
        });
        
        // Update counter
        adCounterBadge.textContent = visibleCount + ' Banners';
        
        // Handle empty filtered states
        if (visibleCount === 0 && cards.length > 0) {
          filterEmptyState.style.display = 'block';
        } else {
          filterEmptyState.style.display = 'none';
        }
      });
    });
  });
</script>
@endsection
