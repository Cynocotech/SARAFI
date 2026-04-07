<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExchangeOffice;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardStatsController extends Controller
{
    public function show(): JsonResponse
    {
        /** @var ExchangeOffice $office */
        $office = Auth::guard('exchange')->user();

        if (! $office instanceof ExchangeOffice) {
            abort(403);
        }

        $gbpRate = $office->exchangeRates()
            ->where('from_currency', 'GBP')
            ->where('to_currency', 'IRR')
            ->first();

        return response()->json([
            'subscription_active'    => $office->isSubscriptionActive(),
            'subscription_days'      => $office->getSubscriptionDaysRemaining(),
            'plan_name'              => optional($office->getCurrentPlan())->name_fa,
            'gbp_buy_rate'           => $gbpRate?->buy_rate,
            'gbp_sell_rate'          => $gbpRate?->sell_rate,
        ]);
    }
}
