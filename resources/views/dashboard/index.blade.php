@extends('layouts.dashboard')
@section('title', 'پنل مدیریت صرافی | آقای صرافی')
@section('content')
        @if(session('success'))
          <div style="background: var(--success); color: white; padding: 0.75rem 1rem; border-radius: var(--radius); margin-bottom: 1rem; font-size: 0.9rem;">{{ session('success') }}</div>
        @endif
        @if(isset($subscriptionActive) && !$subscriptionActive)
          <div class="subscription-alert" style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.35); color: var(--danger); padding: 0.75rem 1rem; border-radius: var(--radius); margin-bottom: 1rem; font-size: 0.9rem;">
            <strong>اشتراک شما فعال نیست.</strong> برای فعال‌سازی حساب لطفاً اشتراک را تهیه یا تمدید کنید.
            <a href="{{ route('dashboard.subscription') }}" style="color: inherit; font-weight: 700; text-decoration: underline; margin-right: 0.5rem;">رفتن به صفحه اشتراک</a>
          </div>
        @endif
        <div class="stats-row">
          <div class="stat-card">
            @if(isset($subscriptionActive))
              <div class="stat-value" style="{{ $subscriptionActive ? 'color: var(--success);' : 'color: var(--danger);' }}">
                {{ $subscriptionActive ? 'فعال' : 'غیرفعال' }}
              </div>
              @if($planName)
                <div class="stat-sublabel" style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">{{ $planName }}</div>
              @endif
              @if($subscriptionActive && $subscriptionDaysRemaining !== null)
                <div class="stat-sublabel" style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.15rem;">{{ farsi_num($subscriptionDaysRemaining) }} روز باقی‌مانده</div>
              @elseif($subscriptionActive && $subscriptionDaysRemaining === null)
                <div class="stat-sublabel" style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.15rem;">تخصیص ادمین</div>
              @endif
            @else
              <div class="stat-value">—</div>
            @endif
            <div class="stat-label">وضعیت اشتراک</div>
          </div>
          <div class="stat-card">
            <div class="stat-value">—</div>
            <div class="stat-label">نرخ خرید پوند به تومان</div>
          </div>
          <div class="stat-card">
            <div class="stat-value">—</div>
            <div class="stat-label">نرخ فروش پوند به تومان</div>
          </div>
        </div>

        <div class="dashboard-view active" id="view-info">
          <div class="dashboard-grid info-with-notice">
            <div class="dash-card" style="padding:0;">
              <div class="office-info-card">
                @if($office && ($office->identity_verified || $office->status === \App\Models\ExchangeOffice::STATUS_ACTIVE))
                  <div class="office-info-header">
                    @if($office->logoUrl())
                      <img src="{{ $office->logoUrl() }}" alt="" class="office-info-avatar office-info-avatar-img" width="64" height="64">
                    @else
                      <div class="office-info-avatar" aria-hidden="true">{{ mb_substr($office->name, 0, 1) ?: '؟' }}</div>
                    @endif
                    <div class="office-info-title-wrap">
                      <h2 class="office-info-title">
                        {{ $office->name }}
                        <span class="office-info-badge">
                          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                          تأیید شده
                        </span>
                      </h2>
                    </div>
                  </div>
                  <div class="office-info-logo-upload" style="padding:1rem 1.5rem; border-top:1px solid var(--border);">
                    @if(session('success'))
                      <p class="logo-upload-success" style="margin:0 0 0.75rem;padding:0.5rem 0.75rem;background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.4);border-radius:var(--radius);color:var(--success);font-weight:600;">{{ session('success') }}</p>
                    @endif
                    @if($errors->has('logo'))
                      <p class="logo-upload-error" style="margin:0 0 0.75rem;padding:0.5rem 0.75rem;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:var(--radius);color:var(--danger);font-weight:600;">{{ $errors->first('logo') }}</p>
                    @endif
                    @if($errors->has('logo_url'))
                      <p class="logo-upload-error" style="margin:0 0 0.75rem;padding:0.5rem 0.75rem;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:var(--radius);color:var(--danger);font-weight:600;">{{ $errors->first('logo_url') }}</p>
                    @endif
                    <div id="logo-dropzone" class="logo-dropzone">
                      <div class="dz-message" data-dz-message>
                        <span>کلیک یا تصویر را اینجا بکشید</span>
                        <small>JPG، PNG، GIF، WebP — حداکثر ۲ مگابایت</small>
                      </div>
                    </div>
                    <p id="logo-upload-error" class="logo-upload-error-inline" style="display:none;margin:0.5rem 0 0;font-size:0.85rem;color:var(--danger);"></p>
                    <form id="logo-fallback-form" action="{{ route('dashboard.logo.update') }}" method="POST" enctype="multipart/form-data" style="display:none;margin-top:0.75rem;">
                      @csrf
                      <input type="file" name="logo" accept="image/jpeg,image/png,image/gif,image/webp" id="logo-fallback-input" style="font-size:0.9rem;">
                      <button type="submit" class="btn btn-secondary" style="margin-top:0.5rem;">آپلود فایل</button>
                    </form>
                    <p style="margin:1rem 0 0.5rem;font-size:0.9rem;font-weight:600;color:var(--text-muted);">یا لینک تصویر لوگو</p>
                    <form id="logo-url-form" action="{{ route('dashboard.logo-url.update') }}" method="POST" style="display:flex;flex-wrap:wrap;gap:0.5rem;align-items:center;margin-top:0.25rem;">
                      @csrf
                      <input type="url" name="logo_url" id="logo-url-input" value="{{ old('logo_url', $office->logo_url ?? '') }}" placeholder="https://example.com/logo.png" style="flex:1;min-width:180px;padding:0.5rem 0.75rem;border:1px solid var(--border);border-radius:var(--radius);font-size:0.9rem;">
                      <button type="submit" class="btn btn-primary">ذخیره لینک</button>
                    </form>
                  </div>
                  <div class="office-info-body">
                    <ul class="office-info-list">
                      <li class="office-info-item">
                        <span class="office-info-item-icon" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </span>
                        <div class="office-info-item-content">
                          <div class="office-info-item-label">نام کسب‌وکار</div>
                          <div class="office-info-item-value">{{ $office->name }}</div>
                        </div>
                      </li>
                      <li class="office-info-item">
                        <span class="office-info-item-icon" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <div class="office-info-item-content">
                          <div class="office-info-item-label">آدرس</div>
                          <div class="office-info-item-value">{{ $office->address_line_1 }}، {{ $office->city }} <span class="postcode-uppercase">{{ strtoupper($office->postcode) }}</span></div>
                        </div>
                      </li>
                      @if($office->phone)
                        <li class="office-info-item">
                          <span class="office-info-item-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                          </span>
                          <div class="office-info-item-content">
                            <div class="office-info-item-label">تلفن</div>
                            <div class="office-info-item-value"><a href="tel:{{ preg_replace('/\s+/', '', $office->phone) }}" dir="ltr">{{ $office->phone }}</a></div>
                          </div>
                        </li>
                      @endif
                      @if($office->email)
                        <li class="office-info-item">
                          <span class="office-info-item-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                          </span>
                          <div class="office-info-item-content">
                            <div class="office-info-item-label">ایمیل</div>
                            <div class="office-info-item-value"><a href="mailto:{{ $office->email }}">{{ $office->email }}</a></div>
                          </div>
                        </li>
                      @endif
                      @if($office->fca_number)
                        <li class="office-info-item">
                          <span class="office-info-item-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                          </span>
                          <div class="office-info-item-content">
                            <div class="office-info-item-label">شماره FCA</div>
                            <div class="office-info-item-value">{{ $office->fca_number }}</div>
                          </div>
                        </li>
                      @endif
                      @if($office->company_house_id)
                        <li class="office-info-item">
                          <span class="office-info-item-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                          </span>
                          <div class="office-info-item-content">
                            <div class="office-info-item-label">Company House ID</div>
                            <div class="office-info-item-value">{{ $office->company_house_id }}</div>
                          </div>
                        </li>
                      @endif
                    </ul>
                    <div class="office-info-landing-link" style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border);">
                      <div class="office-info-item-label" style="margin-bottom:0.35rem;">لینک سایت شما</div>
                      <p style="font-size:0.85rem;color:var(--text-muted);margin:0 0 0.5rem 0;">این لینک صفحه عمومی صرافی شماست (درباره ما، نرخ‌ها، ماشین‌حساب، تماس). می‌توانید آن را در شبکه‌های اجتماعی یا روی صفحه نمایش دیجیتال استفاده کنید.</p>
                      <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0.5rem;">
                        <input type="text" id="landingPageLink" readonly value="{{ url()->route('exchanges.show', $office) }}" style="flex:1;min-width:180px;padding:0.5rem 0.75rem;font-size:0.85rem;border:1px solid var(--border);border-radius:var(--radius);background:var(--bg-elevated);color:var(--text);direction:ltr;">
                        <button type="button" id="copyLandingLink" class="btn btn-primary" style="white-space:nowrap;">کپی لینک</button>
                        <a href="{{ route('exchanges.show', $office) }}" target="_blank" rel="noopener" class="btn btn-secondary" style="white-space:nowrap;">باز کردن</a>
                      </div>
                    </div>
                    <p class="office-info-footer">از منوی «مدیریت نرخ‌ها» می‌توانید نرخ پوند به تومان را اضافه و ویرایش کنید.</p>
                  </div>
                @else
                  <div class="office-info-body">
                    <div class="office-info-empty">
                      <div class="office-info-empty-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                      </div>
                      <h3>اطلاعات صرافی</h3>
                      <p>برای ثبت صرافی از مراحل زیر استفاده کنید. پس از تأیید توسط ادمین می‌توانید نرخ‌ها را مدیریت کنید.</p>
                      <a href="{{ route('dashboard.onboarding') }}" class="btn-primary">ثبت صرافی</a>
                      <p style="margin-top:1rem;margin-bottom:0;">پس از تأیید توسط ادمین، از منوی «مدیریت نرخ‌ها» می‌توانید نرخ پوند به تومان را اضافه و ویرایش کنید.</p>
                    </div>
                  </div>
                @endif
              </div>
            </div>
            <aside class="notice-board">
              <h2>تابلو اعلانات</h2>
              <div class="notice-item">
                اطلاعات صرافی خود را کامل کنید تا در دایرکتوری با اعتبار بیشتری نمایش داده شوید.
                <div class="date">به‌روزرسانی نرخ‌ها به‌صورت منظم توصیه می‌شود.</div>
              </div>
              <div class="notice-item">
                برای نمایش در لیست صرافی‌ها، اشتراک ماهانه تهیه کنید.
                <div class="date">بعد از فعال‌سازی فوراً اعمال می‌شود.</div>
              </div>
              <div class="notice-item">
                در صورت تغییر آدرس یا شماره تماس، حتماً آن‌ها را به‌روزرسانی کنید.
                <div class="date">اطلاعات نادرست باعث حذف از دایرکتوری می‌شود.</div>
              </div>
            </aside>
          </div>
        </div>

        <div class="dashboard-view" id="view-rates" style="display:none;">
          <div class="dashboard-grid">
            <div class="dash-card span-2">
              <h2>مدیریت نرخ‌ها</h2>
              <p style="font-size:0.9rem;color:var(--text-muted);">پس از تأیید صرافی توسط ادمین، از صفحهٔ مدیریت نرخ‌ها می‌توانید نرخ پوند به تومان را اضافه و ویرایش کنید.</p>
              <a href="{{ route('dashboard.rates') }}" class="btn btn-primary" style="display:inline-block;margin-top:0.75rem;">رفتن به مدیریت نرخ‌ها</a>
            </div>
        </div>
      </div>

  <script>
    document.querySelectorAll('.menu-link[data-view]').forEach(function(link) {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        var viewId = this.getAttribute('data-view');
        document.querySelectorAll('.dashboard-view').forEach(function(v) { v.classList.remove('active'); });
        document.querySelectorAll('.menu-link').forEach(function(l) { l.classList.remove('active'); });
        var target = document.getElementById('view-' + viewId);
        if (target) target.classList.add('active');
        this.classList.add('active');
      });
    });

    (function() {
      var copyBtn = document.getElementById('copyLandingLink');
      var landingInput = document.getElementById('landingPageLink');
      if (copyBtn && landingInput) {
        copyBtn.addEventListener('click', function() {
          landingInput.select();
          landingInput.setSelectionRange(0, 99999);
          try {
            navigator.clipboard.writeText(landingInput.value);
            copyBtn.textContent = 'کپی شد!';
            setTimeout(function() { copyBtn.textContent = 'کپی لینک'; }, 2000);
          } catch (e) {
            copyBtn.textContent = 'کپی دستی';
            setTimeout(function() { copyBtn.textContent = 'کپی لینک'; }, 2000);
          }
        });
      }
    })();
  </script>
