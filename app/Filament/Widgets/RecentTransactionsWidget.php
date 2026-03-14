<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTransactionsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->with(['exchangeOffice', 'plan'])
                    ->orderByDesc('paid_at')
                    ->orderByDesc('id')
                    ->limit(15)
            )
            ->heading('آخرین تراکنش‌ها (پکیج‌های فروخته‌شده به صرافی‌ها)')
            ->emptyStateHeading('هنوز تراکنشی ثبت نشده')
            ->emptyStateDescription('پس از پرداخت موفق صرافی‌ها از طریق Stripe، تراکنش‌ها اینجا نمایش داده می‌شوند. از تنظیمات Stripe وب‌هوک را به آدرس checkout.session.completed تنظیم کنید.')
            ->emptyStateIcon('heroicon-o-banknotes')
            ->columns([
                Tables\Columns\TextColumn::make('exchangeOffice.name')
                    ->label('صرافی')
                    ->searchable(false)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('پکیج')
                    ->searchable(false)
                    ->formatStateUsing(fn ($state, $record) => $record->plan ? ($record->plan->name_fa ?: $record->plan->name) : '—')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('GBP')
                    ->label('مبلغ'),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('تاریخ')
                    ->dateTime()
                    ->sortable(false)
                    ->placeholder('—'),
            ])
            ->paginated(false);
    }
}
