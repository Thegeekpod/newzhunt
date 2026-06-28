@extends('layouts.app')

@section('title', 'সব ভিডিও সংবাদ - নিউজহান্ট')

@section('content')
<div class="main-layout">
  <div class="container">
    <div class="content-sidebar">
      <!-- === MAIN CONTENT === -->
      <div class="main-content-area">
      
      <!-- VIDEO SECTION -->
      <div class="video-section" style="margin-top: 10px;">
        <div class="section-header" style="border-bottom: 2px solid var(--primary-red); padding-bottom: 8px; margin-bottom: 20px;">
          <h2 class="section-title" style="font-size: 22px; font-weight: 800; color: var(--primary-red);">সব ভিডিও সংবাদ</h2>
        </div>
        
        <div class="video-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
          @forelse($videos as $video)
            <article class="video-card" data-youtube-url="{{ $video->youtube_url }}" style="cursor: pointer; background: var(--white); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); transition: var(--transition);">
              <div class="video-thumb" style="position: relative; overflow: hidden;">
                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title_bn }}" loading="lazy" style="width: 100%; height: 160px; object-fit: cover; transition: transform 0.4s;">
                <div class="play-btn" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 46px; height: 46px; background: rgba(232, 16, 26, 0.9); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; transition: var(--transition);"><i class="fas fa-play"></i></div>
                <span class="video-duration" style="position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,0.8); color: white; font-size: 11px; padding: 2px 6px; border-radius: 4px; font-weight: 600;">{{ $video->duration }}</span>
              </div>
              <div class="video-body" style="padding: 12px;">
                <h3 class="video-title" style="font-size: 14px; font-weight: 700; line-height: 1.5; color: var(--text-dark); margin: 0;">{{ $video->title_bn }}</h3>
              </div>
            </article>
          @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
              কোনো ভিডিও পাওয়া যায়নি।
            </div>
          @endforelse
        </div>

        <!-- Pagination -->
        <div style="margin-top: 30px; display: flex; justify-content: center;">
          {{ $videos->links('vendor.pagination.custom') ?? $videos->links() }}
        </div>
      </div>



      </div>

      <!-- === SIDEBAR === -->
      <aside class="sidebar" aria-label="সাইডবার">
          @include('partials.sidebar')
      </aside>
    </div>
  </div>
</div>
@endsection
