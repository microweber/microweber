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
        Schema::create('workflow_execution_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('execution_id')->constrained('workflow_executions')->onDelete('cascade');
            $table->foreignId('node_id')->constrained('workflow_nodes')->onDelete('cascade');
            $table->string('status'); // 'pending', 'running', 'completed', 'failed', 'skipped'
            $table->integer('step_number');
            $table->json('input_data')->nullable();
            $table->json('output_data')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index(['execution_id', 'status']);
            $table->index(['execution_id', 'step_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_execution_steps');
    }
};
