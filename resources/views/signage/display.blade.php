<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>نرخ‌ها — {{ $office->name }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Vazirmatn', sans-serif;
      font-weight: 400;
      overflow: hidden;
      min-height: 100vh;
      min-height: 100dvh;
      -webkit-font-smoothing: antialiased;
    }
    .signage-wrap {
      position: relative;
      width: 100vw;
      height: 100vh;
      height: 100dvh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 3rem 2rem;
      padding-left: min(280px, 22vw);
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
    .signage-wrap::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(165deg, rgba(6, 24, 44, 0.92) 0%, rgba(15, 23, 42, 0.88) 50%, rgba(6, 24, 44, 0.92) 100%);
      pointer-events: none;
    }
    .signage-wrap.has-custom-bg::before {
      background: linear-gradient(180deg, rgba(0,0,0,0.35) 0%, transparent 40%, transparent 60%, rgba(0,0,0,0.4) 100%);
    }
    .signage-wrap::after {
      content: '';
      position: absolute;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120' viewBox='0 0 120 120'%3E%3Ccircle cx='60' cy='60' r='28' fill='none' stroke='%23fff' stroke-width='1.5' opacity='0.15'/%3E%3Ccircle cx='60' cy='60' r='18' fill='none' stroke='%23fff' stroke-width='1' opacity='0.12'/%3E%3Ctext x='60' y='65' text-anchor='middle' fill='%23fff' font-size='24' font-family='serif' opacity='0.12'%3E£%3C/text%3E%3C/svg%3E");
      background-repeat: repeat;
      opacity: 0.3;
      pointer-events: none;
    }
    .signage-wrap.has-custom-bg::after {
      opacity: 0.25;
    }
    .signage-pairing {
      position: absolute;
      top: 1.5rem;
      left: 1.5rem;
      background: rgba(255,255,255,0.06);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      color: rgba(255,255,255,0.85);
      padding: 0.6rem 1.1rem;
      border-radius: 14px;
      font-size: clamp(0.8rem, 2.2vw, 1rem);
      font-weight: 600;
      letter-spacing: 0.2em;
      border: 1px solid rgba(255,255,255,0.12);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    .signage-pairing-qr {
      width: 52px;
      height: 52px;
      border-radius: 8px;
      background: #fff;
      padding: 4px;
      flex-shrink: 0;
    }
    .signage-pairing-qr img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      display: block;
    }
    .signage-pairing-label {
      font-size: 0.7em;
      font-weight: 500;
      letter-spacing: 0.15em;
      opacity: 0.75;
      margin-bottom: 0.2rem;
    }
    .signage-world-time {
      position: absolute;
      top: 1.5rem;
      right: 1.5rem;
      background: rgba(255,255,255,0.06);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      color: rgba(255,255,255,0.9);
      padding: 0.65rem 1.2rem;
      border-radius: 14px;
      font-size: clamp(0.75rem, 2vw, 0.95rem);
      font-weight: 500;
      border: 1px solid rgba(255,255,255,0.12);
      text-align: right;
      line-height: 1.5;
    }
    .signage-world-time-title {
      font-size: 0.7em;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.12em;
      color: rgba(255,255,255,0.6);
      margin-bottom: 0.35rem;
    }
    .signage-world-time-row {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 0.6rem;
      font-variant-numeric: tabular-nums;
    }
    .signage-world-time-label { color: rgba(255,255,255,0.7); }
    .signage-world-time-value { font-weight: 700; color: rgba(255,255,255,0.98); }
    .signage-brand {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1.75rem;
      margin-bottom: 3rem;
      flex-wrap: wrap;
      z-index: 1;
    }
    .signage-logo {
      width: clamp(72px, 14vw, 140px);
      height: clamp(72px, 14vw, 140px);
      border-radius: 20px;
      object-fit: cover;
      border: 2px solid rgba(255,255,255,0.2);
      box-shadow: 0 8px 40px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.05) inset;
    }
    .signage-name {
      color: rgba(255,255,255,0.98);
      font-size: clamp(1.5rem, 4.5vw, 3rem);
      font-weight: 700;
      margin: 0;
      letter-spacing: -0.02em;
      text-shadow: 0 2px 24px rgba(0,0,0,0.4);
    }
    .signage-special-rate-banner {
      position: relative;
      z-index: 1;
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      justify-content: center;
      gap: 0.5rem 1.25rem;
      padding: 0.6rem 1.25rem;
      margin-bottom: 1.5rem;
      background: rgba(251, 191, 36, 0.2);
      border: 1px solid rgba(251, 191, 36, 0.5);
      border-radius: 14px;
      font-size: clamp(0.85rem, 2.2vw, 1.1rem);
      color: rgba(255,255,255,0.95);
      animation: signage-special-rate-pulse 2s ease-in-out infinite;
    }
    @keyframes signage-special-rate-pulse {
      0%, 100% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.2); opacity: 1; }
      50% { box-shadow: 0 0 20px 6px rgba(251, 191, 36, 0.35); opacity: 0.95; }
    }
    .signage-special-rate-label {
      font-weight: 700;
      letter-spacing: 0.03em;
    }
    .signage-special-rate-icon {
      width: 1.1em;
      height: 1.1em;
      vertical-align: -0.2em;
      margin-inline-end: 0.25em;
      color: rgba(251, 191, 36, 0.95);
    }
    .signage-special-rate-item.buy { color: #34d399; font-weight: 700; }
    .signage-special-rate-item.sell { color: #f87171; font-weight: 700; }
    .signage-rates {
      position: relative;
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      justify-content: center;
      align-items: stretch;
      z-index: 1;
    }
    .signage-rate-box {
      background: rgba(255,255,255,0.07);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      padding: clamp(2rem, 5vw, 3.5rem) clamp(1.75rem, 4.5vw, 3rem);
      border-radius: 24px;
      text-align: center;
      border: 1px solid rgba(255,255,255,0.12);
      box-shadow: 0 16px 48px rgba(0,0,0,0.25), 0 0 0 1px rgba(255,255,255,0.04) inset;
      min-width: 220px;
      max-width: 420px;
      width: 100%;
      overflow: hidden;
    }
    .signage-rate-box.buy {
      border-color: rgba(16, 185, 129, 0.35);
      box-shadow: 0 16px 48px rgba(0,0,0,0.25), 0 0 40px -10px rgba(16, 185, 129, 0.25);
    }
    .signage-rate-box.sell {
      border-color: rgba(239, 68, 68, 0.3);
      box-shadow: 0 16px 48px rgba(0,0,0,0.25), 0 0 40px -10px rgba(239, 68, 68, 0.2);
    }
    .signage-rate-label {
      font-size: clamp(0.85rem, 2.2vw, 1rem);
      font-weight: 600;
      margin-bottom: 0.75rem;
      color: rgba(255,255,255,0.7);
      letter-spacing: 0.02em;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    .signage-rate-icon {
      width: 1.25em;
      height: 1.25em;
      flex-shrink: 0;
    }
    .signage-rate-box.buy .signage-rate-icon { color: #34d399; }
    .signage-rate-box.sell .signage-rate-icon { color: #f87171; }
    .signage-rate-box-icon {
      width: clamp(2.5rem, 6vw, 3.5rem);
      height: clamp(2.5rem, 6vw, 3.5rem);
      margin: 0 auto 0.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .signage-rate-box-icon svg {
      width: 100%;
      height: 100%;
    }
    .signage-rate-box.buy .signage-rate-box-icon { color: #34d399; }
    .signage-rate-box.sell .signage-rate-box-icon { color: #f87171; }
    .signage-rate-value {
      font-size: clamp(1.5rem, 4.5vw, 2.75rem);
      font-weight: 800;
      letter-spacing: -0.02em;
      font-variant-numeric: tabular-nums;
      line-height: 1.2;
      overflow: hidden;
      max-width: 100%;
    }
    .signage-rate-box.buy .signage-rate-value { color: #34d399; text-shadow: 0 0 30px rgba(52, 211, 153, 0.3); }
    .signage-rate-box.sell .signage-rate-value { color: #f87171; text-shadow: 0 0 30px rgba(248, 113, 113, 0.25); }
    .signage-rate-unit {
      font-size: clamp(0.8rem, 2vw, 1rem);
      font-weight: 500;
      color: rgba(255,255,255,0.55);
      margin-top: 0.35rem;
      letter-spacing: 0.05em;
    }
    .signage-empty {
      position: relative;
      z-index: 1;
      color: rgba(255,255,255,0.8);
      font-size: clamp(1rem, 2.8vw, 1.35rem);
      font-weight: 500;
      letter-spacing: 0.02em;
    }
    .signage-other-section {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width:  min(96vw, 1200px);
      margin-top: 2.5rem;
    }
    .signage-other-title {
      font-size: clamp(0.9rem, 2.2vw, 1.1rem);
      font-weight: 700;
      color: rgba(255,255,255,0.75);
      margin: 0 0 0.75rem 0;
      letter-spacing: 0.05em;
    }
    .signage-other-list {
      background: rgba(255,255,255,0.06);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 16px;
      overflow: hidden;
      display: grid;
      gap: 0;
    }
    .signage-other-row {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      align-items: center;
      gap: 1rem;
      padding: 0.75rem 1.25rem;
      border-bottom: 1px solid rgba(255,255,255,0.06);
      font-size: clamp(0.85rem, 2vw, 1.05rem);
    }
    .signage-other-row:last-child { border-bottom: none; }
    .signage-other-row.header {
      font-size: 0.8em;
      font-weight: 700;
      color: rgba(255,255,255,0.6);
      text-transform: uppercase;
      letter-spacing: 0.08em;
    }
    .signage-other-pair {
      font-weight: 700;
      color: rgba(255,255,255,0.95);
    }
    .signage-other-buy { color: #34d399; font-weight: 700; }
    .signage-other-sell { color: #f87171; font-weight: 700; }
    .signage-crypto-section {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: min(96vw, 1200px);
      margin-top: 2rem;
    }
    .signage-crypto-title {
      font-size: clamp(0.9rem, 2.2vw, 1.1rem);
      font-weight: 700;
      color: rgba(255,255,255,0.75);
      margin: 0 0 0.75rem 0;
      letter-spacing: 0.05em;
    }
    .signage-crypto-list {
      background: rgba(255,255,255,0.06);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 16px;
      overflow: hidden;
      display: grid;
      gap: 0;
    }
    .signage-crypto-row {
      display: grid;
      grid-template-columns: 32px 1fr 1fr;
      align-items: center;
      gap: 0.75rem 1rem;
      padding: 0.7rem 1.25rem;
      border-bottom: 1px solid rgba(255,255,255,0.06);
      font-size: clamp(0.85rem, 2vw, 1.05rem);
    }
    .signage-crypto-row:last-child { border-bottom: none; }
    .signage-crypto-row.header {
      font-size: 0.8em;
      font-weight: 700;
      color: rgba(255,255,255,0.6);
      text-transform: uppercase;
      letter-spacing: 0.08em;
    }
    .signage-crypto-icon {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      object-fit: contain;
      background: rgba(255,255,255,0.08);
      flex-shrink: 0;
    }
    .signage-crypto-row.header .signage-crypto-icon { visibility: hidden; }
    .signage-crypto-name { font-weight: 700; color: rgba(255,255,255,0.95); }
    .signage-crypto-price { color: #06b6d4; font-weight: 700; }
    .signage-crypto-price-col {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 0.15rem;
    }
    .signage-crypto-change {
      display: inline-flex;
      align-items: center;
      gap: 0.25em;
      font-size: 0.9em;
      font-weight: 700;
    }
    .signage-crypto-change.up { color: #34d399; }
    .signage-crypto-change.down { color: #f87171; }
    .signage-crypto-change-icon { width: 1em; height: 1em; flex-shrink: 0; }
    .signage-main-row {
      position: relative;
      z-index: 1;
      display: flex;
      flex-wrap: wrap;
      align-items: stretch;
      justify-content: center;
      gap: 2rem;
      width: 100%;
      max-width: min(96vw, 1400px);
    }
    @media (orientation: landscape) and (min-width: 800px) {
      .signage-main-row {
        flex-wrap: nowrap;
        justify-content: center;
        align-items: stretch;
      }
      .signage-rates { order: 0; flex: 0 1 auto; }
      .signage-crypto-inline { order: 1; flex: 0 0 auto; margin-right: 0; margin-left: 2rem; }
    }
    .signage-rates {
      flex: 0 1 auto;
    }
    .signage-crypto-inline {
      position: relative;
      z-index: 1;
      min-width: 260px;
      max-width: 380px;
    }
    .signage-crypto-inline .signage-crypto-list { margin-top: 0; }
    .signage-crypto-inline .signage-crypto-title { margin-bottom: 0.5rem; }
    .signage-ticker-wrap {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 2;
      background: rgba(0,0,0,0.5);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-top: 1px solid rgba(255,255,255,0.1);
      overflow: hidden;
      direction: ltr;
      padding: 0.5rem 0;
    }
    .signage-ticker-inner {
      display: inline-block;
      white-space: nowrap;
      padding-right: 100%;
      animation: signage-ticker 40s linear infinite;
      color: rgba(255,255,255,0.95);
      font-size: clamp(0.85rem, 2vw, 1.05rem);
      font-weight: 500;
    }
    @keyframes signage-ticker {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }
    @keyframes rateNumberChange {
      0% { opacity: 0.5; filter: blur(6px); }
      100% { opacity: 1; filter: blur(0); }
    }
    .rate-number-animate {
      animation: rateNumberChange 0.5s ease-out forwards;
    }
    .signage-payment-qr-row {
      position: absolute;
      left: 1.5rem;
      top: 1.5rem;
      z-index: 1;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 1.25rem;
      margin: 0;
      width: auto;
      max-width: none;
    }
    .signage-payment-methods {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 0.75rem 1.25rem;
      padding: 0.9rem 1.5rem;
      background: rgba(255,255,255,0.06);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 16px;
    }
    .signage-payment-methods-label {
      font-size: clamp(0.8rem, 2vw, 0.95rem);
      font-weight: 700;
      color: rgba(255,255,255,0.7);
      margin-left: 0.5rem;
      letter-spacing: 0.03em;
    }
    .signage-payment-methods-list {
      display: flex;
      align-items: center;
      gap: 0.85rem;
      flex-wrap: wrap;
    }
    .signage-payment-method-item {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: rgba(255,255,255,0.9);
    }
    .signage-payment-method-item svg {
      display: block;
    }
    .signage-qr-block {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
      padding: 1rem 1.25rem;
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.15);
      border-radius: 16px;
    }
    .signage-qr-block img {
      width: clamp(100px, 12vw, 140px);
      height: clamp(100px, 12vw, 140px);
      border-radius: 10px;
      background: #fff;
      padding: 6px;
      display: block;
    }
    .signage-qr-label {
      font-size: clamp(0.75rem, 1.8vw, 0.9rem);
      font-weight: 600;
      color: rgba(255,255,255,0.85);
      letter-spacing: 0.02em;
    }
    .signage-qr-url {
      font-size: clamp(0.65rem, 1.5vw, 0.8rem);
      color: rgba(255,255,255,0.7);
      direction: ltr;
      word-break: break-all;
      text-align: center;
      max-width: 180px;
      line-height: 1.35;
    }
  </style>
</head>
<body>
  <div class="signage-wrap @if(filled($screen->background_color) || $screen->background_image_path) has-custom-bg @endif"
       style="background-color: {{ $screen->background_color ?? '#06182c' }}; @if($screen->backgroundImageUrl()) background-image: url('{{ $screen->backgroundImageUrl() }}'); @endif">
    <div class="signage-world-time" id="signageWorldTime" aria-live="polite">
      <div class="signage-world-time-title">ساعت جهانی</div>
      <div class="signage-world-time-row"><span class="signage-world-time-label">UK (لندن)</span><span class="signage-world-time-value" id="time-uk">—</span></div>
      <div class="signage-world-time-row"><span class="signage-world-time-label">ایران (تهران)</span><span class="signage-world-time-value" id="time-iran">—</span></div>
    </div>
    <div class="signage-brand">
      @if($office->logoUrl())
        <img src="{{ $office->logoUrl() }}" alt="" class="signage-logo">
      @endif
      <h1 class="signage-name">{{ $office->name }}</h1>
    </div>
    @if($office->hasSpecialRateToday())
      <div class="signage-special-rate-banner" aria-live="polite">
        <span class="signage-special-rate-label"><svg class="signage-special-rate-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg> نرخ ویژه امروز</span>
        <span class="signage-special-rate-values">
          @if($office->special_rate_buy !== null)
            <span class="signage-special-rate-item buy">خرید {{ farsi_num(number_format($office->special_rate_buy, 0)) }} تومان</span>
          @endif
          @if($office->special_rate_sell !== null)
            <span class="signage-special-rate-item sell">فروش {{ farsi_num(number_format($office->special_rate_sell, 0)) }} تومان</span>
          @endif
        </span>
      </div>
    @endif
    @if($rates->isNotEmpty())
      @php $gbpRate = $rates->first(); @endphp
      <div class="signage-main-row">
        <div class="signage-rates">
          <div class="signage-rate-box buy">
            <div class="signage-rate-box-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 15l-6-6-6 6"/></svg>
            </div>
            <div class="signage-rate-label">
              <svg class="signage-rate-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 15l-6-6-6 6"/></svg>
              خرید پوند → تومان
            </div>
            <div class="signage-rate-value rate-number-animate">{{ farsi_num(number_format($gbpRate->buy_rate, 0)) }}</div>
            <div class="signage-rate-unit">تومان</div>
          </div>
          <div class="signage-rate-box sell">
            <div class="signage-rate-box-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div class="signage-rate-label">
              <svg class="signage-rate-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
              فروش پوند → تومان
            </div>
            <div class="signage-rate-value rate-number-animate">{{ farsi_num(number_format($gbpRate->sell_rate, 0)) }}</div>
            <div class="signage-rate-unit">تومان</div>
          </div>
        </div>
        @if(isset($cryptoRates) && count($cryptoRates) > 0)
          <div class="signage-crypto-inline signage-crypto-section">
            <h2 class="signage-crypto-title">نرخ ارزهای دیجیتال</h2>
            <div class="signage-crypto-list">
              <div class="signage-crypto-row header">
                <span class="signage-crypto-icon"></span>
                <span>ارز</span>
                <span>نرخ (دلار) · ۲۴س</span>
              </div>
              @foreach($cryptoRates as $c)
                @php
                  $ch = $c['change_24h'] ?? null;
                  $isUp = $ch !== null && $ch >= 0;
                @endphp
                <div class="signage-crypto-row">
                  @if(!empty($c['icon_url']))
                    <img src="{{ $c['icon_url'] }}" alt="" class="signage-crypto-icon" width="28" height="28">
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
    @if((isset($paymentMethods) && count($paymentMethods) > 0) || (isset($qrLink) && $qrLink))
      <div class="signage-payment-qr-row">
        @if(isset($paymentMethods) && count($paymentMethods) > 0)
          <div class="signage-payment-methods">
            <span class="signage-payment-methods-label">پرداخت با</span>
            <div class="signage-payment-methods-list">
              @foreach($paymentMethods as $pm)
                @include('partials.payment-method-logo', ['key' => $pm, 'size' => 36, 'class' => 'signage-payment-method-item'])
              @endforeach
            </div>
          </div>
        @endif
        @if(isset($qrLink) && $qrLink)
          <div class="signage-qr-block">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrLink) }}&bgcolor=ffffff&color=06182c&format=svg" width="140" height="140" alt="QR code" loading="lazy">
            <span class="signage-qr-label">برای اطلاعات بیشتر اسکن کنید</span>
            <span class="signage-qr-url">{{ $qrLink }}</span>
          </div>
        @endif
      </div>
    @endif
    @if(isset($otherRates) && $otherRates->isNotEmpty())
      <div class="signage-other-section">
        <h2 class="signage-other-title">سایر جفت ارزها (ثبت‌شده توسط صرافی)</h2>
        <div class="signage-other-list">
          <div class="signage-other-row header">
            <span>جفت ارز</span>
            <span>خرید</span>
            <span>فروش</span>
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
  @if(isset($tickerText) && filled($tickerText))
    <div class="signage-ticker-wrap" aria-live="polite">
      <div class="signage-ticker-inner">{{ $tickerText }}</div>
    </div>
  @endif
  </div>
  <script>
    (function() {
      var displayUrl = '{{ route("signage.display", ["token" => $screen->token]) }}';
      setInterval(function() {
        window.location.href = displayUrl;
      }, 60000);

      function getTimeInZone(tz) {
        return new Date().toLocaleString('en-GB', { timeZone: tz, hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
      }
      function toFarsiNum(str) {
        var fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        return String(str).replace(/\d/g, function(d) { return fa[+d]; });
      }
      function updateWorldTime() {
        var ukEl = document.getElementById('time-uk');
        var iranEl = document.getElementById('time-iran');
        if (ukEl) ukEl.textContent = toFarsiNum(getTimeInZone('Europe/London'));
        if (iranEl) iranEl.textContent = toFarsiNum(getTimeInZone('Asia/Tehran'));
      }
      updateWorldTime();
      setInterval(updateWorldTime, 1000);
    })();
  </script>
</body>
</html>
