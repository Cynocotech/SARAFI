@extends('layouts.app')
@section('title', $exchangeOffice->name . ' | بهترین نرخ ارز')
@section('meta_description', Str::limit(strip_tags($exchangeOffice->about ?? $exchangeOffice->name . ' - نرخ خرید و فروش ارز'), 160))
@section('styles')
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  {{-- Material Symbols (icon font) loaded locally if needed --}}
  <link href="{{ asset('css/sarafi-landing.css') }}" rel="stylesheet">
@endsection
@section('body_start')
  <header class="sticky top-0 z-50 border-b-2" style="background-color: var(--color-header); border-color: var(--color-header-border);">
    <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16 md:h-20">
      <a href="#home" class="flex items-center gap-3">
        @if($exchangeOffice->logoUrl())
          <img src="{{ $exchangeOffice->logoUrl() }}" alt="" class="w-10 h-10 md:w-12 md:h-12 rounded-lg object-cover flex-shrink-0" style="border: 1px solid var(--color-border);">
        @else
          <div class="logo-mark"></div>
        @endif
        <div>
          <span class="font-bold text-lg md:text-xl text-white block">{{ $exchangeOffice->name }}</span>
          <span class="text-xs text-slate-400 hidden sm:inline">تبدیل و حواله ارز</span>
        </div>
      </a>
      <nav class="hidden md:flex items-center gap-1">
        <a href="#home" class="nav-link px-4 py-2 text-slate-300 transition text-sm">خانه</a>
        <a href="#calculator" class="nav-link px-4 py-2 text-slate-300 transition text-sm">ماشین‌حساب</a>
        <a href="#rates" class="nav-link px-4 py-2 text-slate-300 transition text-sm">قیمت ارز</a>
        <a href="#services" class="nav-link px-4 py-2 text-slate-300 transition text-sm">خدمات ارزی</a>
        <a href="#about" class="nav-link px-4 py-2 text-slate-300 transition text-sm">درباره ما</a>
      </nav>
      <div class="hidden md:flex items-center gap-2">
        <button id="theme-toggle" class="p-2.5 rounded-lg text-slate-300 hover:text-white transition border border-slate-600 hover:border-slate-500" title="حالت روشن" aria-label="حالت روشن">
          <svg id="theme-icon-sun" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          <svg id="theme-icon-moon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        </button>
        <a href="{{ route('exchanges.index') }}" class="text-slate-300 hover:text-white text-sm font-medium px-4 py-2">بازگشت به دایرکتوری</a>
        @if($exchangeOffice->phone)
          <a href="tel:{{ $exchangeOffice->phone }}" class="btn-gold inline-flex landing-cta" data-click-type="call">تماس</a>
        @else
          <a href="#contact" class="btn-gold inline-flex">تماس و آدرس</a>
        @endif
      </div>
      <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-400 hover:text-white" aria-label="منو">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
    <div id="mobile-menu" class="hidden md:hidden border-t px-4 py-3" style="background-color: var(--color-bg-section-alt); border-color: var(--color-border);">
      <a href="#home" class="block py-2 text-slate-300">خانه</a>
      <a href="#calculator" class="block py-2 text-slate-300">ماشین‌حساب</a>
      <a href="#rates" class="block py-2 text-slate-300">قیمت ارز</a>
      <a href="#services" class="block py-2 text-slate-300">خدمات ارزی</a>
      <a href="#about" class="block py-2 text-slate-300">درباره ما</a>
      <a href="{{ route('exchanges.index') }}" class="block py-2 text-slate-300">بازگشت به دایرکتوری</a>
      <button id="theme-toggle-mobile" class="block py-2 text-slate-300 w-full text-right">حالت روشن</button>
      @if($exchangeOffice->phone)
        <a href="tel:{{ $exchangeOffice->phone }}" class="block py-2 btn-gold inline-block mt-2 landing-cta" data-click-type="call">تماس</a>
      @else
        <a href="#contact" class="block py-2 btn-gold inline-block mt-2">تماس و آدرس</a>
      @endif
    </div>
  </header>
