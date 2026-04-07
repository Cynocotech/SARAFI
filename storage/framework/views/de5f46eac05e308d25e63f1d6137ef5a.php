
<?php
  $size = $size ?? 32;
  $class = $class ?? 'payment-method-logo';
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($key === 'visa'): ?>
  <span class="<?php echo e($class); ?> payment-logo-visa" role="img" aria-label="Visa" style="display:inline-flex;align-items:center;justify-content:center;width:<?php echo e($size); ?>px;height:<?php echo e($size * 0.65); ?>px;">
    <svg viewBox="0 0 54 34" width="<?php echo e($size); ?>" height="<?php echo e($size * 0.63); ?>" style="display:block;" aria-hidden="true">
      <rect width="54" height="34" rx="4" fill="#1A1F71"/>
      <text x="27" y="22" text-anchor="middle" fill="#fff" font-family="Arial,sans-serif" font-weight="700" font-size="14">VISA</text>
    </svg>
  </span>
<?php elseif($key === 'mastercard'): ?>
  <span class="<?php echo e($class); ?> payment-logo-mastercard" role="img" aria-label="Mastercard" style="display:inline-flex;align-items:center;justify-content:center;width:<?php echo e($size); ?>px;height:<?php echo e($size * 0.63); ?>px;">
    <svg viewBox="0 0 54 34" width="<?php echo e($size); ?>" height="<?php echo e($size * 0.63); ?>" style="display:block;" aria-hidden="true">
      <rect width="54" height="34" rx="4" fill="#fff"/>
      <circle cx="18" cy="17" r="12" fill="#EB001B"/>
      <circle cx="36" cy="17" r="12" fill="#F79E1B"/>
      <path d="M27 6.5a11.5 11.5 0 0 1 0 21A11.5 11.5 0 0 1 27 6.5z" fill="#FF5F00"/>
    </svg>
  </span>
<?php elseif($key === 'credit_cards'): ?>
  <span class="<?php echo e($class); ?> payment-logo-credit" role="img" aria-label="کارت اعتباری" style="display:inline-flex;align-items:center;justify-content:center;width:<?php echo e($size); ?>px;height:<?php echo e($size); ?>px;">
    <svg viewBox="0 0 24 24" width="<?php echo e($size); ?>" height="<?php echo e($size); ?>" style="display:block;" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <rect x="2" y="5" width="20" height="14" rx="2.5"/>
      <rect x="5" y="8" width="5" height="4" rx="0.8" fill="currentColor" opacity="0.4"/>
      <line x1="2" y1="12" x2="22" y2="12"/>
    </svg>
  </span>
<?php elseif($key === 'cash'): ?>
  <span class="<?php echo e($class); ?> payment-logo-cash" role="img" aria-label="نقد" style="display:inline-flex;align-items:center;justify-content:center;width:<?php echo e($size); ?>px;height:<?php echo e($size); ?>px;">
    <svg viewBox="0 0 24 24" width="<?php echo e($size); ?>" height="<?php echo e($size); ?>" style="display:block;" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <rect x="2" y="6" width="20" height="12" rx="1.5"/>
      <circle cx="7" cy="12" r="2.5"/>
      <line x1="12" y1="8" x2="18" y2="8"/>
      <line x1="12" y1="12" x2="18" y2="12"/>
      <line x1="12" y1="16" x2="18" y2="16"/>
    </svg>
  </span>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /Volumes/G-DRIVE  SSD  1TB/Exchange Landing/exchange-backend/resources/views/partials/payment-method-logo.blade.php ENDPATH**/ ?>