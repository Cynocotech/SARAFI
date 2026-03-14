<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->json('services')->nullable()->after('landing_show_contact')->comment('Landing page services: [{title, description, icon}]');
        });
    }

    public function down(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->dropColumn('services');
        });
    }
};
