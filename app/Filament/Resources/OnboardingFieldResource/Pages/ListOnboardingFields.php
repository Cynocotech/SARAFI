<?php

namespace App\Filament\Resources\OnboardingFieldResource\Pages;

use App\Filament\Resources\OnboardingFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOnboardingFields extends ListRecords
{
    protected static string $resource = OnboardingFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
