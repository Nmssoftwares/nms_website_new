<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            if (! Schema::hasColumn('analytics_events', 'section')) {
                $table->string('section', 40)->nullable()->after('label');
            }
            if (! Schema::hasColumn('analytics_events', 'kind')) {
                $table->string('kind', 10)->nullable()->after('section');
            }
            if (! Schema::hasColumn('analytics_events', 'browser')) {
                $table->string('browser', 20)->nullable()->after('device');
            }
            if (! Schema::hasColumn('analytics_events', 'os')) {
                $table->string('os', 20)->nullable()->after('browser');
            }
        });

        Schema::table('analytics_events', function (Blueprint $table) {
            if (! Schema::hasIndex('analytics_events', 'ae_type_created_idx')) {
                $table->index(['type', 'created_at'], 'ae_type_created_idx');
            }
            if (! Schema::hasIndex('analytics_events', 'ae_visitor_created_idx')) {
                $table->index(['visitor_id', 'created_at'], 'ae_visitor_created_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            if (Schema::hasIndex('analytics_events', 'ae_type_created_idx')) {
                $table->dropIndex('ae_type_created_idx');
            }
            if (Schema::hasIndex('analytics_events', 'ae_visitor_created_idx')) {
                $table->dropIndex('ae_visitor_created_idx');
            }
        });

        Schema::table('analytics_events', function (Blueprint $table) {
            $table->dropColumn(['section', 'kind', 'browser', 'os']);
        });
    }
};