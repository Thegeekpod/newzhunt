<!DOCTYPE html>
<html lang="bn">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>শীঘ্রই আসছি – নিউজহান্ট</title>
  
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <style>
    body {
      background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%);
      color: #f8fafc;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
      margin: 0;
      padding: 20px;
    }
    
    .coming-soon-card {
      background: rgba(30, 41, 59, 0.7);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 40px;
      max-width: 600px;
      width: 100%;
      text-align: center;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }
    
    .logo {
      font-size: 36px;
      font-weight: 800;
      margin-bottom: 24px;
      display: inline-block;
      text-decoration: none;
    }
    
    .logo span:first-child {
      color: #ffffff;
      background: #e8101a;
      padding: 4px 12px;
      border-radius: 4px;
      margin-right: 4px;
    }
    
    .logo span:last-child {
      color: #ffffff;
    }
    
    h1 {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 16px;
      color: #f1f5f9;
    }
    
    p {
      color: #94a3b8;
      font-size: 15px;
      line-height: 1.6;
      margin-bottom: 32px;
    }
    
    .countdown {
      display: flex;
      justify-content: center;
      gap: 16px;
      margin-bottom: 32px;
    }
    
    .countdown-item {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 8px;
      padding: 12px;
      min-width: 80px;
    }
    
    .countdown-val {
      font-size: 24px;
      font-weight: 700;
      color: #38bdf8;
    }
    
    .countdown-label {
      font-size: 11px;
      color: #64748b;
      text-transform: uppercase;
      margin-top: 4px;
    }
    
    .subscribe-form {
      display: flex;
      gap: 10px;
      margin-bottom: 32px;
      max-width: 480px;
      margin-left: auto;
      margin-right: auto;
    }
    
    .subscribe-input {
      flex: 1;
      padding: 12px 16px;
      border-radius: 6px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      background: rgba(15, 23, 42, 0.6);
      color: #ffffff;
      font-size: 14px;
      outline: none;
      transition: border-color 0.2s;
    }
    
    .subscribe-input:focus {
      border-color: #38bdf8;
    }
    
    .subscribe-btn {
      padding: 12px 24px;
      border-radius: 6px;
      border: none;
      background: #e8101a;
      color: #ffffff;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    
    .subscribe-btn:hover {
      background: #c10d15;
    }
    
    .btn-home {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: #94a3b8;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: color 0.2s;
    }
    
    .btn-home:hover {
      color: #38bdf8;
    }
    
    .msg-box {
      margin-top: 10px;
      font-size: 13px;
      font-weight: 500;
    }
  </style>
</head>

<body>

  <div class="coming-soon-card">
    <a href="{{ route('home') }}" class="logo">
      <span>নিউজ</span><span>হান্ট</span>
    </a>
    
    <h1>বিভাগটি খুব শীঘ্রই আসছে!</h1>
    <p>আমাদের এই বিভাগটি প্রস্তুত করার কাজ চলছে। সর্বশেষ আপডেট পেতে এবং বিভাগটি চালু হওয়ার সাথে সাথে জানতে আপনার ইমেল দিয়ে সাবস্ক্রাইব করুন।</p>
    
    <div class="countdown">
      <div class="countdown-item">
        <div class="countdown-val" id="days">১২</div>
        <div class="countdown-label">দিন</div>
      </div>
      <div class="countdown-item">
        <div class="countdown-val" id="hours">২০</div>
        <div class="countdown-label">ঘণ্টা</div>
      </div>
      <div class="countdown-item">
        <div class="countdown-val" id="minutes">৪৫</div>
        <div class="countdown-label">মিনিট</div>
      </div>
    </div>
    
    <div class="subscribe-form">
      <input type="email" id="cs-email" placeholder="আপনার ইমেইল ঠিকানা" class="subscribe-input">
      <button id="cs-submit" class="subscribe-btn">সাবস্ক্রাইব</button>
    </div>
    <div class="msg-box" id="cs-msg" style="display: none;"></div>
    
    <div>
      <a href="{{ route('home') }}" class="btn-home">
        <i class="fas fa-arrow-left"></i> হোমপেজে ফিরে যান
      </a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // 1. Simple static countdown reduction simulation
      let days = 12, hours = 20, minutes = 45;
      
      setInterval(() => {
        minutes--;
        if (minutes < 0) {
          minutes = 59;
          hours--;
          if (hours < 0) {
            hours = 23;
            days--;
            if (days < 0) {
              days = 0; hours = 0; minutes = 0;
            }
          }
        }
        
        // Translate numbers to Bengali
        const bnNums = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const toBn = num => String(num).split('').map(d => bnNums[parseInt(d)] || d).join('');
        
        document.getElementById('days').textContent = toBn(days);
        document.getElementById('hours').textContent = toBn(hours);
        document.getElementById('minutes').textContent = toBn(minutes);
      }, 60000); // every minute
      
      // Translate initial values
      const bnNums = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
      const toBn = num => String(num).split('').map(d => bnNums[parseInt(d)] || d).join('');
      document.getElementById('days').textContent = toBn(days);
      document.getElementById('hours').textContent = toBn(hours);
      document.getElementById('minutes').textContent = toBn(minutes);

      // 2. AJAX Subscribe form
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const submitBtn = document.getElementById('cs-submit');
      const emailInput = document.getElementById('cs-email');
      const msgBox = document.getElementById('cs-msg');

      if (submitBtn && emailInput) {
        submitBtn.addEventListener('click', (e) => {
          e.preventDefault();
          const email = emailInput.value.trim();
          if (!email) {
            emailInput.style.borderColor = '#e8101a';
            setTimeout(() => emailInput.style.borderColor = '', 2000);
            return;
          }

          submitBtn.disabled = true;
          submitBtn.textContent = 'অপেক্ষা করুন...';

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
            msgBox.style.display = 'block';
            msgBox.textContent = data.message;
            if (data.success) {
              msgBox.style.color = '#10b981';
              emailInput.value = '';
              submitBtn.textContent = 'সফল!';
              submitBtn.style.background = '#10b981';
              setTimeout(() => {
                msgBox.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.textContent = 'সাবস্ক্রাইব';
                submitBtn.style.background = '';
              }, 3000);
            } else {
              msgBox.style.color = '#ef4444';
              submitBtn.disabled = false;
              submitBtn.textContent = 'সাবস্ক্রাইব';
            }
          })
          .catch(err => {
            msgBox.style.display = 'block';
            msgBox.style.color = '#ef4444';
            msgBox.textContent = 'সার্ভারে সমস্যা হয়েছে, আবার চেষ্টা করুন।';
            submitBtn.disabled = false;
            submitBtn.textContent = 'সাবস্ক্রাইব';
          });
        });
      }
    });
  </script>

</body>

</html>
