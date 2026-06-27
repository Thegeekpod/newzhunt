@php
    use App\Helpers\BengaliHelper;
@endphp
<!DOCTYPE html>
<html lang="bn">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>@yield('title', $g_settings['site_name'] ?? 'নিউজহান্ট') – বাংলার প্রথম ডিজিটাল নিউজ পোর্টাল</title>
  <meta name="description" content="@yield('meta_description', 'নিউজহান্ট – বাংলাদেশ ও পশ্চিমবঙ্গের সর্বশেষ খবর, রাজনীতি, খেলা, বিনোদন, আন্তর্জাতিক এবং আরও অনেক কিছু।')">
  <meta name="keywords" content="@yield('keywords', 'নিউজহান্ট, বাংলা খবর, রাজনীতি, খেলা, বিনোদন, খবর')">
  
  <meta property="og:title" content="@yield('title', $g_settings['site_name'] ?? 'নিউজহান্ট')">
  <meta property="og:description" content="@yield('meta_description', 'সর্বশেষ বাংলা খবর পড়ুন নিউজহান্টে।')">
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:image" content="@yield('og_image', asset('assets/tech_future.png'))">

  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  @yield('styles')
</head>

<body>

  <!-- ===== TOP BAR ===== -->
  <div class="top-bar">
    <div class="container">
      <div class="top-bar-inner">
        <div class="top-bar-date">
          <i class="fas fa-calendar-alt"></i>
          <span id="current-date">{{ BengaliHelper::toBengaliDate(now()) }}</span>
        </div>
        <div class="top-bar-links">
          <a href="#">ই-পেপার</a>
          <a href="#">বিজ্ঞাপন</a>
          <a href="#">যোগাযোগ</a>
          <a href="#">আমাদের সম্পর্কে</a>
        </div>
        <div class="top-bar-social">
          <a href="{{ $g_settings['facebook_url'] ?? '#' }}" title="Facebook" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="{{ $g_settings['twitter_url'] ?? '#' }}" title="Twitter" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a>
          <a href="{{ $g_settings['youtube_url'] ?? '#' }}" title="YouTube" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
          <a href="{{ $g_settings['instagram_url'] ?? '#' }}" title="Instagram" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="{{ $g_settings['whatsapp_url'] ?? '#' }}" title="WhatsApp" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== HEADER ===== -->
  <header class="site-header" id="site-header">
    <div class="container">
      <div class="header-inner">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="site-logo" aria-label="নিউজহান্ট হোম">
          <div class="logo-name">
            <span>{{ mb_substr($g_settings['site_name'] ?? 'নিউজহান্ট', 0, 5) }}</span><span>{{ mb_substr($g_settings['site_name'] ?? 'নিউজহান্ট', 5) }}</span>
          </div>
          <div class="logo-tagline">Bengali News Portal</div>
        </a>

        <!-- Actions -->
        <div class="header-actions">
          <button class="btn-search" id="search-toggle" aria-label="অনুসন্ধান করুন">
            <i class="fas fa-search"></i>
          </button>
          <button class="btn-subscribe">সাবস্ক্রাইব করুন</button>
        </div>
      </div>
    </div>
  </header>

  <!-- ===== NAVIGATION ===== -->
  <nav class="main-nav" role="navigation" aria-label="প্রধান মেনু">
    <div class="container">
      <div class="nav-inner">
        <ul class="nav-menu" id="nav-menu">
          <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">হোম</a></li>
          @foreach($g_categories as $cat)
            <li class="{{ (isset($category) && $category->slug === $cat->slug) ? 'active' : '' }}">
                <a href="{{ route('category.show', $cat->slug) }}">{{ $cat->name_bn }}</a>
            </li>
          @endforeach
        </ul>
        <button class="nav-toggle" id="nav-toggle" aria-label="মেনু খুলুন" aria-expanded="false">
          <i class="fas fa-bars" style="font-size: 20px;"></i>
        </button>
      </div>
    </div>
  </nav>

  <!-- ===== BREAKING NEWS TICKER ===== -->
  <div class="breaking-ticker">
    <div class="container">
      <div class="ticker-inner">
        <div class="ticker-label">
          <span class="ticker-dot"></span>
          ব্রেকিং নিউজ
        </div>
        <div class="ticker-track">
          <div class="ticker-content" id="ticker">
            @foreach($g_tickers as $ticker)
              <a class="ticker-item" href="{{ $ticker->link_url ?? '#' }}">{{ $ticker->text_bn }}</a>
            @endforeach
            {{-- Duplicate for infinite loop --}}
            @foreach($g_tickers as $ticker)
              <a class="ticker-item" href="{{ $ticker->link_url ?? '#' }}">{{ $ticker->text_bn }}</a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== SEARCH OVERLAY ===== -->
  <div class="search-overlay" id="search-overlay" role="dialog" aria-modal="true" aria-label="অনুসন্ধান">
    <button class="search-close" id="search-close" aria-label="বন্ধ করুন">
      <i class="fas fa-times"></i>
    </button>
    <div class="search-box">
      <form class="search-input-wrap" role="search" action="{{ route('home') }}" method="GET">
        <input type="search" name="search" id="search-input" placeholder="কী খুঁজছেন? এখানে লিখুন..." aria-label="অনুসন্ধান" value="{{ request('search') }}">
        <button type="submit" aria-label="অনুসন্ধান করুন"><i class="fas fa-search"></i></button>
      </form>
    </div>
  </div>

  <!-- ===== MAIN CONTENT ===== -->
  <main id="main-content">
      @yield('content')
  </main>

  <!-- ===== FOOTER ===== -->
  <footer class="site-footer" role="contentinfo">
    <div class="footer-top">
      <div class="container">
        <div class="footer-grid">
          <!-- Brand -->
          <div class="footer-brand">
            <h2>{{ mb_substr($g_settings['site_name'] ?? 'নিউজহান্ট', 0, 5) }}<span>{{ mb_substr($g_settings['site_name'] ?? 'নিউজহান্ট', 5) }}</span></h2>
            <p class="footer-desc">{{ $g_settings['footer_about'] ?? 'নিউজহান্ট বাংলার একটি বিশ্বাসযোগ্য ডিজিটাল নিউজ পোর্টাল।' }}</p>
            <div class="footer-social">
              <a href="{{ $g_settings['facebook_url'] ?? '#' }}" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
              <a href="{{ $g_settings['twitter_url'] ?? '#' }}" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a>
              <a href="{{ $g_settings['youtube_url'] ?? '#' }}" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
              <a href="{{ $g_settings['instagram_url'] ?? '#' }}" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
              <a href="{{ $g_settings['whatsapp_url'] ?? '#' }}" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
          </div>

          <!-- Categories -->
          <div class="footer-col">
            <h3>বিভাগ</h3>
            <div class="footer-links">
              @foreach($g_categories->take(6) as $cat)
                <a href="{{ route('category.show', $cat->slug) }}">{{ $cat->name_bn }}</a>
              @endforeach
            </div>
          </div>

          <!-- Quick Links -->
          <div class="footer-col">
            <h3>দ্রুত লিংক</h3>
            <div class="footer-links">
              <a href="#">আমাদের সম্পর্কে</a>
              <a href="#">যোগাযোগ</a>
              <a href="#">বিজ্ঞাপন</a>
              <a href="#">ই-পেপার</a>
              <a href="#">ক্যারিয়ার</a>
            </div>
          </div>

          <!-- Legal -->
          <div class="footer-col">
            <h3>নীতিমালা</h3>
            <div class="footer-links">
              <a href="#">গোপনীয়তা নীতি</a>
              <a href="#">ব্যবহারের শর্ত</a>
              <a href="#">কপিরাইট নীতি</a>
              <a href="#">সংশোধনী নীতি</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="footer-bottom">
        <p class="copyright">© {{ BengaliHelper::toBengaliNumerals(date('Y')) }} {{ $g_settings['site_name'] ?? 'নিউজহান্ট' }}। সমস্ত অধিকার সংরক্ষিত।</p>
        <div class="footer-bottom-links">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
          <a href="#">Sitemap</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- ===== MOBILE NAV ===== -->
  <div class="mobile-nav" id="mobile-nav" role="dialog" aria-modal="true" aria-label="মোবাইল মেনু">
    <div class="mobile-nav-overlay" id="mobile-nav-overlay"></div>
    <div class="mobile-nav-panel">
      <div class="mobile-nav-header">
        <span class="mobile-nav-title">{{ $g_settings['site_name'] ?? 'নিউজহান্ট' }}</span>
        <button class="mobile-nav-close" id="mobile-nav-close" aria-label="মেনু বন্ধ করুন">✕</button>
      </div>
      <div class="mobile-menu-list">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">হোম</a>
        @foreach($g_categories as $cat)
          <a href="{{ route('category.show', $cat->slug) }}" class="{{ (isset($category) && $category->slug === $cat->slug) ? 'active' : '' }}">{{ $cat->name_bn }}</a>
        @endforeach
        <a href="#">যোগাযোগ</a>
      </div>
    </div>
  </div>

  <!-- ===== SCROLL TO TOP ===== -->
  <button class="scroll-top" id="scroll-top" aria-label="উপরে যান">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script src="{{ asset('js/app.js') }}"></script>
  
  <!-- AJAX and Interactive Scripts -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // CSRF AJAX header setup
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      // 1. AJAX Newsletter subscribe (Sidebar)
      const newsSubmitBtn = document.getElementById('sidebar-newsletter-submit');
      const newsInput = document.getElementById('sidebar-newsletter-email');
      const newsMsg = document.getElementById('sidebar-newsletter-msg');

      if (newsSubmitBtn && newsInput) {
        newsSubmitBtn.addEventListener('click', (e) => {
          e.preventDefault();
          const email = newsInput.value.trim();
          if (!email) {
            newsInput.style.border = '2px solid #e8101a';
            setTimeout(() => newsInput.style.border = '', 2000);
            return;
          }

          newsSubmitBtn.disabled = true;
          newsSubmitBtn.textContent = 'অপেক্ষা করুন...';

          fetch("{{ route('newsletter.subscribe') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ email: email })
          })
          .then(res => res.json())
          .then(data => {
            newsMsg.style.display = 'block';
            newsMsg.textContent = data.message;
            if (data.success) {
              newsMsg.style.color = '#16a34a';
              newsInput.value = '';
              newsSubmitBtn.textContent = '✓ সফল হয়েছে!';
              newsSubmitBtn.style.background = '#16a34a';
              setTimeout(() => {
                newsMsg.style.display = 'none';
                newsSubmitBtn.disabled = false;
                newsSubmitBtn.textContent = 'সাবস্ক্রাইব করুন';
                newsSubmitBtn.style.background = '';
              }, 3000);
            } else {
              newsMsg.style.color = '#e8101a';
              newsSubmitBtn.disabled = false;
              newsSubmitBtn.textContent = 'সাবস্ক্রাইব করুন';
            }
          })
          .catch(err => {
            newsMsg.style.display = 'block';
            newsMsg.style.color = '#e8101a';
            newsMsg.textContent = 'সার্ভারে সমস্যা হয়েছে, আবার চেষ্টা করুন।';
            newsSubmitBtn.disabled = false;
            newsSubmitBtn.textContent = 'সাবস্ক্রাইব করুন';
          });
        });
      }

      // 2. AJAX Opinion Poll voting
      const pollVoteBtn = document.getElementById('submit-poll-vote');
      if (pollVoteBtn) {
        pollVoteBtn.addEventListener('click', (e) => {
          e.preventDefault();
          const pollId = pollVoteBtn.dataset.pollId;
          const selectedOption = document.querySelector('input[name="poll_option"]:checked');

          if (!selectedOption) {
            alert('অনুগ্রহ করে একটি বিকল্প নির্বাচন করুন।');
            return;
          }

          const optionId = selectedOption.value;
          pollVoteBtn.disabled = true;
          pollVoteBtn.textContent = 'ভোট দেওয়া হচ্ছে...';

          fetch(`/poll/${pollId}/vote`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ option_id: optionId })
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              // Replace options structure with results layout
              const optionsContainer = document.querySelector(`.poll-widget .poll-options`);
              optionsContainer.classList.add('voted');
              
              let resultsHtml = '';
              data.options.forEach(opt => {
                resultsHtml += `
                  <div class="poll-option-result" style="margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 13px;">
                        <span class="poll-label">${opt.option_text}</span>
                        <span style="font-weight: 600;">${opt.pct_bn}</span>
                    </div>
                    <div class="poll-bar-wrap">
                      <div class="poll-bar" style="--pct: ${opt.pct}%"></div>
                    </div>
                  </div>
                `;
              });

              optionsContainer.innerHTML = resultsHtml;
              
              // Update total votes count
              const totalVotesEl = document.getElementById('poll-total-votes');
              if (totalVotesEl) {
                totalVotesEl.innerHTML = `<i class="fas fa-users"></i> ${data.total_votes_bn}`;
              }
              
              // Hide vote button
              pollVoteBtn.style.display = 'none';
            } else {
              alert(data.message);
              pollVoteBtn.disabled = false;
              pollVoteBtn.textContent = 'ভোট দিন';
            }
          })
          .catch(err => {
            alert('ভোট দিতে সমস্যা হয়েছে, আবার চেষ্টা করুন।');
            pollVoteBtn.disabled = false;
            pollVoteBtn.textContent = 'ভোট দিন';
          });
        });
      }
    });
  </script>
  @yield('scripts')
  @if(($g_settings['weather_auto_fetch'] ?? '0') === '1')
  <script>
    // Background weather refresh — runs after page is fully loaded
    window.addEventListener('load', function() {
      var toBengaliNum = function(str) {
        var map = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
        return String(str).replace(/[0-9]/g, function(d) { return map[d]; });
      };
      fetch('/weather/refresh', { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) { return r.json(); })
        .then(function(d) {
          var el = function(id) { return document.getElementById(id); };
          if (el('w-temp'))     el('w-temp').innerHTML = toBengaliNum(d.temp) + '<sup>°C</sup>';
          if (el('w-desc'))     el('w-desc').textContent = d.desc;
          if (el('w-humidity')) el('w-humidity').textContent = toBengaliNum(d.humidity);
          if (el('w-wind'))     el('w-wind').textContent = toBengaliNum(d.wind);
          if (el('w-high'))     el('w-high').textContent = toBengaliNum(d.high);
          if (el('w-low'))      el('w-low').textContent = toBengaliNum(d.low);
          if (el('w-location')) el('w-location').textContent = d.location;
        })
        .catch(function() {}); // Silently fail — stale DB data still shows
    });
  </script>
  @endif
</body>

</html>
