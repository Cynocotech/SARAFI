@extends('layouts.dashboard')
@section('title', 'پرداخت موفق | آقای صرافی')
@section('content')
  <div class="sub">
    <header class="sub-header">
      <h1 class="sub-title">اشتراک ماهانه</h1>
      <a href="{{ route('dashboard.index') }}" class="sub-back">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        بازگشت به پنل
      </a>
    </header>

    <div class="sub-receipt">
      <div class="sub-receipt-icon">✓</div>
      <h2>پرداخت با موفقیت انجام شد</h2>
      <p class="sub-receipt-id">اشتراک شما فعال است</p>
      <div class="sub-receipt-card">
        <div class="sub-receipt-row"><strong>وضعیت:</strong> <span>فعال</span></div>
        <div class="sub-receipt-row"><strong>پلن:</strong> <span>{{ $planName ?? '—' }}</span></div>
        <div class="sub-receipt-row"><strong>تاریخ:</strong> <span>{{ $paidAt ?? now()->locale('fa')->isoFormat('YYYY/M/D، HH:mm') }}</span></div>
      </div>
      <p style="font-size: 0.95rem; color: var(--text-muted); margin: 1rem 0;">از همین لحظه می‌توانید از امکانات پلن خود استفاده کنید.</p>
      <a href="{{ route('dashboard.index') }}" class="sub-btn sub-btn-primary" style="display: inline-block; text-align: center; text-decoration: none; margin-top: 0.5rem;">بازگشت به پنل</a>
      <a href="{{ route('dashboard.subscription') }}" class="sub-btn sub-btn-secondary" style="display: inline-block; text-align: center; text-decoration: none; margin-top: 0.5rem; margin-right: 0.5rem;">صفحه اشتراک</a>
    </div>
  </div>
@endsection
