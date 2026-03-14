<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeRateHistory extends Model
{
    protected $table = 'exchange_rate_history';

    protected $fillable = [
        'exchange_office_id',
        'from_currency',
        'to_currency',
        'buy_rate',
        'sell_rate',
        'recorded_at',
    ];

    protected $casts = [
        'buy_rate' => 'decimal:6',
        'sell_rate' => 'decimal:6',
        'recorded_at' => 'datetime',
    ];

    public function exchangeOffice(): BelongsTo
    {
        return $this->belongsTo(ExchangeOffice::class);
    }
}
