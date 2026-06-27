@extends('layouts.app')

@section('title', 'অনুসন্ধান ফলাফল: "' . ($query ?? '') . '" - নিউজহান্ট')

@section('content')
@php
    use App\Helpers\BengaliHelper;
@endphp

<div class="main-layout">
  <div class="container">
    <div class="content-sidebar">
      <!-- === MAIN CONTENT === -->
      <div class="main-content-area">
      
      <!-- SEARCH RESULT SECTION -->
      <div class="category-section" style="margin-top: 10px;">
        <div class="section-header" style="border-bottom: 2px solid var(--primary-red); padding-bottom: 8px; margin-bottom: 20px;">
          <h2 class="section-title" style="font-size: 22px; font-weight: 800; color: var(--primary-red);">অনুসন্ধানের ফলাফল</h2>
        </div>

        <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 8px; padding: 12px 18px; margin-bottom: 24px; font-size: 14px; color: var(--text-dark);">
          আইটেম অনুসন্ধান করা হয়েছে: <strong style="color: var(--primary-red);">"{{ $query }}"</strong> 
          (মোট ফলাফল: <strong style="color: var(--primary-red);">{{ BengaliHelper::toBengaliNumerals($articles->total()) }}</strong> টি)
        </div>
        
        <div class="search-news-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px; margin-bottom: 30px;">
          @forelse($articles as $art)
            <article class="news-card" style="background: var(--white); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); transition: var(--transition); display: flex; flex-direction: column;">
              <div class="card-thumb" style="position: relative; overflow: hidden;">
                <a href="{{ route('article.show', $art->slug) }}" style="display: block;">
                  <img src="{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/search-news-'.$art->id.'/600/380' }}" alt="{{ $art->title }}" loading="lazy" style="width: 100%; height: 190px; object-fit: cover; transition: transform 0.4s;">
                </a>
                <span class="card-badge" style="position: absolute; top: 12px; left: 12px; background: var(--primary-red); color: white; font-size: 11px; padding: 3px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase;">
                  {{ $art->category->name_bn }}
                </span>
              </div>
              
              <div class="card-body" style="padding: 16px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; gap: 10px;">
                <div>
                  <a href="{{ route('article.show', $art->slug) }}" style="text-decoration: none;">
                    <h3 class="card-title" style="font-size: 16px; font-weight: 700; line-height: 1.4; color: var(--text-dark); margin: 0 0 8px 0; transition: color 0.2s;">
                      {{ $art->title }}
                    </h3>
                  </a>
                  <p class="card-excerpt" style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin: 0;">
                    {{ Str::limit(strip_tags($art->content), 100) }}
                  </p>
                </div>
                
                <div class="card-meta" style="display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: var(--text-muted); border-top: 1px solid var(--border-color); padding-top: 10px; margin-top: auto;">
                  <span class="card-meta-item"><i class="fas fa-user" style="margin-right: 4px;"></i> {{ $art->author->name }}</span>
                  <span class="card-meta-item"><i class="fas fa-clock" style="margin-right: 4px;"></i> {{ BengaliHelper::toBengaliTime($art->published_at) }}</span>
                </div>
              </div>
            </article>
          @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted); background: rgba(0,0,0,0.1); border-radius: 12px; border: 1px dashed var(--border-color);">
              <i class="fas fa-search-minus" style="font-size: 40px; margin-bottom: 12px; opacity: 0.5;"></i>
              <p>আপনার অনুসন্ধান করা শব্দের সাথে মিল রেখে কোনো খবর পাওয়া যায়নি। অনুগ্রহ করে অন্য কিছু লিখে অনুসন্ধান করুন।</p>
            </div>
          @endforelse
        </div>

        <!-- Pagination -->
        <div style="margin-top: 30px; display: flex; justify-content: center;">
          {{ $articles->appends(['q' => $query])->links('vendor.pagination.custom') ?? $articles->appends(['q' => $query])->links() }}
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
