<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>اتصال صفحه نمایش — آقای صرافی</title>
  <link rel="stylesheet" href="/css/fonts.css">
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Vazirmatn', sans-serif;
      min-height: 100vh;
      min-height: 100dvh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(165deg, #06182c 0%, #0f172a 50%, #06182c 100%);
      color: rgba(255,255,255,0.95);
      padding: 2rem;
      -webkit-font-smoothing: antialiased;
    }
    .pairing-card {
      text-align: center;
      max-width: 420px;
      width: 100%;
    }
    .pairing-title {
      font-size: clamp(1.25rem, 4vw, 1.75rem);
      font-weight: 700;
      margin: 0 0 0.5rem 0;
      letter-spacing: -0.02em;
    }
    .pairing-subtitle {
      font-size: clamp(0.9rem, 2.2vw, 1rem);
      color: rgba(255,255,255,0.7);
      margin: 0 0 2rem 0;
      line-height: 1.5;
    }
    .pairing-qr-wrap {
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.15);
      border-radius: 24px;
      padding: 2rem;
      margin-bottom: 1.5rem;
      display: inline-block;
    }
    .pairing-qr-wrap img {
      display: block;
      width: clamp(220px, 50vw, 320px);
      height: auto;
      border-radius: 12px;
    }
    .pairing-code {
      font-size: 1.1rem;
      font-weight: 600;
      letter-spacing: 0.2em;
      color: rgba(255,255,255,0.9);
      margin-top: 0.5rem;
    }
    .pairing-steps {
      text-align: right;
      font-size: 0.95rem;
      color: rgba(255,255,255,0.8);
      line-height: 1.8;
      margin: 0;
    }
    .pairing-steps strong { color: #fff; }
  </style>
</head>
<body>
  <div class="pairing-card">
    <h1 class="pairing-title">اتصال صفحه نمایش به پنل صرافی</h1>
    <p class="pairing-subtitle">این کد QR را با گزینه «اتصال با اسکن QR» در پنل صرافی خود اسکن کنید.</p>
    <div class="pairing-qr-wrap">
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=320x320&data={{ urlencode($display_url) }}&format=svg" alt="QR code for pairing" width="320" height="320">
      <div class="pairing-code">کد: {{ $screen->pairing_code }}</div>
    </div>
    <ol class="pairing-steps">
      <li>وارد <strong>پنل صرافی</strong> شوید.</li>
      <li>به بخش <strong>صفحه نمایش دیجیتال</strong> بروید.</li>
      <li>روی <strong>اتصال با اسکن QR</strong> بزنید و دوربین را به این کد بگیرید.</li>
    </ol>
  </div>
  <script>
    (function() {
      var displayUrl = '{{ $display_url }}';
      setInterval(function() { window.location.href = displayUrl; }, 60000);
    })();
  </script>
</body>
</html>
