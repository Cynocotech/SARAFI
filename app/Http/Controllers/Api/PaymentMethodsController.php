<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExchangeOffice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodsController extends Controller
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

        $allowedKeys = array_keys(ExchangeOffice::paymentMethodOptions());

        $validated = $request->validate([
            'payment_methods'   => ['present', 'array'],
            'payment_methods.*' => ['string', 'in:' . implode(',', $allowedKeys)],
        ]);

        $office->update(['payment_methods' => $validated['payment_methods']]);

        return response()->json(['ok' => true]);
    }
}
