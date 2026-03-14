@extends('layouts.admin-auth')
@section('title', 'Enable Two-Factor Authentication')
@section('content')
  <h1>Enable Two-Factor Authentication</h1>
  <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:1rem;">Install Google Authenticator or a similar app, then scan the QR code below or enter the key manually.</p>
  <div style="text-align:center;margin-bottom:1rem;padding:1rem;background:var(--bg-elevated);border-radius:var(--radius);">
    <div style="display:inline-block;">{!! $qrSvg !!}</div>
  </div>
  <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:0.25rem;">Manual key:</p>
  <code style="display:block;padding:0.5rem;background:var(--bg-elevated);border-radius:var(--radius);font-size:0.85rem;word-break:break-all;margin-bottom:1rem;">{{ $secret }}</code>
  <form action="{{ route('admin.2fa.confirm') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="code">Enter the 6-digit code from your app <span class="required">*</span></label>
      <input type="text" id="code" name="code" value="{{ old('code') }}" placeholder="000000" maxlength="6" pattern="[0-9]*" inputmode="numeric" autocomplete="one-time-code" required style="font-size:1.1rem;letter-spacing:0.2em;text-align:center;">
      @error('code')<p class="text-danger">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="btn btn-primary">Enable 2FA</button>
  </form>
  <p style="margin-top:1rem;font-size:0.9rem;"><a href="{{ route('filament.admin.pages.dashboard') }}">Back to admin panel</a></p>
@endsection
