<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DigitalSignageScreen extends Model
{
    protected $fillable = [
        'exchange_office_id',
        'name',
        'token',
        'pairing_code',
        'background_color',
        'background_image_path',
        'crypto_enabled',
        'qr_link',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'crypto_enabled' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (DigitalSignageScreen $screen) {
            if (empty($screen->token)) {
                $screen->token = Str::random(24);
            }
            if (empty($screen->pairing_code)) {
                $screen->pairing_code = strtoupper(Str::random(6));
            }
        });
    }

    public function exchangeOffice(): BelongsTo
    {
        return $this->belongsTo(ExchangeOffice::class);
    }

    public function backgroundImageUrl(): ?string
    {
        if (! $this->background_image_path) {
            return null;
        }

        return Storage::disk('public')->url($this->background_image_path);
    }

    public function getDisplayUrl(): string
    {
        return route('signage.display', ['token' => $this->token]);
    }
}
