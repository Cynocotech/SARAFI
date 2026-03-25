<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class AnalyticsDashboard extends BaseDashboard
{
    protected static ?int $navigationSort = -2;

    public static function getNavigationLabel(): string
    {
        return 'تحلیل‌ها';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-chart-bar';
    }

    public function getTitle(): string
    {
        return 'تحلیل‌های پنل مدیریت';
    }

    public function getColumns(): int|string|array
    {
        // More space for analytics widgets/cards
        return 3;
    }
}
