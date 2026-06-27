@extends('layouts.app')

@section('title', 'শীর্ষ সংবাদ - নিউজহান্ট')

@section('content')
@php
    use App\Helpers\BengaliHelper;
@endphp

<!-- ===== PAGE HEADER ===== -->
<div class="container" style="margin-top: 20px;">
  <!-- Breadcrumb -->
  <nav class="article-breadcrumb" aria-label="ব্রেডক্রাম্ব">
    <a href="{{ route('home') }}">হোম</a>
    <span>›</span>
    <span>শীর্ষ সংবাদ</span>
  </nav>

  <!-- Header Banner -->
  <div class="category-header">
    <div class="category-header-inner">
      <div class="category-title-wrap">
        <h1 class="category-badge-title">শীর্ষ সংবাদ</h1>
        <p class="category-desc">বাংলাদেশ ও বিশ্বের সর্বাধিক গুরুত্বপূর্ণ এবং আলোচিত শীর্ষ সংবাদগুলি পড়ুন।</p>
      </div>
      <div class="category-stats">
        <div class="category-stat-item">
          <i class="fas fa-newspaper"></i>
          <span>{{ BengaliHelper::toBengaliNumerals($articles->total()) }} নিবন্ধ</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===== MAIN CONTENT LAYOUT ===== -->
