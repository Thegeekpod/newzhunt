@extends('layouts.app')

@section('title', $article->title)
@section('meta_description', $article->excerpt ?? Str::limit(strip_tags($article->content), 160))
@section('keywords', $article->keywords ?? $article->category->name_bn)
@section('og_type', 'article')
@section('og_image', $article->thumbnail_url ? (Str::startsWith($article->thumbnail_url, 'http') ? $article->thumbnail_url : url($article->thumbnail_url)) : asset('assets/tech_future.png'))

@section('og_article_meta')
  <meta property="article:published_time" content="{{ $article->published_at?->toIso8601String() }}">
  <meta property="article:modified_time" content="{{ $article->updated_at?->toIso8601String() }}">
  <meta property="article:author" content="{{ $article->author->name ?? 'নিউজহান্ট' }}">
  <meta property="article:section" content="{{ $article->category->name_bn }}">
  @if($article->tags->isNotEmpty())
    @foreach($article->tags as $tag)
      <meta property="article:tag" content="{{ $tag->name }}">
    @endforeach
  @endif
@endsection

@section('content')
@php
    use App\Helpers\BengaliHelper;

    // Build the ad banner
    $adBanner = '
        <div class="ad-banner-horizontal ad-slider-container" style="margin: 24px 0;">
          <span class="ad-banner-tag">বিজ্ঞাপন</span>
          <div class="ad-slider-wrapper">';
    if (isset($g_ads['article_body']) && $g_ads['article_body']->isNotEmpty()) {
        foreach ($g_ads['article_body'] as $index => $ad) {
            $activeClass = ($index === 0) ? ' active' : '';
            $adBanner .= '
              <a href="'.$ad->destination_url.'" class="ad-banner-link ad-slide'.$activeClass.'" style="height: 120px;" target="_blank">
                <img src="'.asset($ad->image_url).'" alt="বিজ্ঞাপন" loading="lazy">
              </a>';
        }
    } else {
        $adBanner .= '
              <a href="#" class="ad-banner-link ad-slide active" style="height: 120px;">
                <img src="'.asset('assets/ad_banner_premium.png').'" alt="বিজ্ঞাপন" loading="lazy">
              </a>';
    }
    $adBanner .= '
          </div>
        </div>
    ';

    // Insert ad after the second paragraph (</p>)
    $content = $article->content;
    $paragraphs = explode('</p>', $content);
    if (count($paragraphs) > 2) {
        $paragraphs[1] = $paragraphs[1] . $adBanner;
        // Re-join but avoid adding extra </p> to the last element if it was empty after explode
        $content = implode('</p>', $paragraphs);
    } else {
        $content = $content . $adBanner;
    }
@endphp

