<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_signage_screens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_office_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('token', 32)->unique();
            $table->string('pairing_code', 8)->unique();
            $table->string('background_color', 20)->nullable();
            $table->string('background_image_path')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index(['exchange_office_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_signage_screens');
    }
};
