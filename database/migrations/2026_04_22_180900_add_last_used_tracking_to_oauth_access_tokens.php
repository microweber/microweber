<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('oauth_access_tokens')) {
            return;
        }

        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            if (! Schema::hasColumn('oauth_access_tokens', 'last_used_at')) {
                $table->timestamp('last_used_at')->nullable()->after('expires_at');
            }

            // IPv6 addresses are up to 45 chars; nullable since we don't
            // pretend to audit IPs for tokens that have never been used.
            if (! Schema::hasColumn('oauth_access_tokens', 'last_used_ip')) {
                $table->string('last_used_ip', 45)->nullable()->after('last_used_at');
            }
        });

        // Separate Schema::table call so the column exists before the index
        // references it (some drivers error otherwise).
        if (! $this->hasIndex('oauth_access_tokens', 'oauth_access_tokens_last_used_at_index')) {
            Schema::table('oauth_access_tokens', function (Blueprint $table) {
                $table->index('last_used_at', 'oauth_access_tokens_last_used_at_index');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('oauth_access_tokens')) {
            return;
        }

        if ($this->hasIndex('oauth_access_tokens', 'oauth_access_tokens_last_used_at_index')) {
            Schema::table('oauth_access_tokens', function (Blueprint $table) {
                $table->dropIndex('oauth_access_tokens_last_used_at_index');
            });
        }

        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            if (Schema::hasColumn('oauth_access_tokens', 'last_used_ip')) {
                $table->dropColumn('last_used_ip');
            }
            if (Schema::hasColumn('oauth_access_tokens', 'last_used_at')) {
                $table->dropColumn('last_used_at');
            }
        });
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        foreach (Schema::getIndexes($table) as $index) {
            if (($index['name'] ?? null) === $indexName) {
                return true;
            }
        }

        return false;
    }
};
