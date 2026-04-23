<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Staging tables that back Phase 8 "preview before commit".
 *
 * In dry-run mode the importer writes here instead of the live
 * `content` / `media` tables. The operator then reviews what would
 * land, unchecks anything they don't want, and clicks Commit —
 * which promotes the remaining staging rows to live tables in a
 * single transaction per batch.
 *
 * Why parallel tables rather than a `is_staging` column on `content`?
 *
 *   - `content` has hard-coded assumptions (frontend queries,
 *     sitemap generation, search indexing) that it holds live
 *     publishable rows only. Adding a staging flag would require
 *     touching every downstream query to exclude it — a much
 *     bigger surface than a brand-new table.
 *   - A staging schema can be narrower: we only need the fields
 *     the operator's preview UI renders and the Commit step reads
 *     back to reconstruct a DTO-equivalent payload.
 *   - Rolling back a failed Commit batch is a trivial DELETE on
 *     staging rows rather than a partial-state cleanup on live
 *     content.
 *
 * Relationship to `wp_migration_jobs`
 * -----------------------------------
 * Every staging row belongs to exactly one job (foreign key
 * enforced by index, cascaded on delete so canceling a job
 * discards its preview data automatically).
 *
 * (job_id, source_guid) is unique — staging a DTO twice for the
 * same job upserts onto the same row, matching the live-side
 * idempotency contract on (import_source, source_guid).
 */
return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('wp_migration_staging_content')) {
            Schema::create('wp_migration_staging_content', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('job_id');

                // Idempotency keys mirror the live mapper's contract.
                $table->string('import_source', 64);
                $table->string('source_guid', 255);

                // Fields the Filament preview page displays and the
                // Commit step uses to reconstruct a write payload.
                $table->string('title', 500)->nullable();
                $table->longText('content_html')->nullable();
                $table->text('excerpt')->nullable();
                $table->string('canonical_url', 500)->nullable();
                $table->string('source_host', 255)->nullable();
                $table->string('featured_image_url', 500)->nullable();
                $table->string('author_slug', 255)->nullable();
                $table->timestamp('posted_at')->nullable();

                // Free-form lists — we store them as JSON rather
                // than a join table because staging is ephemeral
                // (truncated on Commit or Cancel) and joining for
                // a UI table that never paginates past a few hundred
                // items isn't worth the schema.
                $table->json('categories')->nullable();
                $table->json('tags')->nullable();

                // Operator toggle in the preview UI. Excluded rows
                // are skipped when the Commit batch runs; the row
                // stays in staging as a receipt of what was skipped.
                $table->boolean('excluded')->default(false);

                $table->timestamps();

                $table->unique(['job_id', 'source_guid'], 'wp_staging_content_job_guid_unique');
                $table->index(['job_id', 'excluded'], 'wp_staging_content_job_excluded_idx');
            });
        }

        if (!Schema::hasTable('wp_migration_staging_media')) {
            Schema::create('wp_migration_staging_media', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('job_id');
                // Null when the media row isn't tied to a specific
                // content item (e.g. a globally-rehosted asset that
                // multiple posts reference).
                $table->unsignedBigInteger('staging_content_id')->nullable();

                $table->string('source_url', 500);
                // 'featured' | 'inline' | other — free-form tag so
                // the preview UI can group media by role without a
                // new column later.
                $table->string('role', 32)->default('inline');
                // Dedup key: sha256 over the URL so re-staging the
                // same asset within one job hits the same row.
                $table->char('source_url_hash', 64);

                $table->boolean('excluded')->default(false);

                $table->timestamps();

                $table->unique(
                    ['job_id', 'source_url_hash'],
                    'wp_staging_media_job_url_unique'
                );
                $table->index('staging_content_id', 'wp_staging_media_content_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wp_migration_staging_media');
        Schema::dropIfExists('wp_migration_staging_content');
    }
};
