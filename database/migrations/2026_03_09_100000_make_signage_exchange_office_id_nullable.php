<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('digital_signage_screens', function (Blueprint $table) {
            $table->dropForeign(['exchange_office_id']);
        });
        Schema::table('digital_signage_screens', function (Blueprint $table) {
            $table->unsignedBigInteger('exchange_office_id')->nullable()->change();
            $table->foreign('exchange_office_id')->references('id')->on('exchange_offices')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('digital_signage_screens', function (Blueprint $table) {
            $table->dropForeign(['exchange_office_id']);
        });
        Schema::table('digital_signage_screens', function (Blueprint $table) {
            $table->unsignedBigInteger('exchange_office_id')->nullable(false)->change();
            $table->foreign('exchange_office_id')->references('id')->on('exchange_offices')->cascadeOnDelete();
        });
    }
};
