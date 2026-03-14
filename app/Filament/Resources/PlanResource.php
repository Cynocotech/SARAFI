<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Filament\Resources\PlanResource\RelationManagers;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-pound';

    protected static ?string $navigationLabel = 'پکیج‌های ماهانه';

    protected static ?string $modelLabel = 'پلن';

    protected static ?string $pluralModelLabel = 'پلن‌ها';

    protected static ?string $navigationGroup = 'مالی';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('جزئیات پلن')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('نام (انگلیسی)'),
                        Forms\Components\TextInput::make('name_fa')
                            ->maxLength(255)
                            ->label('نام (فارسی)'),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('£')
                            ->label('قیمت (پوند)'),
                        Forms\Components\Select::make('interval')
                            ->label('دوره (ماه)')
                            ->options(Plan::intervalOptions())
                            ->default(Plan::INTERVAL_1_MONTH)
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\CheckboxList::make('features')
                            ->label('امکانات')
                            ->options(Plan::featureOptions())
                            ->columns(1)
                            ->helperText('همه امکانات پایه • هایلایت در نتایج • پشتیبانی اختصاصی'),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->label('ترتیب نمایش'),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->label('فعال'),
                        Forms\Components\TextInput::make('stripe_price_id')
                            ->maxLength(255)
                            ->label('شناسه قیمت Stripe (اختیاری)'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_fa')
                    ->label('نام (فا)')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('features')
                    ->label('امکانات')
                    ->formatStateUsing(fn (Plan $record): string => implode(' • ', $record->getFeatureLabels()))
                    ->placeholder('—')
                    ->wrap(),
                Tables\Columns\TextColumn::make('price')
                    ->money('GBP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('interval')
                    ->label('دوره')
                    ->formatStateUsing(fn (Plan $record): string => $record->getIntervalLabel())
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('فعال'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('فعال'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
