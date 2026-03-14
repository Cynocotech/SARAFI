@extends('layouts.admin-auth')
@section('title', 'Two-Factor Authentication')
@section('content')
  <h1>Two-Factor Authentication</h1>
  <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:0.5rem;">Enter the 6-digit code from your authenticator app (e.g. Google Authenticator).</p>
  <p style="font-size:0.9rem;color:var(--text);margin-bottom:1rem;font-weight:500;">You entered your password. Now enter the 6-digit code from your authenticator app.</p>
  <form action="{{ route('admin.2fa.verify') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="code">Code <span class="required">*</span></label>
      <input type="text" id="code" name="code" value="{{ old('code') }}" placeholder="000000" maxlength="6" pattern="[0-9]*" inputmode="numeric" autocomplete="one-time-code" required style="font-size:1.25rem;letter-spacing:0.3em;text-align:center;">
      @error('code')<p class="text-danger">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="btn btn-primary">Verify &amp; continue to admin</button>
  </form>
  <p style="margin-top:1rem;font-size:0.9rem;">
    <form action="{{ url('/admin/logout') }}" method="POST" style="display:inline;">
      @csrf
      <button type="submit" class="btn btn-secondary" style="width:auto;padding:0.35rem 0.75rem;">Log out</button>
    </form>
  </p>
@endsection
