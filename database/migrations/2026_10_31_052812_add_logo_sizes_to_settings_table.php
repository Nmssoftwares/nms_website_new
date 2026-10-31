<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->unsignedSmallInteger('logo_header_h')->nullable()->after('logo_light_path');
            $table->unsignedSmallInteger('logo_footer_h')->nullable()->after('logo_header_h');
            $table->unsignedSmallInteger('logo_admin_h')->nullable()->after('logo_footer_h');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['logo_header_h', 'logo_footer_h', 'logo_admin_h']);
        });
    }
};