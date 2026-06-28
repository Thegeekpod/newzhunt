@extends('layouts.admin')

@section('title', 'Settings Panel')
@section('header_title', 'Settings Panel')

@section('content')
<div class="admin-card">
  <form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    
    <!-- General Settings Section -->
    <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
      <h3 class="card-title" style="font-size: 16px; color: #38bdf8; margin-bottom: 16px;">
        <i class="fas fa-sliders-h"></i> General Settings
      </h3>
      
      <div class="form-group">
        <label for="site_name" class="form-label">Site Name</label>
        <input type="text" name="site_name" id="site_name" class="form-control" value="{{ old('site_name', $settings['site_name'] ?? 'NewzHunt') }}" required>
      </div>

      <div class="form-group">
        <label for="footer_about" class="form-label">About Us (Footer Description)</label>
        <textarea name="footer_about" id="footer_about" class="form-control">{{ old('footer_about', $settings['footer_about'] ?? '') }}</textarea>
      </div>
    </div>

    <!-- Social Links & Counts Section -->
    <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
      <h3 class="card-title" style="font-size: 16px; color: #38bdf8; margin-bottom: 16px;">
        <i class="fas fa-share-nodes"></i> Social Network Links & Followers Count
      </h3>
      
      <div class="form-row form-row-2">
        <div class="form-group">
          <label for="facebook_url" class="form-label">Facebook URL</label>
          <input type="url" name="facebook_url" id="facebook_url" class="form-control" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}">
        </div>
        <div class="form-group">
          <label for="facebook_followers" class="form-label">Facebook Followers Count (e.g. 240K Followers)</label>
          <input type="text" name="facebook_followers" id="facebook_followers" class="form-control" value="{{ old('facebook_followers', $settings['facebook_followers'] ?? '') }}">
        </div>
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label for="youtube_url" class="form-label">YouTube URL</label>
          <input type="url" name="youtube_url" id="youtube_url" class="form-control" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}">
        </div>
        <div class="form-group">
          <label for="youtube_subscribers" class="form-label">YouTube Subscribers Count (e.g. 110K Subscribers)</label>
          <input type="text" name="youtube_subscribers" id="youtube_subscribers" class="form-control" value="{{ old('youtube_subscribers', $settings['youtube_subscribers'] ?? '') }}">
        </div>
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label for="twitter_url" class="form-label">Twitter URL</label>
          <input type="url" name="twitter_url" id="twitter_url" class="form-control" value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}">
        </div>
        <div class="form-group">
          <label for="twitter_followers" class="form-label">Twitter Followers Count (e.g. 87K Followers)</label>
          <input type="text" name="twitter_followers" id="twitter_followers" class="form-control" value="{{ old('twitter_followers', $settings['twitter_followers'] ?? '') }}">
        </div>
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label for="instagram_url" class="form-label">Instagram URL</label>
          <input type="url" name="instagram_url" id="instagram_url" class="form-control" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}">
        </div>
        <div class="form-group">
          <label for="whatsapp_url" class="form-label">WhatsApp Channel URL</label>
          <input type="url" name="whatsapp_url" id="whatsapp_url" class="form-control" value="{{ old('whatsapp_url', $settings['whatsapp_url'] ?? '') }}">
        </div>
      </div>
    </div>

    <!-- Weather configurations -->
    <div style="margin-bottom: 24px; padding-bottom: 10px;">
      <h3 class="card-title" style="font-size: 16px; color: #38bdf8; margin-bottom: 16px;">
        <i class="fas fa-cloud-sun-rain"></i> Weather Widget Settings
      </h3>

      <div class="form-row form-row-2" style="margin-bottom: 15px;">
        <div class="form-group">
          <label class="form-check" style="margin-top: 15px;">
            <input type="checkbox" name="weather_auto_fetch" id="weather_auto_fetch" value="1" {{ ($settings['weather_auto_fetch'] ?? '0') === '1' ? 'checked' : '' }}>
            <span>Auto-fetch Weather (ওয়েদার অটো-ফেচ করুন)</span>
          </label>
        </div>
        
        <div class="form-group">
          <label for="weather_api_key" class="form-label">WeatherAPI API Key (ওয়েদার এপিআই কী)</label>
          <div style="display: flex; gap: 10px;">
            <input type="text" name="weather_api_key" id="weather_api_key" class="form-control" value="{{ old('weather_api_key', $settings['weather_api_key'] ?? '') }}" placeholder="Enter WeatherAPI Key">
            <button type="button" id="test-weather-api" class="btn-admin btn-admin-secondary" style="white-space: nowrap; padding: 0 16px;">Test Connection</button>
          </div>
        </div>
      </div>
      
      <div class="form-row form-row-3">
        <div class="form-group">
          <label for="weather_location" class="form-label">Weather Location</label>
          <input type="text" name="weather_location" id="weather_location" class="form-control" value="{{ old('weather_location', $settings['weather_location'] ?? 'Kolkata, West Bengal') }}">
        </div>
        
        <div class="form-group">
          <label for="weather_temp" class="form-label">Temperature (°C - numbers only)</label>
          <input type="text" name="weather_temp" id="weather_temp" class="form-control" value="{{ old('weather_temp', $settings['weather_temp'] ?? '38') }}">
        </div>
        
        <div class="form-group">
          <label for="weather_desc" class="form-label">Weather Condition</label>
          <input type="text" name="weather_desc" id="weather_desc" class="form-control" value="{{ old('weather_desc', $settings['weather_desc'] ?? 'Partly Cloudy') }}">
        </div>
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label for="weather_humidity" class="form-label">Humidity (e.g. 72%)</label>
          <input type="text" name="weather_humidity" id="weather_humidity" class="form-control" value="{{ old('weather_humidity', $settings['weather_humidity'] ?? '') }}">
        </div>
        
        <div class="form-group">
          <label for="weather_wind" class="form-label">Wind Speed (e.g. 18 km/h)</label>
          <input type="text" name="weather_wind" id="weather_wind" class="form-control" value="{{ old('weather_wind', $settings['weather_wind'] ?? '') }}">
        </div>
      </div>

      <div class="form-row form-row-2">
        <div class="form-group">
          <label for="weather_high" class="form-label">Max Temperature (e.g. 41°C)</label>
          <input type="text" name="weather_high" id="weather_high" class="form-control" value="{{ old('weather_high', $settings['weather_high'] ?? '') }}">
        </div>
        
        <div class="form-group">
          <label for="weather_low" class="form-label">Min Temperature (e.g. 28°C)</label>
          <input type="text" name="weather_low" id="weather_low" class="form-control" value="{{ old('weather_low', $settings['weather_low'] ?? '') }}">
        </div>
      </div>
    </div>

    <!-- Article View Count Settings Section -->
    <div id="article-view-settings" style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
      <h3 class="card-title" style="font-size: 16px; color: #38bdf8; margin-bottom: 16px;">
        <i class="fas fa-eye"></i> Article View Count Settings
      </h3>
      
      <div class="form-row form-row-2">
        <div class="form-group">
          <label class="form-check" style="margin-top: 15px;">
            <input type="checkbox" name="hide_view_count" id="hide_view_count" value="1" {{ ($settings['hide_view_count'] ?? '0') === '1' ? 'checked' : '' }}>
            <span>Hide View Count on Frontend (ফ্রন্টএন্ডে ভিউ কাউন্ট লুকান)</span>
          </label>
        </div>
        
        <div class="form-group">
          <label for="view_count_offset" class="form-label">View Count Increase Offset (ভিউ কাউন্ট বৃদ্ধির মান)</label>
          <input type="number" name="view_count_offset" id="view_count_offset" class="form-control" value="{{ old('view_count_offset', $settings['view_count_offset'] ?? '200') }}" min="0" placeholder="e.g. 200">
        </div>
      </div>
    </div>

    <!-- Submit Form -->
    <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 20px;">
      <button type="submit" class="btn-admin btn-admin-primary">Save All Settings</button>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('weather_auto_fetch');
    const manualFields = [
      document.getElementById('weather_temp'),
      document.getElementById('weather_desc'),
      document.getElementById('weather_humidity'),
      document.getElementById('weather_wind'),
      document.getElementById('weather_high'),
      document.getElementById('weather_low')
    ];
    
    const updateFieldsState = () => {
      if (!toggle) return;
      const isAuto = toggle.checked;
      manualFields.forEach(field => {
        if (field) {
          field.readOnly = isAuto;
          if (isAuto) {
            field.style.opacity = '0.5';
            field.style.cursor = 'not-allowed';
          } else {
            field.style.opacity = '1';
            field.style.cursor = 'text';
          }
        }
      });
    };
    
    if (toggle) {
      toggle.addEventListener('change', updateFieldsState);
      updateFieldsState();
    }

    const showToast = (type, title, message) => {
      const existing = document.getElementById('alert-toast');
      if (existing) {
        existing.remove();
      }
      
      const toast = document.createElement('div');
      toast.className = 'alert-toast';
      toast.id = 'alert-toast';
      if (type === 'error') {
        toast.style.borderLeftColor = 'var(--danger)';
      }
      
      const icon = document.createElement('i');
      icon.className = type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-check-circle';
      icon.style.color = type === 'error' ? 'var(--danger)' : 'var(--success)';
      icon.style.fontSize = '20px';
      
      const content = document.createElement('div');
      content.innerHTML = `
        <div style="font-weight: 600; font-size: 14px;">${title}</div>
        <div style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">${message}</div>
      `;
      
      toast.appendChild(icon);
      toast.appendChild(content);
      document.body.appendChild(toast);
      
      setTimeout(() => {
        toast.style.animation = 'slideIn 0.3s ease reverse forwards';
        setTimeout(() => toast.remove(), 300);
      }, 4000);
    };

    const testBtn = document.getElementById('test-weather-api');
    if (testBtn) {
      testBtn.addEventListener('click', () => {
        const apiKey = document.getElementById('weather_api_key').value;
        const location = document.getElementById('weather_location').value;
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

        if (!apiKey) {
          showToast('error', 'Error!', 'Please enter an API Key first.');
          return;
        }

        testBtn.disabled = true;
        testBtn.innerText = 'Testing...';

        fetch('{{ route("admin.settings.test_weather_api") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({ api_key: apiKey, location: location })
        })
        .then(response => response.json())
        .then(data => {
          testBtn.disabled = false;
          testBtn.innerText = 'Test Connection';

          if (data.success) {
            showToast('success', 'Success!', data.message);
            if (data.data) {
              document.getElementById('weather_temp').value = data.data.temp;
              document.getElementById('weather_desc').value = data.data.desc;
              document.getElementById('weather_humidity').value = data.data.humidity;
              document.getElementById('weather_wind').value = data.data.wind;
              document.getElementById('weather_high').value = data.data.high;
              document.getElementById('weather_low').value = data.data.low;
              
              updateFieldsState();
            }
          } else {
            showToast('error', 'Error!', data.message);
          }
        })
        .catch(error => {
          testBtn.disabled = false;
          testBtn.innerText = 'Test Connection';
          showToast('error', 'Error!', 'An error occurred during verification.');
          console.error(error);
        });
      });
    }
  });
</script>
@endsection
