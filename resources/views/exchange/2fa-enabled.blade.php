@extends('layouts.dashboard')
@section('title', 'Two-Factor Authentication Enabled')
@section('dashboard_nav_title', '2FA Enabled')
@section('content')
  <div class="dashboard-rates-content" style="max-width: 480px;">
    <div class="dash-card" style="border-color: var(--success); background: rgba(16, 185, 129, 0.06);">
      <h2 style="font-size:1.25rem;margin-bottom:0.75rem;color:var(--success);">Two-Factor Authentication is now enabled</h2>
      <p style="font-size:0.95rem;color:var(--text);margin-bottom:0.75rem;">Your account is now protected with 2FA. Whenever you sign in:</p>
      <ol style="margin:0 0 1rem 1.25rem;padding:0;font-size:0.95rem;color:var(--text);line-height:1.6;">
        <li style="margin-bottom:0.35rem;">Enter your <strong>username and password</strong>.</li>
        <li style="margin-bottom:0.35rem;">Then enter the <strong>6-digit code</strong> from your authenticator app (Google Authenticator, Microsoft Authenticator, etc.).</li>
      </ol>
      <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:1.25rem;">Keep your authenticator app secure. You will need it every time you sign in.</p>
      <a href="{{ route('dashboard.index') }}" class="btn btn-primary" style="display:inline-block;">Continue to dashboard</a>
    </div>
  </div>
@endsection
