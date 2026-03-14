<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_rate_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_office_id')->constrained()->cascadeOnDelete();
            $table->string('from_currency', 3);
            $table->string('to_currency', 3);
            $table->decimal('buy_rate', 18, 6);
            $table->decimal('sell_rate', 18, 6);
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['exchange_office_id', 'from_currency', 'to_currency', 'recorded_at']);
        });

        $this->backfillFromCurrentRates();
    }

    protected function backfillFromCurrentRates(): void
    {
        $rates = \DB::table('exchange_rates')
            ->where('from_currency', 'GBP')
            ->where('to_currency', 'IRR')
            ->get(['exchange_office_id', 'from_currency', 'to_currency', 'buy_rate', 'sell_rate', 'created_at']);

        foreach ($rates as $r) {
            \DB::table('exchange_rate_history')->insert([
                'exchange_office_id' => $r->exchange_office_id,
                'from_currency' => $r->from_currency,
                'to_currency' => $r->to_currency,
                'buy_rate' => $r->buy_rate,
                'sell_rate' => $r->sell_rate,
                'recorded_at' => $r->created_at,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rate_history');
    }
};
