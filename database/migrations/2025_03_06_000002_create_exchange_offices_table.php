<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('fca_number')->nullable()->comment('FCA Firm Reference Number');
            $table->string('company_house_id')->nullable();
            $table->string('address_line_1');
            $table->string('city');
            $table->string('postcode'); // UK format validated in model
            $table->string('status')->default('draft'); // draft, pending_kyc, active, suspended
            $table->string('stripe_verification_session_id')->nullable();
            $table->boolean('identity_verified')->default(false);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('postcode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_offices');
    }
};
