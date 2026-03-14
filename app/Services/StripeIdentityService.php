<?php

namespace App\Services;

use App\Models\ExchangeOffice;
use Stripe\Identity\VerificationSession;
use Stripe\StripeClient;

class StripeIdentityService
{
    public function __construct(
        protected StripeClient $stripe
    ) {}

    /**
     * Create a Stripe Identity VerificationSession for an exchange office.
     * Returns the session with 'url' for redirect and 'client_secret' for client-side use.
     */
    public function createVerificationSession(ExchangeOffice $office, string $returnUrl): VerificationSession
    {
        $session = $this->stripe->identity->verificationSessions->create([
            'type' => 'document',
            'metadata' => [
                'exchange_office_id' => (string) $office->id,
            ],
            'return_url' => $returnUrl,
        ]);

        $office->update([
            'stripe_verification_session_id' => $session->id,
            'status' => ExchangeOffice::STATUS_PENDING_KYC,
        ]);

        return $session;
    }

    /**
     * Retrieve a VerificationSession by ID.
     */
    public function retrieveSession(string $sessionId): VerificationSession
    {
        return $this->stripe->identity->verificationSessions->retrieve($sessionId);
    }
}
