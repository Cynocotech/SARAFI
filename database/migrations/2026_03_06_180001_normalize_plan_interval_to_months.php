<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('plans')->where('interval', 'monthly')->update(['interval' => '1']);
    }

    public function down(): void
    {
        DB::table('plans')->where('interval', '1')->update(['interval' => 'monthly']);
    }
};
