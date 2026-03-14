<?php

namespace App\Filament\Resources\ExchangeOfficeResource\Pages;

use App\Filament\Resources\ExchangeOfficeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExchangeOffice extends EditRecord
{
    protected static string $resource = ExchangeOfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
