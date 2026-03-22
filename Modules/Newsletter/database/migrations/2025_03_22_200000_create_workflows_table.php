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
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('trigger_type'); // 'event', 'schedule', 'manual'
            $table->string('trigger_event')->nullable(); // cart_abandoned, order_placed, etc.
            $table->json('trigger_conditions')->nullable(); // Conditions for triggering
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_triggered_at')->nullable();
            $table->integer('execution_count')->default(0);
            $table->integer('success_count')->default(0);
            $table->integer('failure_count')->default(0);
            $table->timestamps();
            
            $table->index('trigger_type');
            $table->index('trigger_event');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflows');
    }
};
