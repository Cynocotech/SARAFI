@extends('layouts.dashboard')
@section('title', 'تاریخچه نرخ پوند به تومان | آقای صرافی')
@section('dashboard_nav_title', 'تاریخچه نرخ‌ها')
@section('dashboard_nav_back', route('dashboard.index'))
@section('content')
  <div class="history-main">
      <div class="history-card">
        <h1>نمودار نرخ خرید و فروش پوند به تومان</h1>
        <p class="subtitle">۳۰ روز گذشته — نرخ به تومان (بر اساس نرخ‌های ثبت‌شده توسط شما)</p>
        <div class="chart-wrap">
          <canvas id="ratesChart"></canvas>
        </div>
        <div class="chart-legend">
          <div class="chart-legend-item"><span class="chart-legend-dot buy"></span><span>نرخ خرید</span></div>
          <div class="chart-legend-item"><span class="chart-legend-dot sell"></span><span>نرخ فروش</span></div>
        </div>
        <p style="margin-top:1rem;font-size:0.9rem;color:var(--text-muted);">در لیست صرافی‌ها و صفحه جزئیات، فلش سبز (بالا) یعنی نرخ نسبت به آخرین تغییر قبلی بالاتر رفته و فلش قرمز (پایین) یعنی نرخ پایین‌تر رفته است. مبنای مقایسه دو آخرین ثبت در تاریخچه زیر است.</p>
      </div>

      @if(isset($historyList) && $historyList->isNotEmpty())
      <div class="history-card" style="margin-top:1.5rem;">
        <h2 style="font-size:1.05rem;margin:0 0 0.75rem 0;">آخرین ثبت‌های نرخ (پوند → تومان)</h2>
        <p class="subtitle" style="margin-bottom:1rem;">همین داده‌ها مبنای نمایش فلش بالا/پایین در کارت صرافی هستند.</p>
        <div style="overflow-x:auto;">
          <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
            <thead>
              <tr style="border-bottom:2px solid var(--border);">
                <th style="text-align:right;padding:0.5rem 0.75rem;">تاریخ و ساعت</th>
                <th style="text-align:right;padding:0.5rem 0.75rem;">خرید (تومان)</th>
                <th style="text-align:right;padding:0.5rem 0.75rem;">فروش (تومان)</th>
              </tr>
            </thead>
            <tbody>
              @foreach($historyList as $row)
                <tr style="border-bottom:1px solid var(--border);">
                  <td style="padding:0.5rem 0.75rem;">{{ $row->recorded_at->timezone('Asia/Tehran')->format('Y/m/d') }} {{ farsi_num($row->recorded_at->format('H:i')) }}</td>
                  <td style="padding:0.5rem 0.75rem;color:var(--success);font-weight:600;">{{ farsi_num(number_format($row->buy_rate, 0)) }}</td>
                  <td style="padding:0.5rem 0.75rem;color:var(--danger);font-weight:600;">{{ farsi_num(number_format($row->sell_rate, 0)) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @else
      <div class="history-card" style="margin-top:1.5rem;">
        <p style="color:var(--text-muted);font-size:0.95rem;margin:0;">با افزودن یا ویرایش نرخ پوند به تومان در صفحه «مدیریت نرخ‌ها»، تاریخچه اینجا و در نمودار پر می‌شود.</p>
      </div>
      @endif
  </div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  (function(){
    document.documentElement.setAttribute('data-theme', 'light');
  })();
  var toP = function(n) { if (n == null) return ''; var s = String(Math.round(n)).replace(/\B(?=(\d{3})+(?!\d))/g, ','); return s.replace(/\d/g, function(d) { return '۰۱۲۳۴۵۶۷۸۹'[d]; }); };
  var labels = @json($chartLabels);
  var buyData = @json($chartBuy);
  var sellData = @json($chartSell);
  var isDark = false;
  var gridColor = 'rgba(100, 116, 139, 0.15)';
  var textColor = '#64748b';
  if (typeof Chart !== 'undefined') {
    new Chart(document.getElementById('ratesChart'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [
          { label: 'نرخ خرید', data: buyData, borderColor: '#0d9488', backgroundColor: 'rgba(13, 148, 136, 0.08)', borderWidth: 2, fill: true, tension: 0.35, spanGaps: true },
          { label: 'نرخ فروش', data: sellData, borderColor: '#0891b2', backgroundColor: 'rgba(6, 182, 212, 0.08)', borderWidth: 2, fill: true, tension: 0.35, spanGaps: true }
        ]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: { legend: { display: false }, tooltip: { rtl: true, textDirection: 'rtl', callbacks: { label: function(ctx) { return ctx.raw != null ? ctx.dataset.label + ': ' + toP(ctx.raw) + ' تومان' : ''; } } } },
        scales: { x: { grid: { color: gridColor }, ticks: { color: textColor, maxRotation: 0, maxTicksLimit: 10 } }, y: { grid: { color: gridColor }, ticks: { color: textColor, callback: function(v) { return toP(v); } } } }
      }
    });
  }
</script>
@endpush
