<nav class="dashboard-nav">
  <button type="button" class="dashboard-menu-toggle" id="dashboard-menu-toggle" aria-label="باز کردن منو" aria-expanded="false">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="22" height="22"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
  </button>
  <div class="dashboard-nav-inner">
    <span class="dashboard-logo dashboard-nav-logo">آقای صرافی</span>
    <div class="dashboard-nav-breadcrumb">
      <span class="dashboard-nav-breadcrumb-sep" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M9 18l6-6-6-6"/></svg>
      </span>
      <h1 class="dashboard-nav-title">{{ $nav_title ?? 'پنل مدیریت' }}</h1>
    </div>
  </div>
  <div class="dashboard-nav-actions">
    @auth('exchange')
      @if(isset($office) && $office->name)
        <div class="dashboard-nav-user">
          <div class="dashboard-nav-user-avatar" aria-hidden="true">
            @if(isset($office) && $office->logoUrl())
              <img src="{{ $office->logoUrl() }}" alt="" width="32" height="32" style="border-radius:50%;object-fit:cover;width:32px;height:32px;">
            @else
              {{ isset($office) ? (mb_substr($office->name, 0, 1) ?: '؟') : '؟' }}
            @endif
          </div>
          <span class="dashboard-nav-user-name">{{ $office->name }}</span>
        </div>
      @endif
    @endauth
    <a href="{{ $nav_back_url ?? route('dashboard.index') }}" class="dashboard-back">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      <span class="dashboard-back-label">بازگشت</span>
    </a>
  </div>
</nav>
