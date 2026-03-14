<?php

use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\AdminTwoFactorController;
use App\Http\Controllers\ExchangeLoginController;
use App\Http\Controllers\ExchangeTwoFactorController;
use App\Http\Controllers\ImpersonateExchangeController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\ExchangeDirectoryController;
use App\Http\Controllers\LandingContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DigitalSignageController;
use App\Http\Controllers\SignageDisplayController;
use App\Models\Plan;
use Illuminate\Support\Facades\Route;

// Installation wizard (only accessible when not installed)
Route::prefix('install')->name('install.')->group(function () {
    Route::get('/', fn () => redirect()->route('install.requirements'));
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('requirements');
    Route::get('/database', [InstallController::class, 'database'])->name('database');
    Route::post('/process', [InstallController::class, 'process'])->name('process');
    Route::get('/complete', [InstallController::class, 'complete'])->name('complete');
});

Route::get('/', function () {
    return redirect()->route('exchanges.index');
});

// Stripe webhook (no CSRF)
Route::post('stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

// Public directory
Route::get('/exchanges', [ExchangeDirectoryController::class, 'index'])->name('exchanges.index');
Route::get('/exchanges/{exchangeOffice}/click', [ExchangeDirectoryController::class, 'recordClickAction'])->name('exchanges.click');
Route::get('/exchanges/{exchangeOffice}', [ExchangeDirectoryController::class, 'show'])->name('exchanges.show');
Route::post('/exchanges/{exchangeOffice}/contact', [LandingContactController::class, 'store'])->name('exchanges.contact');
Route::get('/guide', [PageController::class, 'guide'])->name('guide');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Public digital signage display (fullscreen rates + pairing code)
Route::get('/tv', fn () => redirect()->route('signage.setup'))->name('signage.tv');
Route::get('/signage-setup', [SignageDisplayController::class, 'setup'])->name('signage.setup');
Route::get('/signage/{token}', [SignageDisplayController::class, 'show'])->name('signage.display');

// Exchange login (public)
Route::middleware(['web'])->prefix('exchange')->name('exchange.')->group(function () {
    Route::get('login', [ExchangeLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ExchangeLoginController::class, 'login']);
    Route::post('logout', [ExchangeLoginController::class, 'logout'])->name('logout');
    Route::get('2fa/challenge', [ExchangeTwoFactorController::class, 'showChallenge'])->name('2fa.challenge');
    Route::post('2fa/challenge', [ExchangeTwoFactorController::class, 'verifyChallenge'])->name('2fa.verify');
});

// Admin impersonate exchange (admin only)
Route::middleware(['web', 'auth'])->prefix('impersonate')->name('impersonate.')->group(function () {
    Route::get('exchange/{exchangeOffice}', [ImpersonateExchangeController::class, 'impersonate'])->name('exchange');
});
// Leave impersonation (when logged in as exchange)
Route::middleware(['web', 'auth.exchange'])->get('impersonate/leave', [ImpersonateExchangeController::class, 'leave'])->name('impersonate.leave');

// Admin 2FA (authenticated admin only)
Route::middleware(['web', 'auth'])->prefix('admin-2fa')->name('admin.2fa.')->group(function () {
    Route::get('verify', [AdminTwoFactorController::class, 'showVerify'])->name('verify');
    Route::post('verify', [AdminTwoFactorController::class, 'verify']);
    Route::get('setup', [AdminTwoFactorController::class, 'showSetup'])->name('setup');
    Route::post('setup', [AdminTwoFactorController::class, 'confirmSetup'])->name('confirm');
    Route::get('enabled', [AdminTwoFactorController::class, 'showEnabled'])->name('enabled');
});

// Onboarding (public – register new exchange)
Route::middleware(['web'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/onboarding', [UserDashboardController::class, 'onboarding'])->name('onboarding');
    Route::post('/onboarding/step1', [UserDashboardController::class, 'onboardingStep1'])->name('onboarding.step1');
    Route::get('/onboarding/kyc', [UserDashboardController::class, 'onboardingKyc'])->name('onboarding.kyc');
    Route::get('/onboarding/success', [UserDashboardController::class, 'onboardingSuccess'])->name('onboarding.success');
    Route::get('/set-login', [UserDashboardController::class, 'showSetLogin'])->name('set-login')->middleware('signed');
    Route::post('/set-login', [UserDashboardController::class, 'setLogin'])->name('set-login.store')->middleware('signed');
});

// User dashboard (exchange auth required)
Route::middleware(['web', 'auth.exchange'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('index');
    Route::get('/password', [UserDashboardController::class, 'showPasswordForm'])->name('password');
    Route::post('/password', [UserDashboardController::class, 'updatePassword'])->name('password.update');
    Route::get('/subscription', function () {
        $plans = Plan::active()->ordered()->get();
        $plansJson = $plans->isEmpty()
            ? new \stdClass()
            : $plans->keyBy('id')->map(function ($p) {
                return [
                    'name' => $p->name_fa ?? $p->name,
                    'price' => (float) $p->price,
                    'features' => $p->getFeatureLabels(),
                    'intervalLabel' => $p->getIntervalLabel('fa'),
                ];
            })->all();
        return view('dashboard.subscription', compact('plans', 'plansJson'));
    })->name('subscription');
    Route::post('/subscription/checkout', [StripeCheckoutController::class, 'createSession'])->name('subscription.checkout');
    Route::get('/subscription/success', [StripeCheckoutController::class, 'success'])->name('subscription.success');
    Route::get('/rates-history', [UserDashboardController::class, 'ratesHistory'])->name('rates-history');
    Route::get('/rates', [UserDashboardController::class, 'rates'])->name('rates');
    Route::get('/offices/{office}/rates', [UserDashboardController::class, 'officeRates'])->name('office-rates');
    Route::post('/offices/{office}/rates', [UserDashboardController::class, 'storeRate'])->name('office-rates.store');
    Route::put('/offices/{office}/special-rate', [UserDashboardController::class, 'updateSpecialRate'])->name('special-rate.update');
    Route::put('/offices/{office}/payment-methods', [UserDashboardController::class, 'updatePaymentMethods'])->name('payment-methods.update');
    Route::put('/offices/{office}/transfer-fee', [UserDashboardController::class, 'updateTransferFee'])->name('transfer-fee.update');
    Route::put('/rates/{rate}', [UserDashboardController::class, 'updateRate'])->name('rates.update');
    Route::delete('/rates/{rate}', [UserDashboardController::class, 'deleteRate'])->name('rates.delete');
    Route::post('/logo', [UserDashboardController::class, 'updateLogo'])->name('logo.update');
    Route::post('/logo-url', [UserDashboardController::class, 'updateLogoUrl'])->name('logo-url.update');
    Route::get('/landing', [UserDashboardController::class, 'landing'])->name('landing');
    Route::put('/landing', [UserDashboardController::class, 'updateLanding'])->name('landing.update');
    Route::get('/telegram', [UserDashboardController::class, 'telegram'])->name('telegram');
    Route::post('/telegram', [UserDashboardController::class, 'updateTelegram'])->name('telegram.update');
    Route::post('/telegram/send', [UserDashboardController::class, 'sendTelegramRates'])->name('telegram.send');
    Route::get('/signage', [DigitalSignageController::class, 'index'])->name('signage.index');
    Route::get('/signage/create', [DigitalSignageController::class, 'create'])->name('signage.create');
    Route::post('/signage', [DigitalSignageController::class, 'store'])->name('signage.store');
    Route::post('/signage/pair', [DigitalSignageController::class, 'pair'])->name('signage.pair');
    Route::get('/signage/{screen}/edit', [DigitalSignageController::class, 'edit'])->name('signage.edit');
    Route::put('/signage/{screen}', [DigitalSignageController::class, 'update'])->name('signage.update');
    Route::delete('/signage/{screen}', [DigitalSignageController::class, 'destroy'])->name('signage.destroy');
    Route::get('/2fa', [ExchangeTwoFactorController::class, 'showSetup'])->name('2fa.setup');
    Route::post('/2fa', [ExchangeTwoFactorController::class, 'confirmSetup'])->name('2fa.confirm');
    Route::get('/2fa/enabled', [ExchangeTwoFactorController::class, 'showEnabled'])->name('2fa.enabled');
});
