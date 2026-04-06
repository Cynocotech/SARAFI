<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureAdminTwoFactorVerified;
use App\Http\Middleware\SetAdminLocale;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('آقای صرافی')
            ->font('Yekan Bakh', url: asset('css/fonts.css'))
            ->darkMode(false)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(PanelsRenderHook::STYLES_AFTER, function () {
                $fontsUrl = asset('css/fonts.css');
                $url = asset('css/admin-sidebar-dark.css');

                return '<link rel="stylesheet" href="'.$fontsUrl.'"><link rel="stylesheet" href="'.$url.'">'.view('filament.components.admin-sidebar-dark')->render();
            })
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\AnalyticsDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->navigationItems([
                NavigationItem::make('Two-Factor Authentication (2FA)')
                    ->url(url('/admin-2fa/setup'))
                    ->icon('heroicon-o-shield-check')
                    ->visible(fn (): bool => auth()->user() && ! auth()->user()->hasTwoFactorEnabled()),
            ])
            ->middleware([
                SetAdminLocale::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureAdminTwoFactorVerified::class,
            ]);
    }
}
