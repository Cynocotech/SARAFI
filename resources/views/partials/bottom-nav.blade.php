<nav class="main-nav floating-nav" aria-label="منو خدمات">
  @foreach($nav_items ?? [] as $item)
    @php
      $routeName = $item['route_name'] ?? null;
      $label = $item['label'] ?? '';
      $url = $routeName && \Illuminate\Support\Facades\Route::has($routeName) ? route($routeName) : '#';
      $isActive = isset($current_route) && $routeName === $current_route;
    @endphp
    <a href="{{ $url }}" class="nav-item {{ $isActive ? 'active' : '' }}" aria-current="{{ $isActive ? 'page' : 'false' }}">
      @if($routeName === 'dashboard.onboarding')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
      @elseif($routeName === 'guide')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
      @elseif($routeName === 'contact')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      @else
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
      @endif
      <span>{{ $label }}</span>
    </a>
  @endforeach
</nav>
