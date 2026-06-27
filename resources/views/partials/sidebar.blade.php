@php
    use App\Helpers\BengaliHelper;
    $hasVoted = isset($g_poll) && request()->cookie('voted_poll_' . $g_poll->id) == 'true';
    $sidebarTags = \App\Models\Tag::take(12)->get();
@endphp

<!-- Weather Widget -->
<div class="weather-widget" id="weather-widget">
  <div class="weather-header">
    <div>
      <div class="weather-location" id="w-location">{{ $g_settings['weather_location'] ?? 'কলকাতা, পশ্চিমবঙ্গ' }}</div>
      <div class="weather-date" id="weather-date">{{ BengaliHelper::toBengaliDate(now()) }}</div>
    </div>
    <i class="fas fa-cloud-sun" style="font-size: 28px; opacity: 0.8;"></i>
  </div>
  <div class="weather-main">
    <div class="weather-icon">☀️</div>
    <div>
      <div class="weather-temp" id="w-temp">{{ BengaliHelper::toBengaliNumerals($g_settings['weather_temp'] ?? '৩৮') }}<sup>°C</sup></div>
      <div class="weather-desc" id="w-desc">{{ $g_settings['weather_desc'] ?? 'আংশিক মেঘলা' }}</div>
    </div>
  </div>
  <div class="weather-details">
    <div class="weather-detail-item"><span>আর্দ্রতা</span><span id="w-humidity">{{ BengaliHelper::toBengaliNumerals($g_settings['weather_humidity'] ?? '৭২%') }}</span></div>
    <div class="weather-detail-item"><span>বায়ু গতি</span><span id="w-wind">{{ BengaliHelper::toBengaliNumerals($g_settings['weather_wind'] ?? '১৮ km/h') }}</span></div>
    <div class="weather-detail-item"><span>সর্বোচ্চ</span><span id="w-high">{{ BengaliHelper::toBengaliNumerals($g_settings['weather_high'] ?? '৪১°C') }}</span></div>
    <div class="weather-detail-item"><span>সর্বনিম্ন</span><span id="w-low">{{ BengaliHelper::toBengaliNumerals($g_settings['weather_low'] ?? '২৮°C') }}</span></div>
  </div>
</div>

<!-- Popular News Widget -->
<div class="widget">
  <div class="widget-header">
    <div class="widget-header-left">
      <span class="widget-header-icon"><i class="fas fa-fire"></i></span>
      সবচেয়ে জনপ্রিয়
    </div>
    <a href="#" class="widget-header-link">সব দেখুন <i class="fas fa-chevron-right" style="font-size:9px;"></i></a>
  </div>
  <div class="widget-body">
    <div class="popular-list">
      @foreach($g_popular as $index => $p_art)
      <a href="{{ route('article.show', $p_art->slug) }}" class="popular-item">
        <span class="popular-num">{{ BengaliHelper::toBengaliNumerals($index + 1) }}</span>
        <div>
          <div class="popular-title">{{ $p_art->title }}</div>
          <div class="popular-meta"><i class="fas fa-eye"></i> {{ BengaliHelper::toBengaliNumerals(number_format($p_art->view_count)) }} পাঠক</div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</div>

<!-- Ad Widget -->
@if(isset($g_ads['sidebar_square']) && $g_ads['sidebar_square']->isNotEmpty())
<div class="widget ad-widget">
  <div class="ad-slider-container">
    <div class="ad-slider-wrapper">
      @foreach($g_ads['sidebar_square'] as $ad)
        <a href="{{ $ad->destination_url }}" target="_blank" class="ad-slide @if($loop->first) active @endif">
          <img src="{{ asset($ad->image_url) }}" alt="Ad Banner" style="width: 100%; border-radius: 6px;">
        </a>
      @endforeach
    </div>
  </div>
</div>
@endif

<!-- Latest News Widget -->
<div class="widget">
  <div class="widget-header">
    <div class="widget-header-left">
      <span class="widget-header-icon"><i class="fas fa-bolt"></i></span>
      সর্বশেষ সংবাদ
    </div>
    <a href="#" class="widget-header-link">সব <i class="fas fa-chevron-right" style="font-size:9px;"></i></a>
  </div>
  <div class="widget-body">
    @foreach($g_latest as $l_art)
    <a href="{{ route('article.show', $l_art->slug) }}" class="latest-item">
      <div class="latest-thumb-wrap">
        <img src="{{ $l_art->thumbnail_url ?? 'https://picsum.photos/seed/'.$l_art->id.'/200/150' }}" alt="{{ $l_art->title }}" class="latest-thumb" loading="lazy">
      </div>
      <div>
        <div class="latest-title">{{ $l_art->title }}</div>
        <div class="latest-time"><i class="fas fa-clock"></i> {{ BengaliHelper::toBengaliTime($l_art->published_at) }}</div>
      </div>
    </a>
    @endforeach
  </div>
</div>

<!-- Tags Widget -->
<div class="widget">
  <div class="widget-header">
    <div class="widget-header-left">
      <span class="widget-header-icon"><i class="fas fa-hashtag"></i></span>
      জনপ্রিয় ট্যাগ
    </div>
  </div>
  <div class="widget-body">
    <div class="tag-cloud">
      @foreach($sidebarTags as $tag)
      <a href="{{ route('tag.show', $tag->slug) }}" class="tag-item">{{ $tag->name_bn }}</a>
      @endforeach
    </div>
  </div>
