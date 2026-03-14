<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->decimal('transfer_fee_under_amount', 12, 2)->nullable()->after('payment_methods')->comment('Transfer fee applies for amounts under this (GBP)');
            $table->decimal('transfer_fee_amount', 12, 2)->nullable()->after('transfer_fee_under_amount')->comment('Fee amount in GBP');
        });
    }

    public function down(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->dropColumn(['transfer_fee_under_amount', 'transfer_fee_amount']);
        });
    }
};
