<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="theme-color" content="#f0f9ff">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'پنل مدیریت')</title>
  <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard-extra.css') }}">
  @stack('styles')
</head>
<body class="dashboard-page">
  <div class="dashboard @guest('exchange') dashboard--no-sidebar @endguest">
    @auth('exchange')
    @include('partials.dashboard-sidebar')
    @endauth
    @include('partials.dashboard-bottom-nav')
    <div class="dashboard-body">
      @include('partials.dashboard-nav', [
        'nav_title' => trim($__env->yieldContent('dashboard_nav_title')) ?: 'پنل مدیریت',
        'nav_back_url' => trim($__env->yieldContent('dashboard_nav_back')) ?: route('dashboard.index'),
      ])
      <main class="dashboard-main {{ trim($__env->yieldContent('dashboard_main_class')) }}">
        <header class="dashboard-main-header">
          <h2 class="dashboard-page-title">{{ trim($__env->yieldContent('dashboard_nav_title')) ?: 'پنل مدیریت' }}</h2>
        </header>
        @if(session('impersonating') && isset($office))
          <div class="impersonation-banner" style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); color: #b45309; padding: 0.6rem 1rem; margin: 0 1.5rem 1rem; border-radius: var(--radius); font-size: 0.9rem; display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem;">
            <span>در حال مشاهده پنل به‌عنوان «{{ $office->name }}» (ورود ادمین)</span>
            <a href="{{ route('impersonate.leave') }}" style="font-weight: 700; color: inherit; text-decoration: underline;">خروج و بازگشت به پنل ادمین</a>
          </div>
        @endif
        @yield('content')
      </main>
    </div>
  </div>
  @stack('scripts')
  <script>
    (function(){
      document.documentElement.setAttribute('data-theme', 'light');
      var m = document.querySelector('meta[name="theme-color"]');
      if (m) m.setAttribute('content', '#f0f9ff');
      var side = document.getElementById('dashboard-side');
      var back = document.getElementById('dashboard-backdrop');
      var toggle = document.getElementById('dashboard-menu-toggle');
      function openMenu() {
        if (side) side.classList.add('is-open');
        if (back) { back.classList.add('is-open'); back.setAttribute('aria-hidden', 'false'); }
        if (toggle) toggle.setAttribute('aria-expanded', 'true');
        document.body.classList.add('dashboard-menu-open');
      }
      function closeMenu() {
        if (side) side.classList.remove('is-open');
        if (back) { back.classList.remove('is-open'); back.setAttribute('aria-hidden', 'true'); }
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('dashboard-menu-open');
      }
      if (toggle) toggle.addEventListener('click', openMenu);
      if (back) back.addEventListener('click', closeMenu);
      if (side) {
        side.querySelectorAll('a').forEach(function(a) { a.addEventListener('click', closeMenu); });
        side.querySelectorAll('form').forEach(function(form) {
          form.addEventListener('submit', closeMenu);
          var btn = form.querySelector('button[type="submit"]');
          if (btn) btn.addEventListener('click', closeMenu);
        });
        var closeBtn = document.getElementById('dashboard-side-close');
        if (!closeBtn && side.firstChild) {
          closeBtn = document.createElement('button');
          closeBtn.type = 'button';
          closeBtn.id = 'dashboard-side-close';
          closeBtn.setAttribute('aria-label', 'بستن منو');
          closeBtn.className = 'dashboard-side-close';
          closeBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
          closeBtn.addEventListener('click', closeMenu);
          side.insertBefore(closeBtn, side.firstChild);
        }
      }
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && side && side.classList.contains('is-open')) closeMenu();
      });
    })();
  </script>
</body>
</html>
