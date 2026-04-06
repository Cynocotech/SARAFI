@extends('layouts.dashboard')
@section('title', 'ویرایش صفحه نمایش | آقای صرافی')
@section('dashboard_nav_title', 'ویرایش صفحه نمایش')
@section('dashboard_nav_back', route('dashboard.signage.index'))
@section('content')
        <div class="dash-card">
          <p style="margin-bottom:1rem;">کد pairing: <strong>{{ $screen->pairing_code }}</strong></p>
          @if($screen->last_seen_resolution)
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1.25rem;padding:0.6rem 1rem;background:var(--accent-soft);border-radius:var(--radius);font-size:0.85rem;color:var(--accent);">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
              <span>اندازه صفحه شناسایی‌شده: <strong>{{ $screen->last_seen_resolution }}</strong></span>
            </div>
          @endif
          <form action="{{ route('dashboard.signage.update', $screen) }}" method="POST" enctype="multipart/form-data" class="onboarding-form">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="name">نام صفحه (اختیاری)</label>
              <input type="text" id="name" name="name" value="{{ old('name', $screen->name) }}" placeholder="مثلاً تلویزیون شعبه ۱" class="form-control">
            </div>
            <div class="form-group">
              <label for="background_color">رنگ پس‌زمینه (اختیاری)</label>
              <input type="text" id="background_color" name="background_color" value="{{ old('background_color', $screen->background_color ?? '#0c4a6e') }}" placeholder="#0c4a6e" class="form-control">
            </div>
            @if($screen->background_image_path)
              <div class="form-group">
                <label>تصویر فعلی</label>
                <p style="margin:0.25rem 0;"><img src="{{ $screen->backgroundImageUrl() }}" alt="" style="max-width:200px;height:auto;border-radius:var(--radius);border:1px solid var(--border);"></p>
                <label style="display:inline-flex;align-items:center;gap:0.5rem;margin-top:0.5rem;">
                  <input type="checkbox" name="remove_background_image" value="1"> حذف تصویر پس‌زمینه
                </label>
              </div>
            @endif
            <div class="form-group">
              <label for="background_image">@if($screen->background_image_path) جایگزین تصویر @else تصویر پس‌زمینه (اختیاری) @endif</label>
              <input type="file" id="background_image" name="background_image" accept="image/jpeg,image/png,image/gif,image/webp" class="form-control">
            </div>

            <div class="form-group">
              <label>چرخش صفحه نمایش</label>
              <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:0.75rem;">اگر تلویزیون به صورت عمودی (پرتره) نصب شده، ۹۰ یا ۲۷۰ درجه را انتخاب کنید.</p>
              @php $currentRotation = old('rotation', $screen->rotation ?? 0); @endphp
              <div style="display:flex;flex-wrap:wrap;gap:0.65rem;">
                @foreach([0 => '۰° (افقی)', 90 => '۹۰° (چپ)', 180 => '۱۸۰° (وارونه)', 270 => '۲۷۰° (راست)'] as $deg => $label)
                  <label class="rate-radio-label">
                    <input type="radio" name="rotation" value="{{ $deg }}" {{ (int)$currentRotation === $deg ? 'checked' : '' }}>
                    <span>{{ $label }}</span>
                  </label>
                @endforeach
              </div>
            </div>

            <div class="form-group">
              <label class="toggle-label" for="crypto_enabled">
                <span class="toggle-switch">
                  <input type="checkbox" id="crypto_enabled" name="crypto_enabled" value="1" {{ old('crypto_enabled', $screen->crypto_enabled ?? true) ? 'checked' : '' }}>
                  <span class="toggle-slider"></span>
                </span>
                <span class="toggle-text">نمایش نرخ ارزهای دیجیتال (کریپتو) در صفحه نمایش</span>
              </label>
            </div>
            <div class="form-group">
              <label for="qr_link">لینک QR (اختیاری)</label>
              <input type="url" id="qr_link" name="qr_link" value="{{ old('qr_link', $screen->qr_link) }}" placeholder="https://example.com/page" class="form-control">
              <p class="form-help">این لینک روی صفحه نمایش به صورت QR نمایش داده می‌شود. مثلاً لینک وب‌سایت یا صفحه تماس.</p>
            </div>
            <button type="submit" class="btn btn-primary btn-block">ذخیره تغییرات</button>
          </form>
        </div>
@endsection
