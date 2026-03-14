<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'تراکنش‌ها';

    protected static ?string $modelLabel = 'تراکنش';

    protected static ?string $pluralModelLabel = 'تراکنش‌ها';

    protected static ?string $navigationGroup = 'مالی';

    protected static ?string $description = 'پکیج‌های فروخته‌شده به صرافی‌ها.';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('exchangeOffice.name')
                    ->label('صرافی')
                    ->disabled(),
                Forms\Components\TextInput::make('plan.name')
                    ->label('پلن')
                    ->disabled(),
                Forms\Components\TextInput::make('amount')
                    ->label('مبلغ')
                    ->disabled(),
                Forms\Components\TextInput::make('currency')
                    ->label('واحد پول')
                    ->disabled(),
                Forms\Components\DateTimePicker::make('paid_at')
                    ->label('تاریخ پرداخت')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('exchangeOffice.name')
                    ->label('صرافی')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('پلن')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => $record->plan ? ($record->plan->name_fa ?: $record->plan->name) : '—'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('GBP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('تاریخ')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('paid_at', 'desc')
            ->emptyStateHeading('هنوز تراکنشی ثبت نشده')
            ->emptyStateDescription('پس از پرداخت موفق صرافی‌ها از طریق Stripe، تراکنش‌ها اینجا نمایش داده می‌شوند.')
            ->emptyStateIcon('heroicon-o-banknotes')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'view' => Pages\ViewTransaction::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
