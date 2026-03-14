@extends('layouts.dashboard')
@section('title', 'ورود صرافی')
@section('content')
  <div class="onboarding-section" style="max-width: 400px; margin: 2rem auto; padding: 1.5rem;">
    <header class="onboarding-header" style="display:flex;align-items:center;gap:1rem;padding:1rem 0;border-bottom:1px solid var(--border);margin-bottom:1.5rem;">
      <a href="{{ route('exchanges.index') }}" class="onboarding-back" aria-label="بازگشت">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      </a>
      <h1 class="onboarding-title" style="margin:0;font-size:1.25rem;">ورود به پنل صرافی</h1>
    </header>
    @if(session('success'))
      <p style="font-size:0.9rem;color:var(--success);margin-bottom:1rem;">{{ session('success') }}</p>
    @endif
    @if(session('info'))
      <p style="font-size:0.9rem;color:var(--accent);margin-bottom:1rem;">{{ session('info') }}</p>
    @endif
    <form action="{{ route('exchange.login') }}" method="POST" class="onboarding-form">
      @csrf
      <div class="form-group">
        <label for="username">نام کاربری <span class="required">*</span></label>
        <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="نام کاربری صرافی" required autocomplete="username">
        @error('username')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
      </div>
      <div class="form-group">
        <label for="password">رمز عبور <span class="required">*</span></label>
        <input type="password" id="password" name="password" placeholder="رمز عبور" required autocomplete="current-password">
        @error('password')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
      </div>
      <div class="form-group" style="display:flex;align-items:center;gap:0.5rem;">
        <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
        <label for="remember" style="margin:0;">مرا به خاطر بسپار</label>
      </div>
      <button type="submit" class="btn btn-primary btn-block">ورود</button>
    </form>
    <p style="font-size:0.9rem;color:var(--text-muted);margin-top:1rem;text-align:center;">
      صرافی ثبت نکرده‌اید؟ <a href="{{ route('dashboard.onboarding') }}">ثبت صرافی</a>
    </p>
  </div>
@endsection
