<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('settings', 'header_shade')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->string('header_shade', 7)->nullable(); // #RRGGBB or null = default
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('settings', 'header_shade')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('header_shade');
            });
        }
    }
};