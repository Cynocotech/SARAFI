<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Pages\Concerns\InteractsWithFormActions;

class SettingsPage extends Page
{
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'تنظیمات سیستم';

    protected static ?string $title = 'تنظیمات سیستم';

    protected static ?string $navigationGroup = 'مدیریت';

    protected static string $view = 'filament.pages.settings-page';

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $navItems = Setting::get('nav_items');
        if (is_string($navItems)) {
            $decoded = json_decode($navItems, true);
            $navItems = is_array($decoded) ? $decoded : null;
        }
        if (! is_array($navItems) || empty($navItems)) {
            $navItems = [
                ['label' => 'ثبت صرافی خود', 'route_name' => 'dashboard.onboarding'],
                ['label' => 'راهنما', 'route_name' => 'guide'],
                ['label' => 'تماس', 'route_name' => 'contact'],
            ];
        }
        $this->form->fill([
            'signage_ticker_text' => Setting::get('signage_ticker_text', ''),
            'stripe_key' => Setting::get('stripe_key', ''),
            'stripe_secret' => Setting::get('stripe_secret', ''),
            'stripe_webhook_secret' => Setting::get('stripe_webhook_secret', ''),
            'exchange_theme' => Setting::get('exchange_theme', 'default'),
            'exchange_landing_theme' => Setting::get('exchange_landing_theme', 'default'),
            'nav_items' => $navItems,
            'guide_title' => Setting::get('guide_title', 'راهنما'),
            'guide_content' => Setting::get('guide_content', ''),
            'contact_title' => Setting::get('contact_title', 'تماس با ما'),
            'contact_content' => Setting::get('contact_content', ''),
            'contact_phone' => Setting::get('contact_phone', ''),
            'contact_email' => Setting::get('contact_email', ''),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('درگاه پرداخت Stripe')
                    ->description('کلیدهای API و وب‌هوک را از داشبورد Stripe دریافت کنید. در صورت خالی بودن از مقادیر .env استفاده می‌شود.')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('stripe_key')
                            ->label('کلید قابل انتشار (Publishable Key)')
                            ->placeholder('pk_live_... یا pk_test_...')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        \Filament\Forms\Components\TextInput::make('stripe_secret')
                            ->label('کلید مخفی (Secret Key)')
                            ->password()
                            ->revealable()
                            ->placeholder('sk_live_... یا sk_test_...')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        \Filament\Forms\Components\TextInput::make('stripe_webhook_secret')
                            ->label('راز وب‌هوک (Webhook Signing Secret)')
                            ->password()
                            ->revealable()
                            ->placeholder('whsec_...')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->collapsible(),
                \Filament\Forms\Components\Section::make('صفحه نمایش دیجیتال')
                    ->schema([
                        Textarea::make('signage_ticker_text')
                            ->label('متن تیکر صفحه نمایش (تبلیغات)')
                            ->placeholder('متن مورد نظر در نوار متحرک صفحه‌های نمایش دیجیتال نمایش داده می‌شود. خالی = بدون تیکر.')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),
                \Filament\Forms\Components\Section::make('تم دایرکتوری صرافی‌ها')
                    ->description('ظاهر صفحه لیست صرافی‌ها را انتخاب کنید.')
                    ->schema([
                        \Filament\Forms\Components\Select::make('exchange_theme')
                            ->label('تم نمایش')
                            ->options([
                                'default' => 'پیش‌فرض (فین‌تک)',
                                'finteklite' => 'فین‌تک لایت (Fintek Lite)',
                                'luxury' => 'لوکس مشکی و طلایی',
                                'fintek2' => 'Fintek 2 (نارنجی و مشکی)',
                                'soldi' => 'Soldi (آبی و سفید)',
                                'soldi_dark' => 'Soldi دارک (آبی و مشکی)',
                            ])
                            ->default('default')
                            ->required(),
                    ])
                    ->collapsible(),
                \Filament\Forms\Components\Section::make('تم صفحه لندینگ صرافی')
                    ->description('ظاهر صفحه لندینگ هر صرافی (صفحه تک‌صفحه‌ای هر صرافی) را انتخاب کنید. مستقل از تم دایرکتوری است.')
                    ->schema([
                        \Filament\Forms\Components\Select::make('exchange_landing_theme')
                            ->label('تم لندینگ')
                            ->options([
                                'default' => 'پیش‌فرض (سارافی — نارنجی)',
                                'theme2_fintech' => 'Theme 2 Fintech (تم ۲ فین‌تک — نارنجی و مشکی)',
                                'theme2' => 'Theme 2 (نavy و طلایی)',
                            ])
                            ->default('default')
                            ->required(),
                    ])
                    ->collapsible(),
                \Filament\Forms\Components\Section::make('منوی پایین (صفحه صرافی‌ها)')
                    ->description('آیتم‌های منوی شناور در پایین صفحه دایرکتوری. نام مسیر (route) باید معتبر باشد.')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('nav_items')
                            ->label('آیتم‌های منو')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('label')
                                    ->label('متن لینک')
                                    ->required()
                                    ->maxLength(255),
                                \Filament\Forms\Components\TextInput::make('route_name')
                                    ->label('نام مسیر (Route)')
                                    ->placeholder('مثال: guide یا dashboard.onboarding')
                                    ->required()
                                    ->maxLength(64),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('افزودن آیتم')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
                \Filament\Forms\Components\Section::make('صفحه راهنما')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('guide_title')
                            ->label('عنوان')
                            ->maxLength(255),
                        \Filament\Forms\Components\Textarea::make('guide_content')
                            ->label('محتوا (متن یا HTML ساده)')
                            ->rows(6)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
                \Filament\Forms\Components\Section::make('صفحه تماس با ما')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('contact_title')
                            ->label('عنوان')
                            ->maxLength(255),
                        \Filament\Forms\Components\Textarea::make('contact_content')
                            ->label('محتوا')
                            ->rows(4)
                            ->columnSpanFull(),
                        \Filament\Forms\Components\TextInput::make('contact_phone')
                            ->label('تلفن')
                            ->tel()
                            ->maxLength(50),
                        \Filament\Forms\Components\TextInput::make('contact_email')
                            ->label('ایمیل')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        Setting::set('signage_ticker_text', $state['signage_ticker_text'] ?? '');
        if (array_key_exists('stripe_key', $state)) {
            Setting::set('stripe_key', $state['stripe_key'] ?? '');
        }
        if (array_key_exists('stripe_secret', $state)) {
            Setting::set('stripe_secret', $state['stripe_secret'] ?? '');
        }
        if (array_key_exists('stripe_webhook_secret', $state)) {
            Setting::set('stripe_webhook_secret', $state['stripe_webhook_secret'] ?? '');
        }
        if (array_key_exists('exchange_theme', $state)) {
            Setting::set('exchange_theme', $state['exchange_theme'] ?? 'default');
        }
        if (array_key_exists('exchange_landing_theme', $state)) {
            Setting::set('exchange_landing_theme', $state['exchange_landing_theme'] ?? 'default');
        }
        if (array_key_exists('nav_items', $state) && is_array($state['nav_items'])) {
            Setting::set('nav_items', $state['nav_items']);
        }
        if (array_key_exists('guide_title', $state)) {
            Setting::set('guide_title', $state['guide_title'] ?? '');
        }
        if (array_key_exists('guide_content', $state)) {
            Setting::set('guide_content', $state['guide_content'] ?? '');
        }
        if (array_key_exists('contact_title', $state)) {
            Setting::set('contact_title', $state['contact_title'] ?? '');
        }
        if (array_key_exists('contact_content', $state)) {
            Setting::set('contact_content', $state['contact_content'] ?? '');
        }
        if (array_key_exists('contact_phone', $state)) {
            Setting::set('contact_phone', $state['contact_phone'] ?? '');
        }
        if (array_key_exists('contact_email', $state)) {
            Setting::set('contact_email', $state['contact_email'] ?? '');
        }
        Notification::make()
            ->title('تنظیمات ذخیره شد.')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('ذخیره')
                ->submit('save'),
        ];
    }
}
