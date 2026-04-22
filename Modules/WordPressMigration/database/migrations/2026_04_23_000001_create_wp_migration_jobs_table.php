<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One row per WordPress source URL the operator has asked us to
 * migrate. The row is the single resume point for the UI: the
 * Filament form reads `probe_result` to render the detected
 * capabilities, and the background importer reads `progress` to
 * pick up where it left off after a page refresh or a worker
 * restart.
 *
 * Idempotency: re-probing the same URL must upsert onto the same
 * row rather than insert a duplicate. Since URLs can exceed the
 * 191-char utf8mb4 key limit on older MySQL, uniqueness is
 * enforced via `source_url_hash` (sha256) rather than directly
 * on `source_url`.
 *
 * Status transitions:
 *   probing → ready      (probe finished, capabilities detected)
 *   probing → unreachable (no endpoint responded)
 *   ready   → running    (operator clicked "Start import")
 *   running → finished   (all items processed)
 *   running → failed     (worker gave up after retries)
 *   *       → canceled   (operator abort)
 *
 * Credential hygiene (see docs/adr/wordpress-migration.md §1):
 *   `encrypted_credentials` stores an app password at rest via the
 *   Laravel `encrypted` cast. `credentials_expire_at` is 24h after
 *   set; a scheduled command NULLs both columns past that point.
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('wp_migration_jobs')) {
            return;
        }

        Schema::create('wp_migration_jobs', function (Blueprint $table) {
            $table->id();

            $table->string('source_url', 500);
            $table->char('source_url_hash', 64);
            $table->string('source_host', 255);

            $table->string('status', 32)->default('probing');
            $table->string('mode', 32)->nullable();

            $table->json('probe_result')->nullable();
            $table->timestamp('last_probed_at')->nullable();

            $table->json('options')->nullable();
            $table->json('progress')->nullable();

            $table->text('encrypted_credentials')->nullable();
            $table->timestamp('credentials_expire_at')->nullable();

            $table->text('last_error')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

            $table->unique('source_url_hash');
            $table->index('source_host');
            $table->index('status');
            $table->index('credentials_expire_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wp_migration_jobs');
    }
};
