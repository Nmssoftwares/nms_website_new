<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('slug', 100)->unique();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('team_category_id')->nullable()->after('role')
                ->constrained('team_categories')->nullOnDelete();
            $table->string('x_url')->nullable()->after('linkedin_url');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropConstrainedForeignId('team_category_id');
            $table->dropColumn('x_url');
        });

        Schema::dropIfExists('team_categories');
    }
};