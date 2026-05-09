<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * AI-131 / SEC-06 (cycle-124 2026-05-09): Activity Log table.
 *
 * Brief: "Add /admin/activity-log (logins, settings changes, role
 * grants, mass-deletes)."
 *
 * Schema:
 *   id          — surrogate primary key.
 *   user_id     — nullable; set for authenticated actions, null for
 *                 anonymous (e.g. failed login attempt with unknown
 *                 username).
 *   actor_email — denormalized email at the time of the action so
 *                 the audit trail survives a user deletion.
 *   action      — string slug (e.g. `auth.login`, `settings.update`,
 *                 `role.grant`, `content.bulk_delete`).
 *   subject_type — polymorphic — what was acted on (e.g.
 *                 `MicroweberPackages\User\Models\User`).
 *   subject_id   — id of the subject (nullable for actions on
 *                 collections, e.g. bulk delete).
 *   ip_address   — origin IP at time of action.
 *   user_agent   — origin UA (truncated to 500 chars).
 *   metadata     — JSON-typed payload for action-specific details.
 *   created_at   — when the action happened. Indexed for the
 *                 default reverse-chronological listing.
 *
 * Indexed:
 *   (action, created_at)   for the per-action filter view.
 *   (user_id, created_at)  for the per-user audit drill-down.
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('activity_log')) {
            return;
        }
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('actor_email', 191)->nullable();
            $table->string('action', 100);
            $table->string('subject_type', 191)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['action', 'created_at'], 'activity_log_action_created_index');
            $table->index(['user_id', 'created_at'], 'activity_log_user_created_index');
            $table->index(['subject_type', 'subject_id'], 'activity_log_subject_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
