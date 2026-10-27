<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('settings', 'header_design')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->string('header_design', 12)->nullable(); // classic | glass | aurora | spotlight
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('settings', 'header_design')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('header_design');
            });
        }
    }
};