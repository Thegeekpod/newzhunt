@extends('layouts.app')

@section('title', 'নিউজহান্ট')

@section('content')
@php
    use App\Helpers\BengaliHelper;
@endphp

<!-- ===== SPECIAL ALERT BANNER ===== -->
@if(isset($specialArticle))
<div class="container" style="margin-top: 20px;">
  <div class="election-banner">
    <div class="election-text">
      <h3>{{ $specialArticle->title }}</h3>
      <p>{{ $specialArticle->excerpt ?? Str::limit(strip_tags($specialArticle->content), 150) }}</p>
    </div>
    <button class="election-btn" onclick="window.location.href='{{ route('article.show', $specialArticle->slug) }}'">বিস্তারিত পড়ুন →</button>
  </div>
</div>
@endif

<!-- ===== HERO + SIDEBAR ===== -->
<div class="main-layout">
  <div class="container">
    <div class="content-sidebar">

      <!-- === MAIN CONTENT === -->
      <div class="main-content-area">

        <!-- HERO GRID -->
        <div class="section-header" style="margin-bottom: 14px;">
          <h2 class="section-title">শীর্ষ সংবাদ</h2>
          <a href="#" class="see-all">সব দেখুন <i class="fas fa-arrow-right" style="font-size: 11px;"></i></a>
        </div>

        <div class="hero-grid">
          @if(isset($leadArticle))
            <!-- Hero Main -->
            <article class="news-card hero-main">
              <div class="card-img-wrap">
                <a href="{{ route('article.show', $leadArticle->slug) }}">
                  <img src="{{ $leadArticle->thumbnail_url ?? 'https://picsum.photos/seed/hero1/800/480' }}" alt="{{ $leadArticle->title }}" loading="eager">
                </a>
                <span class="card-category">{{ $leadArticle->category->name_bn }}</span>
              </div>
              <div class="card-body">
                <a href="{{ route('article.show', $leadArticle->slug) }}">
                  <h2 class="card-title">{{ $leadArticle->title }}</h2>
                </a>
                <p class="card-excerpt">{{ $leadArticle->excerpt }}</p>
                <div class="card-meta">
                  <span class="card-meta-item"><i class="fas fa-user"></i> {{ $leadArticle->author->name }}</span>
                  <span class="card-meta-item"><i class="fas fa-clock"></i> {{ BengaliHelper::toBengaliTime($leadArticle->published_at) }}</span>
                  <span class="card-meta-item"><i class="fas fa-eye"></i> {{ BengaliHelper::toBengaliNumerals(number_format($leadArticle->view_count)) }}</span>
                </div>
              </div>
            </article>
          @endif

          @if(isset($subLeads[0]))
            <!-- Hero Side 1 -->
            <article class="news-card hero-side1">
              <div class="card-img-wrap">
                <a href="{{ route('article.show', $subLeads[0]->slug) }}">
                  <img src="{{ $subLeads[0]->thumbnail_url ?? 'https://picsum.photos/seed/hero2/600/360' }}" alt="{{ $subLeads[0]->title }}" loading="lazy">
                </a>
                <span class="card-category">{{ $subLeads[0]->category->name_bn }}</span>
              </div>
              <div class="card-body">
                <a href="{{ route('article.show', $subLeads[0]->slug) }}">
                  <h3 class="card-title">{{ $subLeads[0]->title }}</h3>
                </a>
                <div class="card-meta">
                  <span class="card-meta-item"><i class="fas fa-clock"></i> {{ BengaliHelper::toBengaliTime($subLeads[0]->published_at) }}</span>
                </div>
              </div>
            </article>
          @endif

          @if(isset($subLeads[1]))
            <!-- Hero Side 2 -->
            <article class="news-card hero-side2">
              <div class="card-img-wrap">
                <a href="{{ route('article.show', $subLeads[1]->slug) }}">
                  <img src="{{ $subLeads[1]->thumbnail_url ?? 'https://picsum.photos/seed/hero3/600/360' }}" alt="{{ $subLeads[1]->title }}" loading="lazy">
                </a>
                <span class="card-category card-badge-blue">{{ $subLeads[1]->category->name_bn }}</span>
              </div>
              <div class="card-body">
                <a href="{{ route('article.show', $subLeads[1]->slug) }}">
                  <h3 class="card-title">{{ $subLeads[1]->title }}</h3>
                </a>
                <div class="card-meta">
                  <span class="card-meta-item"><i class="fas fa-clock"></i> {{ BengaliHelper::toBengaliTime($subLeads[1]->published_at) }}</span>
                </div>
              </div>
            </article>
          @endif
        </div>

        <!-- AD BANNER AFTER TOP NEWS -->
        @if(isset($g_ads['homepage_top']) && $g_ads['homepage_top']->isNotEmpty())
        <div class="ad-banner-horizontal ad-slider-container">
          <span class="ad-banner-tag">বিজ্ঞাপন</span>
          <div class="ad-slider-wrapper">
            @foreach($g_ads['homepage_top'] as $ad)
              <a href="{{ $ad->destination_url }}" class="ad-banner-link ad-slide @if($loop->first) active @endif" target="_blank">
                <img src="{{ asset($ad->image_url) }}" alt="বিজ্ঞাপন" loading="lazy">
              </a>
            @endforeach
          </div>
        </div>
        @endif

        <!-- HORIZONTAL SCROLL - Latest -->
        <div class="category-section">
          <div class="section-header">
            <h2 class="section-title">সর্বশেষ খবর</h2>
            <a href="#" class="see-all">সব দেখুন <i class="fas fa-arrow-right" style="font-size: 11px;"></i></a>
          </div>
          <div class="horizontal-scroll">
            @foreach($latestArticles as $art)
            <article class="news-card">
              <div class="card-img-wrap">
                <a href="{{ route('article.show', $art->slug) }}">
                  <img src="{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/l'.$art->id.'/400/250' }}" alt="{{ $art->title }}" loading="lazy">
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

        <!-- POLITICS -->
        <div class="category-section">
          <div class="section-header">
            <h2 class="section-title">রাজনীতি</h2>
            <a href="{{ route('category.show', 'politics') }}" class="see-all">সব দেখুন <i class="fas fa-arrow-right" style="font-size: 11px;"></i></a>
          </div>
          
          @if($politicsArticles->count() >= 3)
          <div class="cards-grid-3">
            @foreach($politicsArticles->take(3) as $art)
            <article class="news-card">
              <div class="card-img-wrap">
                <a href="{{ route('article.show', $art->slug) }}">
                  <img src="{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/p'.$art->id.'/600/380' }}" alt="{{ $art->title }}" loading="lazy">
                </a>
                <span class="card-category">রাজনীতি</span>
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
          @endif

          <!-- List below grid -->
          @if($politicsArticles->count() > 3)
          <div class="card-list" style="margin-top: 16px;">
            @foreach($politicsArticles->slice(3)->take(2) as $art)
            <article class="card-horizontal">
              <div class="card-img-wrap">
                <a href="{{ route('article.show', $art->slug) }}">
                  <img src="{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/p'.$art->id.'/300/200' }}" alt="{{ $art->title }}" loading="lazy">
                </a>
              </div>
              <div class="card-body">
                <span class="card-category" style="position:static; display:inline-block; margin-bottom: 4px;">রাজনীতি</span>
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
          @endif
        </div>

        <!-- SPORTS WITH TABS -->
        <div class="category-section sports-section">
          <div class="sports-tab-header">
            <div class="sports-tab-title">
              <span class="sports-icon-wrap"><i class="fas fa-trophy"></i></span>
              <h2>খেলাধুলা</h2>
            </div>
            <a href="{{ route('category.show', 'sports') }}" class="see-all">সব দেখুন <i class="fas fa-arrow-right" style="font-size: 11px;"></i></a>
          </div>
          <div class="sports-tabs-wrap">
            <div class="cat-tabs" role="tablist" id="sports-tablist">
              <button class="cat-tab active" role="tab" data-tab="cricket" id="tab-cricket" aria-selected="true">
                <span class="tab-icon">🏏</span>
                <span class="tab-label">ক্রিকেট</span>
              </button>
              <button class="cat-tab" role="tab" data-tab="football" id="tab-football" aria-selected="false">
                <span class="tab-icon">⚽</span>
                <span class="tab-label">ফুটবল</span>
              </button>
              <button class="cat-tab" role="tab" data-tab="kabaddi" id="tab-kabaddi" aria-selected="false">
                <span class="tab-icon">🤼</span>
                <span class="tab-label">কাবাডি</span>
              </button>
              <button class="cat-tab" role="tab" data-tab="others" id="tab-others" aria-selected="false">
                <span class="tab-icon">🏅</span>
                <span class="tab-label">অন্যান্য</span>
              </button>
              <span class="tab-slider" id="tab-slider"></span>
            </div>
          </div>
          <div class="card-list" id="sports-content">
            <!-- Rendered dynamically via JS on load / tab switch -->
          </div>
        </div>

        <!-- INTERNATIONAL -->
        <div class="category-section">
          <div class="section-header">
            <h2 class="section-title">আন্তর্জাতিক</h2>
            <a href="{{ route('category.show', 'international') }}" class="see-all">সব দেখুন <i class="fas fa-arrow-right" style="font-size: 11px;"></i></a>
          </div>
          <div class="cards-grid-3">
            @foreach($internationalArticles as $art)
            <article class="news-card">
              <div class="card-img-wrap">
                <a href="{{ route('article.show', $art->slug) }}">
                  <img src="{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/i'.$art->id.'/600/380' }}" alt="{{ $art->title }}" loading="lazy">
                </a>
                <span class="card-category">আন্তর্জাতিক</span>
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

        <!-- VIDEO SECTION -->
        <div class="video-section">
          <div class="section-header">
            <h2 class="section-title">ভিডিও সংবাদ</h2>
            <a href="#" class="see-all">সব দেখুন <i class="fas fa-arrow-right" style="font-size: 11px;"></i></a>
          </div>
          <div class="video-grid">
            @foreach($videos as $video)
            <article class="video-card" onclick="window.open('{{ $video->youtube_url }}', '_blank')">
              <div class="video-thumb">
                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title_bn }}" loading="lazy">
                <div class="play-btn"><i class="fas fa-play"></i></div>
                <span class="video-duration">{{ $video->duration }}</span>
              </div>
              <div class="video-body">
                <h3 class="video-title">{{ $video->title_bn }}</h3>
              </div>
            </article>
            @endforeach
          </div>
        </div>

        <!-- AD BANNER AFTER VIDEO NEWS -->
        @if(isset($g_ads['homepage_middle']) && $g_ads['homepage_middle']->isNotEmpty())
        <div class="ad-banner-horizontal ad-slider-container">
          <span class="ad-banner-tag">বিজ্ঞাপন</span>
          <div class="ad-slider-wrapper">
            @foreach($g_ads['homepage_middle'] as $ad)
              <a href="{{ $ad->destination_url }}" class="ad-banner-link ad-slide @if($loop->first) active @endif" target="_blank">
                <img src="{{ asset($ad->image_url) }}" alt="বিজ্ঞাপন" loading="lazy">
              </a>
            @endforeach
          </div>
        </div>
        @endif

        <!-- ENTERTAINMENT -->
        <div class="category-section">
          <div class="section-header">
            <h2 class="section-title">বিনোদন</h2>
            <a href="{{ route('category.show', 'entertainment') }}" class="see-all">সব দেখুন <i class="fas fa-arrow-right" style="font-size: 11px;"></i></a>
          </div>
          <div class="card-list">
            @foreach($entertainmentArticles as $art)
            <article class="card-horizontal">
              <div class="card-img-wrap">
                <a href="{{ route('article.show', $art->slug) }}">
                  <img src="{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/e'.$art->id.'/300/200' }}" alt="{{ $art->title }}" loading="lazy">
                </a>
              </div>
              <div class="card-body">
                <span class="card-category" style="position:static; display:inline-block; margin-bottom: 4px;">বিনোদন</span>
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

        <!-- PAGINATION placeholder -->
        <div class="pagination" role="navigation" aria-label="পৃষ্ঠা নেভিগেশন">
          <button class="page-btn" aria-label="আগের পৃষ্ঠা"><i class="fas fa-chevron-left"></i></button>
          <button class="page-btn active" aria-label="পৃষ্ঠা ১">১</button>
          <button class="page-btn" aria-label="পৃষ্ঠা ২">২</button>
          <button class="page-btn" aria-label="পৃষ্ঠা ৩">৩</button>
          <button class="page-btn" aria-label="পরের পৃষ্ঠা"><i class="fas fa-chevron-right"></i></button>
        </div>

      </div><!-- /main-content-area -->

      <!-- === SIDEBAR === -->
      <aside class="sidebar" aria-label="সাইডবার">
          @include('partials.sidebar')
      </aside>

    </div><!-- /content-sidebar -->
  </div><!-- /container -->
