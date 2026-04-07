<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExchangeOffice;
use App\Models\ExchangeRate;
use App\Models\ExchangeRateHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficeRatesController extends Controller
{
    protected function authorizeOffice(ExchangeOffice $office): void
    {
        $current = Auth::guard('exchange')->user();
        if (! $current || $current->id !== $office->id) {
            abort(403, 'دسترسی مجاز نیست.');
        }
        if (! $office->identity_verified && $office->status !== ExchangeOffice::STATUS_ACTIVE) {
            abort(403, 'پس از تأیید صرافی می‌توانید نرخ‌ها را مدیریت کنید.');
        }
    }

    protected function authorizeRate(ExchangeRate $rate): void
    {
        $office = $rate->exchangeOffice;
        $this->authorizeOffice($office);
    }

    public function index(ExchangeOffice $office): JsonResponse
    {
        $this->authorizeOffice($office);
        return response()->json(['rates' => $office->exchangeRates()->get()]);
    }

    public function store(Request $request, ExchangeOffice $office): JsonResponse
    {
        $this->authorizeOffice($office);

        $validated = $request->validate([
            'from_currency' => ['required', 'string', 'size:3', 'alpha'],
            'to_currency'   => ['required', 'string', 'size:3', 'alpha'],
            'buy_rate'      => ['required', 'numeric', 'min:0', 'max:999999999'],
            'sell_rate'     => ['required', 'numeric', 'min:0', 'max:999999999'],
        ]);

        $validated['from_currency'] = strtoupper($validated['from_currency']);
        $validated['to_currency']   = strtoupper($validated['to_currency']);

        $exists = $office->exchangeRates()
            ->where('from_currency', $validated['from_currency'])
            ->where('to_currency', $validated['to_currency'])
            ->exists();

        if ($exists) {
            return response()->json([
                'errors' => ['from_currency' => ['این جفت ارز قبلاً ثبت شده است. برای تغییر، نرخ موجود را ویرایش کنید.']],
            ], 422);
        }

        $rate = $office->exchangeRates()->create($validated);

        ExchangeRateHistory::create([
            'exchange_office_id' => $office->id,
            'from_currency'      => $validated['from_currency'],
            'to_currency'        => $validated['to_currency'],
            'buy_rate'           => $validated['buy_rate'],
            'sell_rate'          => $validated['sell_rate'],
            'recorded_at'        => now(),
        ]);

        return response()->json(['rate' => $rate], 201);
    }

    public function update(Request $request, ExchangeRate $rate): JsonResponse
    {
        $this->authorizeRate($rate);

        $validated = $request->validate([
            'buy_rate'  => ['required', 'numeric', 'min:0', 'max:999999999'],
            'sell_rate' => ['required', 'numeric', 'min:0', 'max:999999999'],
        ]);

        $rate->update($validated);

        ExchangeRateHistory::create([
            'exchange_office_id' => $rate->exchange_office_id,
            'from_currency'      => $rate->from_currency,
            'to_currency'        => $rate->to_currency,
            'buy_rate'           => $validated['buy_rate'],
            'sell_rate'          => $validated['sell_rate'],
            'recorded_at'        => now(),
        ]);

        return response()->json(['rate' => $rate->fresh()]);
    }

    public function destroy(ExchangeRate $rate): JsonResponse
    {
        $this->authorizeRate($rate);
        $rate->delete();
        return response()->json(['ok' => true]);
    }
}
