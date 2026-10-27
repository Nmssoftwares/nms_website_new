<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('analytics_events')) {
            return;
        }

        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id', 40)->index();
            $table->string('type', 10)->index();          // view | scroll | click
            $table->string('path', 255);
            $table->string('label', 150)->nullable();     // what was clicked
            $table->unsignedTinyInteger('value')->nullable(); // scroll % reached
            $table->string('device', 10)->default('desktop');
            $table->string('referrer', 190)->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};