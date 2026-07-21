<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Safety migration: guarantees the `metadata` column exists on the media table.
 *
 * add_metadata_to_media_table (2026_03_21) adds it, but some databases recorded
 * that migration as run without the column ever being applied (inconsistent
 * migration history). This runs with a fresh timestamp so it applies to those
 * databases too; it's fully guarded and idempotent. Mirrors the cdn-sync
 * package's ensure_media_cdn_fields safety migration for the CDN columns.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('media')) {
            return;
        }

        if (!Schema::hasColumn('media', 'metadata')) {
            Schema::table('media', function (Blueprint $table) {
                $table->json('metadata')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Intentionally empty: we don't drop a column the Media module's own
        // migration may have created.
    }
};
