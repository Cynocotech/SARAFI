<div class="dashboard-side-backdrop" id="dashboard-backdrop" aria-hidden="true"></div>
<aside class="dashboard-side" id="dashboard-side" aria-label="منو">
  <div class="dashboard-side-header">
    <span class="dashboard-logo">آقای صرافی</span>
    <span class="dashboard-side-subtitle">پنل مدیریت صرافی</span>
  </div>
  <nav class="dashboard-side-nav" aria-label="منوی اصلی">
    <span class="dashboard-side-menu-label">منوی اصلی</span>
    <a href="{{ route('dashboard.index') }}" class="menu-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">اطلاعات صرافی</a>
    @if(isset($office) && $office->canUseDigitalSignage())
      <a href="{{ route('dashboard.signage.index') }}" class="menu-link {{ request()->routeIs('dashboard.signage.*') ? 'active' : '' }}">صفحه نمایش دیجیتال</a>
    @endif
    <a href="{{ route('dashboard.landing') }}" class="menu-link {{ request()->routeIs('dashboard.landing*') ? 'active' : '' }}">سایت</a>
    <a href="{{ route('dashboard.subscription') }}" class="menu-link {{ request()->routeIs('dashboard.subscription') ? 'active' : '' }}">اشتراک ماهانه</a>
    <a href="{{ route('dashboard.rates') }}" class="menu-link {{ request()->routeIs('dashboard.rates') || request()->routeIs('dashboard.office-rates') ? 'active' : '' }}">مدیریت نرخ‌ها</a>
    <a href="{{ route('dashboard.rates-history') }}" class="menu-link {{ request()->routeIs('dashboard.rates-history') ? 'active' : '' }}">تاریخچه نرخ‌ها</a>
    <a href="{{ route('dashboard.telegram') }}" class="menu-link {{ request()->routeIs('dashboard.telegram') ? 'active' : '' }}">تلگرام</a>
    <a href="{{ route('dashboard.password') }}" class="menu-link {{ request()->routeIs('dashboard.password') ? 'active' : '' }}">تغییر رمز عبور</a>
    @if(isset($office) && !$office->hasTwoFactorEnabled())
      <a href="{{ route('dashboard.2fa.setup') }}" class="menu-link {{ request()->routeIs('dashboard.2fa.*') ? 'active' : '' }}">Two-Factor Authentication (2FA)</a>
    @endif
  </nav>
  <div class="dashboard-side-footer">
    <span class="dashboard-side-menu-label">خروج و بازگشت</span>
    <a href="{{ route('exchanges.index') }}" class="menu-link">بازگشت به سایت</a>
    <form action="{{ route('exchange.logout') }}" method="POST" style="margin-top:0.25rem;">
      @csrf
      <button type="submit" class="menu-link" style="width:100%;text-align:right;background:none;border:none;cursor:pointer;font:inherit;color:inherit;padding:0.5rem 1rem;">خروج</button>
    </form>
  </div>
</aside>
