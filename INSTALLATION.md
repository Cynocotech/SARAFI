# آقای صرافی — راهنمای نصب روی cPanel

## روش ۱: نصب با ویزارد (توصیه شده)

### ۱. آپلود فایل‌ها

1. فایل‌های پروژه را روی cPanel آپلود کنید (مثلاً در `public_html/aghasarafi` یا `public_html`).
2. **مهم:** مطمئن شوید پوشه `public` به‌عنوان Document Root تنظیم شده است:
   - **cPanel:** Domains → Addon Domain یا Subdomain
   - Document Root را روی `public_html/aghasarafi/public` قرار دهید (یا مسیری که فایل‌های پروژه را در آن آپلود کرده‌اید).

### ۲. ایجاد دیتابیس MySQL

1. cPanel → **MySQL® Databases**
2. یک دیتابیس جدید بسازید (مثلاً `username_aghasarafi`)
3. یک کاربر MySQL بسازید و رمز عبور تنظیم کنید
4. کاربر را به دیتابیس اضافه کنید و همه دسترسی‌ها را بدهید (ALL PRIVILEGES)

### ۳. اجرای ویزارد نصب

1. در مرورگر به آدرس سایت بروید (مثلاً `https://yourdomain.com`).
2. اگر نصب نشده باشد، به‌طور خودکار به **`/install`** هدایت می‌شوید.
3. مراحل را دنبال کنید:
   - **مرحله ۱:** بررسی پیش‌نیازها (PHP، اکستنشن‌ها، مجوز نوشتن)
   - **مرحله ۲:** اطلاعات دیتابیس و حساب مدیر را وارد کنید
4. دکمه **«نصب و راه‌اندازی»** را بزنید.
5. پس از اتمام، به `/admin` بروید و با ایمیل و رمز مدیر وارد شوید.

---

## روش ۲: نصب دستی (SSH)

اگر به SSH دسترسی دارید:

```bash
cd /path/to/project
composer install --no-dev
cp .env.example .env
php artisan key:generate
# .env را ویرایش کنید: DB_* و APP_URL
php artisan migrate --force
php artisan db:seed --class=Database\\Seeders\\PlanSeeder --force
php artisan db:seed --class=Database\\Seeders\\OnboardingFieldSeeder --force
php artisan storage:link
php artisan make:filament-user
```

---

## تنظیم Document Root در cPanel

برای Laravel، Document Root باید پوشه `public` باشد:

| مسیر پروژه                     | Document Root صحیح              |
|--------------------------------|---------------------------------|
| `public_html/aghasarafi/`      | `public_html/aghasarafi/public` |
| `home/user/aghasarafi/`        | `home/user/aghasarafi/public`   |

اگر کل پروژه در `public_html` است، دو روش دارید:

1. **تغییر مسیر:** پوشه‌های `app`, `config`, `database`, ... را به یک پوشه بالا (مثلاً `laravel`) منتقل کنید و فقط `public` را در `public_html` نگه دارید، و در `.env` مسیر صحیح را تنظیم کنید.
2. **استفاده از Subdomain یا Addon Domain** با Document Root روی `.../public`.

---

## Stripe (اختیاری)

برای پرداخت و KYC، در Stripe Dashboard تنظیم کنید و در پنل ادمین → Settings کلیدها را وارد کنید.

---

## رفع مشکلات متداول

- **۵۰۰ Error:** مجوزها را بررسی کنید: `storage` و `bootstrap/cache` باید قابل نوشتن باشند (۷۵۵ یا ۷۷۵).
- **Database connection failed:** Host را معمولاً `localhost` بگذارید؛ در برخی هاست‌ها `127.0.0.1` یا نام مشخص شده توسط میزبانی استفاده می‌شود.
- **صفحه سفید:** `APP_DEBUG=true` را در `.env` موقتاً فعال کنید و در `storage/logs/laravel.log` خطا را ببینید.
