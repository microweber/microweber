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
        if (Schema::hasTable('error_tracking')) {
            return;
        }

        Schema::create('error_tracking', function (Blueprint $table) {
            $table->id();
            
            // Error details
            $table->string('level')->index(); // critical, error, warning, info
            $table->text('message');
            $table->string('exception_class')->nullable()->index();
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->string('code', 50)->nullable();
            $table->longText('trace')->nullable();
            
            // Request context
            $table->text('url')->nullable();
            $table->string('method', 10)->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('user_ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('server_data')->nullable();
            $table->json('context')->nullable();
            
            // Tracking
            $table->boolean('is_resolved')->default(false)->index();
            $table->integer('occurrence_count')->default(1);
            $table->timestamp('last_occurred_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->unsignedInteger('resolved_by')->nullable();
            $table->text('resolution_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for common queries
            $table->index(['level', 'is_resolved']);
            $table->index(['file', 'line']);
            $table->index(['created_at', 'is_resolved']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_tracking');
    }
};
