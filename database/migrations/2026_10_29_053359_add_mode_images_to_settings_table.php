<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('logo_dark_path')->nullable()->after('logo_path');
            $table->string('logo_light_path')->nullable()->after('logo_dark_path');
            $table->string('bg_dark_demo', 80)->nullable()->after('bg_demo');
            $table->string('bg_light_demo', 80)->nullable()->after('bg_dark_demo');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['logo_dark_path', 'logo_light_path', 'bg_dark_demo', 'bg_light_demo']);
        });
    }
};