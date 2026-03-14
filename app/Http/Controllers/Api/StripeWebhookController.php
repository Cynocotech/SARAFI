<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExchangeOffice;
use App\Models\Plan;
use App\Models\Transaction;
use App\Notifications\ExchangeOfficeVerifiedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    /**
     * Handle Stripe webhooks (Identity and others).
     * Route should be excluded from CSRF and use Stripe signature verification.
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = \App\Models\Setting::get('stripe_webhook_secret') ?: config('services.stripe.webhook_secret');

        if (! $webhookSecret) {
            Log::warning('Stripe webhook secret not set');
            return response('Webhook secret not configured', 500);
        }

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        if ($event->type === 'identity.verification_session.verified') {
            $this->handleVerificationSessionVerified($event->data->object);
        }

        if ($event->type === 'checkout.session.completed') {
            $this->handleCheckoutSessionCompleted($event->data->object);
        }

        return response('OK', 200);
    }

    protected function handleVerificationSessionVerified(object $session): void
    {
        $exchangeOfficeId = $session->metadata->exchange_office_id ?? null;
        if (! $exchangeOfficeId) {
            Log::warning('Stripe Identity verification_session.verified missing exchange_office_id in metadata');
            return;
        }

        $office = ExchangeOffice::find($exchangeOfficeId);
        if (! $office) {
            Log::warning('Exchange office not found for Stripe Identity verification', ['id' => $exchangeOfficeId]);
            return;
        }

        $office->update([
            'identity_verified' => true,
            'status' => ExchangeOffice::STATUS_ACTIVE,
        ]);

        if ($office->user) {
            $office->user->notify(new ExchangeOfficeVerifiedNotification($office));
        }
    }

    protected function handleCheckoutSessionCompleted(object $session): void
    {
        $exchangeOfficeId = $session->metadata->exchange_office_id ?? null;
        $planId = $session->metadata->plan_id ?? null;

        if (! $exchangeOfficeId || ! $planId) {
            Log::warning('Stripe checkout.session.completed missing metadata', [
                'session_id' => $session->id ?? null,
            ]);
            return;
        }

        $office = ExchangeOffice::find($exchangeOfficeId);
        $plan = Plan::find($planId);

        if (! $office || ! $plan) {
            Log::warning('Exchange office or plan not found for checkout', [
                'exchange_office_id' => $exchangeOfficeId,
                'plan_id' => $planId,
            ]);
            return;
        }

        $amount = isset($session->amount_total) ? ($session->amount_total / 100) : (float) $plan->price;
        $currency = $session->currency ?? 'gbp';
        $paymentIntentId = $session->payment_intent ?? null;

        if ($paymentIntentId && Transaction::where('stripe_payment_intent_id', $paymentIntentId)->exists()) {
            return;
        }

        Transaction::create([
            'exchange_office_id' => $office->id,
            'plan_id' => $plan->id,
            'amount' => $amount,
            'currency' => strtoupper($currency),
            'stripe_payment_intent_id' => $paymentIntentId,
            'paid_at' => now(),
        ]);

        $office->update(['plan_id' => $plan->id]);
    }
}
