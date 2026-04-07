<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard('exchange')->check()): ?>
<nav class="dash-bottom-nav" aria-label="منوی پایین">
  <a href="<?php echo e(route('dashboard.index')); ?>" class="dash-bottom-nav-item <?php echo e(request()->routeIs('dashboard.index') ? 'active' : ''); ?>">
    <span class="dash-bottom-nav-icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
    </span>
    <span class="dash-bottom-nav-label">داشبورد</span>
  </a>
  <a href="<?php echo e(route('dashboard.rates')); ?>" class="dash-bottom-nav-item <?php echo e(request()->routeIs('dashboard.rates') || request()->routeIs('dashboard.office-rates') ? 'active' : ''); ?>">
    <span class="dash-bottom-nav-icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
    </span>
    <span class="dash-bottom-nav-label">نرخ‌ها</span>
  </a>
  <a href="<?php echo e(route('dashboard.rates-history')); ?>" class="dash-bottom-nav-item <?php echo e(request()->routeIs('dashboard.rates-history') ? 'active' : ''); ?>">
    <span class="dash-bottom-nav-icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </span>
    <span class="dash-bottom-nav-label">تاریخچه</span>
  </a>
  <a href="<?php echo e(route('dashboard.subscription')); ?>" class="dash-bottom-nav-item <?php echo e(request()->routeIs('dashboard.subscription') ? 'active' : ''); ?>">
    <span class="dash-bottom-nav-icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
    </span>
    <span class="dash-bottom-nav-label">اشتراک</span>
  </a>
  <button type="button" class="dash-bottom-nav-item" id="bottom-nav-menu-toggle" aria-label="منوی کامل" aria-expanded="false" onclick="document.getElementById('dashboard-menu-toggle')&&document.getElementById('dashboard-menu-toggle').click()">
    <span class="dash-bottom-nav-icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
    </span>
    <span class="dash-bottom-nav-label">بیشتر</span>
  </button>
</nav>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /Volumes/G-DRIVE  SSD  1TB/Exchange Landing/exchange-backend/resources/views/partials/dashboard-bottom-nav.blade.php ENDPATH**/ ?>