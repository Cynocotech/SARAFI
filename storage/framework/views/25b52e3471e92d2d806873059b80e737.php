<?php $__env->startSection('title', 'دایرکتوری صرافی‌ها | آقای صرافی'); ?>
<?php $__env->startSection('body_start'); ?>
  <div class="splash-screen" id="splashScreen" aria-live="polite">
    <div class="splash-inner">
      <h1 class="splash-title">آقای صرافی</h1>
      <div class="splash-progress-wrap">
        <div class="splash-progress-bar" id="splashProgressBar"></div>
      </div>
    </div>
  </div>
  <div class="mobile-only-message" id="mobileOnlyMessage">
    <div class="mobile-only-content">
      <div class="mobile-only-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="80" height="80">
          <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>
        </svg>
      </div>
      <h1>آقای صرافی</h1>
      <p>لطفاً این صفحه را در موبایل خود باز کنید</p>
      <p class="mobile-only-sub">این وب‌سایت برای تجربه بهتر روی گوشی موبایل طراحی شده است</p>
    </div>
  </div>
  <header class="sticky-header" id="stickyHeader">
    <div class="header-inner">
      <nav class="section-tabs">
        <button type="button" class="tab-btn" data-section="converter" aria-pressed="false">
          <svg class="tab-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4M7 4L3 8M7 4l4 4"/><path d="M17 8v12M17 20l4-4M17 20l-4-4"/></svg>
          تبدیل ارز
        </button>
        <button type="button" class="tab-btn active" data-section="exchanges" aria-pressed="true">
          <svg class="tab-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          صرافی‌ها
        </button>
      </nav>
      <div class="header-toolbar converter-toolbar" id="converterToolbar" hidden>
        
      </div>
      <div class="header-toolbar exchanges-toolbar" id="exchangesToolbar">
        <div class="search-bar">
          <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input type="search" id="searchInput" placeholder="جستجوی صرافی..." inputmode="search" autocomplete="off">
        </div>
        <div class="filter-row">
          <select id="filterSelect" aria-label="فیلتر">
            <option value="rate">بهترین نرخ</option>
            <option value="rating">بیشترین امتیاز</option>
            <option value="near">نزدیک‌ترین صرافی</option>
          </select>
          <select id="sortSelect" aria-label="مرتب‌سازی">
            <option value="buy-desc">بهترین خرید</option>
            <option value="sell-asc">بهترین فروش</option>
            <option value="rating">امتیاز</option>
          </select>
        </div>
      </div>
    </div>
  </header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
  <?php
    $rates = $offices->flatMap->exchangeRates->filter();
    $avgBuy = $rates->isEmpty() ? 0 : round($rates->avg('buy_rate'));
    $avgSell = $rates->isEmpty() ? 0 : round($rates->avg('sell_rate'));
  ?>
  <main class="converter-section" id="converterSection" hidden>
    <div class="converter-card">
      <h2 class="converter-title">تبدیل <span class="conv-currency"><svg class="conv-flag" viewBox="0 0 60 30" width="22" height="11" aria-hidden="true"><rect width="60" height="30" fill="#012169"/><path d="M0 0l60 30M60 0L0 30" stroke="#fff" stroke-width="6"/><path d="M0 0l60 30M60 0L0 30" stroke="#C8102E" stroke-width="4"/><path d="M30 0v30M0 15h60" stroke="#fff" stroke-width="10"/><path d="M30 0v30M0 15h60" stroke="#C8102E" stroke-width="6"/></svg> پوند</span> به <span class="conv-currency"><svg class="conv-flag conv-flag-ir" viewBox="0 0 60 30" width="22" height="11" aria-hidden="true"><rect y="0" width="60" height="10" fill="#00A651"/><rect y="10" width="60" height="10" fill="#fff"/><rect y="20" width="60" height="10" fill="#EE1C25"/></svg> تومان</span></h2>
      <p class="converter-subtitle">نرخ میانگین بر اساس صرافی‌های ثبت‌شده</p>
      <div class="converter-form">
        <div class="conv-field">
          <label>مبلغ</label>
          <input type="number" id="convertAmount" value="100" min="0" step="any" inputmode="decimal" placeholder="۰">
        </div>
        <button type="button" class="swap-currencies" id="swapCurrencies" aria-label="تعویض ارز">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><path d="M7 16V4M7 4L3 8M7 4l4 4"/><path d="M17 8v12M17 20l4-4M17 20l-4-4"/></svg>
        </button>
        <div class="conv-field">
          <label>از</label>
          <select id="convertFrom">
            <option value="GBP">🇬🇧 پوند (£)</option>
            <option value="IRR">🇮🇷 تومان</option>
          </select>
        </div>
        <div class="conv-field">
          <label>به</label>
          <select id="convertTo">
            <option value="IRR">🇮🇷 تومان</option>
            <option value="GBP">🇬🇧 پوند (£)</option>
          </select>
        </div>
      </div>
      <div class="converter-result">
        <span class="result-label">نتیجه</span>
        <span class="result-value" id="convertResult">۰ تومان</span>
      </div>
      <div class="converter-rates">
        <span>خرید: <strong id="rateBuy"><?php echo e(farsi_num(number_format($avgBuy))); ?></strong> <span class="conv-currency-inline"><svg class="conv-flag conv-flag-ir" viewBox="0 0 60 30" width="16" height="8" aria-hidden="true"><rect y="0" width="60" height="10" fill="#00A651"/><rect y="10" width="60" height="10" fill="#fff"/><rect y="20" width="60" height="10" fill="#EE1C25"/></svg> تومان</span></span>
        <span>فروش: <strong id="rateSell"><?php echo e(farsi_num(number_format($avgSell))); ?></strong> <span class="conv-currency-inline"><svg class="conv-flag conv-flag-ir" viewBox="0 0 60 30" width="16" height="8" aria-hidden="true"><rect y="0" width="60" height="10" fill="#00A651"/><rect y="10" width="60" height="10" fill="#fff"/><rect y="20" width="60" height="10" fill="#EE1C25"/></svg> تومان</span></span>
      </div>
    </div>
  </main>

  <main class="directory-list" id="directoryList">
    <div class="exchange-cards" id="exchangeCards">
      <?php
        $bestBuy = $offices->isEmpty() ? 0 : $offices->max(fn($o) => $o->exchangeRates->max('buy_rate') ?? 0);
        $bestSell = $offices->isEmpty() ? 0 : $offices->min(fn($o) => $o->exchangeRates->min('sell_rate') ?? PHP_INT_MAX);
      ?>
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $offices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $office): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
          $rate = $office->exchangeRates->first();
          $buy = $rate ? (float) $rate->buy_rate : 0;
          $sell = $rate ? (float) $rate->sell_rate : 0;
          $isBestBuy = $rate && $buy && $buy == $bestBuy;
          $isBestSell = $rate && $sell && $sell == $bestSell;
          $addressFull = $office->address_line_1 . ', ' . $office->city . ' ' . strtoupper($office->postcode);
          $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($addressFull);
          $trend = $office->getRateTrend();
        ?>
        <article class="exchange-card <?php echo e((is_array($office->features ?? null) && in_array('highlight', $office->features)) ? 'exchange-card-highlight' : ''); ?>" role="button" tabindex="0"
          data-id="<?php echo e($office->id); ?>"
          data-name="<?php echo e(e($office->name)); ?>"
          data-address="<?php echo e(e($addressFull)); ?>"
          data-phone="<?php echo e(e($office->phone ?? '')); ?>"
          data-email="<?php echo e(e($office->email ?? '')); ?>"
          data-buy="<?php echo e($buy); ?>"
          data-sell="<?php echo e($sell); ?>"
          data-verified="<?php echo e($office->identity_verified ? '1' : '0'); ?>"
          data-map-url="<?php echo e($mapUrl); ?>"
          data-logo-url="<?php echo e($office->logoUrl() ?? ''); ?>"
          data-features="<?php echo e(e(json_encode($office->features ?? []))); ?>"
          data-currencies="<?php echo e(e(json_encode($office->currencies ?? []))); ?>"
          data-special-rate-buy="<?php echo e($office->special_rate_buy ?? ''); ?>"
          data-special-rate-sell="<?php echo e($office->special_rate_sell ?? ''); ?>"
          data-payment-methods="<?php echo e(e(implode(',', $office->getAcceptedPaymentMethods()))); ?>"
          data-trend-buy="<?php echo e($trend['buy'] ?? ''); ?>"
          data-trend-sell="<?php echo e($trend['sell'] ?? ''); ?>"
          data-transfer-fee-under="<?php echo e($office->transfer_fee_under_amount ?? ''); ?>"
          data-transfer-fee-amount="<?php echo e($office->transfer_fee_amount ?? ''); ?>">
          <div class="card-header">
            <div class="card-brand">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->logoUrl()): ?>
                <img src="<?php echo e($office->logoUrl()); ?>" alt="" class="card-logo card-logo-img" width="40" height="40">
              <?php else: ?>
                <div class="card-logo" aria-hidden="true"><?php echo e(mb_substr($office->name, 0, 1)); ?></div>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <span class="card-name"><?php echo e($office->name); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->identity_verified): ?>
                  <span class="verified-badge" title="تأیید شده"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              </span>
            </div>
          </div>
          <p class="card-region"><?php echo e($office->city); ?> / <span class="postcode-uppercase"><?php echo e(strtoupper($office->postcode)); ?></span></p>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($office->features) && is_array($office->features)): ?>
            <div class="card-features">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $office->getExchangeFeatureLabels(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="card-feature-badge"><?php echo e($label); ?></span>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->hasSpecialRateToday()): ?>
            <div class="special-rate-banner">
              <span class="special-rate-label"><svg class="special-rate-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg> نرخ ویژه امروز</span>
              <span class="special-rate-values">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->special_rate_buy !== null): ?>
                  <span class="special-rate-item buy">خرید <?php echo e(farsi_num(number_format($office->special_rate_buy))); ?> تومان</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->special_rate_sell !== null): ?>
                  <span class="special-rate-item sell">فروش <?php echo e(farsi_num(number_format($office->special_rate_sell))); ?> تومان</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              </span>
            </div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rate): ?>
            <div class="card-rates">
              <p class="rates-title">پوند → تومان</p>
              <div class="rates-row">
                <div class="rate-item buy">
                  <span class="label">خرید</span>
                  <span class="value"><?php echo e(farsi_num(number_format($buy))); ?> تومان <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trend['buy'] === 'up'): ?><span class="rate-trend-arrow up" title="نرخ نسبت به قبل بالاتر"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg></span><?php elseif($trend['buy'] === 'down'): ?><span class="rate-trend-arrow down" title="نرخ نسبت به قبل پایین‌تر"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isBestBuy): ?><span class="best-badge buy">بهترین</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></span>
                </div>
                <div class="rate-item sell">
                  <span class="label">فروش</span>
                  <span class="value"><?php echo e(farsi_num(number_format($sell))); ?> تومان <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trend['sell'] === 'up'): ?><span class="rate-trend-arrow up" title="نرخ نسبت به قبل بالاتر"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg></span><?php elseif($trend['sell'] === 'down'): ?><span class="rate-trend-arrow down" title="نرخ نسبت به قبل پایین‌تر"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isBestSell): ?><span class="best-badge sell">بهترین</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></span>
                </div>
              </div>
            </div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($office->getAcceptedPaymentMethods()) > 0): ?>
            <div class="card-accepted-payments">
              <span class="card-accepted-label">پرداخت با</span>
              <div class="card-accepted-logos">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $office->getAcceptedPaymentMethods(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php echo $__env->make('partials.payment-method-logo', ['key' => $pm, 'size' => 24, 'class' => 'card-payment-logo'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              </div>
            </div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->hasTransferFee()): ?>
            <p class="card-transfer-fee" style="font-size:0.75rem;color:var(--text-muted);margin-top:0.5rem;padding-top:0.5rem;border-top:1px solid var(--border);">کارمزد حواله زیر <?php echo e(farsi_num(number_format($office->transfer_fee_under_amount, 0))); ?> پوند: <?php echo e(farsi_num(number_format($office->transfer_fee_amount, 0))); ?> پوند</p>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </article>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
  </main>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('portals'); ?>
  <div class="sheet-overlay" id="sheetOverlay" aria-hidden="true"></div>
  <aside class="bottom-sheet" id="bottomSheet" role="dialog" aria-modal="true" aria-labelledby="sheetTitle" aria-hidden="true">
    <div class="sheet-handle" aria-hidden="true"></div>
    <div class="sheet-content">
      <header class="sheet-hero sheet-header-with-logo">
        <div class="sheet-logo-wrap" id="sheetLogoWrap" style="display:none;">
          <img id="sheetLogoImg" src="" alt="" width="56" height="56" class="sheet-logo-img">
        </div>
        <div class="sheet-title-wrap">
          <h2 class="sheet-title" id="sheetTitle">نام صرافی</h2>
          <span class="sheet-type-tag sheet-type-company" id="sheetTypeTag" aria-hidden="true">شرکت</span>
        </div>
      </header>

      <div class="sheet-special-rate-banner" id="sheetSpecialRateBanner" hidden>
        <span class="special-rate-label"><svg class="special-rate-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg> نرخ ویژه امروز</span>
        <span class="sheet-special-rate-values" id="sheetSpecialRateValues"></span>
      </div>

      <section class="sheet-section sheet-rates" aria-labelledby="sheetRatesLabel">
        <h3 class="sheet-section-title" id="sheetRatesLabel">نرخ لحظه‌ای پوند به تومان</h3>
        <div class="rates-grid">
          <div class="rate-box buy">
            <span class="rate-label">خرید</span>
            <span class="rate-value-wrap"><span class="rate-value" id="sheetBuyRate">۰</span><span class="rate-trend-arrow-wrap" id="sheetBuyTrend" aria-hidden="true"></span></span>
            <span class="rate-unit">تومان</span>
          </div>
          <div class="rate-box sell">
            <span class="rate-label">فروش</span>
            <span class="rate-value-wrap"><span class="rate-value" id="sheetSellRate">۰</span><span class="rate-trend-arrow-wrap" id="sheetSellTrend" aria-hidden="true"></span></span>
            <span class="rate-unit">تومان</span>
          </div>
        </div>
        <div class="sheet-accepted-payments" id="sheetAcceptedPayments" hidden>
          <span class="sheet-accepted-label">پرداخت با</span>
          <span class="sheet-accepted-logos" id="sheetAcceptedLogos"></span>
        </div>
        <div class="sheet-transfer-fee" id="sheetTransferFee" hidden style="font-size:0.8rem;color:var(--text-muted);margin-top:0.75rem;padding-top:0.75rem;border-top:1px solid var(--border);">
          <span class="sheet-transfer-fee-label">کارمزد حواله زیر </span><span id="sheetTransferFeeUnder">۰</span> پوند: <span id="sheetTransferFeeAmount">۰</span> پوند
        </div>
      </section>

      <section class="sheet-section sheet-info" aria-labelledby="sheetContactLabel">
        <h3 class="sheet-section-title" id="sheetContactLabel">تماس و آدرس</h3>
        <div class="sheet-contact-list">
          <p class="info-row">
            <span class="info-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
            <span class="info-text" id="sheetAddress">—</span>
          </p>
          <p class="info-row" id="sheetPhoneRow">
            <span class="info-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
            <span class="info-text" id="sheetPhone">—</span>
          </p>
          <p class="info-row info-row-email" id="sheetEmailRow" style="display:none;">
            <span class="info-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
            <span class="info-text" id="sheetEmail">—</span>
          </p>
        </div>
      </section>

      <section class="sheet-section sheet-features-section" id="sheetFeaturesSection" aria-labelledby="sheetFeaturesLabel" hidden>
        <h3 class="sheet-section-title" id="sheetFeaturesLabel">ویژگی‌ها و ارزها</h3>
        <div class="sheet-features-list" id="sheetFeaturesList"></div>
        <div class="sheet-currencies-list" id="sheetCurrenciesList"></div>
      </section>

      <section class="sheet-section sheet-map-wrap" aria-label="نقشه">
        <div class="sheet-map" id="sheetMapFrame"></div>
      </section>

      <div class="sheet-person-note" id="sheetPersonNote" hidden>
        <span class="sheet-note-icon" aria-hidden="true">⚠</span>
        <p>در معاملات با افراد حقیقی احتیاط کنید و مراقب کلاهبرداری باشید.</p>
      </div>

      <div class="sheet-global-alert" role="alert">
        <svg class="sheet-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <p>همیشه معاملات خود را با احتیاط انجام دهید.</p>
      </div>

      <div class="sheet-actions">
        <a href="#" class="btn btn-primary sheet-action-call" id="sheetCallBtn" style="display:none;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          تماس با صرافی
        </a>
        <a href="#" class="btn btn-secondary" id="sheetMapBtn" target="_blank" rel="noopener">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          مسیریابی در نقشه
        </a>
      </div>
    </div>
    <button type="button" class="sheet-close" id="sheetClose" aria-label="بستن">×</button>
  </aside>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('bottom_nav'); ?>
  <?php echo $__env->make('partials.bottom-nav', ['nav_items' => $nav_items ?? [], 'current_route' => 'exchanges.index'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
(function() {
  // Splash: show loading progress 0.9s then hide
  var splashEl = document.getElementById('splashScreen');
  if (splashEl) {
    setTimeout(function() {
      splashEl.classList.add('splash-done');
    }, 900);
  }

  var avgBuy = <?php echo e((int) $avgBuy); ?>;
  var avgSell = <?php echo e((int) $avgSell); ?>;
  if (avgBuy === 0) avgBuy = 1;
  if (avgSell === 0) avgSell = 1;

  // Feature labels for sheet/card badges (not the strip - strip removed)
  var EXCHANGE_FEATURES = {
    highlight: 'هایلایت',
    best_rates: 'بهترین نرخ',
    no_commission: 'بدون کارمزد',
    '24_7': '۲۴ ساعته',
    physical_branch: 'شعبه فیزیکی',
    fast_transfer: 'انتقال سریع',
    online_booking: 'رزرو آنلاین',
    fca_regulated: 'دارای مجوز FCA',
    high_limit: 'سقف بالا',
    multi_currency: 'چند ارزه'
  };

  function toPersianNum(num) {
    var persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    var n = typeof num === 'string' ? parseFloat(num) : num;
    var fixed = (n || 0).toFixed(0);
    var intPart = Math.round(parseFloat(fixed)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return intPart.replace(/\d/g, function(d) { return persianDigits[parseInt(d)]; });
  }

  var sheetOverlay = document.getElementById('sheetOverlay');
  var bottomSheet = document.getElementById('bottomSheet');
  var sheetClose = document.getElementById('sheetClose');
  var sheetCallBtn = document.getElementById('sheetCallBtn');
  var sheetMapBtn = document.getElementById('sheetMapBtn');
  var currentSheetOfficeId = null;

  function openSheet(card) {
    if (!sheetOverlay || !bottomSheet) return;
    // Show sheet first so it appears even if population throws
    sheetOverlay.classList.add('open');
    bottomSheet.classList.add('open');
    sheetOverlay.setAttribute('aria-hidden', 'false');
    bottomSheet.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    var id = card.getAttribute('data-id');
    currentSheetOfficeId = id;
    if (id) {
      fetch('/exchanges/' + id + '/click?type=view', { method: 'GET', headers: { 'Accept': 'application/json' } }).catch(function() {});
    }
    var name = card.getAttribute('data-name') || '';
    var address = card.getAttribute('data-address') || '—';
    var phone = card.getAttribute('data-phone') || '';
    var email = card.getAttribute('data-email') || '';
    var buy = card.getAttribute('data-buy') || '0';
    var sell = card.getAttribute('data-sell') || '0';
    var verified = card.getAttribute('data-verified') === '1';
    var mapUrl = card.getAttribute('data-map-url') || '#';
    var logoUrl = card.getAttribute('data-logo-url') || '';
    var initial = name.charAt(0) || '؟';
    var featuresJson = card.getAttribute('data-features') || '[]';
    var currenciesJson = card.getAttribute('data-currencies') || '[]';
    var specialBuy = card.getAttribute('data-special-rate-buy') || '';
    var specialSell = card.getAttribute('data-special-rate-sell') || '';
    var paymentMethods = (card.getAttribute('data-payment-methods') || '').split(',').map(function(s) { return s.trim(); }).filter(Boolean);
    var trendBuy = card.getAttribute('data-trend-buy') || '';
    var trendSell = card.getAttribute('data-trend-sell') || '';
    var transferFeeUnder = card.getAttribute('data-transfer-fee-under') || '';
    var transferFeeAmount = card.getAttribute('data-transfer-fee-amount') || '';

    var sheetLogoWrap = document.getElementById('sheetLogoWrap');
    var sheetLogoImg = document.getElementById('sheetLogoImg');
    if (logoUrl && sheetLogoWrap && sheetLogoImg) {
      sheetLogoWrap.style.display = '';
      sheetLogoImg.src = logoUrl;
      sheetLogoImg.alt = '';
      document.getElementById('sheetTitle').innerHTML = name + (verified ? '<span class="verified-badge" title="تأیید شده"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>' : '');
    } else {
      if (sheetLogoWrap) sheetLogoWrap.style.display = 'none';
      document.getElementById('sheetTitle').innerHTML =
        '<span class="sheet-title-row">' +
        '<span class="sheet-logo">' + initial + '</span>' +
        '<span>' + name + (verified ? '<span class="verified-badge" title="تأیید شده"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>' : '') + '</span>' +
        '</span>';
    }

    document.getElementById('sheetBuyRate').textContent = toPersianNum(buy);
    document.getElementById('sheetSellRate').textContent = toPersianNum(sell);
    var arrowUp = '<span class="rate-trend-arrow up" title="نرخ نسبت به قبل بالاتر"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><polyline points="18 15 12 9 6 15"/></svg></span>';
    var arrowDown = '<span class="rate-trend-arrow down" title="نرخ نسبت به قبل پایین‌تر"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><polyline points="6 9 12 15 18 9"/></svg></span>';
    var sheetBuyTrend = document.getElementById('sheetBuyTrend');
    var sheetSellTrend = document.getElementById('sheetSellTrend');
    if (sheetBuyTrend) sheetBuyTrend.innerHTML = trendBuy === 'up' ? arrowUp : (trendBuy === 'down' ? arrowDown : '');
    if (sheetSellTrend) sheetSellTrend.innerHTML = trendSell === 'up' ? arrowUp : (trendSell === 'down' ? arrowDown : '');
    document.getElementById('sheetAddress').textContent = address;

    var specialBanner = document.getElementById('sheetSpecialRateBanner');
    var specialValuesEl = document.getElementById('sheetSpecialRateValues');
    if (specialBanner && specialValuesEl) {
      var hasSpecial = (specialBuy !== '' && specialBuy !== '0') || (specialSell !== '' && specialSell !== '0');
      if (hasSpecial) {
        var parts = [];
        if (specialBuy !== '' && specialBuy !== '0') parts.push('<span class="special-rate-item buy">خرید ' + toPersianNum(Number(specialBuy).toLocaleString('en')) + ' تومان</span>');
        if (specialSell !== '' && specialSell !== '0') parts.push('<span class="special-rate-item sell">فروش ' + toPersianNum(Number(specialSell).toLocaleString('en')) + ' تومان</span>');
        specialValuesEl.innerHTML = parts.join(' ');
        specialBanner.hidden = false;
      } else {
        specialBanner.hidden = true;
      }
    }

    var sheetAcceptedPayments = document.getElementById('sheetAcceptedPayments');
    var sheetAcceptedLogos = document.getElementById('sheetAcceptedLogos');
    if (sheetAcceptedPayments && sheetAcceptedLogos) {
      if (paymentMethods && paymentMethods.length > 0) {
        var paymentLogos = {
          visa: '<span class="card-payment-logo" style="display:inline-flex;align-items:center;width:28px;height:18px;"><svg viewBox="0 0 54 34" width="28" height="18"><rect width="54" height="34" rx="4" fill="#1A1F71"/><text x="27" y="22" text-anchor="middle" fill="#fff" font-family="Arial" font-weight="700" font-size="14">VISA</text></svg></span>',
          mastercard: '<span class="card-payment-logo" style="display:inline-flex;align-items:center;width:28px;height:18px;"><svg viewBox="0 0 54 34" width="28" height="18"><rect width="54" height="34" rx="4" fill="#fff"/><circle cx="18" cy="17" r="12" fill="#EB001B"/><circle cx="36" cy="17" r="12" fill="#F79E1B"/><path d="M27 6.5a11.5 11.5 0 0 1 0 21A11.5 11.5 0 0 1 27 6.5z" fill="#FF5F00"/></svg></span>',
          credit_cards: '<span class="card-payment-logo" style="display:inline-flex;align-items:center;width:24px;height:24px;"><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2.5"/><rect x="5" y="8" width="5" height="4" rx="0.8" fill="currentColor" opacity="0.4"/><line x1="2" y1="12" x2="22" y2="12"/></svg></span>',
          cash: '<span class="card-payment-logo" style="display:inline-flex;align-items:center;width:24px;height:24px;"><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="1.5"/><circle cx="7" cy="12" r="2.5"/><line x1="12" y1="8" x2="18" y2="8"/><line x1="12" y1="12" x2="18" y2="12"/><line x1="12" y1="16" x2="18" y2="16"/></svg></span>'
        };
        sheetAcceptedLogos.innerHTML = paymentMethods.map(function(k) { return paymentLogos[k] || ''; }).filter(Boolean).join('');
        sheetAcceptedPayments.hidden = false;
    } else {
      sheetAcceptedPayments.hidden = true;
    }
    }

    var sheetTransferFee = document.getElementById('sheetTransferFee');
    if (sheetTransferFee && transferFeeUnder !== '' && transferFeeAmount !== '' && transferFeeUnder !== '0' && transferFeeAmount !== '0') {
      document.getElementById('sheetTransferFeeUnder').textContent = toPersianNum(Number(transferFeeUnder).toLocaleString('en'));
      document.getElementById('sheetTransferFeeAmount').textContent = toPersianNum(Number(transferFeeAmount).toLocaleString('en'));
      sheetTransferFee.hidden = false;
    } else if (sheetTransferFee) {
      sheetTransferFee.hidden = true;
    }

    var phoneRow = document.getElementById('sheetPhoneRow');
    var phoneEl = document.getElementById('sheetPhone');
    if (phone) {
      phoneRow.style.display = '';
      phoneEl.innerHTML = '<a href="tel:' + phone.replace(/\s/g, '') + '" dir="ltr">' + phone + '</a>';
      sheetCallBtn.href = 'tel:' + phone.replace(/\s/g, '');
      sheetCallBtn.style.display = '';
    } else {
      phoneRow.style.display = '';
      phoneEl.textContent = '—';
      sheetCallBtn.style.display = 'none';
    }

    var emailRow = document.getElementById('sheetEmailRow');
    var emailEl = document.getElementById('sheetEmail');
    if (email) {
      emailRow.style.display = '';
      emailEl.innerHTML = '<a href="mailto:' + email + '">' + email + '</a>';
    } else {
      emailRow.style.display = 'none';
    }

    sheetMapBtn.href = mapUrl;

    var mapFrame = document.getElementById('sheetMapFrame');
    var embedUrl = 'https://www.google.com/maps?q=' + encodeURIComponent(address) + '&output=embed';
    mapFrame.innerHTML = '<iframe src="' + embedUrl + '" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';

    var CURRENCY_NAMES = { GBP: 'پوند', EUR: 'یورو', USD: 'دلار آمریکا', AED: 'درهم', CAD: 'دلار کانادا', IRR: 'تومان' };
    var sheetFeaturesSection = document.getElementById('sheetFeaturesSection');
    var sheetFeaturesList = document.getElementById('sheetFeaturesList');
    var sheetCurrenciesList = document.getElementById('sheetCurrenciesList');
    var hasFeatures = features && features.length > 0;
    var hasCurrencies = currencies && currencies.length > 0;
    var displayFeatures = hasFeatures ? features.filter(function(k) { return k !== 'highlight'; }) : [];
    if (sheetFeaturesSection) {
      if (sheetFeaturesList) {
        sheetFeaturesList.innerHTML = displayFeatures.length > 0 ? displayFeatures.map(function(k) { return '<span class="sheet-feature-badge">' + (EXCHANGE_FEATURES[k] || k) + '</span>'; }).join('') : '';
        sheetFeaturesList.style.display = displayFeatures.length > 0 ? '' : 'none';
      }
      if (sheetCurrenciesList) {
        sheetCurrenciesList.innerHTML = hasCurrencies ? currencies.map(function(c) { return '<span class="sheet-currency-badge">' + (CURRENCY_NAMES[c] || c) + '</span>'; }).join('') : '';
        sheetCurrenciesList.style.display = hasCurrencies ? '' : 'none';
      }
      sheetFeaturesSection.hidden = !(displayFeatures.length > 0 || hasCurrencies);
    }

    document.getElementById('sheetPersonNote').hidden = true;

    sheetOverlay.classList.add('open');
    bottomSheet.classList.add('open');
    sheetOverlay.setAttribute('aria-hidden', 'false');
    bottomSheet.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeSheet() {
    if (sheetOverlay) sheetOverlay.classList.remove('open');
    if (bottomSheet) bottomSheet.classList.remove('open');
    if (sheetOverlay) sheetOverlay.setAttribute('aria-hidden', 'true');
    if (bottomSheet) bottomSheet.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && bottomSheet && bottomSheet.classList.contains('open')) closeSheet();
  });

  var exchangeCardsEl = document.getElementById('exchangeCards');
  if (exchangeCardsEl && sheetOverlay && bottomSheet) {
    exchangeCardsEl.addEventListener('click', function(e) {
      var card = e.target.closest('.exchange-card');
      if (card) {
        e.preventDefault();
        openSheet(card);
      }
    });

    exchangeCardsEl.addEventListener('keydown', function(e) {
      if (e.key !== 'Enter' && e.key !== ' ') return;
      var card = e.target.closest('.exchange-card');
      if (card) {
        e.preventDefault();
        openSheet(card);
      }
    });
  }

  if (sheetOverlay) sheetOverlay.addEventListener('click', closeSheet);
  if (sheetClose) sheetClose.addEventListener('click', closeSheet);
  if (sheetCallBtn) {
    sheetCallBtn.addEventListener('click', function() {
      if (currentSheetOfficeId) {
        fetch('/exchanges/' + currentSheetOfficeId + '/click?type=call', { method: 'GET', headers: { 'Accept': 'application/json' } }).catch(function() {});
      }
    });
  }
  if (sheetMapBtn) {
    sheetMapBtn.addEventListener('click', function() {
      if (currentSheetOfficeId) {
        fetch('/exchanges/' + currentSheetOfficeId + '/click?type=map', { method: 'GET', headers: { 'Accept': 'application/json' } }).catch(function() {});
      }
    });
  }
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && bottomSheet.classList.contains('open')) closeSheet();
  });

  // Section tabs: Converter | Exchanges
  var converterSection = document.getElementById('converterSection');
  var directoryList = document.getElementById('directoryList');
  var converterToolbar = document.getElementById('converterToolbar');
  var exchangesToolbar = document.getElementById('exchangesToolbar');

  document.querySelectorAll('.section-tabs .tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var section = this.getAttribute('data-section');
      document.querySelectorAll('.section-tabs .tab-btn').forEach(function(b) {
        b.classList.toggle('active', b.getAttribute('data-section') === section);
        b.setAttribute('aria-pressed', b.getAttribute('data-section') === section ? 'true' : 'false');
      });
      if (section === 'converter') {
        converterSection.hidden = false;
        directoryList.hidden = true;
        directoryList.setAttribute('hidden', '');
        directoryList.style.display = 'none';
        converterSection.style.display = 'block';
        if (converterToolbar) converterToolbar.hidden = false;
        if (exchangesToolbar) exchangesToolbar.hidden = true;
        runConverter();
      } else {
        converterSection.hidden = true;
        directoryList.hidden = false;
        directoryList.removeAttribute('hidden');
        directoryList.style.display = '';
        converterSection.style.display = '';
        if (converterToolbar) converterToolbar.hidden = true;
        if (exchangesToolbar) exchangesToolbar.hidden = false;
      }
    });
  });

  // Converter (GBP ↔ Toman)
  var convertAmount = document.getElementById('convertAmount');
  var convertFrom = document.getElementById('convertFrom');
  var convertTo = document.getElementById('convertTo');
  var convertResult = document.getElementById('convertResult');
  var swapCurrencies = document.getElementById('swapCurrencies');
  var rateBuyEl = document.getElementById('rateBuy');
  var rateSellEl = document.getElementById('rateSell');

  function runConverter() {
    if (!convertAmount || !convertResult) return;
    var amount = parseFloat(convertAmount.value) || 0;
    var from = convertFrom ? convertFrom.value : 'GBP';
    var to = convertTo ? convertTo.value : 'IRR';
    var result = 0;
    var resultStr = '';
    if (from === 'GBP' && to === 'IRR') {
      result = amount * avgBuy;
      convertResult.removeAttribute('dir');
      resultStr = toPersianNum(result) + ' تومان';
    } else {
      result = amount / avgSell;
      convertResult.setAttribute('dir', 'ltr');
      resultStr = '£ ' + result.toFixed(2);
    }
    animateRateNumber(convertResult, resultStr);
  }

  function animateRateNumber(el, text) {
    if (!el) return;
    el.textContent = text;
    el.classList.remove('rate-number-animate');
    void el.offsetWidth;
    el.classList.add('rate-number-animate');
    el.addEventListener('animationend', function once() {
      el.classList.remove('rate-number-animate');
      el.removeEventListener('animationend', once);
    }, { once: true });
  }

  function swapConverterCurrencies() {
    if (!convertFrom || !convertTo) return;
    var from = convertFrom.value;
    var to = convertTo.value;
    convertFrom.value = to;
    convertTo.value = from;
    runConverter();
  }

  if (convertAmount) convertAmount.addEventListener('input', runConverter);
  if (convertFrom) convertFrom.addEventListener('change', runConverter);
  if (convertTo) convertTo.addEventListener('change', runConverter);
  if (swapCurrencies) swapCurrencies.addEventListener('click', swapConverterCurrencies);

  if (rateBuyEl) rateBuyEl.textContent = toPersianNum(avgBuy);
  if (rateSellEl) rateSellEl.textContent = toPersianNum(avgSell);

  // Search
  document.getElementById('searchInput').addEventListener('input', function() {
    var q = this.value.trim().toLowerCase();
    document.querySelectorAll('.exchange-card').forEach(function(card) {
      var name = (card.getAttribute('data-name') || '').toLowerCase();
      var region = (card.querySelector('.card-region') ? card.querySelector('.card-region').textContent : '').toLowerCase();
      card.style.display = (name.includes(q) || region.includes(q)) ? '' : 'none';
    });
  });

  // Filter / sort: reorder cards client-side
  var cardsContainer = document.getElementById('exchangeCards');
  var filterSelect = document.getElementById('filterSelect');
  var sortSelect = document.getElementById('sortSelect');

  function applySort() {
    var sort = sortSelect.value;
    var cards = Array.from(cardsContainer.querySelectorAll('.exchange-card'));
    cards.sort(function(a, b) {
      var buyA = parseFloat(a.getAttribute('data-buy')) || 0;
      var buyB = parseFloat(b.getAttribute('data-buy')) || 0;
      var sellA = parseFloat(a.getAttribute('data-sell')) || Infinity;
      var sellB = parseFloat(b.getAttribute('data-sell')) || Infinity;
      if (sort === 'buy-desc') return buyB - buyA;
      if (sort === 'sell-asc') return sellA - sellB;
      return 0;
    });
    cards.forEach(function(c) { cardsContainer.appendChild(c); });
  }

  filterSelect.addEventListener('change', applySort);
  sortSelect.addEventListener('change', applySort);
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Volumes/G-DRIVE  SSD  1TB/Exchange Landing/exchange-backend/resources/views/exchanges/index.blade.php ENDPATH**/ ?>