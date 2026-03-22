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
        Schema::create('workflow_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->onDelete('cascade');
            $table->string('node_id')->unique(); // UUID for node reference
            $table->string('node_type'); // 'trigger', 'condition', 'action', 'delay', 'split', 'join'
            $table->string('node_key'); // 'send_email', 'wait', 'if_else', etc.
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('config'); // Node-specific configuration
            $table->integer('position_x')->default(0);
            $table->integer('position_y')->default(0);
            $table->json('connections')->nullable(); // Output connections to other nodes
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['workflow_id', 'node_type']);
            $table->index('node_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_nodes');
    }
};
