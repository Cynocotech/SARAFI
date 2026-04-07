<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExchangeOffice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialRateController extends Controller
{
    protected function authorizeOffice(ExchangeOffice $office): void
    {
        $current = Auth::guard('exchange')->user();
        if (! $current || $current->id !== $office->id) {
            abort(403, 'دسترسی مجاز نیست.');
        }
    }

    public function update(Request $request, ExchangeOffice $office): JsonResponse
    {
        $this->authorizeOffice($office);

        $validated = $request->validate([
            'special_rate_option' => ['required', 'in:buy,sell,both'],
            'special_rate_buy'    => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'special_rate_sell'   => ['nullable', 'numeric', 'min:0', 'max:999999999'],
        ]);

        $buy  = in_array($validated['special_rate_option'], ['buy', 'both'])  ? $validated['special_rate_buy']  : null;
        $sell = in_array($validated['special_rate_option'], ['sell', 'both']) ? $validated['special_rate_sell'] : null;

        $office->update(['special_rate_buy' => $buy, 'special_rate_sell' => $sell]);

        return response()->json(['ok' => true]);
    }

    public function destroy(ExchangeOffice $office): JsonResponse
    {
        $this->authorizeOffice($office);
        $office->update(['special_rate_buy' => null, 'special_rate_sell' => null]);
        return response()->json(['ok' => true]);
    }
}