@endsection
  @push('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.css" type="text/css">
  @endpush
  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.js"></script>
  <script>
    (function() {
      var dropzoneEl = document.getElementById('logo-dropzone');
      var avatarImg = document.querySelector('.office-info-avatar-img');
      var errorEl = document.getElementById('logo-upload-error');
      var fallbackForm = document.getElementById('logo-fallback-form');
      var fallbackInput = document.getElementById('logo-fallback-input');
      if (!dropzoneEl) return;

      function showFallbackForm() {
        if (fallbackForm) fallbackForm.style.display = 'block';
      }

      function updateAvatarImg(url) {
        if (url && avatarImg) {
          avatarImg.src = url.indexOf('?') === -1 ? url + '?t=' + Date.now() : url + '&t=' + Date.now();
        }
      }

      if (typeof Dropzone === 'undefined') {
        showFallbackForm();
      } else {
        Dropzone.autoDiscover = false;
        try {
          var dz = new Dropzone('#logo-dropzone', {
            url: '{{ route("dashboard.logo.update") }}',
            paramName: 'logo',
            maxFiles: 1,
            maxFilesize: 2,
            acceptedFiles: 'image/jpeg,image/png,image/gif,image/webp',
            addRemoveLinks: false,
            dictDefaultMessage: '',
            dictFileTooBig: 'حجم فایل بیش از ۲ مگابایت است.',
            dictInvalidFileType: 'فرمت مجاز: JPG، PNG، GIF، WebP',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            params: { _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            init: function() {
              this.on('sending', function() {
                if (errorEl) { errorEl.style.display = 'none'; errorEl.textContent = ''; }
              });
              this.on('success', function(file, res) {
                if (res && res.logo_url && avatarImg) {
                  updateAvatarImg(res.logo_url);
                }
                this.removeAllFiles(true);
                setTimeout(function() { window.location.reload(); }, 600);
              });
              this.on('error', function(file, msg) {
                var text = 'خطا در آپلود.';
                if (typeof msg === 'string' && msg) text = msg;
                else if (msg && typeof msg === 'object') {
                  if (msg.message) text = msg.message;
                  else if (msg.errors && msg.errors.logo && msg.errors.logo[0]) text = msg.errors.logo[0];
                }
                if (errorEl) { errorEl.textContent = text; errorEl.style.display = 'block'; }
                this.removeAllFiles(true);
              });
            }
          });
        } catch (e) {
          showFallbackForm();
        }
      }

      if (fallbackForm && fallbackInput) {
        fallbackInput.addEventListener('change', function() {
          if (this.files && this.files.length) fallbackForm.style.display = 'block';
        });
      }

      var logoUrlForm = document.getElementById('logo-url-form');
      var logoUrlInput = document.getElementById('logo-url-input');
      if (logoUrlForm && logoUrlInput) {
        logoUrlForm.addEventListener('submit', function(e) {
          e.preventDefault();
          var formData = new FormData(logoUrlForm);
          fetch(logoUrlForm.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
          }).then(function(r) {
            return r.json().then(function(d) { return { ok: r.ok, status: r.status, data: d }; }).catch(function() { return { ok: false, data: null }; });
          }).then(function(res) {
            if (res.ok && res.data && res.data.logo_url && avatarImg) {
              updateAvatarImg(res.data.logo_url);
            }
            if (res.ok) window.location.reload();
            else if (res.data && res.data.message) alert(res.data.message);
            else logoUrlForm.submit();
          }).catch(function() { logoUrlForm.submit(); });
          return false;
        });
      }
    })();
  </script>
  @endpush
