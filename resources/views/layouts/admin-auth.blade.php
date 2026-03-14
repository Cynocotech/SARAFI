<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="theme-color" content="#f0f9ff">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'پنل ادمین')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <style>
    body { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; font-family: Vazirmatn, sans-serif; }
    .admin-auth-card { max-width: 400px; width: 100%; padding: 1.5rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
    .admin-auth-card h1 { margin: 0 0 1rem; font-size: 1.25rem; color: var(--text); }
    .admin-auth-card .form-group { margin-bottom: 1rem; }
    .admin-auth-card label { display: block; margin-bottom: 0.35rem; font-size: 0.9rem; color: var(--text); }
    .admin-auth-card input[type="text"] { width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--border); border-radius: var(--radius); font-size: 1rem; }
    .admin-auth-card .text-danger { font-size: 0.85rem; color: var(--danger); margin-top: 0.25rem; }
    .admin-auth-card .btn { padding: 0.6rem 1rem; border-radius: var(--radius); font-weight: 600; cursor: pointer; border: none; width: 100%; font-size: 1rem; }
    .admin-auth-card .btn-primary { background: var(--accent); color: white; }
    .admin-auth-card .btn-secondary { background: var(--bg-elevated); color: var(--text); margin-top: 0.5rem; }
    .admin-auth-card a { color: var(--accent); text-decoration: none; font-size: 0.9rem; }
  </style>
</head>
<body>
  <div class="admin-auth-card">
    @yield('content')
  </div>
</body>
</html>
