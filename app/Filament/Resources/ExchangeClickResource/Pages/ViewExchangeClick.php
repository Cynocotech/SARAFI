<?php

namespace App\Filament\Resources\ExchangeClickResource\Pages;

use App\Filament\Resources\ExchangeClickResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExchangeClick extends ViewRecord
{
    protected static string $resource = ExchangeClickResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
