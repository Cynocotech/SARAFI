<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExchangeOffice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferFeeController extends Controller
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
            'transfer_fee_under_amount' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'transfer_fee_amount'       => ['nullable', 'numeric', 'min:0', 'max:999999'],
        ]);

        $office->update($validated);

        return response()->json(['ok' => true]);
    }
}
