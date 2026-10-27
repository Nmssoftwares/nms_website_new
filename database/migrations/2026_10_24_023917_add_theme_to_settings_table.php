<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('theme_a', 7)->nullable();       // main theme colour, e.g. #2563EB
            $table->string('theme_b', 7)->nullable();       // second colour of the gradient
            $table->string('font_body', 60)->nullable();    // text font
            $table->string('font_heading', 60)->nullable(); // headings font
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['theme_a', 'theme_b', 'font_body', 'font_heading']);
        });
    }
};