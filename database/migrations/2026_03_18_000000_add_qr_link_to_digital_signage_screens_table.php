<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('digital_signage_screens', function (Blueprint $table) {
            $table->string('qr_link', 500)->nullable()->after('crypto_enabled')->comment('Custom URL for QR code on display');
        });
    }

    public function down(): void
    {
        Schema::table('digital_signage_screens', function (Blueprint $table) {
            $table->dropColumn('qr_link');
        });
    }
};
