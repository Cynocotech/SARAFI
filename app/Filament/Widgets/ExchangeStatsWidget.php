<?php

namespace App\Filament\Widgets;

use App\Models\ExchangeOffice;
use App\Models\ExchangeRate;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ExchangeStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $activeCount = ExchangeOffice::where('status', ExchangeOffice::STATUS_ACTIVE)->count();

        $mostTradedPair = ExchangeRate::query()
            ->select('from_currency', 'to_currency', DB::raw('count(*) as total'))
            ->groupBy('from_currency', 'to_currency')
            ->orderByDesc('total')
            ->first();

        $pairLabel = $mostTradedPair
            ? $mostTradedPair->from_currency . '/' . $mostTradedPair->to_currency
            : 'GBP/USD';

        return [
            Stat::make('صرافی‌های فعال UK', $activeCount)
                ->description('صرافی‌های تأییدشده در دایرکتوری')
                ->icon('heroicon-o-building-office-2')
                ->color('success'),
            Stat::make('پرمعامله‌ترین جفت ارز', $pairLabel)
                ->description('جفت ارز با بیشترین نرخ ثبت‌شده')
                ->icon('heroicon-o-currency-pound')
                ->color('primary'),
        ];
    }
}
