@extends('layouts.dashboard')
@section('title', 'مدیریت نرخ‌ها | آقای صرافی')
@section('dashboard_nav_title', 'مدیریت نرخ‌ها')
@section('dashboard_nav_back', route('dashboard.index'))
@section('content')
    @if($offices->isEmpty())
        <div class="dash-card" style="padding:2rem;text-align:center;">
          <h2 style="font-size:1.1rem;margin-bottom:0.75rem;color:var(--text);">هنوز صرافی تأیید‌شده ندارید</h2>
          <p style="color:var(--text-muted);font-size:0.95rem;margin-bottom:1rem;">پس از تأیید صرافی توسط ادمین می‌توانید نرخ پوند به تومان را اضافه و ویرایش کنید.</p>
          <a href="{{ route('dashboard.onboarding') }}" class="btn btn-primary">ثبت صرافی</a>
        </div>
      @else
        <div class="dash-card">
          <h2 style="font-size:1.1rem;margin-bottom:1rem;color:var(--text);">صرافی‌های تأیید‌شده</h2>
          <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:1rem;">یکی از صرافی‌های زیر را انتخاب کنید تا نرخ‌ها را مدیریت کنید.</p>
          <ul style="list-style:none;padding:0;margin:0;">
            @foreach($offices as $office)
              <li style="margin-bottom:0.75rem;">
                <a href="{{ route('dashboard.office-rates', $office) }}" class="btn btn-secondary" style="display:block;text-align:center;text-decoration:none;">
                  {{ $office->name }} — {{ $office->city }} <span class="postcode-uppercase">{{ strtoupper($office->postcode) }}</span>
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      @endif
@endsection
