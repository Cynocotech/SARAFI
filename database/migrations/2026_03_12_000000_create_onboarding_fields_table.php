<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onboarding_fields', function (Blueprint $table) {
            $table->id();
            $table->string('key', 64)->unique()->comment('Field key matching ExchangeOffice attribute, e.g. name, phone');
            $table->string('label');
            $table->string('type', 32)->default('text'); // text, email, tel, textarea
            $table->string('placeholder')->nullable();
            $table->boolean('required')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onboarding_fields');
    }
};
