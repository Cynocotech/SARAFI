<?php

namespace App\Filament\Widgets;

use App\Models\ExchangeOffice;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopExchangesByClicksWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ExchangeOffice::query()
                    ->orderByDesc('clicks')
                    ->limit(10)
            )
            ->heading('پربازدیدترین صرافی‌ها (بر اساس مجموع کلیک‌ها)')
            ->emptyStateHeading('هنوز کلیکی ثبت نشده')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('صرافی')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('شهر')
                    ->searchable(false)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('postcode')
                    ->label('پست کد')
                    ->searchable(false)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('clicks')
                    ->label('کلیک‌ها')
                    ->numeric()
                    ->sortable(false),
                Tables\Columns\IconColumn::make('identity_verified')
                    ->label('تأیید شده')
                    ->boolean(),
            ])
            ->paginated(false);
    }
}
