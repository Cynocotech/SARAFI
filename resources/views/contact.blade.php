@extends('layouts.app')
@section('title', 'تماس با ما | آقای صرافی')
@section('body_start')
  <header class="sticky-header" id="stickyHeader">
    <div class="header-inner">
      <nav class="section-tabs">
        <a href="{{ route('exchanges.index') }}" class="tab-btn">
          <svg class="tab-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          صرافی‌ها
        </a>
        <a href="{{ route('guide') }}" class="tab-btn">
          <svg class="tab-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
          راهنما
        </a>
        <a href="{{ route('contact') }}" class="tab-btn active">
          <svg class="tab-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          تماس
        </a>
      </nav>
    </div>
  </header>
@endsection
@section('content')
  <main class="directory-list" style="padding: 1.25rem; padding-bottom: 4rem;">
    <div class="dash-card" style="margin: 0;">
      <h1 style="font-size:1.25rem;font-weight:700;color:var(--text);margin:0 0 1rem">{{ $contact_title ?? 'تماس با ما' }}</h1>
      <div style="color:var(--text-muted);line-height:1.7;">{!! $contact_content ?: 'برای تماس با ما از اطلاعات زیر استفاده کنید.' !!}</div>
      @if(!empty($contact_phone ?? '') || !empty($contact_email ?? ''))
        <div style="margin-top:1rem;">
          @if(!empty($contact_phone ?? ''))<p style="margin:0.25rem 0;"><strong>تلفن:</strong> {{ $contact_phone }}</p>@endif
          @if(!empty($contact_email ?? ''))<p style="margin:0.25rem 0;"><strong>ایمیل:</strong> <a href="mailto:{{ $contact_email }}">{{ $contact_email }}</a></p>@endif
        </div>
      @endif
    </div>
  </main>
@endsection
@section('bottom_nav')
  @include('partials.bottom-nav', ['nav_items' => $nav_items ?? [], 'current_route' => 'contact'])
@endsection
