@extends('layouts.dashboard')
@section('title', 'در انتظار بررسی')
@section('content')
  <header class="onboarding-header" style="display:flex;align-items:center;gap:1rem;padding:1rem 1.5rem;background:var(--bg-card);border-bottom:1px solid var(--border);">
    <a href="{{ route('exchange.login') }}" class="onboarding-back" aria-label="بازگشت">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="onboarding-title" style="margin:0;font-size:1.25rem;">در انتظار بررسی</h1>
  </header>
  <div class="onboarding-success" style="text-align:center;padding:2rem 1.5rem;">
    <div class="success-icon success-icon-contact" style="width:64px;height:64px;margin:0 auto 1rem;background:var(--success);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="36" height="36"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    </div>
    <h2 style="font-size:1.25rem;margin-bottom:0.5rem;color:var(--text);">درخواست شما ثبت شد</h2>
    <p style="color:var(--text-muted);margin-bottom:1rem;">صرافی «{{ $office->name }}» برای بررسی به ادمین ارسال شد. پس از تأیید توسط ادمین می‌توانید با نام کاربری و رمز عبور وارد پنل شده و نرخ‌ها را مدیریت کنید.</p>
    @if(session('info'))
      <p style="font-size:0.9rem;color:var(--accent);margin-bottom:1rem;">{{ session('info') }}</p>
    @endif
    @if(!$office->username)
      <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:0.5rem;">برای ورود به پنل، ابتدا نام کاربری و رمز عبور تنظیم کنید:</p>
      <a href="{{ url()->signedRoute('dashboard.set-login', ['office' => $office->id]) }}" class="btn btn-primary" style="display:inline-block;margin-bottom:1rem;">تنظیم نام کاربری و رمز عبور</a>
      <br>
    @endif
    <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:0.75rem;">احراز هویت با Stripe (اختیاری):</p>
    <a href="{{ route('dashboard.onboarding.kyc', ['office' => $office->id]) }}" class="btn btn-secondary" style="display:inline-block;margin-bottom:1rem;">احراز هویت با Stripe</a>
    <br>
    <a href="{{ route('exchange.login') }}" class="btn btn-secondary">ورود به پنل</a>
  </div>
@endsection
