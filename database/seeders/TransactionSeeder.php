<?php

namespace Database\Seeders;

use App\Models\ExchangeOffice;
use App\Models\Plan;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Create sample transactions so they show in آخرین تراکنش‌ها (admin widget and Transaction resource).
     */
    public function run(): void
    {
        $offices = ExchangeOffice::limit(5)->get();
        $plans = Plan::active()->ordered()->get();

        if ($offices->isEmpty() || $plans->isEmpty()) {
            return;
        }

        $samples = [
            ['plan_index' => 0, 'amount' => 9.99, 'days_ago' => 2],
            ['plan_index' => 1, 'amount' => 19.99, 'days_ago' => 5],
            ['plan_index' => 2, 'amount' => 39.99, 'days_ago' => 0],
            ['plan_index' => 0, 'amount' => 9.99, 'days_ago' => 10],
            ['plan_index' => 1, 'amount' => 19.99, 'days_ago' => 1],
        ];

        foreach ($samples as $i => $s) {
            $office = $offices[$i % $offices->count()];
            $plan = $plans->get(min($s['plan_index'], $plans->count() - 1)) ?? $plans->first();
            $paidAt = Carbon::now()->subDays($s['days_ago']);
            $uniqueId = 'sim_pi_' . $office->id . '_' . $plan->id . '_' . $i;

            Transaction::firstOrCreate(
                ['stripe_payment_intent_id' => $uniqueId],
                [
                    'exchange_office_id' => $office->id,
                    'plan_id' => $plan->id,
                    'amount' => $s['amount'],
                    'currency' => 'GBP',
                    'paid_at' => $paidAt,
                ]
            );
        }

        // Ensure at least one office has plan_id set (for display)
        $firstOffice = $offices->first();
        if ($firstOffice && ! $firstOffice->plan_id) {
            $firstOffice->update(['plan_id' => $plans->first()->id]);
        }
    }
}
