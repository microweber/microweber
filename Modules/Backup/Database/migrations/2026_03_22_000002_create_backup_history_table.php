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
        Schema::create('backup_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backup_schedule_id')->nullable()->constrained('backup_schedules')->onDelete('set null');
            $table->string('type'); // manual, scheduled
            $table->string('backup_type'); // contentBackup, fullBackup, customBackup
            $table->string('filename');
            $table->string('filepath');
            $table->bigInteger('size')->nullable();
            $table->string('status')->default('pending'); // pending, running, completed, failed
            $table->text('tables')->nullable(); // JSON array
            $table->boolean('include_media')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['backup_schedule_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_history');
    }
};
