<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExchangeClickResource\Pages;
use App\Filament\Resources\ExchangeClickResource\RelationManagers;
use App\Models\ExchangeClick;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExchangeClickResource extends Resource
{
    protected static ?string $model = ExchangeClick::class;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-rays';

    protected static ?string $navigationLabel = 'کلیک‌ها';

    protected static ?string $modelLabel = 'کلیک';

    protected static ?string $pluralModelLabel = 'کلیک‌ها';

    protected static ?string $navigationGroup = 'دایرکتوری';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('exchangeOffice.name')
                    ->label('صرافی')
                    ->disabled(),
                Forms\Components\TextInput::make('event_type')
                    ->label('نوع')
                    ->disabled(),
                Forms\Components\DateTimePicker::make('created_at')
                    ->label('تاریخ')
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
                Tables\Columns\TextColumn::make('event_type')
                    ->label('نوع')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        ExchangeClick::TYPE_VIEW => 'مشاهده',
                        ExchangeClick::TYPE_CALL => 'تماس',
                        ExchangeClick::TYPE_MAP => 'نقشه',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        ExchangeClick::TYPE_VIEW => 'primary',
                        ExchangeClick::TYPE_CALL => 'success',
                        ExchangeClick::TYPE_MAP => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event_type')
                    ->options([
                        ExchangeClick::TYPE_VIEW => 'مشاهده',
                        ExchangeClick::TYPE_CALL => 'تماس',
                        ExchangeClick::TYPE_MAP => 'نقشه',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExchangeClicks::route('/'),
            'view' => Pages\ViewExchangeClick::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
