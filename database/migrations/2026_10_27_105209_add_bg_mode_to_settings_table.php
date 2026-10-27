<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('bg_mode', 10)->nullable()->after('bg_image_path'); // demo | upload | plain
            $table->string('bg_demo', 80)->nullable()->after('bg_mode');       // gallery file name
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['bg_mode', 'bg_demo']);
        });
    }
};