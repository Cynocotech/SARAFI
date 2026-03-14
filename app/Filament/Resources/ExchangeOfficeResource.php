<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExchangeOfficeResource\Pages;
use App\Filament\Resources\ExchangeOfficeResource\RelationManagers;
use App\Models\ExchangeOffice;
use App\Models\Plan;
use App\Services\StripeIdentityService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExchangeOfficeResource extends Resource
{
    protected static ?string $model = ExchangeOffice::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'صرافی‌ها';

    protected static ?string $navigationGroup = 'دایرکتوری';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اطلاعات کسب‌وکار')
                    ->schema([
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('لوگو')
                            ->image()
                            ->directory('exchange-logos')
                            ->disk('public')
                            ->maxSize(2048)
                            ->nullable()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tagline')
                            ->label('شعار / تگ‌لاین (سایت)')
                            ->placeholder('مثال: بهترین نرخ پوند در لندن')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('about')
                            ->label('درباره ما (سایت)')
                            ->placeholder('توضیح کوتاه درباره صرافی، خدمات و مزایا.')
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('fca_number')
                            ->label('شماره FCA')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('company_house_id')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('address_line_1')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('city')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('postcode')
                            ->required()
                            ->maxLength(20)
                            ->rule(ExchangeOffice::postcodeRule()),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(30),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options([
                                ExchangeOffice::STATUS_DRAFT => 'پیش‌نویس',
                                ExchangeOffice::STATUS_PENDING_KYC => 'در انتظار KYC',
                                ExchangeOffice::STATUS_ACTIVE => 'فعال',
                                ExchangeOffice::STATUS_SUSPENDED => 'معلق',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('identity_verified')
                            ->label('احراز هویت شده'),
                    ])->columns(2),
                Forms\Components\Section::make('ویژگی‌های صرافی')
                    ->description('ویژگی‌های برتر این صرافی برای نمایش در دایرکتوری.')
                    ->schema([
                        Forms\Components\CheckboxList::make('features')
                            ->label('ویژگی‌ها')
                            ->options(ExchangeOffice::exchangeFeatureOptions())
                            ->columns(2)
                            ->bulkToggleable(),
                        Forms\Components\Select::make('currencies')
                            ->label('ارزهای پشتیبانی‌شده')
                            ->options(ExchangeOffice::supportedCurrencyOptions())
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText('ارزهایی که این صرافی در آنها نرخ دارد.'),
                    ])
                    ->collapsible(),
                Forms\Components\Section::make('ظاهر صفحه لندینگ')
                    ->description('تم و رنگ صفحه لندینگ عمومی این صرافی. در صورت خالی بودن تم، از تنظیمات پیش‌فرض سایت استفاده می‌شود.')
                    ->schema([
                        Forms\Components\Select::make('landing_theme')
                            ->label('تم لندینگ')
                            ->options(ExchangeOffice::landingThemeOptions())
                            ->default('')
                            ->placeholder('استفاده از تم پیش‌فرض سایت')
                            ->helperText('Theme 2 = تم نavy و طلایی.'),
                        Forms\Components\TextInput::make('about_image_url')
                            ->label('تصویر بخش درباره ما')
                            ->url()
                            ->maxLength(2048)
                            ->placeholder('https://...')
                            ->helperText('اختیاری. در صورت خالی بودن از تصویر پیش‌فرض استفاده می‌شود.')
                            ->columnSpanFull(),
                        Forms\Components\ColorPicker::make('primary_color')
                            ->label('رنگ اصلی (اختیاری)')
                            ->helperText('برای تم‌هایی که از رنگ سفارشی پشتیبانی می‌کنند (مثلاً #d4af37).'),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Forms\Components\Section::make('خدمات ما (صفحه لندینگ)')
                    ->description('خدماتی که در بخش «خدمات ما» روی صفحه لندینگ نمایش داده می‌شوند. در صورت خالی بودن، سه خدمت پیش‌فرض نمایش داده می‌شود.')
                    ->schema([
                        Forms\Components\Repeater::make('services')
                            ->label('خدمات')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('عنوان')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('مثال: انتقال سریع وجه'),
                                Forms\Components\Textarea::make('description')
                                    ->label('توضیح کوتاه')
                                    ->rows(2)
                                    ->maxLength(500)
                                    ->placeholder('توضیح یک یا دو خطی'),
                                Forms\Components\Select::make('icon')
                                    ->label('آیکون')
                                    ->options(ExchangeOffice::serviceIconOptions())
                                    ->default('payments')
                                    ->required()
                                    ->searchable(),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel('افزودن خدمت')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                    ])
                    ->collapsible(),
                Forms\Components\Section::make('پلن')
                    ->description('پلن فعلی این صرافی. امکانات از همین پلن گرفته می‌شود.')
                    ->schema([
                        Forms\Components\Select::make('plan_id')
                            ->label('پلن فعلی')
                            ->options(fn () => Plan::getCachedActiveOrdered()->mapWithKeys(fn (Plan $p) => [$p->id => $p->name_fa ?: $p->name])->all())
                            ->searchable()
                            ->nullable()
                            ->placeholder('پلنی اختصاص داده نشده'),
                    ])
                    ->collapsible(),
                Forms\Components\Section::make('امکانات (از اشتراک)')
                    ->description('امکاناتی که این صرافی به آن دسترسی دارد (از پلن بالا یا تراکنش‌های پرداخت‌شده).')
                    ->schema([
                        Forms\Components\Placeholder::make('exchange_features')
                            ->label('امکانات فعال')
                            ->content(fn (Forms\Components\Placeholder $component): string => $component->getRecord() instanceof ExchangeOffice
                                ? (implode(' • ', $component->getRecord()->getActiveFeatureLabels()) ?: '—')
                                : '—'),
                    ])
                    ->collapsible(),
                Forms\Components\Section::make('ورود (پنل صرافی)')
                    ->description('نام کاربری و رمز عبور برای ورود این صرافی به /exchange/login')
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->label('نام کاربری')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('رمز عبور (خالی = بدون تغییر)')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255),
                    ])->columns(2)->collapsible(),
                Forms\Components\Section::make('وضعیت مسدودیت')
                    ->description('در صورت مسدود بودن، صرافی نمی‌تواند وارد پنل شود و در دایرکتوری نمایش داده نمی‌شود.')
                    ->schema([
                        Forms\Components\Placeholder::make('blocked_info')
                            ->label('وضعیت')
                            ->content(fn (ExchangeOffice $record): string => $record->isBlocked()
                                ? 'مسدود از ' . $record->blocked_at?->format('Y/m/d H:i') . ($record->blocked_reason ? "\nدلیل: " . $record->blocked_reason : '')
                                : 'مسدود نیست'),
                        Forms\Components\Textarea::make('blocked_reason')
                            ->label('دلیل مسدودیت (قابل ویرایش)')
                            ->rows(2)
                            ->maxLength(1000)
                            ->visible(fn (ExchangeOffice $record) => $record->isBlocked()),
                    ])
                    ->visible(fn (?ExchangeOffice $record) => $record && $record->isBlocked())
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('لوگو')
                    ->getStateUsing(fn (ExchangeOffice $record): ?string => $record->logoUrl())
                    ->disk(null)
                    ->rounded()
                    ->defaultImageUrl(fn (ExchangeOffice $record): string => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&size=64'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('ورود')
                    ->placeholder('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('پلن')
                    ->formatStateUsing(fn (ExchangeOffice $record): string => $record->plan?->name_fa ?: $record->plan?->name ?: '—')
                    ->sortable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('exchange_features_badges')
                    ->label('ویژگی‌ها')
                    ->formatStateUsing(fn (ExchangeOffice $record): string => implode(' • ', $record->getExchangeFeatureLabels()) ?: '—')
                    ->wrap()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('currencies_display')
                    ->label('ارزها')
                    ->formatStateUsing(fn (ExchangeOffice $record): string => $record->currencies ? implode(', ', $record->currencies) : '—')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('active_features')
                    ->label('امکانات')
                    ->placeholder('—')
                    ->formatStateUsing(fn (ExchangeOffice $record): string => implode(' • ', $record->getActiveFeatureLabels()) ?: '—')
                    ->wrap()
                    ->sortable(false),
                Tables\Columns\TextColumn::make('clicks')
                    ->label('کلیک‌ها')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('postcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        ExchangeOffice::STATUS_ACTIVE => 'success',
                        ExchangeOffice::STATUS_PENDING_KYC => 'warning',
                        ExchangeOffice::STATUS_SUSPENDED => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('identity_verified')
                    ->boolean()
                    ->label('تأیید شده'),
                Tables\Columns\TextColumn::make('blocked_at')
                    ->label('مسدود')
                    ->formatStateUsing(fn (ExchangeOffice $record): string => $record->isBlocked() ? 'بله' : '—')
                    ->badge()
                    ->color(fn (ExchangeOffice $record): string => $record->isBlocked() ? 'danger' : 'gray')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        ExchangeOffice::STATUS_DRAFT => 'پیش‌نویس',
                        ExchangeOffice::STATUS_PENDING_KYC => 'در انتظار KYC',
                        ExchangeOffice::STATUS_ACTIVE => 'فعال',
                        ExchangeOffice::STATUS_SUSPENDED => 'معلق',
                    ]),
                Tables\Filters\SelectFilter::make('plan_id')
                    ->label('پلن')
                    ->relationship('plan', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('currencies')
                    ->label('ارز')
                    ->form(fn () => [
                        Forms\Components\Select::make('currency')
                            ->options(ExchangeOffice::supportedCurrencyOptions())
                            ->label('ارز پشتیبانی‌شده'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => isset($data['currency']) && $data['currency']
                        ? $query->whereJsonContains('currencies', $data['currency'])
                        : $query),
                Tables\Filters\TernaryFilter::make('blocked_at')
                    ->label('مسدود')
                    ->nullable()
                    ->placeholder('همه')
                    ->trueLabel('مسدود شده')
                    ->falseLabel('مسدود نشده')
                    ->queries(
                        true: fn (Builder $q) => $q->whereNotNull('blocked_at'),
                        false: fn (Builder $q) => $q->whereNull('blocked_at'),
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('impersonate')
                    ->label('ورود به پنل صرافی')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->color('gray')
                    ->url(fn (ExchangeOffice $record): string => route('impersonate.exchange', $record))
                    ->openUrlInNewTab(false),
                Tables\Actions\Action::make('verify_manually')
                    ->label('تأیید (ادمین)')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('این صرافی را تأیید کنید تا مالک بتواند نرخ‌ها را مدیریت کند. تأیید دستی بدون Stripe.')
                    ->action(fn (ExchangeOffice $record) => $record->update([
                        'identity_verified' => true,
                        'status' => ExchangeOffice::STATUS_ACTIVE,
                    ]))
                    ->visible(fn (ExchangeOffice $record) => ! $record->identity_verified),
                Tables\Actions\Action::make('trigger_stripe_identity')
                    ->label('Stripe KYC (اختیاری)')
                    ->icon('heroicon-o-identification')
                    ->action(function (ExchangeOffice $record) {
                        $service = app(StripeIdentityService::class);
                        $returnUrl = url('/admin/exchange-offices');
                        $session = $service->createVerificationSession($record, $returnUrl);
                        return redirect($session->url);
                    })
                    ->visible(fn (ExchangeOffice $record) => $record->status !== ExchangeOffice::STATUS_ACTIVE),
                Tables\Actions\Action::make('block')
                    ->label('مسدود کردن')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('blocked_reason')
                            ->label('دلیل مسدودیت')
                            ->placeholder('دلیل مسدودیت را وارد کنید (اختیاری)')
                            ->rows(3)
                            ->maxLength(1000),
                    ])
                    ->action(function (ExchangeOffice $record, array $data): void {
                        $record->update([
                            'blocked_at' => now(),
                            'blocked_reason' => $data['blocked_reason'] ?? null,
                        ]);
                    })
                    ->visible(fn (ExchangeOffice $record) => ! $record->isBlocked()),
                Tables\Actions\Action::make('unblock')
                    ->label('رفع مسدودیت')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('مسدودیت این صرافی برداشته می‌شود و می‌توانند دوباره وارد پنل شوند.')
                    ->action(fn (ExchangeOffice $record) => $record->update([
                        'blocked_at' => null,
                        'blocked_reason' => null,
                    ]))
                    ->visible(fn (ExchangeOffice $record) => $record->isBlocked()),
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
            RelationManagers\ExchangeRatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExchangeOffices::route('/'),
            'create' => Pages\CreateExchangeOffice::route('/create'),
            'view' => Pages\ViewExchangeOffice::route('/{record}'),
            'edit' => Pages\EditExchangeOffice::route('/{record}/edit'),
        ];
    }
}