@endsection
@section('content')
  @php
    $currencyNames = [
      'GBP' => 'پوند انگلیس',
      'USD' => 'دلار آمریکا',
      'EUR' => 'یورو',
      'AED' => 'درهم امارات',
      'CAD' => 'دلار کانادا',
      'TRY' => 'لیر ترکیه',
      'IRR' => 'تومان',
    ];
    $ratesToIrr = $exchangeOffice->exchangeRates->where('to_currency', 'IRR')->keyBy('from_currency');
    $featureLabels = $exchangeOffice->getExchangeFeatureLabels();
    $osmMapUrl = $exchangeOffice->getOpenStreetMapSearchUrl();
    $osmEmbedUrl = $exchangeOffice->getOpenStreetMapEmbedUrl();
  @endphp
  <main class="scroll-smooth antialiased">
    {{-- Hero --}}
    <section id="home" class="py-16 md:py-24 bg-exchange-pattern relative overflow-hidden section-bg">
      <div class="absolute top-4 left-4 text-4xl font-bold select-none text-heading" style="opacity: 0.08;" dir="ltr">$ € £ ¥</div>
      <div class="absolute bottom-8 right-8 text-3xl font-bold select-none text-heading" style="opacity: 0.08;">₺ AED</div>
      <div class="max-w-6xl mx-auto px-4 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
          <div>
            <div class="flex flex-wrap gap-2 mb-4">
              <span class="live-badge">نرخ لحظه‌ای</span>
              @if($exchangeOffice->identity_verified)
                <span class="trust-badge">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                  صرافی تأیید شده
                </span>
              @endif
              <span class="trust-badge">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                تبدیل آنی
              </span>
            </div>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-heading leading-tight mb-4">
              {{ $exchangeOffice->hero_title ?? $exchangeOffice->name }}<br>
              <span class="hero-subtitle">{{ $exchangeOffice->hero_subtitle ?? $exchangeOffice->tagline ?? 'با بهترین نرخ' }}</span>
            </h1>
            <p class="text-body leading-relaxed mb-8">
              @if(filled($exchangeOffice->about))
                {{ Str::limit(strip_tags($exchangeOffice->about), 220) }}
              @else
                به امن‌ترین شکل و از مجاری کاملاً حقوقی، پول شما را انتقال می‌دهیم. دلار، یورو، پوند و سایر ارزها — یک صرافی به وسعت دنیا.
              @endif
            </p>
            <a href="#calculator" class="btn-gold inline-flex items-center gap-2 px-6 py-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
              محاسبه نرخ ارز
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            </a>
          </div>
          <div class="relative">
            <div class="aspect-video md:aspect-[4/3] rounded-xl overflow-hidden border-2 shadow-xl exchange-card section-alt" style="border-color: var(--color-border);">
              @if($exchangeOffice->heroImageUrl())
                <img src="{{ $exchangeOffice->heroImageUrl() }}" alt="{{ $exchangeOffice->name }}" class="w-full h-full object-cover" loading="eager" width="800" height="600">
              @else
                <img src="https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=800&q=85" alt="تبدیل ارز و حواله" class="w-full h-full object-cover" loading="eager" width="800" height="600">
              @endif
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- Live Rates --}}
    @if(($exchangeOffice->landing_show_rates ?? true) && $exchangeOffice->exchangeRates->isNotEmpty())
    <section id="rates" class="py-12 md:py-16 section-alt bg-exchange-pattern">
      <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
          <h2 class="text-2xl md:text-3xl font-bold text-heading">قیمت خرید و فروش ارز</h2>
          <span class="live-badge">نرخ لحظه‌ای — به‌روزرسانی روزانه</span>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
          @foreach($exchangeOffice->exchangeRates as $r)
            @php
              $fromName = $currencyNames[$r->from_currency] ?? $r->from_currency;
            @endphp
            <div class="rounded-xl border-2 p-4 shadow-sm exchange-card section-bg" style="border-color: var(--color-border);">
              <div class="flex items-center gap-2 mb-3">
                <span class="font-bold text-heading">{{ $fromName }}</span>
                <span class="text-body text-sm">{{ $r->from_currency }} → {{ $r->to_currency }}</span>
              </div>
              <p class="text-sm text-body mb-2">آخرین بروزرسانی: {{ now()->locale('fa')->isoFormat('YYYY MMMM D - HH:mm') }}</p>
              <p class="text-buy font-semibold text-sm">خرید: {{ farsi_num(number_format($r->buy_rate, 0)) }} تومان</p>
              <p class="text-sell font-semibold text-sm">فروش: {{ farsi_num(number_format($r->sell_rate, 0)) }} تومان</p>
            </div>
          @endforeach
        </div>
      </div>
    </section>
    @endif

    {{-- Currency Calculator --}}
    @if($exchangeOffice->landing_show_calculator ?? true)
    <section id="calculator" class="py-16 md:py-24 section-alt bg-exchange-pattern">
      <div class="max-w-6xl mx-auto px-4">
        <div class="max-w-xl mx-auto">
          <div class="flex items-center gap-2 mb-2">
            <span class="calc-convert-icon">⇄</span>
            <h2 class="text-2xl md:text-3xl font-bold text-heading">ماشین حساب تبدیل ارز</h2>
          </div>
          <p class="text-body mb-6">به راحتی مبلغ را تبدیل کنید — دلار، یورو، پوند و سایر ارزها به تومان.</p>
          <div class="rounded-2xl shadow-xl border-2 p-6 md:p-8 exchange-card section-bg" style="border-color: var(--color-border);">
            @if($ratesToIrr->isNotEmpty())
              @php $first = $ratesToIrr->first(); @endphp
              <p id="rate-hint" class="text-xs text-body mb-4 text-center font-medium" dir="ltr">۱ {{ $first->from_currency }} ≈ {{ farsi_num(number_format($first->buy_rate, 0)) }} تومان</p>
            @else
              <p id="rate-hint" class="text-xs text-body mb-4 text-center font-medium">نرخ ثبت نشده است.</p>
            @endif
            <div class="space-y-4">
              <div>
                <label for="amount" class="block text-sm font-medium text-body mb-1">مبلغ</label>
                <input type="number" id="amount" min="0" step="0.01" placeholder="۰" class="input-light" dir="ltr" value="100">
              </div>
              <div>
                <label for="from-currency" class="block text-sm font-medium text-body mb-1">ارز مبدأ</label>
                <select id="from-currency" class="input-light py-3 cursor-pointer">
                  @foreach($ratesToIrr as $code => $rate)
                    <option value="{{ $code }}" data-buy="{{ (float)$rate->buy_rate }}" data-sell="{{ (float)$rate->sell_rate }}">
                      {{ $currencyNames[$code] ?? $code }} ({{ $code }})
                    </option>
                  @endforeach
                  @if($ratesToIrr->isEmpty())
                    <option value="">— نرخ ثبت نشده —</option>
                  @endif
                </select>
              </div>
              <div>
                <p class="text-sm text-body mb-1">معادل به تومان <span class="text-xs opacity-80">(IRR)</span></p>
                <p id="result" class="result-display text-xl md:text-2xl font-bold font-mono-num border rounded-xl px-4 py-3 text-center" dir="ltr">۰</p>
              </div>
              <button type="button" id="calc-btn" class="w-full btn-gold py-3 rounded-xl text-base">
                محاسبه
              </button>
            </div>
            <p class="text-xs text-body mt-4 text-center">نرخ جهت راهنماست. برای نرخ دقیق با صرافی تماس بگیرید.</p>
          </div>
        </div>
      </div>
    </section>
    @endif

    {{-- Services / خدمات ما --}}
    <section id="services" class="py-16 md:py-24 section-bg bg-exchange-pattern">
      <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-heading text-center mb-4">خدمات ما</h2>
        <p class="text-body text-center mb-12 max-w-xl mx-auto">با اطمینان از کیفیت و امنیت، بهترین خدمات ارزی را ارائه می‌دهیم</p>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($exchangeOffice->getLandingServices() as $service)
            <article class="card-service">
              <div class="icon-bg w-14 h-14 rounded-xl flex items-center justify-center mb-4 icon-accent">
                <span class="material-symbols-outlined text-3xl" style="font-size: 2rem;" aria-hidden="true">{{ $service['icon'] }}</span>
              </div>
              <h3 class="text-lg font-bold text-heading mb-2">{{ $service['title'] }}</h3>
              <p class="text-body text-sm leading-relaxed">{{ $service['description'] ?: 'خدمات با کیفیت و شفاف.' }}</p>
            </article>
          @endforeach
        </div>
      </div>
    </section>

    {{-- About --}}
    <section id="about" class="py-16 md:py-24 section-alt bg-exchange-pattern">
      <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-heading mb-6 text-center">درباره {{ $exchangeOffice->name }}</h2>
        <div class="flex flex-col md:flex-row items-center gap-10 md:gap-12">
          <div class="flex-1 text-center md:text-right">
            <p class="text-body max-w-2xl mx-auto md:mr-0 leading-relaxed">
              @if(filled($exchangeOffice->about))
                {!! nl2br(e($exchangeOffice->about)) !!}
              @else
                {{ $exchangeOffice->name }} با سال‌ها تجربه در <strong class="text-heading">تبدیل و حواله ارز</strong>، متعهد به ارائه نرخ‌های رقابتی و خدمات مطمئن است. خرید و فروش دلار، یورو، پوند و سایر ارزها با شفافیت و امنیت — حواله به سراسر جهان.
              @endif
            </p>
          </div>
          <div class="flex-shrink-0 w-full max-w-sm md:max-w-md">
            <img src="{{ $exchangeOffice->aboutImageUrl() }}" alt="" class="w-full h-auto object-contain" loading="lazy" width="560" height="560">
          </div>
        </div>
      </div>
    </section>
  </main>

  {{-- Footer / Contact --}}
  <footer id="contact" class="py-12 md:py-16" style="background-color: var(--color-header); color: var(--color-text-muted);">
    <div class="max-w-6xl mx-auto px-4">
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-10 mb-10">
        <div>
          <div class="flex items-center gap-3 mb-4">
            @if($exchangeOffice->logoUrl())
              <img src="{{ $exchangeOffice->logoUrl() }}" alt="" class="w-10 h-10 rounded-lg object-cover flex-shrink-0" style="border: 1px solid var(--color-border);">
            @else
              <div class="logo-mark flex-shrink-0"></div>
            @endif
            <span class="font-bold text-heading">{{ $exchangeOffice->name }}</span>
          </div>
          <p class="text-body text-sm leading-relaxed">{{ $exchangeOffice->tagline ?? 'تبدیل ارز و حواله بین‌المللی — انتقال سریع پول با بهترین نرخ.' }}</p>
          <div class="mt-4 flex flex-wrap gap-2">
            <a href="{{ route('exchanges.index') }}" class="text-body hover:opacity-80 text-sm">دایرکتوری</a>
            <span>·</span>
            <a href="#about" class="text-body hover:opacity-80 text-sm">درباره ما</a>
          </div>
        </div>
        <div>
          <h4 class="font-semibold text-heading mb-3 flex items-center gap-2">
            <svg class="footer-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            آدرس
          </h4>
          <p class="text-body text-sm">{{ $exchangeOffice->getFullAddress() ?: '—' }}</p>
        </div>
        <div>
          <h4 class="font-semibold text-heading mb-3 flex items-center gap-2">
            <svg class="footer-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            تماس
          </h4>
          @if($exchangeOffice->phone)
            <p class="text-body text-sm" dir="ltr"><a href="tel:{{ $exchangeOffice->phone }}" class="hover:opacity-90 landing-cta" data-click-type="call">{{ $exchangeOffice->phone }}</a></p>
          @endif
          @if($exchangeOffice->email)
            <p class="text-body text-sm mt-1" dir="ltr"><a href="mailto:{{ $exchangeOffice->email }}" class="hover:opacity-90">{{ $exchangeOffice->email }}</a></p>
          @endif
          @if(!$exchangeOffice->phone && !$exchangeOffice->email)
            <p class="text-body text-sm">—</p>
          @endif
        </div>
        <div>
          <h4 class="font-semibold text-heading mb-3">دسترسی</h4>
          @if($exchangeOffice->phone)
            <a href="tel:{{ $exchangeOffice->phone }}" class="btn-gold inline-flex landing-cta" data-click-type="call">تماس</a>
          @else
            <p class="text-body text-sm">—</p>
          @endif
        </div>
      </div>

      {{-- Contact form --}}
      @if(filled($exchangeOffice->email) && ($exchangeOffice->landing_show_contact ?? true))
      <div id="location" class="border-t pt-10 mt-6" style="border-color: var(--color-border);">
        <h4 class="font-semibold text-heading mb-4">ارسال پیام</h4>
        @if(session('contact_success'))
          <p class="text-green-600 dark:text-green-400 text-sm mb-4">{{ session('contact_success') }}</p>
        @elseif(session('contact_error'))
          <p class="text-red-600 dark:text-red-400 text-sm mb-4">{{ session('contact_error') }}</p>
        @endif
        <form action="{{ route('exchanges.contact', $exchangeOffice) }}" method="post" class="max-w-xl space-y-4">
          @csrf
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label for="contact-name" class="block text-sm font-medium text-heading mb-1">نام <span class="text-red-500">*</span></label>
              <input type="text" id="contact-name" name="name" value="{{ old('name') }}" required maxlength="255" class="w-full px-3 py-2 border rounded-lg text-body" style="border-color: var(--color-border); background: var(--color-bg);">
              @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label for="contact-email" class="block text-sm font-medium text-heading mb-1">ایمیل <span class="text-red-500">*</span></label>
              <input type="email" id="contact-email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border rounded-lg text-body" style="border-color: var(--color-border); background: var(--color-bg);">
              @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
          </div>
          <div>
            <label for="contact-phone" class="block text-sm font-medium text-heading mb-1">تلفن</label>
            <input type="text" id="contact-phone" name="phone" value="{{ old('phone') }}" maxlength="50" dir="ltr" class="w-full px-3 py-2 border rounded-lg text-body" style="border-color: var(--color-border); background: var(--color-bg);">
            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="contact-message" class="block text-sm font-medium text-heading mb-1">پیام <span class="text-red-500">*</span></label>
            <textarea id="contact-message" name="message" rows="4" required maxlength="2000" class="w-full px-3 py-2 border rounded-lg text-body" style="border-color: var(--color-border); background: var(--color-bg);">{{ old('message') }}</textarea>
            @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          <button type="submit" class="btn-gold inline-flex">ارسال پیام</button>
        </form>
      </div>
      @endif

      <div class="border-t pt-8 text-center text-sm mt-10" style="border-color: var(--color-border); color: var(--color-text-muted);">
        <p>© <span id="footer-year"></span> {{ $exchangeOffice->name }}. تمامی حقوق محفوظ است.</p>
      </div>
    </div>
  </footer>

  <script>
    (function () {
      var ratesByCode = {};
      @foreach($ratesToIrr as $code => $rate)
        ratesByCode['{{ $code }}'] = { buy: {{ (float)$rate->buy_rate }}, sell: {{ (float)$rate->sell_rate }} };
      @endforeach
      function faNum(n) {
        if (n === undefined || n === null || isNaN(n)) return '۰';
        return Number(n).toLocaleString('fa-IR', { maximumFractionDigits: 0 });
      }
      var amountEl = document.getElementById('amount');
      var fromCurrencyEl = document.getElementById('from-currency');
      var resultEl = document.getElementById('result');
      var calcBtn = document.getElementById('calc-btn');
      var rateHint = document.getElementById('rate-hint');

      function runConversion() {
        if (!amountEl || !fromCurrencyEl || !resultEl) return;
        var amount = parseFloat(amountEl.value) || 0;
        var code = fromCurrencyEl.value;
        var rates = ratesByCode[code];
        if (!rates) {
          resultEl.textContent = '۰';
          return;
        }
        var total = amount * rates.buy;
        resultEl.textContent = faNum(Math.round(total));
        resultEl.setAttribute('dir', 'ltr');
      }

      function updateRateHint() {
        if (!rateHint || !fromCurrencyEl) return;
        var code = fromCurrencyEl.value;
        var rates = ratesByCode[code];
        if (rates) rateHint.textContent = '۱ ' + code + ' ≈ ' + faNum(rates.buy) + ' تومان';
        rateHint.setAttribute('dir', 'ltr');
      }

      if (calcBtn) calcBtn.addEventListener('click', runConversion);
      if (amountEl) {
        amountEl.addEventListener('input', runConversion);
        amountEl.addEventListener('keydown', function (e) { if (e.key === 'Enter') runConversion(); });
      }
      if (fromCurrencyEl) {
        fromCurrencyEl.addEventListener('change', function () {
          runConversion();
          updateRateHint();
        });
      }
      runConversion();
      updateRateHint();
    })();
  </script>
  <script>
    (function () {
      var mobileMenuBtn = document.getElementById('mobile-menu-btn');
      var mobileMenu = document.getElementById('mobile-menu');
      if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function () { mobileMenu.classList.toggle('hidden'); });
        document.querySelectorAll('#mobile-menu a, #mobile-menu button').forEach(function (el) {
          el.addEventListener('click', function () { mobileMenu.classList.add('hidden'); });
        });
      }

      function setTheme(theme) {
        document.body.setAttribute('data-theme', theme);
        document.documentElement.setAttribute('data-theme', theme);
        try { localStorage.setItem('theme', theme); } catch (e) {}
        var sun = document.getElementById('theme-icon-sun');
        var moon = document.getElementById('theme-icon-moon');
        var mobileBtn = document.getElementById('theme-toggle-mobile');
        if (theme === 'light') {
          if (sun) sun.classList.add('hidden');
          if (moon) moon.classList.remove('hidden');
          if (mobileBtn) mobileBtn.textContent = 'حالت تاریک';
        } else {
          if (sun) sun.classList.remove('hidden');
          if (moon) moon.classList.add('hidden');
          if (mobileBtn) mobileBtn.textContent = 'حالت روشن';
        }
      }
      function toggleTheme() {
        var current = document.body.getAttribute('data-theme') || document.documentElement.getAttribute('data-theme') || 'dark';
        setTheme(current === 'dark' ? 'light' : 'dark');
      }
      document.getElementById('theme-toggle') && document.getElementById('theme-toggle').addEventListener('click', toggleTheme);
      document.getElementById('theme-toggle-mobile') && document.getElementById('theme-toggle-mobile').addEventListener('click', function () {
        toggleTheme();
        if (mobileMenu) mobileMenu.classList.add('hidden');
      });
      try {
        var saved = localStorage.getItem('theme');
        if (saved === 'light' || saved === 'dark') setTheme(saved);
        else setTheme(document.body.getAttribute('data-theme') || 'dark');
      } catch (e) {
        setTheme('dark');
      }

      var yearEl = document.getElementById('footer-year');
      if (yearEl) yearEl.textContent = new Date().getFullYear();

      document.querySelectorAll('.landing-cta[data-click-type]').forEach(function (a) {
        a.addEventListener('click', function () {
          var type = this.getAttribute('data-click-type');
          if (type && type !== 'view') {
            fetch('{{ route("exchanges.click", $exchangeOffice) }}?type=' + type, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } }).catch(function () {});
          }
        });
      });
    })();
  </script>
@endsection
