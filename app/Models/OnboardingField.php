<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class OnboardingField extends Model
{
    protected $fillable = ['key', 'label', 'type', 'placeholder', 'required', 'sort_order'];

    protected $casts = [
        'required' => 'boolean',
        'sort_order' => 'integer',
    ];

    /** Allowed keys that map to exchange_offices columns */
    public const ALLOWED_KEYS = [
        'name',
        'fca_number',
        'company_house_id',
        'address_line_1',
        'city',
        'postcode',
        'phone',
        'email',
    ];

    public static function ordered(): \Illuminate\Database\Eloquent\Builder
    {
        return static::query()->orderBy('sort_order')->orderBy('id');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('onboarding_fields.ordered'));
    }

    /** Cached ordered fields (5 min) for onboarding forms. */
    public static function getCachedOrdered()
    {
        return Cache::remember('onboarding_fields.ordered', 300, fn () => static::ordered()->get());
    }
}
