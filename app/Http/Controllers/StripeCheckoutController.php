<?php

namespace App\Http\Controllers;

use App\Models\ExchangeOffice;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Stripe\StripeClient;

class StripeCheckoutController extends Controller
{
    /**
     * Create a Stripe Checkout Session for a plan and redirect to Stripe.
     */
    public function createSession(Request $request): RedirectResponse
    {
        $office = Auth::guard('exchange')->user();
        if (! $office instanceof ExchangeOffice) {
            return redirect()->route('dashboard.subscription')->withErrors(['stripe' => 'دسترسی غیرمجاز.']);
        }

        $validated = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
        ]);

        $plan = Plan::active()->findOrFail($validated['plan_id']);
        $secret = Setting::get('stripe_secret') ?: config('services.stripe.secret');

        if (empty($secret)) {
            return redirect()->route('dashboard.subscription')
                ->withErrors(['stripe' => 'درگاه پرداخت Stripe در تنظیمات فعال نشده است.']);
        }

        try {
            $stripe = new StripeClient($secret);
            $baseUrl = $request->getSchemeAndHttpHost();

            $lineItems = [];
            if (! empty($plan->stripe_price_id)) {
                $lineItems[] = [
                    'price' => $plan->stripe_price_id,
                    'quantity' => 1,
                ];
            } else {
                $amountPence = (int) round((float) $plan->price * 100);
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'gbp',
                        'product_data' => [
                            'name' => $plan->name_fa ?: $plan->name,
                            'description' => $plan->description ?? $plan->getIntervalLabel('fa'),
                        ],
                        'unit_amount' => $amountPence,
                    ],
                    'quantity' => 1,
                ];
            }

            $session = $stripe->checkout->sessions->create([
                'mode' => 'payment',
                'line_items' => $lineItems,
                'success_url' => $baseUrl . route('dashboard.subscription.success', ['session_id' => '{CHECKOUT_SESSION_ID}'], false),
                'cancel_url' => $baseUrl . route('dashboard.subscription', [], false),
                'metadata' => [
                    'exchange_office_id' => (string) $office->id,
                    'plan_id' => (string) $plan->id,
                ],
                'payment_intent_data' => [
                    'metadata' => [
                        'exchange_office_id' => (string) $office->id,
                        'plan_id' => (string) $plan->id,
                    ],
                ],
            ]);

            return redirect($session->url);
        } catch (\Throwable $e) {
            Log::error('Stripe Checkout session creation failed', ['error' => $e->getMessage(), 'plan_id' => $plan->id]);
            return redirect()->route('dashboard.subscription')
                ->withErrors(['stripe' => 'خطا در ایجاد صفحه پرداخت. لطفاً بعداً تلاش کنید یا با پشتیبانی تماس بگیرید.']);
        }
    }

    /**
     * Success page after Stripe Checkout (user returns with session_id).
     * Shows a dedicated success page instead of redirecting back to subscription.
     */
    public function success(Request $request): View|RedirectResponse
    {
        $office = Auth::guard('exchange')->user();

        if (! $office instanceof ExchangeOffice) {
            return redirect()->route('dashboard.index');
        }

        $planName = null;
        $paidAt = now()->locale('fa')->isoFormat('YYYY/M/D، HH:mm');

        $sessionId = $request->query('session_id');
        if ($sessionId) {
            $secret = Setting::get('stripe_secret') ?: config('services.stripe.secret');
            if ($secret) {
                try {
                    $stripe = new StripeClient($secret);
                    $session = $stripe->checkout->sessions->retrieve($sessionId);
                    $planId = $session->metadata->plan_id ?? null;
                    if ($planId) {
                        $plan = Plan::find($planId);
                        $planName = $plan ? ($plan->name_fa ?: $plan->name) : null;
                    }
                    if (isset($session->payment_status) && $session->payment_status === 'paid' && isset($session->created)) {
                        $paidAt = \Carbon\Carbon::createFromTimestamp($session->created)->locale('fa')->isoFormat('YYYY/M/D، HH:mm');
                    }

                    // Ensure a transaction exists for this payment (so it shows in آخرین تراکنش‌ها even if webhook hasn't run yet)
                    if (isset($session->payment_status) && $session->payment_status === 'paid' && $planId) {
                        $paymentIntentId = $session->payment_intent ?? null;
                        $exists = $paymentIntentId
                            ? Transaction::where('stripe_payment_intent_id', $paymentIntentId)->exists()
                            : $office->transactions()->where('plan_id', $planId)->where('paid_at', '>=', now()->subMinutes(30))->exists();
                        if (! $exists) {
                            $plan = Plan::find($planId);
                            $amount = isset($session->amount_total) ? ($session->amount_total / 100) : ($plan ? (float) $plan->price : 0);
                            $currency = $session->currency ?? 'gbp';
                            Transaction::create([
                                'exchange_office_id' => $office->id,
                                'plan_id' => $planId,
                                'amount' => $amount,
                                'currency' => strtoupper($currency),
                                'stripe_payment_intent_id' => $paymentIntentId,
                                'paid_at' => isset($session->created) ? \Carbon\Carbon::createFromTimestamp($session->created) : now(),
                            ]);
                            $office->update(['plan_id' => $planId]);
                        }
                    }
                } catch (\Throwable $e) {
                    // keep defaults, fallback to office plan below
                }
            }
        }

        // Fallback: if we still don't have a plan name, use the office's current plan (e.g. after webhook or from assigned plan)
        if ($planName === null || $planName === '') {
            $plan = $office->getCurrentPlan();
            $planName = $plan ? ($plan->name_fa ?: $plan->name) : null;
        }
        // Fallback: paidAt from latest paid transaction if we didn't get it from Stripe
        if ($paidAt === now()->locale('fa')->isoFormat('YYYY/M/D، HH:mm')) {
            $latestTx = $office->transactions()->whereNotNull('paid_at')->latest('paid_at')->first();
            if ($latestTx && $latestTx->paid_at) {
                $paidAt = $latestTx->paid_at->locale('fa')->isoFormat('YYYY/M/D، HH:mm');
            }
        }

        return view('dashboard.subscription-success', [
            'planName' => $planName,
            'paidAt' => $paidAt,
        ]);
    }
}
