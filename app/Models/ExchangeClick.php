<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeClick extends Model
{
    public const TYPE_VIEW = 'view';
    public const TYPE_CALL = 'call';
    public const TYPE_MAP = 'map';

    protected $fillable = [
        'exchange_office_id',
        'event_type',
    ];

    public function exchangeOffice(): BelongsTo
    {
        return $this->belongsTo(ExchangeOffice::class);
    }
}
