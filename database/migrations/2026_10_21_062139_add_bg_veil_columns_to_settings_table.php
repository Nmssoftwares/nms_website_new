<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'bg_veil'))       $table->unsignedTinyInteger('bg_veil')->default(55);
            if (! Schema::hasColumn('settings', 'bg_brightness')) $table->unsignedSmallInteger('bg_brightness')->default(105);
            if (! Schema::hasColumn('settings', 'bg_saturation')) $table->unsignedSmallInteger('bg_saturation')->default(110);
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            foreach (['bg_veil', 'bg_brightness', 'bg_saturation'] as $col) {
                if (Schema::hasColumn('settings', $col)) $table->dropColumn($col);
            }
        });
    }
};