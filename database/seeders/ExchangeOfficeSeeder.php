<?php

namespace Database\Seeders;

use App\Models\ExchangeOffice;
use App\Models\ExchangeRate;
use Illuminate\Database\Seeder;

class ExchangeOfficeSeeder extends Seeder
{
    /**
     * Dummy exchange offices with buy/sell rates (GBP → Toman).
     */
    public function run(): void
    {
        if (ExchangeOffice::where('status', ExchangeOffice::STATUS_ACTIVE)->exists()) {
            return;
        }
        $dummies = [
            [
                'name' => 'صرافی لندن',
                'address_line_1' => '123 High Street',
                'city' => 'London',
                'postcode' => 'W1A 1AA',
                'phone' => '+44 20 7123 4567',
                'email' => 'info@london-exchange.co.uk',
                'identity_verified' => true,
                'buy_rate' => 85000,
                'sell_rate' => 86500,
            ],
            [
                'name' => 'TEST T',
                'address_line_1' => '45 Kenton Road',
                'city' => 'London',
                'postcode' => 'NW4 1PT',
                'phone' => '+44 20 8458 1234',
                'email' => 'contact@testexchange.co.uk',
                'identity_verified' => false,
                'buy_rate' => 84800,
                'sell_rate' => 86200,
            ],
            [
                'name' => 'صرافی پارسیان',
                'address_line_1' => '78 Edgware Road',
                'city' => 'London',
                'postcode' => 'W2 2EG',
                'phone' => '+44 20 7402 9876',
                'email' => null,
                'identity_verified' => true,
                'buy_rate' => 85200,
                'sell_rate' => 86800,
            ],
        ];

        foreach ($dummies as $d) {
            $sellRate = $d['sell_rate'];
            $buyRate = $d['buy_rate'];
            unset($d['buy_rate'], $d['sell_rate']);

            $office = ExchangeOffice::create([
                'name' => $d['name'],
                'address_line_1' => $d['address_line_1'],
                'city' => $d['city'],
                'postcode' => $d['postcode'],
                'status' => ExchangeOffice::STATUS_ACTIVE,
                'identity_verified' => $d['identity_verified'],
                'phone' => $d['phone'] ?? null,
                'email' => $d['email'] ?? null,
            ]);

            $office->exchangeRates()->create([
                'from_currency' => 'GBP',
                'to_currency' => 'IRR',
                'buy_rate' => $buyRate,
                'sell_rate' => $sellRate,
                'margin' => round($sellRate - $buyRate, 2),
            ]);
        }
    }
}
