@extends('layouts.dashboard')
@section('title', 'ارسال نرخ‌ها به تلگرام | آقای صرافی')
@section('dashboard_nav_title', 'تلگرام')
@section('dashboard_nav_back', route('dashboard.index'))
@section('content')
        <div style="max-width: 520px; margin: 0 auto; padding: 1.5rem;">
          @if(session('success'))
            <p style="font-size:0.9rem;color:var(--success);margin-bottom:1rem;">{{ session('success') }}</p>
          @endif
          @if($errors->has('telegram'))
            <p style="font-size:0.9rem;color:var(--danger);margin-bottom:1rem;">{{ $errors->first('telegram') }}</p>
          @endif

          <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:1rem;">نرخ‌های پوند به تومان را به ربات یا کانال تلگرام خود ارسال کنید. توکن ربات را از @BotFather و شناسه چت/کانال را در تنظیمات ربات یا با ربات get_id دریافت کنید.</p>

          <form action="{{ route('dashboard.telegram.update') }}" method="POST" class="onboarding-form" style="margin-bottom:1.5rem;">
            @csrf
            <div class="form-group">
              <label for="telegram_bot_token">توکن ربات تلگرام</label>
              <input type="password" id="telegram_bot_token" name="telegram_bot_token" placeholder="{{ $hasToken ? '•••••••• (خالی = بدون تغییر)' : '123456:ABC-DEF...' }}" autocomplete="off">
              @if($hasToken)
                <p style="font-size:0.8rem;color:var(--text-muted);margin-top:0.25rem;">برای حفظ توکن فعلی فیلد را خالی بگذارید.</p>
              @endif
            </div>
            <div class="form-group">
              <label for="telegram_chat_id">شناسه چت یا کانال (Chat ID)</label>
              <input type="text" id="telegram_chat_id" name="telegram_chat_id" value="{{ old('telegram_chat_id', $telegramChatId) }}" placeholder="@channel یا -1001234567890">
            </div>
            <button type="submit" class="btn btn-primary btn-block">ذخیره تنظیمات</button>
          </form>

          <form action="{{ route('dashboard.telegram.send') }}" method="POST" style="margin-top:1rem;">
            @csrf
            <button type="submit" class="btn btn-secondary btn-block">ارسال نرخ‌ها به تلگرام الان</button>
          </form>
        </div>
@endsection
