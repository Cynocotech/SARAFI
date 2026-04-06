<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>نرخ‌ها — {{ $office->name }}</title>
  <link rel="stylesheet" href="/css/fonts.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    /* ── Base ── */
    html, body {
      width: 100%; height: 100%;
      font-family: 'Vazirmatn', 'Yekan Bakh', sans-serif;
      font-weight: 400;
      overflow: hidden;
      -webkit-font-smoothing: antialiased;
      background: #06182c;
    }

    /* ── Rotation ── */
    body.rotate-90,
    body.rotate-270 {
      position: fixed;
      width: 100vh;
      height: 100vw;
      top: calc(50% - 50vw);
      left: calc(50% - 50vh);
    }
    body.rotate-90  { transform: rotate(90deg); }
    body.rotate-180 { transform: rotate(180deg); }
    body.rotate-270 { transform: rotate(270deg); }

    /* ── Signage wrap ── */
    .signage-wrap {
      position: relative;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      overflow: hidden;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
    .signage-wrap::before {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(165deg, rgba(6,24,44,0.92) 0%, rgba(15,23,42,0.88) 50%, rgba(6,24,44,0.92) 100%);
      pointer-events: none; z-index: 0;
    }
    .signage-wrap.has-custom-bg::before {
      background: linear-gradient(180deg, rgba(0,0,0,0.4) 0%, transparent 40%, transparent 60%, rgba(0,0,0,0.45) 100%);
    }
    .signage-wrap::after {
      content: '';
      position: absolute; inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120' viewBox='0 0 120 120'%3E%3Ccircle cx='60' cy='60' r='28' fill='none' stroke='%23fff' stroke-width='1.5' opacity='0.15'/%3E%3Ccircle cx='60' cy='60' r='18' fill='none' stroke='%23fff' stroke-width='1' opacity='0.12'/%3E%3Ctext x='60' y='65' text-anchor='middle' fill='%23fff' font-size='24' font-family='serif' opacity='0.12'%3E£%3C/text%3E%3C/svg%3E");
      background-repeat: repeat; opacity: 0.3; pointer-events: none; z-index: 0;
    }

    /* ── Scrollable content area ── */
    .signage-content {
      position: relative; z-index: 1;
      width: 100%; height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: clamp(0.75rem, 2vh, 2rem) clamp(0.75rem, 2vw, 2rem);
      padding-bottom: calc(clamp(0.75rem, 2vh, 2rem) + 40px); /* ticker space */
      gap: clamp(0.5rem, 1.5vh, 1.5rem);
      overflow: hidden;
    }

    /* ── Corner widgets ── */
    .signage-corner-tl {
      position: absolute; top: clamp(0.75rem,2vh,1.5rem); left: clamp(0.75rem,2vw,1.5rem);
      z-index: 2;
      display: flex; flex-direction: column; gap: 0.75rem; align-items: flex-start;
    }
    .signage-corner-tr {
      position: absolute; top: clamp(0.75rem,2vh,1.5rem); right: clamp(0.75rem,2vw,1.5rem);
      z-index: 2;
    }
    .signage-glass {
      background: rgba(255,255,255,0.07);
      backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 14px;
    }

    /* World time */
    .signage-world-time {
      color: rgba(255,255,255,0.9);
      padding: clamp(0.4rem,1vh,0.65rem) clamp(0.75rem,2vw,1.2rem);
      font-size: clamp(0.65rem, min(1.8vw,2.5vh), 0.95rem);
      font-weight: 500;
      text-align: right;
      line-height: 1.5;
    }
    .signage-world-time-title {
      font-size: 0.7em; font-weight: 700; text-transform: uppercase;
      letter-spacing: 0.12em; color: rgba(255,255,255,0.6); margin-bottom: 0.3rem;
    }
    .signage-world-time-row {
      display: flex; align-items: center; justify-content: flex-end;
      gap: 0.5rem; font-variant-numeric: tabular-nums;
    }
    .signage-world-time-label { color: rgba(255,255,255,0.7); }
    .signage-world-time-value { font-weight: 700; color: rgba(255,255,255,0.98); }

    /* QR + payment corner */
    .signage-payment-qr-row {
      display: flex; flex-direction: column; align-items: flex-start; gap: 0.75rem;
    }
    .signage-payment-methods {
      display: flex; align-items: center; flex-wrap: wrap;
      gap: 0.5rem 0.75rem;
      padding: clamp(0.5rem,1vh,0.9rem) clamp(0.75rem,1.5vw,1.25rem);
    }
    .signage-payment-methods-label {
      font-size: clamp(0.7rem, min(1.6vw,2vh), 0.9rem);
      font-weight: 700; color: rgba(255,255,255,0.7); letter-spacing: 0.03em;
    }
    .signage-payment-methods-list {
      display: flex; align-items: center; gap: clamp(0.5rem,1vw,0.85rem); flex-wrap: wrap;
    }
    .signage-payment-method-item { display: inline-flex; align-items: center; color: rgba(255,255,255,0.9); }
    .signage-qr-block {
      display: flex; flex-direction: column; align-items: center; gap: 0.4rem;
      padding: clamp(0.5rem,1vh,1rem) clamp(0.75rem,1.5vw,1.25rem);
    }
    .signage-qr-block img {
      width: clamp(70px, min(10vw,12vh), 130px);
      height: clamp(70px, min(10vw,12vh), 130px);
      border-radius: 8px; background: #fff; padding: 5px; display: block;
    }
    .signage-qr-label {
      font-size: clamp(0.6rem, min(1.5vw,1.8vh), 0.85rem);
      font-weight: 600; color: rgba(255,255,255,0.85); text-align: center;
    }
    .signage-qr-url {
      font-size: clamp(0.55rem, min(1.2vw,1.5vh), 0.75rem);
      color: rgba(255,255,255,0.7); direction: ltr; word-break: break-all;
      text-align: center; max-width: 160px; line-height: 1.3;
    }

    /* ── Brand ── */
    .signage-brand {
      display: flex; align-items: center; justify-content: center;
      gap: clamp(0.75rem, 2vw, 1.75rem); flex-wrap: wrap; flex-shrink: 0;
    }
    .signage-logo {
      width: clamp(48px, min(8vw,10vh), 110px);
      height: clamp(48px, min(8vw,10vh), 110px);
      border-radius: 16px; object-fit: cover;
      border: 2px solid rgba(255,255,255,0.2);
      box-shadow: 0 8px 32px rgba(0,0,0,0.35);
      flex-shrink: 0;
    }
    .signage-name {
      color: rgba(255,255,255,0.98);
      font-size: clamp(1.1rem, min(4vw,5vh), 2.75rem);
      font-weight: 700; letter-spacing: -0.02em;
      text-shadow: 0 2px 24px rgba(0,0,0,0.4);
    }

    /* ── Special rate banner ── */
    .signage-special-rate-banner {
      display: flex; align-items: center; flex-wrap: wrap;
      justify-content: center; gap: 0.4rem 1rem;
      padding: clamp(0.4rem,1vh,0.6rem) clamp(0.75rem,2vw,1.25rem);
      background: rgba(251,191,36,0.2);
      border: 1px solid rgba(251,191,36,0.5);
      border-radius: 12px;
      font-size: clamp(0.75rem, min(1.8vw,2.5vh), 1rem);
      color: rgba(255,255,255,0.95); flex-shrink: 0;
      animation: special-pulse 2s ease-in-out infinite;
    }
    @keyframes special-pulse {
      0%,100% { box-shadow: 0 0 0 0 rgba(251,191,36,0.2); }
      50%      { box-shadow: 0 0 18px 5px rgba(251,191,36,0.3); }
    }
    .signage-special-rate-item.buy  { color: #34d399; font-weight: 700; }
    .signage-special-rate-item.sell { color: #f87171; font-weight: 700; }

    /* ── Main row (rates + crypto side by side) ── */
    .signage-main-row {
      display: flex; flex-wrap: wrap; align-items: stretch;
      justify-content: center;
      gap: clamp(0.75rem, 2vw, 2rem);
      width: 100%; flex-shrink: 0;
    }

    /* ── Rate boxes ── */
    .signage-rates {
      display: flex; flex-wrap: wrap; gap: clamp(0.75rem,2vw,2rem);
      justify-content: center; align-items: stretch;
    }
    .signage-rate-box {
      background: rgba(255,255,255,0.07);
      backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
      padding: clamp(1rem,3vh,3rem) clamp(0.85rem,3vw,2.75rem);
      border-radius: 22px; text-align: center;
      border: 1px solid rgba(255,255,255,0.12);
      box-shadow: 0 16px 48px rgba(0,0,0,0.25);
      min-width: clamp(140px, 20vw, 300px);
      flex: 1 1 clamp(140px, 20vw, 300px);
      max-width: 380px;
    }
    .signage-rate-box.buy  { border-color: rgba(16,185,129,0.35); box-shadow: 0 16px 48px rgba(0,0,0,0.25), 0 0 40px -10px rgba(16,185,129,0.25); }
    .signage-rate-box.sell { border-color: rgba(239,68,68,0.3);   box-shadow: 0 16px 48px rgba(0,0,0,0.25), 0 0 40px -10px rgba(239,68,68,0.2); }
    .signage-rate-box-icon {
      width: clamp(1.75rem, min(4vw,5vh), 3rem);
      height: clamp(1.75rem, min(4vw,5vh), 3rem);
      margin: 0 auto 0.5rem;
      display: flex; align-items: center; justify-content: center;
    }
    .signage-rate-box-icon svg { width: 100%; height: 100%; }
    .signage-rate-box.buy  .signage-rate-box-icon { color: #34d399; }
    .signage-rate-box.sell .signage-rate-box-icon { color: #f87171; }
    .signage-rate-label {
      font-size: clamp(0.7rem, min(1.8vw,2.2vh), 0.95rem);
      font-weight: 600; margin-bottom: 0.5rem;
      color: rgba(255,255,255,0.7); letter-spacing: 0.02em;
      display: flex; align-items: center; justify-content: center; gap: 0.4rem;
    }
    .signage-rate-value {
      font-size: clamp(1.25rem, min(4vw,5vh), 2.75rem);
      font-weight: 800; letter-spacing: -0.02em;
      font-variant-numeric: tabular-nums; line-height: 1.2;
    }
    .signage-rate-box.buy  .signage-rate-value { color: #34d399; text-shadow: 0 0 30px rgba(52,211,153,0.3); }
    .signage-rate-box.sell .signage-rate-value { color: #f87171; text-shadow: 0 0 30px rgba(248,113,113,0.25); }
    .signage-rate-unit {
      font-size: clamp(0.65rem, min(1.6vw,2vh), 0.95rem);
      font-weight: 500; color: rgba(255,255,255,0.55); margin-top: 0.25rem;
    }
    .signage-empty {
      color: rgba(255,255,255,0.8);
      font-size: clamp(0.9rem, min(2.5vw,3.5vh), 1.35rem); font-weight: 500;
    }

    /* ── Crypto inline ── */
    .signage-crypto-inline {
      min-width: clamp(180px, 22vw, 360px);
      max-width: 380px; flex: 0 1 auto;
    }
    .signage-crypto-title, .signage-other-title {
      font-size: clamp(0.7rem, min(1.8vw,2.2vh), 1rem);
      font-weight: 700; color: rgba(255,255,255,0.75);
      margin: 0 0 0.5rem; letter-spacing: 0.05em;
    }
    .signage-crypto-list, .signage-other-list {
      background: rgba(255,255,255,0.06);
      backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 14px; overflow: hidden;
    }
    .signage-crypto-row, .signage-other-row {
      display: grid;
      align-items: center; gap: 0.5rem 0.75rem;
      padding: clamp(0.4rem,0.8vh,0.7rem) clamp(0.75rem,1.5vw,1.25rem);
      border-bottom: 1px solid rgba(255,255,255,0.06);
      font-size: clamp(0.7rem, min(1.6vw,2vh), 1rem);
    }
    .signage-crypto-row { grid-template-columns: 26px 1fr auto; }
    .signage-other-row  { grid-template-columns: 1fr 1fr 1fr; }
    .signage-crypto-row:last-child, .signage-other-row:last-child { border-bottom: none; }
    .signage-crypto-row.header, .signage-other-row.header {
      font-size: 0.75em; font-weight: 700;
      color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.08em;
    }
    .signage-crypto-icon {
      width: 24px; height: 24px; border-radius: 50%; object-fit: contain;
      background: rgba(255,255,255,0.08); flex-shrink: 0;
    }
    .signage-crypto-row.header .signage-crypto-icon { visibility: hidden; }
    .signage-crypto-name { font-weight: 700; color: rgba(255,255,255,0.95); }
    .signage-crypto-price-col { display: flex; flex-direction: column; align-items: flex-end; gap: 0.1rem; }
    .signage-crypto-price { color: #06b6d4; font-weight: 700; }
    .signage-crypto-change {
      display: inline-flex; align-items: center; gap: 0.2em;
      font-size: 0.85em; font-weight: 700;
    }
    .signage-crypto-change.up   { color: #34d399; }
    .signage-crypto-change.down { color: #f87171; }
    .signage-crypto-change-icon { width: 1em; height: 1em; flex-shrink: 0; }
    .signage-other-pair { font-weight: 700; color: rgba(255,255,255,0.95); }
    .signage-other-buy  { color: #34d399; font-weight: 700; }
    .signage-other-sell { color: #f87171; font-weight: 700; }

    /* ── Other rates section (below main row) ── */
    .signage-other-section {
      width: 100%; max-width: min(96%, 1100px); flex-shrink: 0;
    }

    /* ── Ticker ── */
    .signage-ticker-wrap {
      position: absolute; bottom: 0; left: 0; right: 0; z-index: 3;
      background: rgba(0,0,0,0.55);
      backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
      border-top: 1px solid rgba(255,255,255,0.1);
      overflow: hidden; direction: ltr;
      padding: clamp(0.3rem,0.7vh,0.5rem) 0;
    }
    .signage-ticker-inner {
      display: inline-block; white-space: nowrap; padding-right: 100%;
      animation: ticker 40s linear infinite;
      color: rgba(255,255,255,0.95);
      font-size: clamp(0.75rem, min(1.8vw,2.2vh), 1rem);
      font-weight: 500;
    }
    @keyframes ticker {
      0%   { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }

    /* ── Rate change animation ── */
    @keyframes rateNumberChange {
      0%   { opacity: 0.4; filter: blur(6px); }
      100% { opacity: 1;   filter: blur(0); }
    }
    .rate-number-animate { animation: rateNumberChange 0.5s ease-out forwards; }

    /* ── Portrait layout adjustments ──
       Applied when body has rotate-90 or rotate-270 (effective portrait view)
       In that state vw/vh are swapped relative to visible area, so we use vh-based sizing */
    body.rotate-90 .signage-content,
    body.rotate-270 .signage-content {
      padding: clamp(0.5rem,1.5vh,1rem) clamp(3.5rem,8vw,6rem); /* wider side padding for corner widgets */
    }
    body.rotate-90 .signage-main-row,
    body.rotate-270 .signage-main-row {
      flex-direction: column; align-items: center;
    }
    body.rotate-90 .signage-rates,
    body.rotate-270 .signage-rates {
      flex-direction: row; width: 100%; justify-content: center;
    }
    body.rotate-90 .signage-crypto-inline,
    body.rotate-270 .signage-crypto-inline {
      width: 100%; max-width: 100%;
    }

    /* ── Small screens / tablets ── */
    @media (max-width: 600px) {
      .signage-content { padding: 0.5rem; padding-bottom: 40px; gap: 0.5rem; }
      .signage-brand { gap: 0.5rem; }
      .signage-rates { gap: 0.75rem; }
      .signage-rate-box { min-width: 120px; padding: 0.85rem 0.75rem; border-radius: 14px; }
      .signage-corner-tl, .signage-corner-tr { display: none; } /* hide on very small screens */
    }
  </style>
</head>
<body class="@php echo match((int)($screen->rotation ?? 0)) { 90 => 'rotate-90', 180 => 'rotate-180', 270 => 'rotate-270', default => '' }; @endphp">
  <div class="signage-wrap @if(filled($screen->background_color) || $screen->background_image_path) has-custom-bg @endif"
       style="background-color:{{ $screen->background_color ?? '#06182c' }};@if($screen->backgroundImageUrl()) background-image:url('{{ $screen->backgroundImageUrl() }}');@endif">

    {{-- Top-right: world time --}}
    <div class="signage-corner-tr">
      <div class="signage-glass signage-world-time" id="signageWorldTime" aria-live="polite">
        <div class="signage-world-time-title">ساعت جهانی</div>
        <div class="signage-world-time-row"><span class="signage-world-time-label">UK</span><span class="signage-world-time-value" id="time-uk">—</span></div>
        <div class="signage-world-time-row"><span class="signage-world-time-label">ایران</span><span class="signage-world-time-value" id="time-iran">—</span></div>
      </div>
    </div>

    {{-- Top-left: payment methods + QR --}}
    @if((isset($paymentMethods) && count($paymentMethods) > 0) || (isset($qrLink) && $qrLink))
      <div class="signage-corner-tl">
        @if(isset($paymentMethods) && count($paymentMethods) > 0)
          <div class="signage-glass signage-payment-methods">
            <span class="signage-payment-methods-label">پرداخت با</span>
            <div class="signage-payment-methods-list">
              @foreach($paymentMethods as $pm)
                @include('partials.payment-method-logo', ['key' => $pm, 'size' => 30, 'class' => 'signage-payment-method-item'])
              @endforeach
            </div>
          </div>
        @endif
        @if(isset($qrLink) && $qrLink)
          <div class="signage-glass signage-qr-block">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrLink) }}&bgcolor=ffffff&color=06182c&format=svg" width="130" height="130" alt="QR code" loading="lazy">
            <span class="signage-qr-label">برای اطلاعات بیشتر اسکن کنید</span>
            <span class="signage-qr-url">{{ $qrLink }}</span>
          </div>
        @endif
      </div>
    @endif

    {{-- Main scrollable content --}}
    <div class="signage-content">
      {{-- Brand --}}
      <div class="signage-brand">
        @if($office->logoUrl())
          <img src="{{ $office->logoUrl() }}" alt="" class="signage-logo">
        @endif
        <h1 class="signage-name">{{ $office->name }}</h1>
      </div>

      {{-- Special rate --}}
      @if($office->hasSpecialRateToday())
        <div class="signage-special-rate-banner" aria-live="polite">
          <span style="font-weight:700;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:-2px;margin-inline-end:0.25em;color:rgba(251,191,36,0.95);"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            نرخ ویژه امروز
          </span>
          @if($office->special_rate_buy !== null)
            <span class="signage-special-rate-item buy">خرید {{ farsi_num(number_format($office->special_rate_buy, 0)) }} تومان</span>
          @endif
          @if($office->special_rate_sell !== null)
            <span class="signage-special-rate-item sell">فروش {{ farsi_num(number_format($office->special_rate_sell, 0)) }} تومان</span>
          @endif
        </div>
      @endif

      {{-- Rates + crypto --}}
      @if($rates->isNotEmpty())
        @php $gbpRate = $rates->first(); @endphp
        <div class="signage-main-row">
          <div class="signage-rates">
            <div class="signage-rate-box buy">
              <div class="signage-rate-box-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 15l-6-6-6 6"/></svg>
              </div>
              <div class="signage-rate-label">خرید پوند → تومان</div>
              <div class="signage-rate-value rate-number-animate">{{ farsi_num(number_format($gbpRate->buy_rate, 0)) }}</div>
              <div class="signage-rate-unit">تومان</div>
            </div>
            <div class="signage-rate-box sell">
              <div class="signage-rate-box-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
              </div>
              <div class="signage-rate-label">فروش پوند → تومان</div>
              <div class="signage-rate-value rate-number-animate">{{ farsi_num(number_format($gbpRate->sell_rate, 0)) }}</div>
              <div class="signage-rate-unit">تومان</div>
            </div>
          </div>

          @if(isset($cryptoRates) && count($cryptoRates) > 0)
            <div class="signage-crypto-inline">
              <h2 class="signage-crypto-title">نرخ ارزهای دیجیتال</h2>
              <div class="signage-crypto-list">
                <div class="signage-crypto-row header">
                  <span class="signage-crypto-icon"></span>
                  <span>ارز</span>
                  <span>نرخ (دلار) · ۲۴س</span>
                </div>
                @foreach($cryptoRates as $c)
                  @php $ch = $c['change_24h'] ?? null; $isUp = $ch !== null && $ch >= 0; @endphp
                  <div class="signage-crypto-row">
                    @if(!empty($c['icon_url']))
                      <img src="{{ $c['icon_url'] }}" alt="" class="signage-crypto-icon" width="24" height="24">
                    @else
                      <span class="signage-crypto-icon"></span>
                    @endif
                    <span class="signage-crypto-name">{{ $c['name'] }}</span>
                    <span class="signage-crypto-price-col">
                      <span class="signage-crypto-price rate-number-animate">$ {{ farsi_num(number_format($c['price_usd'], $c['price_usd'] >= 1 ? 0 : 2)) }}</span>
                      @if($ch !== null)
                        <span class="signage-crypto-change {{ $isUp ? 'up' : 'down' }} rate-number-animate">
                          @if($isUp)
                            <svg class="signage-crypto-change-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                          @else
                            <svg class="signage-crypto-change-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
                          @endif
                          {{ farsi_num(number_format(abs($ch), 1)) }}٪
                        </span>
                      @endif
                    </span>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      @else
        <p class="signage-empty">نرخ‌ها به‌زودی به‌روزرسانی می‌شوند.</p>
      @endif

      {{-- Other currency pairs --}}
      @if(isset($otherRates) && $otherRates->isNotEmpty())
        <div class="signage-other-section">
          <h2 class="signage-other-title">سایر جفت ارزها</h2>
          <div class="signage-other-list">
            <div class="signage-other-row header">
              <span>جفت ارز</span><span>خرید</span><span>فروش</span>
            </div>
            @foreach($otherRates as $r)
              <div class="signage-other-row">
                <span class="signage-other-pair">{{ $r->from_currency }} → {{ $r->to_currency }}</span>
                <span class="signage-other-buy">{{ farsi_num(number_format($r->buy_rate, 0)) }} تومان</span>
                <span class="signage-other-sell">{{ farsi_num(number_format($r->sell_rate, 0)) }} تومان</span>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>{{-- /signage-content --}}

    {{-- Ticker --}}
    @if(isset($tickerText) && filled($tickerText))
      <div class="signage-ticker-wrap" aria-live="polite">
        <div class="signage-ticker-inner">{{ $tickerText }}</div>
      </div>
    @endif
  </div>

  <script>
    (function() {
      // Auto-refresh every 60s
      var displayUrl = '{{ route("signage.display", ["token" => $screen->token]) }}';
      setInterval(function() { window.location.href = displayUrl; }, 60000);

      // World clock
      function getTimeInZone(tz) {
        return new Date().toLocaleString('en-GB', { timeZone: tz, hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
      }
      function toFarsi(str) {
        var fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        return String(str).replace(/\d/g, function(d) { return fa[+d]; });
      }
      function updateClock() {
        var uk = document.getElementById('time-uk');
        var ir = document.getElementById('time-iran');
        if (uk) uk.textContent = toFarsi(getTimeInZone('Europe/London'));
        if (ir) ir.textContent = toFarsi(getTimeInZone('Asia/Tehran'));
      }
      updateClock();
      setInterval(updateClock, 1000);

      // Report screen size to dashboard
      try {
        var res = window.screen.width + 'x' + window.screen.height;
        fetch('/signage/{{ $screen->token }}/report-size', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          body: JSON.stringify({ resolution: res })
        });
      } catch(e) {}
    })();
  </script>
</body>
</html>
