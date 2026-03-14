@extends('layouts.dashboard')
@section('title', 'Enable Two-Factor Authentication')
@section('dashboard_nav_title', 'Two-Factor Authentication')
@section('content')
  <div class="dashboard-rates-content" style="max-width: 480px;">
    @if(session('success'))
      <div class="dashboard-rates-alert" style="background: var(--success); color: white; padding: 0.75rem 1rem; border-radius: var(--radius); margin-bottom: 1rem; font-size: 0.9rem;">{{ session('success') }}</div>
    @endif
    <div class="dash-card">
      <h2 style="font-size:1.1rem;margin-bottom:0.5rem;color:var(--text);">فعال‌سازی Google Authenticator</h2>
      <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:1rem;">اپلیکیشن Google Authenticator یا مشابه (مثل Microsoft Authenticator) را نصب کنید، سپس QR زیر را اسکن کنید یا کلید را دستی وارد کنید.</p>
      <div style="text-align:center;margin-bottom:1rem;padding:1rem;background:var(--bg-elevated);border-radius:var(--radius);">
        <div style="display:inline-block;">{!! $qrSvg !!}</div>
      </div>
      <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:0.25rem;">کلید دستی (در صورت نبودن اسکن):</p>
      <code style="display:block;padding:0.5rem;background:var(--bg-elevated);border-radius:var(--radius);font-size:0.9rem;word-break:break-all;margin-bottom:1rem;">{{ $secret }}</code>
      <form action="{{ route('dashboard.2fa.confirm') }}" method="POST" class="onboarding-form">
        @csrf
        <div class="form-group">
          <label for="code">Enter the 6-digit code from your app <span class="required">*</span></label>
          <input type="text" id="code" name="code" value="{{ old('code') }}" placeholder="000000" maxlength="6" pattern="[0-9]*" inputmode="numeric" autocomplete="one-time-code" required style="font-size:1.1rem;letter-spacing:0.2em;text-align:center;">
          @error('code')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="btn btn-primary btn-block">Enable Two-Factor Authentication</button>
      </form>
    </div>
  </div>
@endsection
