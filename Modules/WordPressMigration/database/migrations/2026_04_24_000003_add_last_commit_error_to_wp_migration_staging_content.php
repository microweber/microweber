<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 9 addition — persist the last-commit error on each staging
 * row so the "Retry failed items" action can re-target only the
 * rows that actually failed in the previous Commit pass.
 *
 * Why persist on the staging row rather than keep it in a side
 * table? The {@see CommitReport} is scoped to a single call; it
 * disappears the moment the Filament request ends. For the
 * operator to come back later (or let a background worker pick up
 * the retry), the "this row failed" state has to survive the
 * request. The staging row is already the authoritative "about
 * to be committed" record — marking it with a `last_commit_error`
 * keeps state in one place instead of drifting across a new log
 * table.
 */
return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('wp_migration_staging_content')) {
            return;
        }

        Schema::table('wp_migration_staging_content', function (Blueprint $table) {
            if (! Schema::hasColumn('wp_migration_staging_content', 'last_commit_error')) {
                $table->text('last_commit_error')->nullable()->after('excluded');
            }
            if (! Schema::hasColumn('wp_migration_staging_content', 'last_committed_at')) {
                $table->timestamp('last_committed_at')->nullable()->after('last_commit_error');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('wp_migration_staging_content')) {
            return;
        }

        Schema::table('wp_migration_staging_content', function (Blueprint $table) {
            if (Schema::hasColumn('wp_migration_staging_content', 'last_committed_at')) {
                $table->dropColumn('last_committed_at');
            }
            if (Schema::hasColumn('wp_migration_staging_content', 'last_commit_error')) {
                $table->dropColumn('last_commit_error');
            }
        });
    }
};
