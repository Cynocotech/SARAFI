<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OnboardingFieldResource\Pages;
use App\Models\OnboardingField;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OnboardingFieldResource extends Resource
{
    protected static ?string $model = OnboardingField::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationLabel = 'فیلدهای فرم ثبت صرافی';

    protected static ?string $modelLabel = 'فیلد فرم ثبت صرافی';

    protected static ?string $pluralModelLabel = 'فیلدهای فرم ثبت صرافی';

    protected static ?string $navigationGroup = 'مدیریت';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('key')
                    ->label('کلید فیلد')
                    ->options(function (?OnboardingField $record): array {
                        $keyOptions = array_combine(OnboardingField::ALLOWED_KEYS, OnboardingField::ALLOWED_KEYS);
                        $used = OnboardingField::query()->pluck('key')->all();
                        if ($record) {
                            $used = array_diff($used, [$record->key]);
                        }
                        return array_diff_key($keyOptions, array_flip($used));
                    })
                    ->required()
                    ->helperText('فیلدهای جدول صرافی. هر کلید فقط یک بار قابل استفاده است.'),
                Forms\Components\TextInput::make('label')
                    ->label('برچسب (نمایش به کاربر)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label('نوع ورودی')
                    ->options([
                        'text' => 'متن',
                        'email' => 'ایمیل',
                        'tel' => 'تلفن',
                        'textarea' => 'متن چندخطی',
                    ])
                    ->default('text')
                    ->required(),
                Forms\Components\TextInput::make('placeholder')
                    ->label('Placeholder')
                    ->maxLength(255),
                Forms\Components\Toggle::make('required')
                    ->label('الزامی')
                    ->default(true),
                Forms\Components\TextInput::make('sort_order')
                    ->label('ترتیب نمایش')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')->label('ترتیب')->sortable()->numeric(),
                Tables\Columns\TextColumn::make('key')->label('کلید'),
                Tables\Columns\TextColumn::make('label')->label('برچسب'),
                Tables\Columns\TextColumn::make('type')->label('نوع'),
                Tables\Columns\IconColumn::make('required')->label('الزامی')->boolean(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOnboardingFields::route('/'),
            'create' => Pages\CreateOnboardingField::route('/create'),
            'edit' => Pages\EditOnboardingField::route('/{record}/edit'),
        ];
    }
}
