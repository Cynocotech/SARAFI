@extends('layouts.dashboard')
@section('title', 'تنظیم نام کاربری و رمز عبور')
@section('content')
  <header class="onboarding-header" style="display:flex;align-items:center;gap:1rem;padding:1rem 1.5rem;background:var(--bg-card);border-bottom:1px solid var(--border);">
    <a href="{{ route('exchanges.index') }}" class="onboarding-back" aria-label="بازگشت">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="onboarding-title" style="margin:0;font-size:1.25rem;">تنظیم ورود برای «{{ $office->name }}»</h1>
  </header>
  <div style="max-width: 480px; margin: 0 auto; padding: 1.5rem;">
    <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:1rem;">نام کاربری و رمز عبور را انتخاب کنید. پس از تأیید صرافی توسط ادمین با همین اطلاعات وارد پنل شوید.</p>
    <form action="{{ url()->signedRoute('dashboard.set-login.store', ['office' => $office->id]) }}" method="POST" class="onboarding-form">
      @csrf
      <input type="hidden" name="office_id" value="{{ $office->id }}">
      <div class="form-group">
        <label for="username">نام کاربری <span class="required">*</span></label>
        <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="مثال: myexchange" required autocomplete="username">
        @error('username')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
      </div>
      <div class="form-group">
        <label for="password">رمز عبور <span class="required">*</span></label>
        <input type="password" id="password" name="password" placeholder="حداقل ۸ کاراکتر" required autocomplete="new-password">
        @error('password')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
      </div>
      <div class="form-group">
        <label for="password_confirmation">تکرار رمز عبور <span class="required">*</span></label>
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="رمز عبور را دوباره وارد کنید" required autocomplete="new-password">
      </div>
      <button type="submit" class="btn btn-primary btn-block">ثبت و ادامه</button>
    </form>
  </div>
@endsection
