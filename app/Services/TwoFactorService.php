<?php

namespace App\Services;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorService
{
    public function __construct(
        protected Google2FA $google2fa
    ) {}

    /**
     * Generate a new secret key (base32).
     */
    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey(32);
    }

    /**
     * Get the otpauth URL for the given secret (for QR code or manual entry).
     */
    public function getQRCodeUrl(string $issuer, string $accountName, #[\SensitiveParameter] string $secret): string
    {
        return $this->google2fa->getQRCodeUrl($issuer, $accountName, $secret);
    }

    /**
     * Generate QR code as SVG string for the given otpauth URL.
     */
    public function getQRCodeSvg(string $otpauthUrl): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(200, 4),
            new SvgImageBackEnd
        );
        $writer = new Writer($renderer);

        return $writer->writeString($otpauthUrl);
    }

    /**
     * Verify a one-time code against the secret. Uses a small time window for clock drift.
     */
    public function verify(#[\SensitiveParameter] string $secret, string $code): bool
    {
        $code = preg_replace('/\s+/', '', $code);
        if (strlen($code) !== 6 || ! ctype_digit($code)) {
            return false;
        }

        return $this->google2fa->verifyKey($secret, $code, 1);
    }
}
