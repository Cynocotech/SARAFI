<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'exchange_office_id',
        'plan_id',
        'amount',
        'currency',
        'stripe_payment_intent_id',
        'stripe_invoice_id',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function exchangeOffice(): BelongsTo
    {
        return $this->belongsTo(ExchangeOffice::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
