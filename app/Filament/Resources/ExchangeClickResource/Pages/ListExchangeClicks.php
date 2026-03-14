<?php

namespace App\Filament\Resources\ExchangeClickResource\Pages;

use App\Filament\Resources\ExchangeClickResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExchangeClicks extends ListRecords
{
    protected static string $resource = ExchangeClickResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
