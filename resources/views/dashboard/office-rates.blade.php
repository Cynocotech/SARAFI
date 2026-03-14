@extends('layouts.dashboard')
@section('title', 'نرخ‌های ' . $office->name . ' | آقای صرافی')
@section('dashboard_nav_title', 'نرخ‌ها — ' . $office->name)
@section('dashboard_nav_back', route('dashboard.rates'))
@section('dashboard_main_class', 'dashboard-rates')
@section('content')
    <div class="dashboard-rates-content">
      @if(session('success'))
        <div class="dashboard-rates-alert" style="background:var(--success);color:white;padding:0.75rem 1rem;border-radius:var(--radius);font-size:0.9rem;">
          {{ session('success') }}
        </div>
      @endif

      <div class="dash-card">
        <h2 style="font-size:1.1rem;margin-bottom:1rem;color:var(--text);">نرخ ویژه امروز</h2>
        <p style="color:var(--text-muted);font-size:0.9rem;margin-bottom:1rem;">نرخ ویژه در لیست صرافی‌ها، صفحه جزئیات و صفحه نمایش (تلویزیون) به صورت بنر «نرخ ویژه امروز» نمایش داده می‌شود. انتخاب کنید نرخ ویژه برای خرید، فروش یا هر دو باشد.</p>
        <form action="{{ route('dashboard.special-rate.update', $office) }}" method="POST" class="onboarding-form" id="specialRateForm">
          @csrf
          @method('PUT')
          @php
            $hasBuy = $office->special_rate_buy !== null && $office->special_rate_buy != '';
            $hasSell = $office->special_rate_sell !== null && $office->special_rate_sell != '';
            $specialRateOption = old('special_rate_option', $hasBuy && $hasSell ? 'both' : ($hasSell ? 'sell' : 'buy'));
          @endphp
          <div class="form-group" style="margin-bottom:1rem;">
            <label style="display:block;font-weight:600;margin-bottom:0.5rem;">نرخ ویژه برای</label>
            <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
              <label style="display:flex;align-items:center;gap:0.4rem;cursor:pointer;">
                <input type="radio" name="special_rate_option" value="buy" {{ $specialRateOption === 'buy' ? 'checked' : '' }} class="special-rate-option-input">
                <span>خرید</span>
              </label>
              <label style="display:flex;align-items:center;gap:0.4rem;cursor:pointer;">
                <input type="radio" name="special_rate_option" value="sell" {{ $specialRateOption === 'sell' ? 'checked' : '' }} class="special-rate-option-input">
                <span>فروش</span>
              </label>
              <label style="display:flex;align-items:center;gap:0.4rem;cursor:pointer;">
                <input type="radio" name="special_rate_option" value="both" {{ $specialRateOption === 'both' ? 'checked' : '' }} class="special-rate-option-input">
                <span>خرید و فروش</span>
              </label>
            </div>
          </div>
          <div class="form-row special-rate-fields" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="form-group special-rate-buy-wrap" style="{{ $specialRateOption === 'sell' ? 'display:none;' : '' }}">
              <label for="special_rate_buy">نرخ خرید ویژه (تومان)</label>
              <input type="number" id="special_rate_buy" name="special_rate_buy" value="{{ old('special_rate_buy', $office->special_rate_buy) }}" min="0" step="1" placeholder="خالی = غیرفعال">
              @error('special_rate_buy')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group special-rate-sell-wrap" style="{{ $specialRateOption === 'buy' ? 'display:none;' : '' }}">
              <label for="special_rate_sell">نرخ فروش ویژه (تومان)</label>
              <input type="number" id="special_rate_sell" name="special_rate_sell" value="{{ old('special_rate_sell', $office->special_rate_sell) }}" min="0" step="1" placeholder="خالی = غیرفعال">
              @error('special_rate_sell')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
            </div>
          </div>
          <script>
            (function(){
              var form = document.getElementById('specialRateForm');
              if (!form) return;
              var optBuy = form.querySelector('input[name="special_rate_option"][value="buy"]');
              var optSell = form.querySelector('input[name="special_rate_option"][value="sell"]');
              var optBoth = form.querySelector('input[name="special_rate_option"][value="both"]');
              var wrapBuy = form.querySelector('.special-rate-buy-wrap');
              var wrapSell = form.querySelector('.special-rate-sell-wrap');
              var inputBuy = form.querySelector('#special_rate_buy');
              var inputSell = form.querySelector('#special_rate_sell');
              function updateVisibility(){
                var v = (form.querySelector('input[name="special_rate_option"]:checked') || {}).value;
                if (wrapBuy) wrapBuy.style.display = (v === 'sell') ? 'none' : '';
                if (wrapSell) wrapSell.style.display = (v === 'buy') ? 'none' : '';
                if (v === 'buy' && inputSell) inputSell.value = '';
                if (v === 'sell' && inputBuy) inputBuy.value = '';
              }
              form.querySelectorAll('input[name="special_rate_option"]').forEach(function(r){ r.addEventListener('change', updateVisibility); });
              updateVisibility();
            })();
          </script>
          <button type="submit" class="btn btn-primary btn-block" style="margin-top:0.5rem;">ذخیره نرخ ویژه امروز</button>
          @if($office->hasSpecialRateToday())
            <div style="margin-top:0.75rem;">
              <form action="{{ route('dashboard.special-rate.update', $office) }}" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <input type="hidden" name="clear" value="1">
                <button type="submit" class="btn btn-secondary">حذف نرخ ویژه</button>
              </form>
            </div>
          @endif
        </form>
      </div>

      <div class="dash-card">
        <h2 style="font-size:1.1rem;margin-bottom:0.5rem;color:var(--text);">پرداخت با</h2>
        <p style="color:var(--text-muted);font-size:0.9rem;margin-bottom:1rem;">روش‌های پرداختی که در صرافی می‌پذیرید را انتخاب کنید. در کارت صرافی و صفحه جزئیات با لوگو نمایش داده می‌شوند.</p>
        <form action="{{ route('dashboard.payment-methods.update', $office) }}" method="POST" class="onboarding-form">
          @csrf
          @method('PUT')
          <div style="display:flex;flex-wrap:wrap;gap:1rem 1.5rem;">
            @foreach(\App\Models\ExchangeOffice::paymentMethodOptions() as $key => $label)
              <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;padding:0.5rem 0.75rem;border:1px solid var(--border);border-radius:var(--radius);background:var(--bg-elevated);">
                <input type="checkbox" name="payment_methods[]" value="{{ $key }}" {{ in_array($key, $office->payment_methods ?? [], true) ? 'checked' : '' }}>
                @include('partials.payment-method-logo', ['key' => $key, 'size' => 28])
                <span>{{ $label }}</span>
              </label>
            @endforeach
          </div>
          <button type="submit" class="btn btn-primary" style="margin-top:0.75rem;">ذخیره</button>
        </form>
      </div>

      <div class="dash-card">
        <h2 style="font-size:1.1rem;margin-bottom:0.5rem;color:var(--text);">کارمزد حواله</h2>
        <p style="color:var(--text-muted);font-size:0.9rem;margin-bottom:1rem;">در صورت تمایل کارمزد حواله برای مبالغ زیر یک حد مشخص (مثلاً زیر ۱۰۰۰ پوند) را وارد کنید. هر دو فیلد را پر کنید تا در کارت صرافی و صفحه جزئیات نمایش داده شود.</p>
        <form action="{{ route('dashboard.transfer-fee.update', $office) }}" method="POST" class="onboarding-form">
          @csrf
          @method('PUT')
          <div class="form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="form-group">
              <label for="transfer_fee_under_amount">برای حواله زیر (پوند)</label>
              <input type="number" id="transfer_fee_under_amount" name="transfer_fee_under_amount" value="{{ old('transfer_fee_under_amount', $office->transfer_fee_under_amount) }}" min="0" step="1" placeholder="مثال: 1000">
              @error('transfer_fee_under_amount')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
              <label for="transfer_fee_amount">مبلغ کارمزد (پوند)</label>
              <input type="number" id="transfer_fee_amount" name="transfer_fee_amount" value="{{ old('transfer_fee_amount', $office->transfer_fee_amount) }}" min="0" step="0.01" placeholder="مثال: 5">
              @error('transfer_fee_amount')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
            </div>
          </div>
          <button type="submit" class="btn btn-primary" style="margin-top:0.5rem;">ذخیره کارمزد حواله</button>
        </form>
      </div>

      <div class="dash-card">
        <form action="{{ route('dashboard.office-rates.store', $office) }}" method="POST" class="onboarding-form">
          @csrf
          <input type="hidden" name="from_currency" value="GBP">
          <input type="hidden" name="to_currency" value="IRR">
          <div class="form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="form-group">
              <label for="buy_rate">نرخ خرید (تومان)</label>
              <input type="number" id="buy_rate" name="buy_rate" value="{{ old('buy_rate') }}" min="0" step="1" required placeholder="مثال: 85000">
              @error('buy_rate')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
              <label for="sell_rate">نرخ فروش (تومان)</label>
              <input type="number" id="sell_rate" name="sell_rate" value="{{ old('sell_rate') }}" min="0" step="1" required placeholder="مثال: 86500">
              @error('sell_rate')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
            </div>
          </div>
          @error('from_currency')<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
          <button type="submit" class="btn btn-primary btn-block" style="margin-top:0.5rem;">افزودن نرخ</button>
        </form>
      </div>

      <div class="dash-card span-full">
        <h2 style="font-size:1.1rem;margin-bottom:1rem;color:var(--text);">نرخ‌های فعلی</h2>
        @if($office->exchangeRates->isEmpty())
          <p style="color:var(--text-muted);font-size:0.9rem;">هنوز نرخی ثبت نشده است. با فرم بالا یک نرخ پوند به تومان اضافه کنید.</p>
        @else
          <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
              <thead>
                <tr style="border-bottom:1px solid var(--border);">
                  <th style="text-align:right;padding:0.5rem;">جفت ارز</th>
                  <th style="text-align:right;padding:0.5rem;">خرید</th>
                  <th style="text-align:right;padding:0.5rem;">فروش</th>
                  <th style="text-align:center;padding:0.5rem;">عملیات</th>
                </tr>
              </thead>
              <tbody>
                @foreach($office->exchangeRates as $rate)
                  <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:0.5rem;">{{ $rate->from_currency }} → {{ $rate->to_currency }}</td>
                    <td style="padding:0.5rem;">{{ farsi_num(number_format($rate->buy_rate, 0)) }}</td>
                    <td style="padding:0.5rem;">{{ farsi_num(number_format($rate->sell_rate, 0)) }}</td>
                    <td style="padding:0.5rem;white-space:nowrap;">
                      <a href="{{ route('dashboard.office-rates', $office) }}?edit={{ $rate->id }}" class="btn btn-secondary" style="padding:0.35rem 0.6rem;font-size:0.8rem;">ویرایش</a>
                      <form action="{{ route('dashboard.rates.delete', $rate) }}" method="POST" style="display:inline-block;margin-right:0.25rem;" onsubmit="return confirm('حذف این نرخ؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="padding:0.35rem 0.6rem;font-size:0.8rem;background:var(--danger);color:white;border:none;border-radius:var(--radius);cursor:pointer;">حذف</button>
                      </form>
                    </td>
                  </tr>
                  @if(request('edit') == $rate->id)
                    <tr style="background:var(--bg-elevated);">
                      <td colspan="4" style="padding:1rem;">
                        <form action="{{ route('dashboard.rates.update', $rate) }}" method="POST">
                          @csrf
                          @method('PUT')
                          <div class="form-row" style="display:grid;grid-template-columns:1fr 1fr auto;gap:0.75rem;align-items:end;">
                            <div class="form-group" style="margin:0;">
                              <label>خرید</label>
                              <input type="number" name="buy_rate" value="{{ old('buy_rate', $rate->buy_rate) }}" min="0" step="1" required>
                            </div>
                            <div class="form-group" style="margin:0;">
                              <label>فروش</label>
                              <input type="number" name="sell_rate" value="{{ old('sell_rate', $rate->sell_rate) }}" min="0" step="1" required>
                            </div>
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                          </div>
                        </form>
                      </td>
                    </tr>
                  @endif
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
@endsection
