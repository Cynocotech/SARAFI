<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->string('tagline')->nullable()->after('name')->comment('Short tagline for landing page hero');
            $table->text('about')->nullable()->after('tagline')->comment('About / description for landing page');
        });
    }

    public function down(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->dropColumn(['tagline', 'about']);
        });
    }
};
