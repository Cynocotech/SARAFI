<?php $__env->startSection('title', 'Step 1: Requirements'); ?>
<?php $__env->startSection('content'); ?>
<div class="install-card">
  <div class="install-header">
    <h1>آقای صرافی</h1>
    <p>نصب — مرحله ۱: بررسی پیش‌نیازها</p>
  </div>

  <div class="install-body">
    <table class="req-table">
      <thead>
        <tr>
          <th>مورد</th>
          <th>وضعیت</th>
        </tr>
      </thead>
      <tbody>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="<?php echo e($r['passed'] ? 'passed' : 'failed'); ?>">
          <td><?php echo e($r['name']); ?></td>
          <td>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($r['passed']): ?>
              <span class="badge success">✓ <?php echo e($r['current'] ?? 'OK'); ?></span>
            <?php else: ?>
              <span class="badge fail">✗ <?php echo e($r['current'] ?? 'Failed'); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </tbody>
    </table>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($passed): ?>
      <a href="<?php echo e(route('install.database')); ?>" class="btn-primary">ادامه →</a>
    <?php else: ?>
      <p class="text-warn">لطفاً موارد بالا را برطرف کنید و صفحه را بازخوانی کنید.</p>
      <a href="<?php echo e(route('install.requirements')); ?>" class="btn-secondary">بررسی مجدد</a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('install.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Volumes/G-DRIVE  SSD  1TB/Exchange Landing/exchange-backend/resources/views/install/requirements.blade.php ENDPATH**/ ?>