</div>

<!-- Newsletter Widget -->
<div class="newsletter-widget" id="sidebar-newsletter-box">
  <div class="newsletter-icon"><i class="fas fa-envelope-open-text"></i></div>
  <div class="newsletter-title">নিউজলেটার</div>
  <div class="newsletter-desc">প্রতিদিন সকালে সেরা খবর আপনার ইমেইলে পৌঁছে যাবে</div>
  <input type="email" id="sidebar-newsletter-email" placeholder="আপনার ইমেইল ঠিকানা" class="newsletter-input">
  <button class="newsletter-btn" id="sidebar-newsletter-submit">সাবস্ক্রাইব করুন</button>
  <div class="newsletter-message" id="sidebar-newsletter-msg" style="margin-top: 10px; font-size: 13px; font-weight: 500; display: none;"></div>
</div>

<div class="sidebar-sticky-group" style="display: flex; flex-direction: column; gap: 20px;">
  <!-- Opinion Poll Widget -->
  @if(isset($g_poll))
  <div class="widget">
    <div class="widget-header">
      <div class="widget-header-left">
        <span class="widget-header-icon"><i class="fas fa-poll"></i></span>
        মতামত জরিপ
      </div>
    </div>
    <div class="widget-body poll-widget" id="poll-container-{{ $g_poll->id }}">
      <p class="poll-question">{{ $g_poll->question }}</p>
      
      <div class="poll-options {{ $hasVoted ? 'voted' : '' }}">
        @foreach($g_poll->options as $option)
          @php
              $pct = $g_poll->total_votes > 0 ? round(($option->vote_count / $g_poll->total_votes) * 100) : 0;
              $pctBn = BengaliHelper::toBengaliNumerals($pct) . '%';
          @endphp
          
          @if($hasVoted)
            <div class="poll-option-result" style="margin-bottom: 12px;">
              <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 13px;">
                  <span class="poll-label">{{ $option->option_text }}</span>
                  <span style="font-weight: 600;">{{ $pctBn }}</span>
              </div>
              <div class="poll-bar-wrap">
                <div class="poll-bar" style="--pct: {{ $pct }}%"></div>
              </div>
            </div>
          @else
            <label class="poll-option" data-vote="{{ $option->id }}">
              <input type="radio" name="poll_option" value="{{ $option->id }}">
              <span class="poll-label">{{ $option->option_text }}</span>
              <div class="poll-bar-wrap" style="display: none;">
                <div class="poll-bar" style="--pct: 0%"><span>০%</span></div>
              </div>
            </label>
          @endif
        @endforeach
      </div>
      
      <div class="poll-footer">
        <span class="poll-total" id="poll-total-votes"><i class="fas fa-users"></i> {{ BengaliHelper::toBengaliNumerals(number_format($g_poll->total_votes)) }} vote</span>
        @if(!$hasVoted)
          <button class="poll-vote-btn" id="submit-poll-vote" data-poll-id="{{ $g_poll->id }}">ভোট দিন</button>
        @endif
      </div>
    </div>
  </div>
  @endif

  <!-- Social Follow Widget -->
  <div class="widget">
    <div class="widget-header">
      <div class="widget-header-left">
        <span class="widget-header-icon"><i class="fas fa-share-alt"></i></span>
        আমাদের ফলো করুন
      </div>
    </div>
    <div class="widget-body social-follow-body">
      <a href="{{ $g_settings['facebook_url'] ?? '#' }}" class="social-follow-item facebook" target="_blank">
        <div class="social-follow-icon"><i class="fab fa-facebook-f"></i></div>
        <div class="social-follow-info">
          <span class="social-name">Facebook</span>
          <span class="social-count">{{ $g_settings['facebook_followers'] ?? '২.৪ লক্ষ ফলোয়ার' }}</span>
        </div>
        <span class="social-follow-btn">ফলো</span>
      </a>
      <a href="{{ $g_settings['youtube_url'] ?? '#' }}" class="social-follow-item youtube" target="_blank">
        <div class="social-follow-icon"><i class="fab fa-youtube"></i></div>
        <div class="social-follow-info">
          <span class="social-name">YouTube</span>
          <span class="social-count">{{ $g_settings['youtube_subscribers'] ?? '১.১ লক্ষ সাবস্ক্রাইবার' }}</span>
        </div>
        <span class="social-follow-btn">সাবস্ক্রাইব</span>
      </a>
      <a href="{{ $g_settings['twitter_url'] ?? '#' }}" class="social-follow-item twitter" target="_blank">
        <div class="social-follow-icon"><i class="fab fa-x-twitter"></i></div>
        <div class="social-follow-info">
          <span class="social-name">X (Twitter)</span>
          <span class="social-count">{{ $g_settings['twitter_followers'] ?? '৮৭ হাজার ফলোয়ার' }}</span>
        </div>
        <span class="social-follow-btn">ফলো</span>
      </a>
    </div>
  </div>
</div>
