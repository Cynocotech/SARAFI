<?php

namespace App\Filament\Widgets;

use App\Models\ExchangeClick;
use App\Models\ExchangeOffice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClicksAnalyticsWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $todayStart = now()->startOfDay();

        $todayClicks = ExchangeClick::query()
            ->where('created_at', '>=', $todayStart)
            ->count();

        $todayCalls = ExchangeClick::query()
            ->where('event_type', ExchangeClick::TYPE_CALL)
            ->where('created_at', '>=', $todayStart)
            ->count();

        $todayMaps = ExchangeClick::query()
            ->where('event_type', ExchangeClick::TYPE_MAP)
            ->where('created_at', '>=', $todayStart)
            ->count();

        $topOffice = ExchangeOffice::query()
            ->orderByDesc('clicks')
            ->first();

        $topOfficeLabel = $topOffice?->name ?: '—';

        return [
            Stat::make('کلیک‌های امروز', $todayClicks)
                ->description('جمع همه‌ی رویدادها (view/call/map)')
                ->color('primary'),
            Stat::make('تماس‌های امروز', $todayCalls)
                ->description('رویداد: call')
                ->icon('heroicon-o-phone')
                ->color('success'),
            Stat::make('نقشه‌های امروز', $todayMaps)
                ->description('رویداد: map')
                ->icon('heroicon-o-map')
                ->color('warning'),
            Stat::make('پربازدیدترین صرافی', $topOfficeLabel)
                ->description('بر اساس مجموع کلیک‌ها')
                ->icon('heroicon-o-building-office-2')
                ->color('primary'),
        ];
    }
}