<div class="main-layout">
  <div class="container">
    <div class="content-sidebar">

      <!-- === LEFT COLUMN: NEWS === -->
      <div class="main-content-area">

        @if($articles->isEmpty())
          <div style="text-align: center; padding: 40px; background: var(--white); border-radius: 8px; border: 1px solid var(--border-color);">
            <i class="fas fa-newspaper" style="font-size: 48px; color: var(--text-light); margin-bottom: 16px;"></i>
            <h3 style="color: var(--text-dark);">কোনো শীর্ষ সংবাদ পাওয়া যায়নি।</h3>
            <p style="color: var(--text-medium); margin-top: 8px;">শীঘ্রই নতুন খবর যোগ করা হবে, আমাদের সাথেই থাকুন।</p>
          </div>
        @else

          {{-- Page 1: Hero Grid for top 3 articles --}}
          @if($articles->currentPage() == 1)
            <div class="hero-grid">
              <!-- Hero Main -->
              @if($articles->count() >= 1)
                @php $first = $articles[0]; @endphp
                <article class="news-card hero-main">
                  <div class="card-img-wrap">
                    <a href="{{ route('article.show', $first->slug) }}">
                      <img src="{{ $first->thumbnail_url ?? 'https://picsum.photos/seed/top1/800/480' }}" alt="{{ $first->title }}" loading="eager">
                    </a>
                    <span class="card-category">{{ $first->category->name_bn }}</span>
                  </div>
                  <div class="card-body">
                    <a href="{{ route('article.show', $first->slug) }}">
                      <h2 class="card-title">{{ $first->title }}</h2>
                    </a>
                    <p class="card-excerpt">{{ $first->excerpt ?? Str::limit(strip_tags($first->content), 150) }}</p>
                    <div class="card-meta">
                      <span class="card-meta-item"><i class="fas fa-user"></i> {{ $first->author->name }}</span>
                      <span class="card-meta-item"><i class="fas fa-clock"></i> {{ BengaliHelper::toBengaliTime($first->published_at) }}</span>
                      <span class="card-meta-item"><i class="fas fa-eye"></i> {{ BengaliHelper::toBengaliNumerals(number_format($first->view_count)) }}</span>
                    </div>
                  </div>
                </article>
              @endif

              <!-- Hero Side 1 -->
              @if($articles->count() >= 2)
                @php $second = $articles[1]; @endphp
                <article class="news-card hero-side1">
                  <div class="card-img-wrap">
                    <a href="{{ route('article.show', $second->slug) }}">
                      <img src="{{ $second->thumbnail_url ?? 'https://picsum.photos/seed/top2/600/360' }}" alt="{{ $second->title }}" loading="lazy">
                    </a>
                    <span class="card-category">{{ $second->category->name_bn }}</span>
                  </div>
                  <div class="card-body">
                    <a href="{{ route('article.show', $second->slug) }}">
                      <h3 class="card-title">{{ $second->title }}</h3>
                    </a>
                    <div class="card-meta">
                      <span class="card-meta-item"><i class="fas fa-clock"></i> {{ BengaliHelper::toBengaliTime($second->published_at) }}</span>
                    </div>
                  </div>
                </article>
              @endif

              <!-- Hero Side 2 -->
              @if($articles->count() >= 3)
                @php $third = $articles[2]; @endphp
                <article class="news-card hero-side2">
                  <div class="card-img-wrap">
                    <a href="{{ route('article.show', $third->slug) }}">
                      <img src="{{ $third->thumbnail_url ?? 'https://picsum.photos/seed/top3/600/360' }}" alt="{{ $third->title }}" loading="lazy">
                    </a>
                    <span class="card-category card-badge-blue">{{ $third->category->name_bn }}</span>
                  </div>
                  <div class="card-body">
                    <a href="{{ route('article.show', $third->slug) }}">
                      <h3 class="card-title">{{ $third->title }}</h3>
                    </a>
                    <div class="card-meta">
                      <span class="card-meta-item"><i class="fas fa-clock"></i> {{ BengaliHelper::toBengaliTime($third->published_at) }}</span>
                    </div>
                  </div>
                </article>
              @endif
            </div>

            {{-- Remaining articles in grid --}}
            @if($articles->count() > 3)
              <div class="category-section">
                <div class="section-header">
                  <h2 class="section-title">সব শীর্ষ সংবাদ</h2>
                </div>
                <div class="cards-grid-3">
                  @foreach($articles->slice(3) as $art)
                    <article class="news-card">
                      <div class="card-img-wrap">
                        <a href="{{ route('article.show', $art->slug) }}">
                          <img src="{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/top'.$art->id.'/600/380' }}" alt="{{ $art->title }}" loading="lazy">
                        </a>
                        <span class="card-category">{{ $art->category->name_bn }}</span>
                      </div>
                      <div class="card-body">
                        <a href="{{ route('article.show', $art->slug) }}">
                          <h3 class="card-title">{{ $art->title }}</h3>
                        </a>
                        <div class="card-meta">
                          <span class="card-meta-item"><i class="fas fa-clock"></i> {{ BengaliHelper::toBengaliTime($art->published_at) }}</span>
                        </div>
                      </div>
                    </article>
                  @endforeach
                </div>
              </div>
            @endif

          {{-- Page 2+: Simple grid --}}
          @else
            <div class="category-section">
              <div class="cards-grid-3">
                @foreach($articles as $art)
                  <article class="news-card">
                    <div class="card-img-wrap">
                      <a href="{{ route('article.show', $art->slug) }}">
                        <img src="{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/top'.$art->id.'/600/380' }}" alt="{{ $art->title }}" loading="lazy">
                      </a>
                      <span class="card-category">{{ $art->category->name_bn }}</span>
                    </div>
                    <div class="card-body">
                      <a href="{{ route('article.show', $art->slug) }}">
                        <h3 class="card-title">{{ $art->title }}</h3>
                      </a>
                      <div class="card-meta">
                        <span class="card-meta-item"><i class="fas fa-clock"></i> {{ BengaliHelper::toBengaliTime($art->published_at) }}</span>
                      </div>
                    </div>
                  </article>
                @endforeach
              </div>
            </div>
          @endif

          <!-- PAGINATION -->
          @if($articles->hasPages())
            <div class="pagination" role="navigation" aria-label="পৃষ্ঠা নেভিগেশন">
              @if ($articles->onFirstPage())
                <button class="page-btn" aria-label="আগের পৃষ্ঠা" style="opacity: 0.5;" disabled><i class="fas fa-chevron-left"></i></button>
              @else
                <a href="{{ $articles->previousPageUrl() }}" class="page-btn" aria-label="আগের পৃষ্ঠা"><i class="fas fa-chevron-left"></i></a>
              @endif

              @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                @if ($page == $articles->currentPage())
                  <button class="page-btn active" aria-label="পৃষ্ঠা {{ $page }}">{{ BengaliHelper::toBengaliNumerals($page) }}</button>
                @else
                  <a href="{{ $url }}" class="page-btn" aria-label="পৃষ্ঠা {{ $page }}">{{ BengaliHelper::toBengaliNumerals($page) }}</a>
                @endif
              @endforeach

              @if ($articles->hasMorePages())
                <a href="{{ $articles->nextPageUrl() }}" class="page-btn" aria-label="পরের পৃষ্ঠা"><i class="fas fa-chevron-right"></i></a>
              @else
                <button class="page-btn" aria-label="পরের পৃষ্ঠা" style="opacity: 0.5;" disabled><i class="fas fa-chevron-right"></i></button>
              @endif
            </div>
          @endif

        @endif

      </div><!-- /main-content-area -->

      <!-- === RIGHT COLUMN: SIDEBAR === -->
      <aside class="sidebar" aria-label="সাইডবার">
          @include('partials.sidebar')
      </aside>

    </div><!-- /content-sidebar -->
  </div><!-- /container -->
</div><!-- /main-layout -->
@endsection
