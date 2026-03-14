<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\StripeIdentityService;
use App\Services\TwoFactorService;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use PragmaRX\Google2FA\Google2FA;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(StripeClient::class, function () {
            $secret = Setting::get('stripe_secret') ?: config('services.stripe.secret');
            if (empty($secret)) {
                throw new \InvalidArgumentException(
                    'Stripe API key is not set. Configure it in Admin → تنظیمات سیستم (Stripe) or add STRIPE_SECRET to .env.'
                );
            }
            return new StripeClient($secret);
        });

        $this->app->singleton(StripeIdentityService::class, function ($app) {
            return new StripeIdentityService($app->make(StripeClient::class));
        });

        $this->app->singleton(Google2FA::class, fn () => new Google2FA);
        $this->app->singleton(TwoFactorService::class, function ($app) {
            return new TwoFactorService($app->make(Google2FA::class));
        });
    }

    public function boot(): void
    {
        FilamentView::registerRenderHook(PanelsRenderHook::HEAD_END, function (): string {
            return '<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap" rel="stylesheet">';
        });

        View::composer(['exchanges.index', 'guide', 'contact'], function ($view) {
            $navItems = Setting::get('nav_items');
            if (is_string($navItems)) {
                $decoded = json_decode($navItems, true);
                $navItems = is_array($decoded) ? $decoded : [];
            }
            if (! is_array($navItems) || empty($navItems)) {
                $navItems = [
                    ['label' => 'ثبت صرافی خود', 'route_name' => 'dashboard.onboarding'],
                    ['label' => 'راهنما', 'route_name' => 'guide'],
                    ['label' => 'تماس', 'route_name' => 'contact'],
                ];
            }
            $view->with('nav_items', $navItems);
        });

        View::composer('layouts.app', function ($view) {
            $isLanding = request()->routeIs('exchanges.show');
            $exchangeTheme = (request()->routeIs('exchanges.index') || $isLanding)
                ? (Setting::get('exchange_theme') ?: 'default')
                : 'default';
            $exchangeLandingTheme = Setting::get('exchange_landing_theme') ?: 'default';
            $exchangePrimaryColor = null;

            if ($isLanding) {
                $office = request()->route('exchangeOffice');
                if ($office instanceof \App\Models\ExchangeOffice) {
                    $exchangeLandingTheme = $office->landing_theme ?: $exchangeLandingTheme;
                    $exchangePrimaryColor = $office->primary_color ?: null;
                }
            }

            $view->with('exchange_theme', $exchangeTheme);
            $view->with('exchange_landing_theme', $exchangeLandingTheme);
            $view->with('exchange_primary_color', $exchangePrimaryColor);
            $view->with('is_exchange_landing', $isLanding);
        });

        View::composer('layouts.dashboard', function ($view) {
            $office = Auth::guard('exchange')->user();
            $view->with('office', $office);
        });
    }
}
