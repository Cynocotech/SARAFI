<?php

namespace App\Filament\Resources\ExchangeClickResource\Pages;

use App\Filament\Resources\ExchangeClickResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExchangeClick extends EditRecord
{
    protected static string $resource = ExchangeClickResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
