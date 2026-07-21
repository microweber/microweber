<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Safety migration: ensures the is_synced_to_cdn and related CDN fields
 * exist in the media table, preventing fatal errors when the Media module's
 * own migration hasn't run yet.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('media')) {
            return;
        }

        Schema::table('media', function (Blueprint $table) {
            if (!Schema::hasColumn('media', 'cdn_url')) {
                $table->string('cdn_url')->nullable();
            }
            if (!Schema::hasColumn('media', 'cdn_provider')) {
                $table->string('cdn_provider')->nullable();
            }
            if (!Schema::hasColumn('media', 'cdn_metadata')) {
                $table->json('cdn_metadata')->nullable();
            }
            if (!Schema::hasColumn('media', 'is_synced_to_cdn')) {
                $table->boolean('is_synced_to_cdn')->default(false);
            }
            if (!Schema::hasColumn('media', 'file_size')) {
                $table->bigInteger('file_size')->nullable();
            }
            if (!Schema::hasColumn('media', 'file_hash')) {
                $table->string('file_hash')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Intentionally empty: we don't drop columns that may have been
        // created by the Media module's own migration.
    }
};