<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('backup_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->default('contentBackup'); // contentBackup, fullBackup, customBackup
            $table->text('tables')->nullable(); // JSON array of tables for custom backup
            $table->boolean('include_media')->default(true);
            $table->string('frequency')->default('daily'); // hourly, daily, weekly, monthly
            $table->string('time')->nullable(); // Time in HH:MM format
            $table->integer('day_of_week')->nullable(); // 0-6 for weekly
            $table->integer('day_of_month')->nullable(); // 1-31 for monthly
            $table->integer('retention_days')->default(7);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index('next_run_at');
            $table->index(['is_active', 'next_run_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_schedules');
    }
};
