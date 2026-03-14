@extends('layouts.dashboard')
@section('title', 'تغییر رمز عبور | آقای صرافی')
@section('dashboard_nav_title', 'تغییر رمز عبور')
@section('dashboard_nav_back', route('dashboard.index'))
@section('content')
        <div style="max-width: 480px; margin: 0 auto; padding: 1.5rem;">
          @if(session('success'))
            <p style="font-size:0.9rem;color:var(--success);margin-bottom:1rem;">{{ session('success') }}</p>
          @endif
          <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:1rem;">رمز عبور ورود به پنل «{{ $office->name }}» را تنظیم یا تغییر دهید.</p>
          <form action="{{ route('dashboard.password.update') }}" method="POST" class="onboarding-form">
            @csrf
            @if(filled($office->password))
              <div class="form-group">
                <label for="current_password">رمز عبور فعلی <span class="required">*</span></label>
                <input type="password" id="current_password" name="current_password" placeholder="رمز عبور فعلی" required autocomplete="current-password">
                @error('current_password')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
              </div>
            @else
              <p style="font-size:0.9rem;color:var(--accent);margin-bottom:0.75rem;">هنوز رمز عبور تنظیم نشده است. رمز عبور جدید را وارد کنید.</p>
            @endif
            <div class="form-group">
              <label for="password">رمز عبور جدید <span class="required">*</span></label>
              <input type="password" id="password" name="password" placeholder="حداقل ۸ کاراکتر" required autocomplete="new-password">
              @error('password')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
              <label for="password_confirmation">تکرار رمز عبور جدید <span class="required">*</span></label>
              <input type="password" id="password_confirmation" name="password_confirmation" placeholder="رمز عبور را دوباره وارد کنید" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">ذخیره رمز عبور</button>
          </form>
        </div>
@endsection
