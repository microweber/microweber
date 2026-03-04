<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('agent_chats')) {
            return;
        }


        Schema::create('agent_chats', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('agent_type')->default('general'); // general, customer, shop, content, media
            $table->unsignedBigInteger('user_id')->nullable();
            $table->json('metadata')->nullable(); // For storing chat settings, context, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'agent_type']);
            $table->index(['is_active', 'created_at']);
        });

        Schema::create('agent_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id');
            $table->enum('role', ['user', 'assistant', 'system']);
            $table->longText('content');
            $table->json('metadata')->nullable(); // For storing tool calls, processing time, etc.
            $table->string('agent_type')->nullable(); // Which agent handled this message
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->foreign('chat_id')->references('id')->on('agent_chats')->onDelete('cascade');
            $table->index(['chat_id', 'created_at']);
            $table->index(['role', 'created_at']);
        });

        Schema::create('agent_chat_searches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id');
            $table->unsignedBigInteger('message_id')->nullable();
            $table->string('query');
            $table->longText('results')->nullable();
            $table->json('metadata')->nullable(); // Search params, source info, etc.
            $table->float('relevance_score')->nullable();
            $table->timestamps();


            $table->index(['chat_id', 'query']);

            // Add fulltext index only for MySQL/PostgreSQL, not SQLite
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText(['query', 'results']);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_chat_searches');
        Schema::dropIfExists('agent_chat_messages');
        Schema::dropIfExists('agent_chats');
    }
};
