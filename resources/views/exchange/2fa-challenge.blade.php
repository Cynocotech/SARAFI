@extends('layouts.dashboard')
@section('title', 'Two-Factor Authentication')
@section('content')
  <div class="onboarding-section" style="max-width: 400px; margin: 2rem auto; padding: 1.5rem;">
    <header class="onboarding-header" style="display:flex;align-items:center;gap:1rem;padding:1rem 0;border-bottom:1px solid var(--border);margin-bottom:1.5rem;">
      <a href="{{ route('exchange.login') }}" class="onboarding-back" aria-label="Back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      </a>
      <h1 class="onboarding-title" style="margin:0;font-size:1.25rem;">Two-Factor Authentication</h1>
    </header>
    <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:1rem;">Enter the 6-digit code from your authenticator app (e.g. Google Authenticator).</p>
    <p style="font-size:0.9rem;color:var(--text);margin-bottom:1rem;font-weight:500;">You entered your password. Now enter the 6-digit code from your authenticator app.</p>
    <form action="{{ route('exchange.2fa.verify') }}" method="POST" class="onboarding-form">
      @csrf
      <div class="form-group">
        <label for="code">Code <span class="required">*</span></label>
        <input type="text" id="code" name="code" value="{{ old('code') }}" placeholder="000000" maxlength="6" pattern="[0-9]*" inputmode="numeric" autocomplete="one-time-code" required style="font-size:1.25rem;letter-spacing:0.3em;text-align:center;">
        @error('code')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
      </div>
      <button type="submit" class="btn btn-primary btn-block">Verify &amp; sign in</button>
    </form>
  </div>
@endsection
