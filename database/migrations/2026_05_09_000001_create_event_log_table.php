<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * AI-106 / TICKET-AZ (cycle-113 2026-05-09): Event-bus replay buffer.
 *
 * Microweber's frontend event bus (`mw.app.dispatch(...)` /
 * `mw.app.on(...)`) has no replay — handlers attached AFTER an
 * event fires miss it. The `mw-app-event-bus-no-replay` skill
 * documents the gotcha. This migration adds a server-side log
 * table so backend events CAN be replayed via a new
 * `event:replay {name} [--since=]` artisan command (separate
 * cycle ships the command implementation).
 *
 * Schema:
 *   id           — surrogate primary key.
 *   name         — event name (e.g. `order.created`,
 *                   `newsletter.subscribed`). Indexed for replay
 *                   lookup.
 *   payload      — serialized event payload. JSON-typed (MySQL
 *                   8 supports JSON natively; older runtimes
 *                   fall back to TEXT).
 *   fired_at     — when the event was originally fired. Indexed
 *                   for `--since=` window queries.
 *   replayed_at  — null on initial log; set when a replay
 *                   re-emits the event. Allows replay-of-replay
 *                   detection.
 *
 * Phase 2 (separate cycle): wire the 12 module events listed in
 * the brief to write into this table on fire. Phase 3 ships the
 * `event:replay` command. Phase 1 (this commit) creates the
 * storage so Phase 2 can write to it.
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('event_log')) {
            return;
        }
        Schema::create('event_log', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->json('payload')->nullable();
            $table->timestamp('fired_at')->useCurrent();
            $table->timestamp('replayed_at')->nullable();
            $table->timestamps();

            // (name, fired_at) for `event:replay <name> --since=<ts>`.
            $table->index(['name', 'fired_at'], 'event_log_name_fired_at_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_log');
    }
};
