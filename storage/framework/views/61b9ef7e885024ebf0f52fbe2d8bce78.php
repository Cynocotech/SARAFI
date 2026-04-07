<div class="dashboard-side-backdrop" id="dashboard-backdrop" aria-hidden="true"></div>
<aside class="dashboard-side" id="dashboard-side" aria-label="منو">
  <div class="dashboard-side-header">
    <div class="dashboard-side-brand">
      <div class="dashboard-side-brand-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="28" height="28"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
      </div>
      <div>
        <span class="dashboard-logo">آقای صرافی</span>
        <span class="dashboard-side-subtitle">پنل مدیریت صرافی</span>
      </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($office) && $office->name): ?>
      <div class="dashboard-side-office-chip">
        <div class="dashboard-side-office-avatar" aria-hidden="true">
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->logoUrl()): ?>
            <img src="<?php echo e($office->logoUrl()); ?>" alt="" width="32" height="32" style="border-radius:50%;object-fit:cover;width:32px;height:32px;">
          <?php else: ?>
            <?php echo e(mb_substr($office->name, 0, 1) ?: '؟'); ?>

          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="dashboard-side-office-info">
          <span class="dashboard-side-office-name"><?php echo e($office->name); ?></span>
          <span class="dashboard-side-office-status <?php echo e(($office->status ?? '') === 'active' ? 'is-active' : 'is-pending'); ?>">
            <?php echo e(($office->status ?? '') === 'active' ? 'فعال' : 'در انتظار تأیید'); ?>

          </span>
        </div>
      </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>

  <nav class="dashboard-side-nav" aria-label="منوی اصلی">
    <span class="dashboard-side-menu-label">منوی اصلی</span>

    <a href="<?php echo e(route('dashboard.index')); ?>" class="menu-link <?php echo e(request()->routeIs('dashboard.index') ? 'active' : ''); ?>">
      <span class="menu-link-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      </span>
      <span class="menu-link-text">اطلاعات صرافی</span>
    </a>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($office) && $office->canUseDigitalSignage()): ?>
      <a href="<?php echo e(route('dashboard.signage.index')); ?>" class="menu-link <?php echo e(request()->routeIs('dashboard.signage.*') ? 'active' : ''); ?>">
        <span class="menu-link-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        </span>
        <span class="menu-link-text">صفحه نمایش دیجیتال</span>
      </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <a href="<?php echo e(route('dashboard.landing')); ?>" class="menu-link <?php echo e(request()->routeIs('dashboard.landing*') ? 'active' : ''); ?>">
      <span class="menu-link-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
      </span>
      <span class="menu-link-text">سایت</span>
    </a>

    <a href="<?php echo e(route('dashboard.subscription')); ?>" class="menu-link <?php echo e(request()->routeIs('dashboard.subscription') ? 'active' : ''); ?>">
      <span class="menu-link-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      </span>
      <span class="menu-link-text">اشتراک ماهانه</span>
    </a>

    <a href="<?php echo e(route('dashboard.rates')); ?>" class="menu-link <?php echo e(request()->routeIs('dashboard.rates') || request()->routeIs('dashboard.office-rates') ? 'active' : ''); ?>">
      <span class="menu-link-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
      </span>
      <span class="menu-link-text">مدیریت نرخ‌ها</span>
    </a>

    <a href="<?php echo e(route('dashboard.rates-history')); ?>" class="menu-link <?php echo e(request()->routeIs('dashboard.rates-history') ? 'active' : ''); ?>">
      <span class="menu-link-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </span>
      <span class="menu-link-text">تاریخچه نرخ‌ها</span>
    </a>

    <a href="<?php echo e(route('dashboard.telegram')); ?>" class="menu-link <?php echo e(request()->routeIs('dashboard.telegram') ? 'active' : ''); ?>">
      <span class="menu-link-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
      </span>
      <span class="menu-link-text">تلگرام</span>
    </a>

    <span class="dashboard-side-menu-label" style="margin-top:0.5rem;">تنظیمات</span>

    <a href="<?php echo e(route('dashboard.password')); ?>" class="menu-link <?php echo e(request()->routeIs('dashboard.password') ? 'active' : ''); ?>">
      <span class="menu-link-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </span>
      <span class="menu-link-text">تغییر رمز عبور</span>
    </a>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($office) && !$office->hasTwoFactorEnabled()): ?>
      <a href="<?php echo e(route('dashboard.2fa.setup')); ?>" class="menu-link <?php echo e(request()->routeIs('dashboard.2fa.*') ? 'active' : ''); ?>">
        <span class="menu-link-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </span>
        <span class="menu-link-text">احراز هویت دو مرحله‌ای</span>
        <span class="menu-link-badge">جدید</span>
      </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </nav>

  <div class="dashboard-side-footer">
    <a href="<?php echo e(route('exchanges.index')); ?>" class="menu-link">
      <span class="menu-link-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      </span>
      <span class="menu-link-text">بازگشت به سایت</span>
    </a>
    <form action="<?php echo e(route('exchange.logout')); ?>" method="POST">
      <?php echo csrf_field(); ?>
      <button type="submit" class="menu-link menu-link-logout">
        <span class="menu-link-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </span>
        <span class="menu-link-text">خروج از حساب</span>
      </button>
    </form>
  </div>
</aside>
<?php /**PATH /Volumes/G-DRIVE  SSD  1TB/Exchange Landing/exchange-backend/resources/views/partials/dashboard-sidebar.blade.php ENDPATH**/ ?>