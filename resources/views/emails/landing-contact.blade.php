<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>پیام تماس</title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; line-height: 1.6; color: #334155; padding: 1rem; }
        .box { max-width: 560px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; background: #f8fafc; }
        h1 { font-size: 1.25rem; margin: 0 0 1rem; color: #0f172a; }
        .row { margin-bottom: 0.75rem; }
        .label { font-weight: 700; color: #475569; font-size: 0.85rem; }
        .value { margin-top: 0.25rem; }
        .message { white-space: pre-wrap; background: #fff; padding: 1rem; border-radius: 6px; border: 1px solid #e2e8f0; margin-top: 0.5rem; }
    </style>
</head>
<body>
    <div class="box">
        <h1>پیام جدید از فرم تماس سایت ({{ $exchangeOffice->name }})</h1>
        <div class="row">
            <div class="label">نام</div>
            <div class="value">{{ $senderName }}</div>
        </div>
        <div class="row">
            <div class="label">ایمیل</div>
            <div class="value"><a href="mailto:{{ $senderEmail }}">{{ $senderEmail }}</a></div>
        </div>
        @if($senderPhone)
        <div class="row">
            <div class="label">تلفن</div>
            <div class="value" dir="ltr">{{ $senderPhone }}</div>
        </div>
        @endif
        <div class="row">
            <div class="label">پیام</div>
            <div class="message">{{ $message }}</div>
        </div>
    </div>
</body>
</html>
