<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->json('features')->nullable()->after('plan_id')->comment('Feature keys: best_rates, no_commission, 24_7, physical_branch, fast_transfer, online_booking, fca_regulated, etc.');
            $table->json('currencies')->nullable()->after('features')->comment('ISO codes: GBP, EUR, USD, AED, CAD, IRR');
        });
    }

    public function down(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->dropColumn(['features', 'currencies']);
        });
    }
};
