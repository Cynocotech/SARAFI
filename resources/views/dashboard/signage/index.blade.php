@extends('layouts.dashboard')
@section('title', 'صفحه نمایش دیجیتال | آقای صرافی')
@section('dashboard_nav_title', 'صفحه نمایش دیجیتال')
@section('dashboard_nav_back', route('dashboard.index'))
@section('content')
        @if(session('success'))
          <div style="background: var(--success); color: white; padding: 0.75rem 1rem; border-radius: var(--radius); margin-bottom: 1rem; font-size: 0.9rem;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div style="background: var(--danger); color: white; padding: 0.75rem 1rem; border-radius: var(--radius); margin-bottom: 1rem; font-size: 0.9rem;">{{ session('error') }}</div>
        @endif
        <p style="color:var(--text-muted);margin-bottom:1rem;">لینک ثابت را روی تلویزیون باز کنید؛ سپس با اسکن QR از پنل، صفحه را به حساب خود متصل کنید.</p>

        <div class="dash-card" style="margin-bottom:1.5rem;">
          <h2 style="font-size:1rem;font-weight:700;margin:0 0 0.75rem 0;">لینک ثابت برای تلویزیون</h2>
          <p style="color:var(--text-muted);font-size:0.9rem;margin:0 0 0.75rem 0;">این لینک را در مرورگر تلویزیون یا مانیتور باز کنید. پس از باز شدن، کد QR روی صفحه را با دکمه «اتصال با اسکن QR» اسکن کنید.</p>
          <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0.75rem;">
            <input type="text" id="staticTvLink" readonly value="{{ url('/tv') }}" style="flex:1;min-width:200px;padding:0.6rem 0.75rem;font-size:0.9rem;border:1px solid var(--border);border-radius:var(--radius);background:var(--bg-elevated);color:var(--text);">
            <button type="button" id="copyTvLink" class="btn btn-primary" style="white-space:nowrap;">کپی لینک</button>
          </div>
        </div>

        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.5rem;">
          <a href="{{ route('dashboard.signage.create') }}" class="btn btn-primary">افزودن صفحه نمایش</a>
          <button type="button" class="btn btn-secondary" id="btnPairByQr" aria-label="اتصال با اسکن QR">اتصال با اسکن QR</button>
        </div>
        <div class="dash-card" style="margin-bottom:1.5rem;">
          <h2 style="font-size:1rem;font-weight:700;margin:0 0 0.5rem 0;">یا وارد کردن کد اتصال</h2>
          <p style="color:var(--text-muted);font-size:0.9rem;margin:0 0 0.75rem 0;">اگر دوربین کار نمی‌کند یا اسکن ممکن نیست، کد ۶ حرفی روی صفحه نمایش تلویزیون را وارد کنید.</p>
          <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0.5rem;">
            <input type="text" id="pairingCodeInput" maxlength="8" placeholder="مثال: ABC12X" autocomplete="off" style="width:140px;padding:0.6rem 0.75rem;font-size:1rem;font-family:monospace;letter-spacing:0.15em;text-transform:uppercase;border:1px solid var(--border);border-radius:var(--radius);background:var(--bg-elevated);color:var(--text);">
            <button type="button" id="btnPairByCode" class="btn btn-primary">اتصال با کد</button>
          </div>
          <p id="pairByCodeError" style="display:none;font-size:0.85rem;color:var(--danger);margin:0.5rem 0 0;"></p>
        </div>
        <div class="dash-card">
          @if($screens->isEmpty())
            <p style="color:var(--text-muted);">هنوز صفحه‌ای نساخته‌اید. با دکمهٔ بالا یک صفحه اضافه کنید.</p>
          @else
            <ul style="list-style:none;padding:0;margin:0;">
              @foreach($screens as $screen)
                <li style="border-bottom:1px solid var(--border);padding:1rem 0;">
                  <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0.75rem;">
                    <strong>{{ $screen->name ?: ('صفحه ' . $screen->id) }}</strong>
                    <span style="font-size:0.85rem;color:var(--text-muted);">کد: <code style="background:var(--bg-elevated);padding:0.2rem 0.5rem;border-radius:4px;">{{ $screen->pairing_code }}</code></span>
                    <a href="{{ $screen->getDisplayUrl() }}" target="_blank" rel="noopener" class="btn btn-secondary" style="padding:0.35rem 0.75rem;font-size:0.9rem;">باز کردن لینک نمایش</a>
                    <a href="{{ route('dashboard.signage.edit', $screen) }}" class="btn btn-secondary" style="padding:0.35rem 0.75rem;font-size:0.9rem;">ویرایش</a>
                    <form action="{{ route('dashboard.signage.destroy', $screen) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('حذف این صفحه؟');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn" style="padding:0.35rem 0.75rem;font-size:0.9rem;background:var(--danger);color:white;border:none;border-radius:var(--radius);cursor:pointer;">حذف</button>
                    </form>
                  </div>
                  <p style="margin:0.5rem 0 0;font-size:0.8rem;color:var(--text-muted);word-break:break-all;">{{ $screen->getDisplayUrl() }}</p>
                </li>
              @endforeach
            </ul>
          @endif
        </div>

  <div id="qrPairModal" role="dialog" aria-modal="true" aria-labelledby="qrPairModalTitle" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:1000;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:var(--bg);border-radius:var(--radius);max-width:400px;width:100%;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
      <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);">
        <h2 id="qrPairModalTitle" style="margin:0;font-size:1.1rem;font-weight:700;">اتصال با اسکن QR</h2>
        <p style="margin:0.5rem 0 0;font-size:0.9rem;color:var(--text-muted);">دوربین را به QR روی صفحه نمایش بگیرید. روی تلویزیون لینک ثابت <strong>/tv</strong> را باز کنید تا کد QR نمایش داده شود.</p>
      </div>
      <div style="padding:1rem;position:relative;background:#000;">
        <div id="qrReader" style="width:100%;min-height:280px;"></div>
        <p id="qrPairError" style="display:none;color:var(--danger);font-size:0.85rem;margin:0.5rem 0 0;text-align:center;"></p>
      </div>
      <div style="padding:1rem 1.25rem;border-top:1px solid var(--border);">
        <p id="qrPairCameraError" style="display:none;font-size:0.85rem;color:var(--danger);margin-bottom:0.75rem;"></p>
        <p style="font-size:0.85rem;color:var(--text-muted);margin:0 0 0.5rem 0;">اگر دوربین کار نمی‌کند، کد اتصال را در پنل در بخش «وارد کردن کد اتصال» وارد کنید.</p>
        <button type="button" id="qrPairClose" class="btn btn-secondary" style="width:100%;">بستن</button>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
  <script>
  (function() {
    var pairUrl = '{{ route("dashboard.signage.pair") }}';
    var csrf = '{{ csrf_token() }}';
    var modal = document.getElementById('qrPairModal');
    var btnOpen = document.getElementById('btnPairByQr');
    var btnClose = document.getElementById('qrPairClose');
    var errEl = document.getElementById('qrPairError');
    var scanner = null;

    function showModal() {
      modal.style.display = 'flex';
      errEl.style.display = 'none';
      errEl.textContent = '';
      if (!window.Html5Qrcode) {
        errEl.textContent = 'کتابخانه اسکن در حال بارگذاری است…';
        errEl.style.display = 'block';
        return;
      }
      if (scanner && scanner.isScanning()) return;
      if (scanner) scanner.clear().catch(function(){});
      scanner = new Html5Qrcode('qrReader');
      scanner.start({ facingMode: 'environment' }, { fps: 8 }, function(decodedText) {
        var match = decodedText.match(/\/signage\/([a-zA-Z0-9]+)/);
        if (!match) {
          errEl.textContent = 'این QR مربوط به صفحه نمایش نیست. لینک نمایش را اسکن کنید.';
          errEl.style.display = 'block';
          return;
        }
        scanner.stop().then(function() {
          closeModal();
          var formData = new FormData();
          formData.append('_token', csrf);
          formData.append('display_url', decodedText);
          formData.append('token', match[1]);
          fetch(pairUrl, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
          }).then(function(r) { return r.json().then(function(data) { return { ok: r.ok, status: r.status, data: data }; }); })
            .then(function(res) {
              if (res.ok && res.data.redirect) {
                window.location.href = res.data.redirect;
              } else if (res.data && res.data.message) {
                alert(res.data.message);
                if (res.data.redirect) window.location.href = res.data.redirect;
              } else {
                alert(res.data && res.data.message ? res.data.message : 'خطا در اتصال.');
              }
            })
            .catch(function() {
              window.location.href = '{{ route("dashboard.signage.index") }}';
            });
        }).catch(function() {});
      }, function() {      }).catch(function(err) {
        errEl.textContent = 'دسترسی به دوربین ممکن نیست. لطفاً اجازه دوربین را بدهید یا در پایین صفحه از «وارد کردن کد اتصال» استفاده کنید.';
        errEl.style.display = 'block';
        var cameraErr = document.getElementById('qrPairCameraError');
        if (cameraErr) { cameraErr.textContent = err && err.message ? err.message : 'دوربین در دسترس نیست.'; cameraErr.style.display = 'block'; }
      });
    }

    function closeModal() {
      modal.style.display = 'none';
      if (scanner && scanner.isScanning()) {
        scanner.stop().then(function() {}).catch(function() {});
      }
    }

    if (btnOpen) btnOpen.addEventListener('click', showModal);
    if (btnClose) btnClose.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
      if (e.target === modal) closeModal();
    });

    function doPairByCode(code) {
      var normalized = (code || '').trim().toUpperCase();
      if (!normalized) return;
      var formData = new FormData();
      formData.append('_token', csrf);
      formData.append('pairing_code', normalized);
      fetch(pairUrl, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
      }).then(function(r) { return r.json().then(function(data) { return { ok: r.ok, status: r.status, data: data }; }); })
        .then(function(res) {
          if (res.ok && res.data.redirect) {
            window.location.href = res.data.redirect;
          } else {
            var msg = (res.data && res.data.message) ? res.data.message : 'خطا در اتصال.';
            var errBox = document.getElementById('pairByCodeError');
            if (errBox) { errBox.textContent = msg; errBox.style.display = 'block'; }
            else alert(msg);
          }
        })
        .catch(function() {
          var errBox = document.getElementById('pairByCodeError');
          if (errBox) { errBox.textContent = 'خطا در ارتباط با سرور.'; errBox.style.display = 'block'; }
        });
    }

    var pairingInput = document.getElementById('pairingCodeInput');
    var btnPairByCode = document.getElementById('btnPairByCode');
    if (btnPairByCode && pairingInput) {
      btnPairByCode.addEventListener('click', function() {
        document.getElementById('pairByCodeError').style.display = 'none';
        doPairByCode(pairingInput.value);
      });
      pairingInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        var errBox = document.getElementById('pairByCodeError');
        if (errBox) errBox.style.display = 'none';
      });
      pairingInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          doPairByCode(pairingInput.value);
        }
      });
    }

    var copyBtn = document.getElementById('copyTvLink');
    var staticInput = document.getElementById('staticTvLink');
    if (copyBtn && staticInput) {
      copyBtn.addEventListener('click', function() {
        staticInput.select();
        staticInput.setSelectionRange(0, 99999);
        try {
          navigator.clipboard.writeText(staticInput.value);
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
