<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
