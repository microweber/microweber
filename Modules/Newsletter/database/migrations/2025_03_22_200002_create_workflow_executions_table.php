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
        if (Schema::hasTable('workflow_executions')) {
            return;
        }
        Schema::create('workflow_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->onDelete('cascade');
            $table->string('execution_key')->unique(); // UUID for this execution
            $table->string('status'); // 'pending', 'running', 'completed', 'failed', 'cancelled'
            $table->string('trigger_source'); // 'event', 'schedule', 'manual'
            $table->json('trigger_data'); // Data that triggered the workflow
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('current_step')->default(0);
            $table->integer('total_steps')->default(0);
            $table->json('execution_log')->nullable(); // Step-by-step execution log
            $table->text('error_message')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['workflow_id', 'status']);
            $table->index('execution_key');
            $table->index('status');
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_executions');
    }
};