</div><!-- /main-layout -->
@endsection

@section('scripts')
<script>
    // Bind database driven sports articles to sportsData javascript object
    window.sportsData = {
        cricket: [
            @foreach(\App\Models\Article::published()->whereHas('category', fn($q) => $q->where('slug', 'sports'))->take(3)->get() as $art)
            {
                img: "{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/crick'.$art->id.'/300/200' }}",
                title: "{{ $art->title }}",
                time: "{{ BengaliHelper::toBengaliTime($art->published_at) }}",
                views: "{{ BengaliHelper::toBengaliNumerals(number_format($art->view_count)) }}"
            },
            @endforeach
        ],
        football: [
            @foreach(\App\Models\Article::published()->whereHas('category', fn($q) => $q->where('slug', 'international'))->take(3)->get() as $art)
            {
                img: "{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/foot'.$art->id.'/300/200' }}",
                title: "{{ $art->title }}",
                time: "{{ BengaliHelper::toBengaliTime($art->published_at) }}",
                views: "{{ BengaliHelper::toBengaliNumerals(number_format($art->view_count)) }}"
            },
            @endforeach
        ],
        kabaddi: [
            @foreach(\App\Models\Article::published()->whereHas('category', fn($q) => $q->where('slug', 'health'))->take(2)->get() as $art)
            {
                img: "{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/kabad'.$art->id.'/300/200' }}",
                title: "{{ $art->title }}",
                time: "{{ BengaliHelper::toBengaliTime($art->published_at) }}",
                views: "{{ BengaliHelper::toBengaliNumerals(number_format($art->view_count)) }}"
            },
            @endforeach
        ],
        others: [
            @foreach(\App\Models\Article::published()->whereHas('category', fn($q) => $q->where('slug', 'tech'))->take(2)->get() as $art)
            {
                img: "{{ $art->thumbnail_url ?? 'https://picsum.photos/seed/oth'.$art->id.'/300/200' }}",
                title: "{{ $art->title }}",
                time: "{{ BengaliHelper::toBengaliTime($art->published_at) }}",
                views: "{{ BengaliHelper::toBengaliNumerals(number_format($art->view_count)) }}"
            },
            @endforeach
        ]
    };
    
    // Auto-trigger rendering cricket tab content on page load
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof renderSportsContent === 'function') {
            renderSportsContent('cricket');
        }
    });
</script>
@endsection
