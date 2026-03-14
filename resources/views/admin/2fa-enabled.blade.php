@extends('layouts.admin-auth')
@section('title', 'Two-Factor Authentication Enabled')
@section('content')
  <h1 style="color: #10b981;">Two-Factor Authentication is now enabled</h1>
  <p style="font-size:0.95rem;color:var(--text);margin-bottom:0.75rem;">Your admin account is now protected with 2FA. Whenever you sign in:</p>
  <ol style="margin:0 0 1rem 1.25rem;padding:0;font-size:0.95rem;color:var(--text);line-height:1.6;">
    <li style="margin-bottom:0.35rem;">Enter your <strong>email and password</strong>.</li>
    <li style="margin-bottom:0.35rem;">Then enter the <strong>6-digit code</strong> from your authenticator app (Google Authenticator, Microsoft Authenticator, etc.).</li>
  </ol>
  <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:1.25rem;">Keep your authenticator app secure. You will need it every time you sign in.</p>
  <a href="{{ route('filament.admin.pages.dashboard') }}" class="btn btn-primary" style="display:inline-block;text-decoration:none;">Continue to admin panel</a>
@endsection
