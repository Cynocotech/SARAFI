<?php

namespace App\Filament\Resources\ExchangeOfficeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ExchangeRatesRelationManager extends RelationManager
{
    protected static string $relationship = 'exchangeRates';

    protected static ?string $title = 'نرخ‌های ارز (پوند / تومان)';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('from_currency')
                    ->label('از ارز')
                    ->required()
                    ->options([
                        'GBP' => 'GBP (پوند)',
                        'EUR' => 'EUR (یورو)',
                        'USD' => 'USD (دلار آمریکا)',
                        'AED' => 'AED (درهم)',
                        'CAD' => 'CAD (دلار کانادا)',
                        'IRR' => 'IRR (تومان)',
                    ])
                    ->default('GBP'),
                Forms\Components\Select::make('to_currency')
                    ->label('به ارز')
                    ->required()
                    ->options([
                        'IRR' => 'IRR (تومان)',
                        'GBP' => 'GBP (پوند)',
                        'EUR' => 'EUR (یورو)',
                        'USD' => 'USD (دلار آمریکا)',
                        'AED' => 'AED (درهم)',
                        'CAD' => 'CAD (دلار کانادا)',
                    ])
                    ->default('IRR'),
                Forms\Components\TextInput::make('buy_rate')
                    ->label('نرخ خرید')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01),
                Forms\Components\TextInput::make('sell_rate')
                    ->label('نرخ فروش')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01),
                Forms\Components\TextInput::make('margin')
                    ->label('مارژین (اختیاری)')
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('from_currency')
            ->columns([
                Tables\Columns\TextColumn::make('from_currency')->label('از'),
                Tables\Columns\TextColumn::make('to_currency')->label('به'),
                Tables\Columns\TextColumn::make('buy_rate')->label('خرید')->numeric(decimalPlaces: 0),
                Tables\Columns\TextColumn::make('sell_rate')->label('فروش')->numeric(decimalPlaces: 0),
                Tables\Columns\TextColumn::make('margin')->numeric(decimalPlaces: 2)->placeholder('—'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('افزودن نرخ')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['margin'] = $data['margin'] ?? null;
                        return $data;
                    })
                    ->action(function (Tables\Actions\CreateAction $action, array $data, RelationManager $livewire): Model {
                        $owner = $livewire->getOwnerRecord();
                        $exists = $owner->exchangeRates()
                            ->where('from_currency', $data['from_currency'])
                            ->where('to_currency', $data['to_currency'])
                            ->exists();
                        if ($exists) {
                            Notification::make()
                                ->title('این صرافی قبلاً نرخ ' . $data['from_currency'] . ' / ' . $data['to_currency'] . ' را دارد.')
                                ->body('نرخ موجود را ویرایش کنید یا جفت ارز دیگری انتخاب کنید.')
                                ->danger()
                                ->send();
                            $action->halt();
                        }
                        return $owner->exchangeRates()->create($data);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
