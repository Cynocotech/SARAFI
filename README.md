# UK Currency Exchange Directory – Laravel Backend

Laravel 12 backend with Filament admin, optional Stripe Identity (KYC), and public directory. Admins can verify exchanges manually; Stripe KYC is optional.

## Stack

- **Laravel 12**
- **Filament 3** (admin panel)
- **Laravel Cashier** (Stripe; ready for billing)
- **Stripe Identity** (optional KYC; admin can verify exchanges instead)
- **Tailwind CSS** (via Vite or CDN fallback)

## Setup

1. **Install dependencies**
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

2. **Database**
   ```bash
   php artisan migrate
   ```

3. **Stripe**
   - In Stripe Dashboard: enable **Identity** and create a webhook endpoint for `identity.verification_session.verified`.
   - Add to `.env`:
     ```
     STRIPE_KEY=pk_...
     STRIPE_SECRET=sk_...
     STRIPE_WEBHOOK_SECRET=whsec_...
     ```
   - Webhook URL: `https://your-domain.com/stripe/webhook` (POST).

4. **Admin user**
   ```bash
   php artisan make:filament-user
   ```
   Then open `/admin` to log in.

5. **Logo uploads (exchange dashboard)**
   For exchange office logos to be visible after upload, create the storage symlink:
   ```bash
   php artisan storage:link
   ```
   This links `public/storage` → `storage/app/public`. Logos are stored under `storage/app/public/exchange-logos/`.

## Routes

| Route | Description |
|-------|-------------|
| `/` | Welcome |
| `/exchanges` | Public list of active offices |
| `/exchanges/{id}` | Public office profile (verified badge, rates, contact, map placeholder) |
| `/dashboard` | User dashboard (onboarding entry) |
| `/dashboard/onboarding` | Step 1: UK business form |
| `/dashboard/onboarding/success?office=id` | After submit: pending admin review; optional link to Stripe KYC |
| `/dashboard/onboarding/kyc?office=id` | Optional: redirects to Stripe Identity |
| `POST /stripe/webhook` | Stripe webhooks (Identity verified → set office active) |
| `/admin` | Filament admin: **Verify (admin)** to allow owner to add rates; optional **Stripe KYC** action |

## UK details

- **Postcode**: Validated with regex `^[A-Z]{1,2}[0-9R][0-9A-Z]?\s?[0-9][ABD-HJLNP-UW-Z]{2}$/i` (see `ExchangeOffice::UK_POSTCODE_REGEX`).
- **FCA**: Optional FCA Firm Reference Number on `ExchangeOffice` (onboarding form and admin).

## Next steps (you)

- Add auth to `/dashboard` routes (e.g. `auth` middleware and login/register).
- Replace placeholder contact form and map on `/exchanges/{id}` with real form handler and Google Maps embed using `postcode`.
- When you have HTML for the user panel, we can convert it to Blade components and wire it to the same controllers.
