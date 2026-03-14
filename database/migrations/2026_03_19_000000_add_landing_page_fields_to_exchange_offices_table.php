<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->string('hero_title')->nullable()->after('about')->comment('Custom hero title for landing page');
            $table->string('hero_subtitle')->nullable()->after('hero_title')->comment('Custom hero subtitle');
            $table->string('hero_image_path')->nullable()->after('hero_subtitle')->comment('Uploaded hero/banner image');
            $table->string('hero_image_url', 500)->nullable()->after('hero_image_path')->comment('External hero image URL');
            $table->text('map_embed')->nullable()->after('hero_image_url')->comment('Google Maps iframe embed HTML or src URL');
            $table->boolean('landing_show_calculator')->default(true)->after('map_embed');
            $table->boolean('landing_show_map')->default(true)->after('landing_show_calculator');
            $table->boolean('landing_show_rates')->default(true)->after('landing_show_map');
            $table->boolean('landing_show_contact')->default(true)->after('landing_show_rates');
        });
    }

    public function down(): void
    {
        Schema::table('exchange_offices', function (Blueprint $table) {
            $table->dropColumn([
                'hero_title',
                'hero_subtitle',
                'hero_image_path',
                'hero_image_url',
                'map_embed',
                'landing_show_calculator',
                'landing_show_map',
                'landing_show_rates',
                'landing_show_contact',
            ]);
        });
    }
};
