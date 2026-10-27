<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('settings', 'site_mode')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->string('site_mode', 5)->nullable(); // 'dark' (default) or 'light'
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('settings', 'site_mode')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('site_mode');
        });
    }
};