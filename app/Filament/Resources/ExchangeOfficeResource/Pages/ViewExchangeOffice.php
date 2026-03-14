<?php

namespace App\Filament\Resources\ExchangeOfficeResource\Pages;

use App\Filament\Resources\ExchangeOfficeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExchangeOffice extends ViewRecord
{
    protected static string $resource = ExchangeOfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('impersonate')
                ->label('ورود به پنل صرافی')
                ->icon('heroicon-o-arrow-right-on-rectangle')
                ->color('gray')
                ->url(fn (): string => route('impersonate.exchange', $this->record)),
        ];
    }
}
