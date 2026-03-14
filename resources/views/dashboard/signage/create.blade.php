@extends('layouts.dashboard')
@section('title', 'افزودن صفحه نمایش | آقای صرافی')
@section('dashboard_nav_title', 'افزودن صفحه نمایش')
@section('dashboard_nav_back', route('dashboard.signage.index'))
@section('content')
        <div class="dash-card">
          <form action="{{ route('dashboard.signage.store') }}" method="POST" enctype="multipart/form-data" class="onboarding-form">
            @csrf
            <div class="form-group">
              <label for="name">نام صفحه (اختیاری)</label>
              <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="مثلاً تلویزیون شعبه ۱" class="form-control">
            </div>
            <div class="form-group">
              <label for="background_color">رنگ پس‌زمینه (اختیاری)</label>
              <input type="text" id="background_color" name="background_color" value="{{ old('background_color', '#0c4a6e') }}" placeholder="#0c4a6e" class="form-control">
            </div>
            <div class="form-group">
              <label for="background_image">تصویر پس‌زمینه (اختیاری)</label>
              <input type="file" id="background_image" name="background_image" accept="image/jpeg,image/png,image/gif,image/webp" class="form-control">
            </div>
            <div class="form-group">
              <label for="qr_link">لینک QR (اختیاری)</label>
              <input type="url" id="qr_link" name="qr_link" value="{{ old('qr_link') }}" placeholder="https://example.com/page" class="form-control">
              <p class="form-help">این لینک روی صفحه نمایش به صورت QR نمایش داده می‌شود. مثلاً لینک وب‌سایت یا صفحه تماس.</p>
            </div>
            <button type="submit" class="btn btn-primary btn-block">ساخت صفحه نمایش</button>
          </form>
        </div>
@endsection
