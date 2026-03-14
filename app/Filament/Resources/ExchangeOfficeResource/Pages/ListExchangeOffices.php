<?php

namespace App\Filament\Resources\ExchangeOfficeResource\Pages;

use App\Filament\Resources\ExchangeOfficeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExchangeOffices extends ListRecords
{
    protected static string $resource = ExchangeOfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
