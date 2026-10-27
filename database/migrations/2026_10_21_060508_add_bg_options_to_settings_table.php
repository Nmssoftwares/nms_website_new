<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('bg_fit')->default('cover')->after('bg_image_path');
            $table->string('bg_position')->default('center')->after('bg_fit');
            $table->unsignedTinyInteger('bg_blur')->default(3)->after('bg_position');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['bg_fit', 'bg_position', 'bg_blur']);
        });
    }
};