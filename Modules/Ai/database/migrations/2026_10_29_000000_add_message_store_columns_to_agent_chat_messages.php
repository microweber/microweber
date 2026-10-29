<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * NeuronAI v4: chat history is a MessageStore with one row per message that
 * carries a message_id (unique within its thread) and a nullable archived_at.
 * Trimming archives rather than deletes, and every active-context load filters
 * on archived_at IS NULL. Add those columns to the existing per-message table.
 */
return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('agent_chat_messages')) {
            return;
        }

        Schema::table('agent_chat_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('agent_chat_messages', 'message_id')) {
                $table->string('message_id')->nullable()->after('chat_id');
                $table->index(['chat_id', 'message_id']);
            }
            if (!Schema::hasColumn('agent_chat_messages', 'archived_at')) {
                $table->timestamp('archived_at')->nullable()->after('processed_at');
                $table->index(['chat_id', 'archived_at']);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('agent_chat_messages')) {
            return;
        }

        Schema::table('agent_chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('agent_chat_messages', 'message_id')) {
                $table->dropIndex(['chat_id', 'message_id']);
                $table->dropColumn('message_id');
            }
            if (Schema::hasColumn('agent_chat_messages', 'archived_at')) {
                $table->dropIndex(['chat_id', 'archived_at']);
                $table->dropColumn('archived_at');
            }
        });
    }
};
