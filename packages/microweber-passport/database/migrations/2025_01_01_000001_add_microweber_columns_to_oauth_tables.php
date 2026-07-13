<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds Microweber-specific auditing columns to the Passport
 * oauth_access_tokens table: last_used_at and last_used_ip.
 *
 * These are stamped by the StampTokenLastUsed middleware.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('oauth_access_tokens')) {
            return;
        }

        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            if (!Schema::hasColumn('oauth_access_tokens', 'last_used_at')) {
                $table->timestamp('last_used_at')->nullable()->after('expires_at');
            }
            if (!Schema::hasColumn('oauth_access_tokens', 'last_used_ip')) {
                $table->string('last_used_ip', 45)->nullable()->after('last_used_at');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('oauth_access_tokens')) {
            return;
        }

        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('oauth_access_tokens', 'last_used_at')) {
                $columns[] = 'last_used_at';
            }
            if (Schema::hasColumn('oauth_access_tokens', 'last_used_ip')) {
                $columns[] = 'last_used_ip';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }

    public function getConnection(): ?string
    {
        return $this->connection ?? config('passport.connection');
    }
};