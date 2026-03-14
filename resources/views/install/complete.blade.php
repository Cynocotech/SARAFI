@extends('install.layout')
@section('title', 'نصب کامل شد')
@section('content')
<div class="install-card">
  <div class="install-header">
    <h1>✓ نصب با موفقیت انجام شد</h1>
    <p>آقای صرافی آماده استفاده است.</p>
  </div>

  <div class="install-body">
    <div class="success-actions">
      <a href="{{ url('/') }}" class="btn-primary">مشاهده سایت</a>
      <a href="{{ url('/admin') }}" class="btn-secondary">ورود به پنل ادمین</a>
    </div>
    <p class="hint">برای تنظیم Stripe و سایر گزینه‌ها به Settings در پنل ادمین مراجعه کنید.</p>
  </div>
</div>
@endsection
