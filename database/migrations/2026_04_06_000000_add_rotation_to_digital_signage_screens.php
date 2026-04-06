<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('digital_signage_screens', function (Blueprint $table) {
            $table->unsignedSmallInteger('rotation')->default(0)->after('qr_link');
            $table->string('last_seen_resolution', 20)->nullable()->after('rotation');
        });
    }

    public function down(): void
    {
        Schema::table('digital_signage_screens', function (Blueprint $table) {
            $table->dropColumn(['rotation', 'last_seen_resolution']);
        });
    }
};
