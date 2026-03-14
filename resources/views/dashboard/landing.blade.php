@extends('layouts.dashboard')
@section('title', 'سایت | آقای صرافی')
@section('dashboard_nav_title', 'ویرایش سایت')
@section('dashboard_nav_back', route('dashboard.index'))
@section('content')
        @if(session('success'))
          <div style="background: var(--success); color: white; padding: 0.75rem 1rem; border-radius: var(--radius); margin-bottom: 1rem; font-size: 0.9rem;">{{ session('success') }}</div>
        @endif
        <p style="color:var(--text-muted);margin-bottom:1.25rem;">تنظیمات صفحه عمومی صرافی شما (درباره ما، نرخ‌ها، ماشین‌حساب، تماس). <a href="{{ route('exchanges.show', $office) }}" target="_blank" rel="noopener" style="color:var(--accent);">مشاهده سایت</a></p>

        <form action="{{ route('dashboard.landing.update') }}" method="POST" enctype="multipart/form-data" class="onboarding-form">
          @csrf
          @method('PUT')

          <div class="dash-card" style="margin-bottom:1.5rem;">
            <h2 style="font-size:1rem;font-weight:700;margin:0 0 1rem 0;">بخش هیرو (بالای صفحه)</h2>
            <div class="form-group">
              <label for="hero_title">عنوان هیرو (اختیاری)</label>
              <input type="text" id="hero_title" name="hero_title" value="{{ old('hero_title', $office->hero_title) }}" placeholder="خالی = نام صرافی" class="form-control">
            </div>
            <div class="form-group">
              <label for="hero_subtitle">زیرعنوان هیرو (اختیاری)</label>
              <input type="text" id="hero_subtitle" name="hero_subtitle" value="{{ old('hero_subtitle', $office->hero_subtitle) }}" placeholder="خالی = شعار (تگ‌لاین)" class="form-control">
            </div>
            @if($office->heroImageUrl())
              <div class="form-group">
                <label>تصویر هیرو فعلی</label>
                <p style="margin:0.25rem 0;"><img src="{{ $office->heroImageUrl() }}" alt="" style="max-width:100%;max-height:200px;object-fit:contain;border-radius:var(--radius);border:1px solid var(--border);"></p>
                <label style="display:inline-flex;align-items:center;gap:0.5rem;margin-top:0.5rem;">
                  <input type="checkbox" name="remove_hero_image" value="1"> حذف تصویر هیرو
                </label>
              </div>
            @endif
            <div class="form-group">
              <label for="hero_image">@if($office->hero_image_path || $office->hero_image_url) جایگزین تصویر هیرو @else تصویر هیرو / بنر (اختیاری) @endif</label>
              <input type="file" id="hero_image" name="hero_image" accept="image/jpeg,image/png,image/gif,image/webp" class="form-control">
            </div>
            <div class="form-group">
              <label for="hero_image_url">یا لینک تصویر هیرو</label>
              <input type="url" id="hero_image_url" name="hero_image_url" value="{{ old('hero_image_url', $office->hero_image_url) }}" placeholder="https://example.com/banner.jpg" class="form-control">
            </div>
          </div>

          <div class="dash-card" style="margin-bottom:1.5rem;">
            <h2 style="font-size:1rem;font-weight:700;margin:0 0 1rem 0;">متن و درباره ما</h2>
            <div class="form-group">
              <label for="tagline">شعار / تگ‌لاین</label>
              <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $office->tagline) }}" placeholder="مثال: بهترین نرخ پوند در لندن" class="form-control">
            </div>
            <div class="form-group">
              <label for="about">درباره ما</label>
              <textarea id="about" name="about" rows="5" placeholder="توضیح کوتاه درباره صرافی، خدمات و مزایا." class="form-control">{{ old('about', $office->about) }}</textarea>
            </div>
            <div class="form-group">
              <label for="about_image_url">تصویر بخش درباره ما (اختیاری)</label>
              <input type="url" id="about_image_url" name="about_image_url" value="{{ old('about_image_url', $office->about_image_url) }}" placeholder="https://example.com/about-image.png" class="form-control">
              <p class="form-help">در صورت خالی بودن از تصویر پیش‌فرض استفاده می‌شود. این تصویر در بخش «درباره ما» صفحه لندینگ نمایش داده می‌شود.</p>
            </div>
          </div>

          <div class="dash-card" style="margin-bottom:1.5rem;">
            <h2 style="font-size:1rem;font-weight:700;margin:0 0 1rem 0;">نقشه</h2>
            <div class="form-group">
              <label for="map_embed">کد embed نقشه گوگل (اختیاری)</label>
              <textarea id="map_embed" name="map_embed" rows="4" placeholder='مثال: &lt;iframe src="https://www.google.com/maps/embed?pb=..." ...&gt;&lt;/iframe&gt; یا فقط آدرس embed' class="form-control" style="font-family:monospace;font-size:0.85rem;">{{ old('map_embed', $office->map_embed) }}</textarea>
              <p class="form-help">از گوگل مپس: Share → Embed a map → کپی iframe را اینجا بچسبانید.</p>
            </div>
          </div>

          <div class="dash-card" style="margin-bottom:1.5rem;">
            <h2 style="font-size:1rem;font-weight:700;margin:0 0 1rem 0;">واتساپ (دکمه شناور در سایت)</h2>
            <div class="form-group">
              <label for="whatsapp_phone">شماره واتساپ برای دکمه تماس سریع</label>
              <input type="text" id="whatsapp_phone" name="whatsapp_phone" value="{{ old('whatsapp_phone', $office->whatsapp_phone) }}" placeholder="مثال: 989123456789 یا 44 20 7123 4567" class="form-control" dir="ltr">
              <p class="form-help">فقط اعداد (با یا بدون فاصله و +). این شماره برای دکمه سبز واتساپ در صفحه لندینگ استفاده می‌شود. خالی = دکمه نمایش داده نمی‌شود.</p>
            </div>
          </div>

          @php $serviceIconOptions = \App\Models\ExchangeOffice::serviceIconOptions(); $existingServices = old('services', $office->services ?? []); @endphp
          <div class="dash-card" style="margin-bottom:1.5rem;">
            <h2 style="font-size:1rem;font-weight:700;margin:0 0 0.5rem 0;">خدمات ما (بخش «خدمات ما» در صفحه سایت)</h2>
            <p class="form-help" style="margin-bottom:1rem;">خدماتی که در صفحه لندینگ نمایش داده می‌شوند. در صورت خالی بودن، سه خدمت پیش‌فرض نمایش داده می‌شود.</p>
            <div id="services-container">
              @foreach($existingServices as $idx => $svc)
                <div class="service-row" style="display:grid;grid-template-columns:1fr 1fr minmax(200px, auto);gap:0.75rem;align-items:start;margin-bottom:1rem;padding:1rem;background:var(--bg-elevated, #f8fafc);border-radius:var(--radius);border:1px solid var(--border, #e2e8f0);">
                  <div class="form-group" style="margin:0;">
                    <label>عنوان</label>
                    <input type="text" name="services[{{ $idx }}][title]" value="{{ $svc['title'] ?? '' }}" placeholder="مثال: انتقال سریع وجه" class="form-control">
                  </div>
                  <div class="form-group" style="margin:0;">
                    <label>توضیح کوتاه</label>
                    <textarea name="services[{{ $idx }}][description]" rows="2" placeholder="یک یا دو خط" class="form-control">{{ $svc['description'] ?? '' }}</textarea>
                  </div>
                  <div style="display:flex;flex-direction:column;gap:0.5rem;min-width:0;">
                    <div class="form-group" style="margin:0;">
                      <label>آیکون</label>
                      <select name="services[{{ $idx }}][icon]" class="form-control services-icon-select">
                        @foreach($serviceIconOptions as $iconValue => $iconLabel)
                          <option value="{{ $iconValue }}" {{ ($svc['icon'] ?? 'payments') === $iconValue ? 'selected' : '' }}>{{ $iconLabel }}</option>
                        @endforeach
                      </select>
                    </div>
                    <button type="button" class="btn-remove-service btn" style="background:var(--danger,#dc2626);color:white;padding:0.4rem 0.6rem;font-size:0.85rem;border-radius:var(--radius);border:none;cursor:pointer;">حذف</button>
                  </div>
                </div>
              @endforeach
            </div>
            <button type="button" id="add-service-btn" class="btn btn-secondary" style="margin-top:0.5rem;">+ افزودن خدمت</button>
            <template id="service-row-tpl">
              <div class="service-row" style="display:grid;grid-template-columns:1fr 1fr minmax(200px, auto);gap:0.75rem;align-items:start;margin-bottom:1rem;padding:1rem;background:var(--bg-elevated, #f8fafc);border-radius:var(--radius);border:1px solid var(--border, #e2e8f0);">
                <div class="form-group" style="margin:0;">
                  <label>عنوان</label>
                  <input type="text" name="services[__INDEX__][title]" value="" placeholder="مثال: انتقال سریع وجه" class="form-control">
                </div>
                <div class="form-group" style="margin:0;">
                  <label>توضیح کوتاه</label>
                  <textarea name="services[__INDEX__][description]" rows="2" placeholder="یک یا دو خط" class="form-control"></textarea>
                </div>
                <div style="display:flex;flex-direction:column;gap:0.5rem;min-width:0;">
                  <div class="form-group" style="margin:0;">
                    <label>آیکون</label>
                    <select name="services[__INDEX__][icon]" class="form-control services-icon-select">
                      @foreach($serviceIconOptions as $iconValue => $iconLabel)
                        <option value="{{ $iconValue }}">{{ $iconLabel }}</option>
                      @endforeach
                    </select>
                  </div>
                  <button type="button" class="btn-remove-service btn" style="background:var(--danger,#dc2626);color:white;padding:0.4rem 0.6rem;font-size:0.85rem;border-radius:var(--radius);border:none;cursor:pointer;">حذف</button>
                </div>
              </div>
            </template>
          </div>

          <div class="dash-card" style="margin-bottom:1.5rem;">
            <h2 style="font-size:1rem;font-weight:700;margin:0 0 1rem 0;">گزینه‌های نمایش</h2>
            <div class="form-group">
              <label class="toggle-label" for="landing_show_calculator">
                <span class="toggle-switch">
                  <input type="checkbox" id="landing_show_calculator" name="landing_show_calculator" value="1" {{ old('landing_show_calculator', $office->landing_show_calculator ?? true) ? 'checked' : '' }}>
                  <span class="toggle-slider"></span>
                </span>
                <span class="toggle-text">نمایش ماشین‌حساب ارز</span>
              </label>
            </div>
            <div class="form-group">
              <label class="toggle-label" for="landing_show_rates">
                <span class="toggle-switch">
                  <input type="checkbox" id="landing_show_rates" name="landing_show_rates" value="1" {{ old('landing_show_rates', $office->landing_show_rates ?? true) ? 'checked' : '' }}>
                  <span class="toggle-slider"></span>
                </span>
                <span class="toggle-text">نمایش نرخ لحظه‌ای</span>
              </label>
            </div>
            <div class="form-group">
              <label class="toggle-label" for="landing_show_map">
                <span class="toggle-switch">
                  <input type="checkbox" id="landing_show_map" name="landing_show_map" value="1" {{ old('landing_show_map', $office->landing_show_map ?? true) ? 'checked' : '' }}>
                  <span class="toggle-slider"></span>
                </span>
                <span class="toggle-text">نمایش نقشه (در صورت وارد کردن کد embed)</span>
              </label>
            </div>
            <div class="form-group">
              <label class="toggle-label" for="landing_show_contact">
                <span class="toggle-switch">
                  <input type="checkbox" id="landing_show_contact" name="landing_show_contact" value="1" {{ old('landing_show_contact', $office->landing_show_contact ?? true) ? 'checked' : '' }}>
                  <span class="toggle-slider"></span>
                </span>
                <span class="toggle-text">نمایش بخش تماس و آدرس</span>
              </label>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block">ذخیره تنظیمات سایت</button>
        </form>

        <script>
        (function () {
          var container = document.getElementById('services-container');
          var tpl = document.getElementById('service-row-tpl');
          var addBtn = document.getElementById('add-service-btn');
          if (!container || !tpl || !addBtn) return;
          var nextIndex = container.querySelectorAll('.service-row').length;

          addBtn.addEventListener('click', function () {
            var html = tpl.innerHTML.replace(/__INDEX__/g, nextIndex);
            var wrap = document.createElement('div');
            wrap.innerHTML = html;
            var row = wrap.firstElementChild;
            container.appendChild(row);
            nextIndex++;
            row.querySelector('.btn-remove-service').addEventListener('click', function () { row.remove(); });
          });

          container.querySelectorAll('.btn-remove-service').forEach(function (btn) {
            btn.addEventListener('click', function () { btn.closest('.service-row').remove(); });
          });
        })();
        </script>
@endsection
