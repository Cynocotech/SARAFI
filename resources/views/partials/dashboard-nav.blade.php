<nav class="dashboard-nav">
  <button type="button" class="dashboard-menu-toggle" id="dashboard-menu-toggle" aria-label="باز کردن منو" aria-expanded="false">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
  </button>
  <div class="dashboard-nav-inner">
    <span class="dashboard-logo dashboard-nav-logo">آقای صرافی</span>
    <h1 class="dashboard-nav-title">{{ $nav_title ?? 'پنل مدیریت' }}</h1>
  </div>
  <div class="dashboard-nav-actions">
    <a href="{{ $nav_back_url ?? route('dashboard.index') }}" class="dashboard-back">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      بازگشت
    </a>
  </div>
</nav>
