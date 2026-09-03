<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A general-purpose per-chat/per-session event + state store for the AI.
 *
 * Rows are keyed by chat_id and/or session_id and a `type` (e.g. 'loop_state',
 * 'event', 'note'), with a free-form `data` payload. The autonomous loop stores
 * its cross-compaction handoff here (type='loop_state'); it can also log events.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_chat_events')) {
            return;
        }

        Schema::create('ai_chat_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id')->nullable()->index();
            $table->string('session_id')->nullable()->index();
            $table->string('type')->default('event')->index();
            $table->string('key')->nullable()->index();
            $table->longText('data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chat_events');
    }
};
