<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->decimal('special_rate_buy', 18, 4)->nullable()->after('currencies');
            $table->decimal('special_rate_sell', 18, 4)->nullable()->after('special_rate_buy');
        });
    }

    public function down(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->dropColumn(['special_rate_buy', 'special_rate_sell']);
        });
    }
};