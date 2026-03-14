<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>راه‌اندازی — آقای صرافی</title>
  <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Vazirmatn', sans-serif; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); min-height: 100vh; color: #f1f5f9; padding: 2rem; }
    .container { max-width: 560px; margin: 0 auto; }
    h1 { font-size: 1.75rem; margin-bottom: 1.5rem; }
    .card { background: #1e293b; border-radius: 1rem; padding: 1.5rem 2rem; margin-bottom: 1.5rem; border: 1px solid #334155; }
    .step { font-size: 0.85rem; color: #94a3b8; margin-bottom: 0.5rem; }
    label { display: block; font-weight: 600; margin-bottom: 0.35rem; font-size: 0.9rem; }
    input, select { width: 100%; padding: 0.65rem 1rem; border-radius: 0.5rem; border: 1px solid #475569; background: #0f172a; color: #f1f5f9; font-size: 1rem; margin-bottom: 1rem; font-family: inherit; }
    input:focus, select:focus { outline: none; border-color: #0891b2; box-shadow: 0 0 0 2px rgba(8,145,178,0.2); }
    .btn { display: inline-block; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600; font-size: 1rem; cursor: pointer; text-decoration: none; border: none; font-family: inherit; transition: all 0.2s; }
    .btn-primary { background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%); color: white; }
    .btn-primary:hover { filter: brightness(1.1); }
    .btn-secondary { background: #334155; color: #f1f5f9; }
    .btn-secondary:hover { background: #475569; }
    .error { color: #f87171; font-size: 0.85rem; margin-top: -0.5rem; margin-bottom: 0.5rem; }
    .req-list { list-style: none; }
    .req-list li { padding: 0.5rem 0; border-bottom: 1px solid #334155; display: flex; justify-content: space-between; align-items: center; }
    .req-list li:last-child { border-bottom: none; }
    .req-ok { color: #34d399; }
    .req-fail { color: #f87171; }
    .install-header { margin-bottom: 1.5rem; }
    .install-header h1 { margin-bottom: 0.35rem; }
    .install-header p { color: #94a3b8; font-size: 0.95rem; }
    .install-body { margin-top: 1rem; }
    .req-table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
    .req-table th, .req-table td { padding: 0.6rem 0; border-bottom: 1px solid #334155; text-align: right; }
    .req-table .passed .badge { color: #34d399; }
    .req-table .failed .badge { color: #f87171; }
    .form-section { margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #334155; }
    .form-section:last-of-type { border-bottom: none; }
    .form-section h3 { font-size: 1rem; margin-bottom: 0.75rem; }
    .hint { font-size: 0.85rem; color: #94a3b8; margin-bottom: 0.75rem; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 1rem; }
    .form-grid .span-2 { grid-column: 1 / -1; }
    .form-actions { display: flex; gap: 0.75rem; margin-top: 1.5rem; flex-wrap: wrap; }
    .error-list { list-style: none; background: rgba(248,113,113,0.15); border: 1px solid #f87171; border-radius: 0.5rem; padding: 0.75rem 1rem; margin-bottom: 1rem; color: #f87171; }
    .text-warn { color: #fbbf24; margin-bottom: 1rem; }
    .success-actions { display: flex; gap: 0.75rem; margin-bottom: 1rem; flex-wrap: wrap; }
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <p class="step">راه‌اندازی آقای صرافی</p>
      @yield('content')
    </div>
  </div>
</body>
</html>
