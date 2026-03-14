@extends('layouts.dashboard')
@section('title', 'ثبت صرافی – مرحله ۱')
@section('content')
  <header class="onboarding-header" style="display:flex;align-items:center;gap:1rem;padding:1rem 1.5rem;background:var(--bg-card);border-bottom:1px solid var(--border);">
    <a href="{{ route('exchanges.index') }}" class="onboarding-back" aria-label="بازگشت">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="onboarding-title" style="margin:0;font-size:1.25rem;">ثبت صرافی خود (اطلاعات UK)</h1>
  </header>
  <div style="max-width: 480px; margin: 0 auto; padding: 1.5rem;">
    @if($onboardingFields->isEmpty())
      <p class="text-muted">در حال حاضر امکان ثبت صرافی از طریق فرم وجود ندارد. لطفاً با مدیر تماس بگیرید.</p>
    @else
    <form action="{{ route('dashboard.onboarding.step1') }}" method="POST" class="onboarding-form">
      @csrf
      @foreach($onboardingFields as $field)
        <div class="form-group">
          <label for="field-{{ $field->key }}">{{ $field->label }} @if($field->required)<span class="required">*</span>@endif</label>
          @if($field->type === 'textarea')
            <textarea id="field-{{ $field->key }}" name="{{ $field->key }}" placeholder="{{ $field->placeholder ?? '' }}" @if($field->required) required @endif rows="3">{{ old($field->key) }}</textarea>
          @else
            <input type="{{ $field->type === 'tel' ? 'tel' : ($field->type === 'email' ? 'email' : 'text') }}" id="field-{{ $field->key }}" name="{{ $field->key }}" value="{{ old($field->key) }}" placeholder="{{ $field->placeholder ?? '' }}" @if($field->required) required @endif>
          @endif
          @error($field->key)<p class="text-danger" style="font-size:0.85rem;margin-top:0.25rem;">{{ $message }}</p>@enderror
        </div>
      @endforeach
      <button type="submit" class="btn btn-primary btn-block onboarding-submit">ثبت و ارسال برای بررسی</button>
    </form>
    @endif
  </div>
@endsection
