<?php

namespace App\Http\Controllers;

use App\Models\DigitalSignageScreen;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class SignageDisplayController extends Controller
{
    private const COINGECKO_IDS = ['bitcoin', 'ethereum', 'tether'];
    private const COINGECKO_CACHE_KEY = 'signage_crypto_rates';
    private const COINGECKO_CACHE_TTL_SECONDS = 120;

    /**
     * Static TV link: one URL the exchange opens on the TV. Reuses the same
     * unpaired screen (via cookie) so refreshing doesn't create new screens.
     * When the TV opens this page we redirect to the pairing view (QR code).
     * They scan the QR from the dashboard to pair the screen to their account.
     */
    public function setup(Request $request): RedirectResponse
    {
        $cookieName = 'signage_pending_token';
        $cookieToken = $request->cookie($cookieName);

        if ($cookieToken) {
            $screen = DigitalSignageScreen::where('token', $cookieToken)->first();
            if ($screen && ! $screen->exchange_office_id) {
                return redirect()->to($screen->getDisplayUrl());
            }
        }

        $screen = DigitalSignageScreen::create([
            'exchange_office_id' => null,
            'name' => null,
            'background_color' => '#06182c',
        ]);

        return redirect()
            ->to($screen->getDisplayUrl())
            ->cookie($cookieName, $screen->token, 60 * 24 * 365); // 1 year
    }

    public function show(Request $request, string $token): View
    {
        $screen = DigitalSignageScreen::where('token', $token)->with('exchangeOffice.exchangeRates')->firstOrFail();

        if (! $screen->exchange_office_id) {
            return view('signage.pairing', [
                'screen' => $screen,
                'display_url' => $screen->getDisplayUrl(),
            ]);
        }

        $office = $screen->exchangeOffice;
        $rates = $office->exchangeRates->filter(fn ($r) => $r->from_currency === 'GBP' && $r->to_currency === 'IRR');
        $otherRates = $office->exchangeRates->filter(fn ($r) => ! ($r->from_currency === 'GBP' && $r->to_currency === 'IRR'));
        $cryptoRates = ($screen->crypto_enabled !== false) ? $this->getCryptoRatesFromApi() : [];
        $paymentMethods = $office->getAcceptedPaymentMethods();
        $qrLink = filled($screen->qr_link) ? $screen->qr_link : route('exchanges.show', $office);

        return view('signage.display', [
            'screen' => $screen,
            'office' => $office,
            'rates' => $rates,
            'otherRates' => $otherRates,
            'cryptoRates' => $cryptoRates,
            'tickerText' => Setting::get('signage_ticker_text', ''),
            'paymentMethods' => $paymentMethods,
            'qrLink' => $qrLink,
        ]);
    }

    /**
     * Fetch crypto prices from CoinGecko demo API (no key required). Cached 2 minutes.
     *
     * @return array<int, array{id: string, symbol: string, name: string, price_usd: float, icon_url: string|null}>
     */
    private function getCryptoRatesFromApi(): array
    {
        return Cache::remember(self::COINGECKO_CACHE_KEY, self::COINGECKO_CACHE_TTL_SECONDS, function () {
            $ids = implode(',', self::COINGECKO_IDS);
            $response = Http::timeout(5)->get("https://api.coingecko.com/api/v3/simple/price", [
                'ids' => $ids,
                'vs_currencies' => 'usd',
                'include_24hr_change' => 'true',
            ]);

            if (! $response->successful()) {
                return [];
            }

            $data = $response->json();
            if (! is_array($data)) {
                return [];
            }

            $names = [
                'bitcoin' => 'بیت‌کوین (BTC)',
                'ethereum' => 'اتریوم (ETH)',
                'tether' => 'تتر (USDT)',
            ];
            $symbols = [
                'bitcoin' => 'BTC',
                'ethereum' => 'ETH',
                'tether' => 'USDT',
            ];
            $iconIds = [
                'bitcoin' => 1,
                'ethereum' => 279,
                'tether' => 325,
            ];

            $list = [];
            foreach (self::COINGECKO_IDS as $id) {
                $p = $data[$id] ?? null;
                if (! is_array($p) || ! isset($p['usd'])) {
                    continue;
                }
                $price = (float) $p['usd'];
                $change24 = isset($p['usd_24h_change']) ? (float) $p['usd_24h_change'] : null;
                $iconId = $iconIds[$id] ?? null;
                $list[] = [
                    'id' => $id,
                    'symbol' => $symbols[$id] ?? strtoupper($id),
                    'name' => $names[$id] ?? $id,
                    'price_usd' => $price,
                    'change_24h' => $change24,
                    'icon_url' => $iconId ? "https://assets.coingecko.com/coins/images/{$iconId}/small/" . $id . '.png' : null,
                ];
            }

            return $list;
        });
    }

    public function reportSize(Request $request, string $token): JsonResponse
    {
        $screen = DigitalSignageScreen::where('token', $token)->firstOrFail();
        $validated = $request->validate([
            'resolution' => ['required', 'string', 'max:20', 'regex:/^\d+x\d+$/'],
        ]);
        $screen->update(['last_seen_resolution' => $validated['resolution']]);

        return response()->json(['ok' => true]);
    }
}
