<?php $__env->startSection('title', $exchangeOffice->name . ' | بهترین نرخ ارز'); ?>
<?php $__env->startSection('meta_description', Str::limit(strip_tags($exchangeOffice->about ?? $exchangeOffice->name . ' - نرخ خرید و فروش ارز'), 160)); ?>

<?php $__env->startSection('styles'); ?>
  
  <link rel="stylesheet" href="<?php echo e(asset('css/theme2-landing.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body_start'); ?>
  <header class="header" id="header">
    <nav class="nav container">
      <a href="<?php echo e(route('exchanges.index')); ?>" class="logo"><?php echo e($exchangeOffice->name); ?></a>
      <button class="menu-toggle" aria-label="منوی ناوبری" type="button">
        <span class="hamburger"></span>
      </button>
      <ul class="nav-links">
        <li><a href="#calculator">ماشین‌حساب</a></li>
        <li><a href="#services">خدمات</a></li>
        <li><a href="#about">درباره ما</a></li>
        <li><a href="#location">تماس</a></li>
      </ul>
    </nav>
    <ul class="nav-mobile">
      <li><a href="#calculator">ماشین‌حساب</a></li>
      <li><a href="#services">خدمات</a></li>
      <li><a href="#about">درباره ما</a></li>
      <li><a href="#location">تماس</a></li>
    </ul>
  </header>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <?php
    $ratesToIrr = $exchangeOffice->exchangeRates->where('to_currency', 'IRR')->keyBy('from_currency');
    $gbpRate = $ratesToIrr->get('GBP');
    $featureLabels = $exchangeOffice->getExchangeFeatureLabels();
    $paymentMethods = $exchangeOffice->getAcceptedPaymentMethods();
    $hasSpecial = $exchangeOffice->hasSpecialRateToday();
    $specialBuy = $exchangeOffice->special_rate_buy;
    $specialSell = $exchangeOffice->special_rate_sell;
    $displayRate = $gbpRate ? (float) $gbpRate->sell_rate : 56000;
    $buyRate = $gbpRate ? (float) $gbpRate->buy_rate : 230166;
    $sellRateVal = $gbpRate ? (float) $gbpRate->sell_rate : 225009;
    $locationLine = $exchangeOffice->getFullAddress() ?: 'لندن';
    $clickUrl = route('exchanges.click', $exchangeOffice);
    $whatsappUrl = $exchangeOffice->getWhatsAppUrl('سلام، برای استعلام نرخ پیام می‌دهم.');
  ?>

  <main>
    <section id="calculator" class="hero-calc">
      <div class="container hero-container">
        <div class="hero-content">
          <span class="currency-float currency-float-gbp" aria-hidden="true">£</span>
          <span class="currency-float currency-float-usd" aria-hidden="true">$</span>
          <span class="currency-float currency-float-eur" aria-hidden="true">€</span>
          <div class="hero-header">
            <h1 class="hero-title"><?php echo e($exchangeOffice->hero_title ?? $exchangeOffice->name); ?></h1>
            <p class="hero-subtitle"><?php echo e($exchangeOffice->hero_subtitle ?? 'انتقال لحظه‌ای بین ایران و بریتانیا'); ?></p>
            <p class="hero-location"><?php echo e($locationLine); ?></p>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($featureLabels) > 0): ?>
              <div class="hero-money-chips" aria-label="ویژگی‌های صرافی">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $featureLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <span class="money-chip"><?php echo e($label); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              </div>
            <?php else: ?>
              <div class="hero-money-chips" aria-label="ویژگی‌های صرافی">
                <span class="money-chip">بهترین نرخ</span>
                <span class="money-chip">انتقال سریع</span>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exchangeOffice->landing_show_calculator !== false): ?>
          <div class="calculator-card">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSpecial && ($specialSell !== null || $specialBuy !== null)): ?>
              <div class="special-rate">
                <svg class="special-rate-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M12 3l2.4 4.9 5.4.8-3.9 3.8.9 5.4L12 15.5 7.2 18l.9-5.4L4.2 8.7l5.4-.8L12 3z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                </svg>
                <span class="special-rate-text">نرخ ویژه امروز</span>
                <strong class="special-rate-value"><?php echo e(number_format($specialSell ?? $specialBuy)); ?> تومان</strong>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="calc-inputs-row">
              <div class="calc-input-block">
                <div class="calc-currency-row">
                  <span class="flag-wrap" title="بریتانیا">
                    <img class="flag-uk" src="https://flagcdn.com/w40/gb.png" srcset="https://flagcdn.com/w80/gb.png 2x" alt="UK" width="32" height="20" loading="lazy" decoding="async">
                  </span>
                  <span class="calc-currency">£ پوند GBP</span>
                </div>
                <label class="sr-only" for="gbp">پوند</label>
                <input type="text" id="gbp" inputmode="decimal" placeholder="۰" value="100">
              </div>
              <div class="calc-swap">
                <button type="button" id="directionToggle" class="calc-direction-btn" aria-label="تغییر جهت تبدیل">
                  <svg class="calc-direction-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 8h13m0 0l-3-3m3 3l-3 3M20 16H7m0 0l3-3m-3 3l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span id="directionLabel">پوند → تومان</span>
                </button>
              </div>
              <div class="calc-input-block">
                <div class="calc-currency-row">
                  <span class="flag-wrap" title="ایران">
                    <img class="flag-ir" src="https://flagcdn.com/w80/ir.png" alt="Iran" width="36" height="20" loading="lazy" decoding="async">
                  </span>
                  <span class="calc-currency">تومان IRT</span>
                </div>
                <label class="sr-only" for="irt">تومان</label>
                <input type="text" id="irt" inputmode="numeric" placeholder="۰">
              </div>
            </div>
            <div class="calc-footer">
              <div class="calc-meta">
                <span class="calc-rate">۱ £ = <strong id="rateDisplay">۰</strong> تومان</span>
                <span class="calc-updated">بروزرسانی: <span id="timeDisplay">--:--</span></span>
              </div>
              <div class="calc-bid-ask" aria-label="نرخ خرید و فروش">
                <span class="bid-ask-title">پوند → تومان</span>
                <span class="bid-ask-item buy">
                  <span class="bid-ask-label">
                    <svg class="bid-ask-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M12 4v16m0 0l-5-5m5 5l5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    خرید
                  </span>
                  <strong id="buyRateDisplay">۰ تومان</strong>
                </span>
                <span class="bid-ask-item sell">
                  <span class="bid-ask-label">
                    <svg class="bid-ask-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M12 20V4m0 0l-5 5m5-5l5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    فروش
                  </span>
                  <strong id="sellRateDisplay">۰ تومان</strong>
                </span>
              </div>
              <div class="calc-extra">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($paymentMethods) > 0): ?>
                  <span class="pay-chip">پرداخت با <strong><?php echo e(strtoupper(implode(', ', $paymentMethods))); ?></strong></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exchangeOffice->hasTransferFee()): ?>
                  <span class="fee-chip">کارمزد حواله زیر <?php echo e(number_format($exchangeOffice->transfer_fee_under_amount)); ?> پوند: <strong><?php echo e($exchangeOffice->transfer_fee_amount); ?> پوند</strong></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              </div>
            </div>
          </div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
      </div>
    </section>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exchangeOffice->landing_show_rates !== false && $ratesToIrr->isNotEmpty()): ?>
    <section class="rates-strip" aria-label="نرخ لحظه‌ای ارزها">
      <div class="container">
        <div class="rates-grid">
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $ratesToIrr->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
              $trend = $exchangeOffice->getRateTrend();
              $direction = ($rate->from_currency === 'GBP' ? ($trend['sell'] ?? null) : null);
            ?>
            <article class="rate-item">
              <span class="rate-pair"><?php echo e($rate->from_currency); ?> / IRT</span>
              <strong class="rate-value"><?php echo e(number_format((float) $rate->sell_rate)); ?></strong>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($direction): ?>
                <span class="rate-change <?php echo e($direction); ?>"><?php echo e($direction === 'up' ? '+0.8%' : '-0.2%'); ?></span>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </article>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
      </div>
    </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <section id="services" class="services">
      <div class="container">
        <h2 class="section-title">خدمات ما</h2>
        <p class="section-subtitle">با اطمینان از کیفیت و امنیت، بهترین خدمات ارزی را ارائه می‌دهیم</p>
        <div class="services-grid">
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $exchangeOffice->getLandingServices(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="service-card">
              <div class="service-icon">
                <span class="material-symbols-outlined" aria-hidden="true"><?php echo e($service['icon']); ?></span>
              </div>
              <h3><?php echo e($service['title']); ?></h3>
              <p><?php echo e($service['description'] ?: 'خدمات با کیفیت و شفاف.'); ?></p>
            </article>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
      </div>
    </section>

    <section id="about" class="about">
      <div class="container">
        <div class="about-content">
          <div class="about-text">
            <h2><?php echo e($exchangeOffice->name); ?>، همکار قابل اعتماد شما</h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($exchangeOffice->about)): ?>
              <p><?php echo nl2br(e($exchangeOffice->about)); ?></p>
            <?php else: ?>
              <p>با سال‌ها تجربه در زمینه خدمات ارزی، امنیت و رضایت مشتریان همواره در اولویت ما بوده است.</p>
              <p>تمام تراکنش‌های ما با بالاترین استانداردهای امنیتی انجام می‌شود و اطلاعات شما محفوظ خواهد ماند.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <ul class="about-features">
              <li><span class="check">✓</span> امنیت بالا در تمامی معاملات</li>
              <li><span class="check">✓</span> سرعت بالا در انتقال وجه</li>
              <li><span class="check">✓</span> نرخ‌های شفاف و رقابتی</li>
              <li><span class="check">✓</span> پشتیبانی ۲۴ ساعته</li>
            </ul>
          </div>
          <div class="about-visual">
            <img src="<?php echo e($exchangeOffice->aboutImageUrl()); ?>" alt="" loading="lazy" width="560" height="560">
          </div>
        </div>
      </div>
    </section>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exchangeOffice->landing_show_contact !== false): ?>
    <section id="location" class="location">
      <div class="container">
        <h2 class="section-title">تماس با ما</h2>
        <p class="section-subtitle">با ما در ارتباط باشید یا از طریق فرم زیر پیام بفرستید</p>
        <div class="location-grid">
          <div class="location-info">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exchangeOffice->getFullAddress()): ?>
              <div class="info-item">
                <span class="info-icon">📍</span>
                <div>
                  <h3>آدرس</h3>
                  <p><?php echo e($exchangeOffice->getFullAddress()); ?></p>
                </div>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($exchangeOffice->phone)): ?>
              <div class="info-item">
                <span class="info-icon">📞</span>
                <div>
                  <h3>تلفن</h3>
                  <p><a href="tel:<?php echo e(preg_replace('/\s+/', '', $exchangeOffice->phone)); ?>" dir="ltr" class="landing-cta" data-click-type="call"><?php echo e($exchangeOffice->phone); ?></a></p>
                </div>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($exchangeOffice->email)): ?>
              <div class="info-item">
                <span class="info-icon">✉️</span>
                <div>
                  <h3>ایمیل</h3>
                  <p><a href="mailto:<?php echo e($exchangeOffice->email); ?>"><?php echo e($exchangeOffice->email); ?></a></p>
                </div>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>
          <div class="contact-form-wrap">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('contact_success')): ?>
              <p class="contact-form-msg contact-form-msg--success"><?php echo e(session('contact_success')); ?></p>
            <?php elseif(session('contact_error')): ?>
              <p class="contact-form-msg contact-form-msg--error"><?php echo e(session('contact_error')); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($exchangeOffice->email)): ?>
              <form action="<?php echo e(route('exchanges.contact', $exchangeOffice)); ?>" method="post" class="contact-form" id="contact-form">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                  <label for="contact-name">نام <span class="required">*</span></label>
                  <input type="text" id="contact-name" name="name" value="<?php echo e(old('name')); ?>" required maxlength="255" placeholder="نام شما">
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="form-group">
                  <label for="contact-email">ایمیل <span class="required">*</span></label>
                  <input type="email" id="contact-email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="example@email.com">
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="form-group">
                  <label for="contact-phone">تلفن</label>
                  <input type="text" id="contact-phone" name="phone" value="<?php echo e(old('phone')); ?>" maxlength="50" placeholder="اختیاری" dir="ltr">
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="form-group">
                  <label for="contact-message">پیام <span class="required">*</span></label>
                  <textarea id="contact-message" name="message" rows="4" required maxlength="2000" placeholder="متن پیام خود را بنویسید..."><?php echo e(old('message')); ?></textarea>
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <button type="submit" class="contact-form-submit">ارسال پیام</button>
              </form>
            <?php else: ?>
              <p class="contact-form-fallback">برای تماس با این صرافی از تلفن یا آدرس بالا استفاده کنید.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>
        </div>
      </div>
    </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </main>

  <footer class="footer">
    <div class="container">
      <div class="footer-top">
        <div class="footer-brand">
          <a href="<?php echo e(route('exchanges.index')); ?>" class="footer-logo"><?php echo e($exchangeOffice->name); ?></a>
          <p class="footer-description">
            <?php echo e($exchangeOffice->tagline ?? 'ارائه‌دهنده خدمات تخصصی انتقال ارز با بهترین نرخ، تسویه سریع و پشتیبانی حرفه‌ای.'); ?>

          </p>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array('fca_regulated', $exchangeOffice->features ?? [])): ?>
            <div class="footer-badges">
              <span class="footer-badge">دارای مجوز FCA</span>
            </div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="footer-col">
          <h3 class="footer-title">دسترسی سریع</h3>
          <ul class="footer-links">
            <li><a href="#calculator">ماشین‌حساب نرخ</a></li>
            <li><a href="#services">خدمات صرافی</a></li>
            <li><a href="#about">درباره مجموعه</a></li>
            <li><a href="#location">تماس با ما</a></li>
            <li><a href="<?php echo e(route('exchanges.index')); ?>">بازگشت به دایرکتوری</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h3 class="footer-title">اطلاعات تماس</h3>
          <ul class="footer-contact">
            <li><span class="footer-contact-label">آدرس:</span> <span><?php echo e($exchangeOffice->getFullAddress() ?: '—'); ?></span></li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exchangeOffice->phone): ?>
              <li><span class="footer-contact-label">تلفن:</span> <a href="tel:<?php echo e(preg_replace('/\s+/', '', $exchangeOffice->phone)); ?>" dir="ltr"><?php echo e($exchangeOffice->phone); ?></a></li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exchangeOffice->email): ?>
              <li><span class="footer-contact-label">ایمیل:</span> <a href="mailto:<?php echo e($exchangeOffice->email); ?>"><?php echo e($exchangeOffice->email); ?></a></li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </ul>
        </div>
        <div class="footer-col">
          <h3 class="footer-title">شبکه‌های اجتماعی</h3>
          <div class="social-links">
            <a href="https://t.me/" target="_blank" rel="noopener noreferrer" aria-label="تلگرام">
              <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0C5.372 0 0 5.372 0 12s5.372 12 12 12 12-5.372 12-12S18.628 0 12 0zm5.659 8.073l-1.969 9.284c-.149.658-.538.82-1.089.51l-3.012-2.22-1.453 1.398c-.161.161-.296.296-.607.296l.216-3.066 5.58-5.04c.243-.216-.054-.337-.378-.121l-6.894 4.34-2.971-.928c-.646-.203-.659-.646.135-.955l11.614-4.479c.538-.203 1.008.121.828.981z"/></svg>
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($whatsappUrl): ?>
              <a href="<?php echo e($whatsappUrl); ?>" target="_blank" rel="noopener noreferrer" aria-label="واتساپ">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2c-5.49 0-9.94 4.45-9.94 9.94 0 1.75.46 3.47 1.33 4.98L2 22l5.23-1.36a9.9 9.9 0 0 0 4.81 1.23h.01c5.49 0 9.95-4.45 9.95-9.94C22 6.45 17.53 2 12.04 2zm5.79 14.05c-.24.69-1.39 1.31-1.92 1.39-.49.07-1.12.1-1.81-.12-.42-.13-.96-.32-1.66-.62-2.92-1.26-4.83-4.2-4.98-4.4-.14-.2-1.19-1.58-1.19-3.01s.75-2.13 1.02-2.42c.26-.29.57-.36.76-.36.19 0 .38 0 .55.01.18.01.42-.07.66.5.24.57.81 1.98.88 2.12.07.14.12.31.02.5-.1.19-.14.31-.29.48-.14.17-.3.38-.42.5-.14.14-.28.29-.12.57.17.29.73 1.2 1.56 1.94 1.07.95 1.97 1.24 2.25 1.38.29.14.45.12.62-.07.17-.19.72-.84.91-1.13.19-.29.38-.24.64-.14.26.1 1.67.79 1.95.94.29.14.48.21.55.33.07.12.07.67-.17 1.35z"/></svg>
              </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p class="copyright">© <span id="year"></span> <?php echo e($exchangeOffice->name); ?>. تمامی حقوق محفوظ است.</p>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exchangeOffice->fca_number): ?>
          <p class="footer-regulation">Registered in UK | Regulated by FCA</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
    </div>
  </footer>

  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($whatsappUrl): ?>
  <a class="floating-whatsapp" href="<?php echo e($whatsappUrl); ?>" target="_blank" rel="noopener noreferrer" aria-label="تماس سریع واتساپ" title="تماس سریع واتساپ">
    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2c-5.49 0-9.94 4.45-9.94 9.94 0 1.75.46 3.47 1.33 4.98L2 22l5.23-1.36a9.9 9.9 0 0 0 4.81 1.23h.01c5.49 0 9.95-4.45 9.95-9.94C22 6.45 17.53 2 12.04 2zm5.79 14.05c-.24.69-1.39 1.31-1.92 1.39-.49.07-1.12.1-1.81-.12-.42-.13-.96-.32-1.66-.62-2.92-1.26-4.83-4.2-4.98-4.4-.14-.2-1.19-1.58-1.19-3.01s.75-2.13 1.02-2.42c.26-.29.57-.36.76-.36.19 0 .38 0 .55.01.18.01.42-.07.66.5.24.57.81 1.98.88 2.12.07.14.12.31.02.5-.1.19-.14.31-.29.48-.14.17-.3.38-.42.5-.14.14-.28.29-.12.57.17.29.73 1.2 1.56 1.94 1.07.95 1.97 1.24 2.25 1.38.29.14.45.12.62-.07.17-.19.72-.84.91-1.13.19-.29.38-.24.64-.14.26.1 1.67.79 1.95.94.29.14.48.21.55.33.07.12.07.67-.17 1.35z"/></svg>
    <span>تماس سریع</span>
  </a>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

  <script>
    (function () {
      "use strict";
      var RATE = <?php echo e($displayRate); ?>;
      var BUY_RATE = <?php echo e($buyRate); ?>;
      var SELL_RATE = <?php echo e($sellRateVal); ?>;
      var conversionDirection = "gbpToIrt";
      var gbpEl = document.getElementById("gbp");
      var irtEl = document.getElementById("irt");
      var rateEl = document.getElementById("rateDisplay");
      var buyRateEl = document.getElementById("buyRateDisplay");
      var sellRateEl = document.getElementById("sellRateDisplay");
      var timeEl = document.getElementById("timeDisplay");
      var directionToggleEl = document.getElementById("directionToggle");
      var directionLabelEl = document.getElementById("directionLabel");
      var clickUrl = <?php echo json_encode($clickUrl, 15, 512) ?>;

      function toEnglishDigits(value) {
        return String(value || "").replace(/[۰-۹]/g, function (d) { return "۰۱۲۳۴۵۶۷۸۹".indexOf(d); }).replace(/[٠-٩]/g, function (d) { return "٠١٢٣٤٥٦٧٨٩".indexOf(d); });
      }
      function formatIrt(num) { return Number(num || 0).toLocaleString("fa-IR"); }
      function sanitizeGbpInput(value) {
        var normalized = toEnglishDigits(value).replace(/[٬,]/g, "").replace(/[^\d.]/g, "");
        var pieces = normalized.split(".");
        if (pieces.length > 2) normalized = pieces.shift() + "." + pieces.join("");
        return normalized;
      }
      function sanitizeIrtInput(value) {
        var normalized = toEnglishDigits(value).replace(/[^\d]/g, "");
        if (!normalized) return "";
        return Number(normalized).toLocaleString("fa-IR");
      }
      function parseIrt(str) { return parseInt(toEnglishDigits(str).replace(/[^\d]/g, ""), 10) || 0; }
      function parseGbp(str) { return parseFloat(sanitizeGbpInput(str)) || 0; }
      function updateFromGbp() {
        if (!gbpEl || !irtEl) return;
        var gbp = parseGbp(gbpEl.value);
        irtEl.value = gbp > 0 ? formatIrt(Math.round(gbp * RATE)) : "";
      }
      function updateFromIrt() {
        if (!gbpEl || !irtEl) return;
        var irt = parseIrt(irtEl.value);
        gbpEl.value = irt > 0 ? (irt / RATE).toFixed(2) : "";
      }
      function recalculateByDirection() {
        if (conversionDirection === "gbpToIrt") updateFromGbp();
        else updateFromIrt();
      }
      function updateDirectionUI() {
        var isGbpToIrt = conversionDirection === "gbpToIrt";
        if (directionLabelEl) directionLabelEl.textContent = isGbpToIrt ? "پوند → تومان" : "تومان → پوند";
        if (directionToggleEl) directionToggleEl.setAttribute("aria-pressed", String(!isGbpToIrt));
        if (gbpEl) { gbpEl.readOnly = !isGbpToIrt; gbpEl.classList.toggle("is-readonly", !isGbpToIrt); }
        if (irtEl) { irtEl.readOnly = isGbpToIrt; irtEl.classList.toggle("is-readonly", isGbpToIrt); }
      }
      function toggleDirection() {
        conversionDirection = conversionDirection === "gbpToIrt" ? "irtToGbp" : "gbpToIrt";
        updateDirectionUI();
        recalculateByDirection();
      }
      function setTime() {
        var now = new Date();
        if (timeEl) timeEl.textContent = String(now.getHours()).padStart(2, "0") + ":" + String(now.getMinutes()).padStart(2, "0");
      }
      function applyRates() {
        if (rateEl) rateEl.textContent = formatIrt(RATE);
        if (buyRateEl) buyRateEl.textContent = formatIrt(BUY_RATE) + " تومان";
        if (sellRateEl) sellRateEl.textContent = formatIrt(SELL_RATE) + " تومان";
        recalculateByDirection();
      }
      if (directionToggleEl) directionToggleEl.addEventListener("click", toggleDirection);
      if (gbpEl) {
        gbpEl.addEventListener("input", function () {
          if (conversionDirection !== "gbpToIrt") return;
          gbpEl.value = sanitizeGbpInput(gbpEl.value);
          updateFromGbp();
        });
      }
      if (irtEl) {
        irtEl.addEventListener("input", function () {
          if (conversionDirection !== "irtToGbp") return;
          irtEl.value = sanitizeIrtInput(irtEl.value);
          updateFromIrt();
        });
      }
      updateDirectionUI();
      if (gbpEl) gbpEl.value = sanitizeGbpInput(gbpEl.value);
      setTime();
      applyRates();
      var yearEl = document.getElementById("year");
      if (yearEl) yearEl.textContent = new Date().getFullYear();
      var header = document.getElementById("header");
      if (header) {
        window.addEventListener("scroll", function () { header.classList.toggle("scrolled", window.scrollY > 20); });
      }
      var toggle = document.querySelector(".menu-toggle");
      var mobileNav = document.querySelector(".nav-mobile");
      if (toggle && mobileNav) {
        toggle.addEventListener("click", function () { mobileNav.classList.toggle("open"); });
      }
      document.querySelectorAll(".nav-mobile a").forEach(function (link) {
        link.addEventListener("click", function () { if (mobileNav) mobileNav.classList.remove("open"); });
      });
      document.querySelectorAll(".landing-cta[data-click-type]").forEach(function (el) {
        el.addEventListener("click", function () {
          var t = el.getAttribute("data-click-type") || "view";
          fetch(clickUrl + "?type=" + t, { method: "GET", headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json" } }).catch(function () {});
        });
      });
    })();
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Volumes/G-DRIVE  SSD  1TB/Exchange Landing/exchange-backend/resources/views/exchanges/show-theme2.blade.php ENDPATH**/ ?>