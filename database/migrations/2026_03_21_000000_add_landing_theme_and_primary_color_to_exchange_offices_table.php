<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->string('landing_theme', 50)->nullable()->after('landing_show_contact')
                ->comment('Theme for this exchange landing: default, theme2_fintech');
            $table->string('primary_color', 20)->nullable()->after('landing_theme')
                ->comment('Hex primary color for landing e.g. #FFB013');
        });
    }

    public function down(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->dropColumn(['landing_theme', 'primary_color']);
        });
    }
};