<!-- ===== ARTICLE PAGE ===== -->
<div class="container" style="margin-top: 20px;">
  <div class="main-layout">
    <div class="content-sidebar">

      <!-- === ARTICLE BODY === -->
      <div class="main-content-area">
        <article itemscope itemtype="https://schema.org/NewsArticle">

          <!-- Breadcrumb -->
          <nav class="article-breadcrumb" aria-label="ব্রেডক্রাম্ব" style="margin-bottom: 16px;">
            <a href="{{ route('home') }}">হোম</a>
            <span>›</span>
            <a href="{{ route('category.show', $article->category->slug) }}">{{ $article->category->name_bn }}</a>
            <span>›</span>
            <span>{{ mb_substr($article->title, 0, 30) }}...</span>
          </nav>

          <!-- AD BANNER AT TOP OF ARTICLE -->
          @if(isset($g_ads['article_top']) && $g_ads['article_top']->isNotEmpty())
          <div class="ad-banner-horizontal ad-slider-container" style="margin-bottom: 20px;">
            <span class="ad-banner-tag">বিজ্ঞাপন</span>
            <div class="ad-slider-wrapper">
              @foreach($g_ads['article_top'] as $ad)
                <a href="{{ $ad->destination_url }}" class="ad-banner-link ad-slide @if($loop->first) active @endif" target="_blank">
                  <img src="{{ asset($ad->image_url) }}" alt="বিজ্ঞাপন" loading="lazy">
                </a>
              @endforeach
            </div>
          </div>
          @endif

          <!-- Article Header -->
          <header class="article-header">
            <span class="article-category-tag" itemprop="articleSection">{{ $article->category->name_bn }}</span>
            <h1 class="article-title" itemprop="headline">{{ $article->title }}</h1>

            <div class="article-meta">
              <div class="article-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                <img src="{{ $article->author->avatar_url ?? 'https://picsum.photos/seed/author/100/100' }}" alt="{{ $article->author->name }}" class="author-avatar" loading="lazy">
                <div>
                  <div class="author-name" itemprop="name">{{ $article->author->name }}</div>
                  <div style="font-size: 11px; color: var(--text-light);">{{ $article->author->designation ?? 'সংবাদদাতা' }}</div>
                </div>
              </div>
              <div class="card-meta-item" style="font-size:13px; color: var(--text-light);">
                <i class="fas fa-calendar-alt"></i>
                <time itemprop="datePublished" datetime="{{ $article->published_at->format('Y-m-d') }}">{{ BengaliHelper::toBengaliDate($article->published_at) }}</time>
              </div>
              <div class="card-meta-item" style="font-size:13px; color: var(--text-light);">
                <i class="fas fa-clock"></i>
                <span>{{ BengaliHelper::toBengaliTime($article->published_at) }}</span>
              </div>
              <div class="card-meta-item" style="font-size:13px; color: var(--text-light);">
                <i class="fas fa-eye"></i>
                <span>{{ BengaliHelper::toBengaliNumerals(number_format($article->display_view_count)) }}</span>
              </div>
              <div class="card-meta-item" style="font-size:13px; color: var(--text-light);">
                <i class="fas fa-book-open"></i>
                <span>{{ BengaliHelper::estimateReadTime($article->content) }}</span>
              </div>
              
              <!-- Share buttons -->
              <div class="article-share">
                <span style="font-weight: 600; color: var(--text-dark);">শেয়ার:</span>
                <button class="share-btn share-fb" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(window.location.href), '_blank')" aria-label="Facebook-এ শেয়ার করুন">
                  <i class="fab fa-facebook-f"></i> Facebook
                </button>
                <button class="share-btn share-tw" onclick="window.open('https://twitter.com/intent/tweet?url='+encodeURIComponent(window.location.href), '_blank')" aria-label="Twitter-এ শেয়ার করুন">
                  <i class="fab fa-x-twitter"></i>
                </button>
                <button class="share-btn share-wa" onclick="window.open('https://api.whatsapp.com/send?text='+encodeURIComponent(window.location.href), '_blank')" aria-label="WhatsApp-এ শেয়ার করুন">
                  <i class="fab fa-whatsapp"></i>
                </button>
              </div>
            </div>
          </header>

          <!-- Featured Image -->
          <img src="{{ $article->thumbnail_url ?? 'https://picsum.photos/seed/article-main/1200/630' }}" alt="{{ $article->title }}" class="article-featured-img" itemprop="image" loading="eager">
          <p class="article-img-caption">ছবি: {{ $article->title }} (প্রতীকী ছবি)</p>

          <!-- Article Content -->
          <div class="article-body" itemprop="articleBody">
              {!! $content !!}
          </div>

          <!-- Article Ads Grid (Bottom) -->
          @if((isset($g_ads['article_bottom_1']) && $g_ads['article_bottom_1']->isNotEmpty()) || (isset($g_ads['article_bottom_2']) && $g_ads['article_bottom_2']->isNotEmpty()))
          <div class="article-ads-grid">
            @if(isset($g_ads['article_bottom_1']) && $g_ads['article_bottom_1']->isNotEmpty())
            <div class="ad-banner-horizontal ad-slider-container">
              <span class="ad-banner-tag">বিজ্ঞাপন</span>
              <div class="ad-slider-wrapper">
                @foreach($g_ads['article_bottom_1'] as $ad)
                  <a href="{{ $ad->destination_url }}" class="ad-banner-link ad-slide @if($loop->first) active @endif" style="height: 140px;" target="_blank">
                    <img src="{{ asset($ad->image_url) }}" alt="বিজ্ঞাপন" loading="lazy">
                  </a>
                @endforeach
              </div>
            </div>
            @endif
            @if(isset($g_ads['article_bottom_2']) && $g_ads['article_bottom_2']->isNotEmpty())
            <div class="ad-banner-horizontal ad-slider-container">
              <span class="ad-banner-tag">বিজ্ঞাপন</span>
              <div class="ad-slider-wrapper">
                @foreach($g_ads['article_bottom_2'] as $ad)
                  <a href="{{ $ad->destination_url }}" class="ad-banner-link ad-slide @if($loop->first) active @endif" style="height: 140px;" target="_blank">
                    <img src="{{ asset($ad->image_url) }}" alt="বিজ্ঞাপন" loading="lazy">
                  </a>
                @endforeach
              </div>
            </div>
            @endif
          </div>
          @endif

          <!-- Article Tags -->
          @if($article->tags->isNotEmpty())
            <div class="article-tags">
              <span class="tags-label">ট্যাগ:</span>
              @foreach($article->tags as $tag)
                <a href="{{ route('tag.show', $tag->slug) }}" class="tag-item">{{ $tag->name_bn }}</a>
              @endforeach
            </div>
          @endif

          <!-- Related News -->
          @if($relatedArticles->isNotEmpty())
            <div class="related-section">
              <div class="section-header" style="margin-bottom: 16px;">
                <h2 class="section-title">সম্পর্কিত সংবাদ</h2>
              </div>
              <div class="cards-grid-3">
                @foreach($relatedArticles as $relArt)
                  <article class="news-card">
                    <div class="card-img-wrap">
                      <a href="{{ route('article.show', $relArt->slug) }}">
                        <img src="{{ $relArt->thumbnail_url ?? 'https://picsum.photos/seed/rel'.$relArt->id.'/500/320' }}" alt="{{ $relArt->title }}" loading="lazy">
                      </a>
                      <span class="card-category">{{ $relArt->category->name_bn }}</span>
                    </div>
                    <div class="card-body">
                      <a href="{{ route('article.show', $relArt->slug) }}">
                        <h3 class="card-title">{{ $relArt->title }}</h3>
                      </a>
                      <div class="card-meta">
                        <span class="card-meta-item"><i class="fas fa-clock"></i> {{ BengaliHelper::toBengaliTime($relArt->published_at) }}</span>
                      </div>
                    </div>
                  </article>
                @endforeach
              </div>
            </div>
          @endif

        </article>
      </div><!-- /main-content-area -->

      <!-- === SIDEBAR === -->
      <aside class="sidebar" aria-label="সাইডবার">
          @include('partials.sidebar')
      </aside>

    </div><!-- /content-sidebar -->
  </div><!-- /container -->
</div><!-- /main-layout -->
<style>
  @media (max-width:768px) {
    img {
    height: auto !important;
    width: auto;
}
  }
  </style>
@endsection
