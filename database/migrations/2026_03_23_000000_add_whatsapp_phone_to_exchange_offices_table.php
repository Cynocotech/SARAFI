<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->string('whatsapp_phone', 30)->nullable()->after('email')
                ->comment('WhatsApp number for floating button (digits only, e.g. 989123456789)');
        });
    }

    public function down(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->dropColumn('whatsapp_phone');
        });
    }
};
