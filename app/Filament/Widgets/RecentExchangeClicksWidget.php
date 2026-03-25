<?php

namespace App\Filament\Widgets;

use App\Models\ExchangeClick;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentExchangeClicksWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ExchangeClick::query()
                    ->with('exchangeOffice')
                    ->orderByDesc('created_at')
                    ->limit(20)
            )
            ->heading('آخرین کلیک‌های کاربران')
            ->emptyStateHeading('هنوز کلیکی ثبت نشده')
            ->emptyStateIcon('heroicon-o-sparkles')
            ->columns([
                Tables\Columns\TextColumn::make('exchangeOffice.name')
                    ->label('صرافی')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event_type')
                    ->label('نوع رویداد')
                    ->formatStateUsing(function (?string $state): string {
                        return match ($state) {
                            ExchangeClick::TYPE_VIEW => 'view',
                            ExchangeClick::TYPE_CALL => 'call',
                            ExchangeClick::TYPE_MAP => 'map',
                            default => $state ?: '—',
                        };
                    })
                    ->sortable(false),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->dateTime(),
            ])
            ->paginated(false);
    }
}
