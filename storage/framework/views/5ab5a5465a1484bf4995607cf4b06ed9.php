<?php $__env->startSection('title', 'ویرایش صفحه نمایش | آقای صرافی'); ?>
<?php $__env->startSection('dashboard_nav_title', 'ویرایش صفحه نمایش'); ?>
<?php $__env->startSection('dashboard_nav_back', route('dashboard.signage.index')); ?>
<?php $__env->startSection('content'); ?>
        <div class="dash-card">
          <p style="margin-bottom:1rem;">کد pairing: <strong><?php echo e($screen->pairing_code); ?></strong></p>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($screen->last_seen_resolution): ?>
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1.25rem;padding:0.6rem 1rem;background:var(--accent-soft);border-radius:var(--radius);font-size:0.85rem;color:var(--accent);">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
              <span>اندازه صفحه شناسایی‌شده: <strong><?php echo e($screen->last_seen_resolution); ?></strong></span>
            </div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          <form action="<?php echo e(route('dashboard.signage.update', $screen)); ?>" method="POST" enctype="multipart/form-data" class="onboarding-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="form-group">
              <label for="name">نام صفحه (اختیاری)</label>
              <input type="text" id="name" name="name" value="<?php echo e(old('name', $screen->name)); ?>" placeholder="مثلاً تلویزیون شعبه ۱" class="form-control">
            </div>
            <div class="form-group">
              <label for="background_color">رنگ پس‌زمینه (اختیاری)</label>
              <input type="text" id="background_color" name="background_color" value="<?php echo e(old('background_color', $screen->background_color ?? '#0c4a6e')); ?>" placeholder="#0c4a6e" class="form-control">
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($screen->background_image_path): ?>
              <div class="form-group">
                <label>تصویر فعلی</label>
                <p style="margin:0.25rem 0;"><img src="<?php echo e($screen->backgroundImageUrl()); ?>" alt="" style="max-width:200px;height:auto;border-radius:var(--radius);border:1px solid var(--border);"></p>
                <label style="display:inline-flex;align-items:center;gap:0.5rem;margin-top:0.5rem;">
                  <input type="checkbox" name="remove_background_image" value="1"> حذف تصویر پس‌زمینه
                </label>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="form-group">
              <label for="background_image"><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($screen->background_image_path): ?> جایگزین تصویر <?php else: ?> تصویر پس‌زمینه (اختیاری) <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></label>
              <input type="file" id="background_image" name="background_image" accept="image/jpeg,image/png,image/gif,image/webp" class="form-control">
            </div>

            <div class="form-group">
              <label>چرخش صفحه نمایش</label>
              <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:0.75rem;">اگر تلویزیون به صورت عمودی (پرتره) نصب شده، ۹۰ یا ۲۷۰ درجه را انتخاب کنید.</p>
              <?php $currentRotation = old('rotation', $screen->rotation ?? 0); ?>
              <div style="display:flex;flex-wrap:wrap;gap:0.65rem;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [0 => '۰° (افقی)', 90 => '۹۰° (چپ)', 180 => '۱۸۰° (وارونه)', 270 => '۲۷۰° (راست)']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deg => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <label class="rate-radio-label">
                    <input type="radio" name="rotation" value="<?php echo e($deg); ?>" <?php echo e((int)$currentRotation === $deg ? 'checked' : ''); ?>>
                    <span><?php echo e($label); ?></span>
                  </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              </div>
            </div>

            <div class="form-group">
              <label class="toggle-label" for="crypto_enabled">
                <span class="toggle-switch">
                  <input type="checkbox" id="crypto_enabled" name="crypto_enabled" value="1" <?php echo e(old('crypto_enabled', $screen->crypto_enabled ?? true) ? 'checked' : ''); ?>>
                  <span class="toggle-slider"></span>
                </span>
                <span class="toggle-text">نمایش نرخ ارزهای دیجیتال (کریپتو) در صفحه نمایش</span>
              </label>
            </div>
            <div class="form-group">
              <label for="qr_link">لینک QR (اختیاری)</label>
              <input type="url" id="qr_link" name="qr_link" value="<?php echo e(old('qr_link', $screen->qr_link)); ?>" placeholder="https://example.com/page" class="form-control">
              <p class="form-help">این لینک روی صفحه نمایش به صورت QR نمایش داده می‌شود. مثلاً لینک وب‌سایت یا صفحه تماس.</p>
            </div>
            <button type="submit" class="btn btn-primary btn-block">ذخیره تغییرات</button>
          </form>
        </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Volumes/G-DRIVE  SSD  1TB/Exchange Landing/exchange-backend/resources/views/dashboard/signage/edit.blade.php ENDPATH**/ ?>