<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'دایرکتوری صرافی‌های پوند و تومان - نرخ لحظه‌ای خرید و فروش پوند به تومان'); ?>">
  <meta name="theme-color" content="#f0f9ff">
  <link rel="stylesheet" href="<?php echo e(asset('css/fonts.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/styles.css')); ?>">
  <?php echo $__env->yieldPushContent('styles'); ?>
  <?php echo $__env->yieldContent('styles'); ?>
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($is_exchange_landing ?? false) && !empty($exchange_primary_color) && preg_match('/^#[0-9A-Fa-f]{3}([0-9A-Fa-f]{3})?$/', $exchange_primary_color)): ?>
  <?php
    $hex = ltrim($exchange_primary_color, '#');
    if (strlen($hex) === 3) { $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2]; }
    $r = hexdec(substr($hex, 0, 2)); $g = hexdec(substr($hex, 2, 2)); $b = hexdec(substr($hex, 4, 2));
    $primary_rgba_15 = "rgba($r,$g,$b,0.15)";
    $primary_rgba_25 = "rgba($r,$g,$b,0.25)";
    $primary_rgba_40 = "rgba($r,$g,$b,0.4)";
    $primary_rgba_50 = "rgba($r,$g,$b,0.5)";
  ?>
  <style>
    body.page-exchange-landing {
      --color-accent: <?php echo e($exchange_primary_color); ?>;
      --color-accent-light: <?php echo e($exchange_primary_color); ?>;
      --color-accent-bg: <?php echo e($primary_rgba_15); ?>;
      --btn-bg: <?php echo e($exchange_primary_color); ?>;
      --input-focus: <?php echo e($exchange_primary_color); ?>;
      --input-focus-ring: <?php echo e($primary_rgba_40); ?>;
      --card-hover-border: <?php echo e($primary_rgba_50); ?>;
      --logo-gradient: linear-gradient(135deg, <?php echo e($exchange_primary_color); ?> 0%, <?php echo e($exchange_primary_color); ?> 100%);
    }
    body.page-exchange-landing.theme-fintek2 {
      --color-accent: <?php echo e($exchange_primary_color); ?>;
      --color-accent-light: <?php echo e($exchange_primary_color); ?>;
      --color-accent-bg: <?php echo e($primary_rgba_25); ?>;
      --btn-bg: <?php echo e($exchange_primary_color); ?>;
      --input-focus: <?php echo e($exchange_primary_color); ?>;
      --card-hover-border: <?php echo e($primary_rgba_50); ?>;
      --logo-gradient: linear-gradient(135deg, <?php echo e($exchange_primary_color); ?> 0%, <?php echo e($exchange_primary_color); ?> 100%);
    }
  </style>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</head>
<?php
  $active_theme = ($is_exchange_landing ?? false) ? ($exchange_landing_theme ?? 'default') : ($exchange_theme ?? 'default');
  $theme_class = $active_theme === 'theme2' ? 'landing-theme2' : ($active_theme === 'luxury' ? 'theme-luxury' : ($active_theme === 'fintek2' || $active_theme === 'theme2_fintech' ? 'theme-fintek2' : ($active_theme === 'finteklite' ? 'theme-finteklite' : ($active_theme === 'soldi' ? 'theme-soldi' : ($active_theme === 'soldi_dark' ? 'theme-soldi-dark' : '')))));
?>
<body class="<?php echo e($theme_class); ?> <?php if($is_exchange_landing ?? false): ?> page-exchange-landing <?php endif; ?>">
  <?php echo $__env->yieldContent('body_start'); ?>
  <div class="app" id="appMain">
    <?php echo $__env->yieldContent('content'); ?>
  </div>
  <?php echo $__env->yieldContent('bottom_nav'); ?>
  <?php echo $__env->yieldContent('portals'); ?>
  <?php echo $__env->yieldPushContent('scripts'); ?>
  <script>
    (function(){
      var t = localStorage.getItem('theme') || (matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light');
      document.documentElement.setAttribute('data-theme', t);
      var m = document.querySelector('meta[name="theme-color"]');
      if (m) {
        if (document.body.classList.contains('theme-luxury')) m.setAttribute('content', '#080808');
        else if (document.body.classList.contains('theme-fintek2')) m.setAttribute('content', '#0d0d0d');
        else if (document.body.classList.contains('theme-finteklite')) m.setAttribute('content', '#ffffff');
        else if (document.body.classList.contains('theme-soldi')) m.setAttribute('content', '#f8fafc');
        else if (document.body.classList.contains('theme-soldi-dark')) m.setAttribute('content', '#0f172a');
        else if (document.body.classList.contains('landing-theme2')) m.setAttribute('content', '#1e3a8a');
        else m.setAttribute('content', t === 'dark' ? '#0c4a6e' : '#f0f9ff');
      }
    })();
  </script>
</body>
</html>
<?php /**PATH /Volumes/G-DRIVE  SSD  1TB/Exchange Landing/exchange-backend/resources/views/layouts/app.blade.php ENDPATH**/ ?>