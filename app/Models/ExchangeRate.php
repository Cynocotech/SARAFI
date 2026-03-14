<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class ExchangeRate extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('exchanges.index'));
        static::deleted(fn () => Cache::forget('exchanges.index'));
    }

    protected $fillable = [
        'exchange_office_id',
        'from_currency',
        'to_currency',
        'buy_rate',
        'sell_rate',
        'margin',
    ];

    protected $casts = [
        'buy_rate' => 'decimal:6',
        'sell_rate' => 'decimal:6',
        'margin' => 'decimal:4',
    ];

    public function exchangeOffice(): BelongsTo
    {
        return $this->belongsTo(ExchangeOffice::class);
    }
}
