<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_office_id')->constrained()->cascadeOnDelete();
            $table->string('from_currency', 3); // ISO e.g. GBP
            $table->string('to_currency', 3);   // ISO e.g. USD, IRR
            $table->decimal('buy_rate', 18, 6);
            $table->decimal('sell_rate', 18, 6);
            $table->decimal('margin', 10, 4)->nullable();
            $table->timestamps();

            $table->unique(['exchange_office_id', 'from_currency', 'to_currency'], 'exrates_office_curr_unique');
            $table->index(['from_currency', 'to_currency']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
