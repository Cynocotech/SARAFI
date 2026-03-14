@extends('install.layout')
@section('title', 'Step 2: Database & Admin')
@section('content')
<div class="install-card">
  <div class="install-header">
    <h1>آقای صرافی</h1>
    <p>نصب — مرحله ۲: پایگاه داده و ادمین</p>
  </div>

  <div class="install-body">
    @if($errors->any())
      <ul class="error-list">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    @endif

    <form action="{{ route('install.process') }}" method="post" id="installForm">
      @csrf
      <div class="form-section" id="section-db">
        <h3>۱. پایگاه داده MySQL (cPanel)</h3>
        <p class="hint">اطلاعات را از cPanel → MySQL® Databases بیابید.</p>
        <div class="form-grid">
          <div>
            <label for="db_host">Host</label>
            <input type="text" id="db_host" name="db_host" value="{{ old('db_host', 'localhost') }}" required placeholder="localhost">
          </div>
          <div>
            <label for="db_port">Port</label>
            <input type="text" id="db_port" name="db_port" value="{{ old('db_port', '3306') }}" required placeholder="3306">
          </div>
          <div>
            <label for="db_database">Database</label>
            <input type="text" id="db_database" name="db_database" value="{{ old('db_database') }}" required placeholder="نام دیتابیس">
          </div>
          <div>
            <label for="db_username">Username</label>
            <input type="text" id="db_username" name="db_username" value="{{ old('db_username') }}" required>
          </div>
          <div>
            <label for="db_password">Password</label>
            <input type="password" id="db_password" name="db_password" value="{{ old('db_password') }}" placeholder="خالی اگر ندارد">
          </div>
        </div>
      </div>

      <div class="form-section" id="section-app">
        <h3>۲. تنظیمات برنامه</h3>
        <div class="form-grid">
          <div>
            <label for="app_name">نام برنامه</label>
            <input type="text" id="app_name" name="app_name" value="{{ old('app_name', 'آقای صرافی') }}" required>
          </div>
          <div class="span-2">
            <label for="app_url">آدرس سایت</label>
            <input type="url" id="app_url" name="app_url" value="{{ old('app_url', url('/')) }}" required placeholder="https://yourdomain.com">
          </div>
        </div>
      </div>

      <div class="form-section" id="section-admin">
        <h3>۳. حساب مدیر (ادمین)</h3>
        <p class="hint">با این اطلاعات وارد پنل Filament می‌شوید: /admin</p>
        <div class="form-grid">
          <div>
            <label for="admin_name">نام</label>
            <input type="text" id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required>
          </div>
          <div>
            <label for="admin_email">ایمیل</label>
            <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email') }}" required>
          </div>
          <div>
            <label for="admin_password">رمز عبور</label>
            <input type="password" id="admin_password" name="admin_password" required>
          </div>
          <div>
            <label for="admin_password_confirmation">تکرار رمز عبور</label>
            <input type="password" id="admin_password_confirmation" name="admin_password_confirmation" required>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <a href="{{ route('install.requirements') }}" class="btn-secondary">← بازگشت</a>
        <button type="submit" class="btn-primary">نصب و راه‌اندازی</button>
      </div>
    </form>
  </div>
</div>
@endsection
