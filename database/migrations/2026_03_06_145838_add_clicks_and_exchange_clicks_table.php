<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->unsignedInteger('clicks')->default(0)->after('identity_verified');
        });

        Schema::create('exchange_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_office_id')->constrained()->cascadeOnDelete();
            $table->string('event_type', 20)->default('view'); // view, call, map
            $table->timestamps();
            $table->index(['exchange_office_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_clicks');
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->dropColumn('clicks');
        });
    }
};
