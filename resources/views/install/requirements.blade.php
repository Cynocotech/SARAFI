@extends('install.layout')
@section('title', 'Step 1: Requirements')
@section('content')
<div class="install-card">
  <div class="install-header">
    <h1>آقای صرافی</h1>
    <p>نصب — مرحله ۱: بررسی پیش‌نیازها</p>
  </div>

  <div class="install-body">
    <table class="req-table">
      <thead>
        <tr>
          <th>مورد</th>
          <th>وضعیت</th>
        </tr>
      </thead>
      <tbody>
        @foreach($requirements as $r)
        <tr class="{{ $r['passed'] ? 'passed' : 'failed' }}">
          <td>{{ $r['name'] }}</td>
          <td>
            @if($r['passed'])
              <span class="badge success">✓ {{ $r['current'] ?? 'OK' }}</span>
            @else
              <span class="badge fail">✗ {{ $r['current'] ?? 'Failed' }}</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    @if($passed)
      <a href="{{ route('install.database') }}" class="btn-primary">ادامه →</a>
    @else
      <p class="text-warn">لطفاً موارد بالا را برطرف کنید و صفحه را بازخوانی کنید.</p>
      <a href="{{ route('install.requirements') }}" class="btn-secondary">بررسی مجدد</a>
    @endif
  </div>
</div>
@endsection
