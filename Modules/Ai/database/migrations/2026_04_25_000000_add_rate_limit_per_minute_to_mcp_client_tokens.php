<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('mcp_client_tokens')) {
            return;
        }

        if (Schema::hasColumn('mcp_client_tokens', 'rate_limit_per_minute')) {
            return;
        }

        Schema::table('mcp_client_tokens', function (Blueprint $table) {
            // Per-token override for the client-level
            // rate_limit_per_minute. NULL means inherit from the
            // client (default behaviour, backward-compatible).
            // Set to 0 to disable rate-limiting for this token only.
            $table->unsignedInteger('rate_limit_per_minute')
                ->nullable()
                ->after('abilities');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('mcp_client_tokens')) {
            return;
        }

        if (! Schema::hasColumn('mcp_client_tokens', 'rate_limit_per_minute')) {
            return;
        }

        Schema::table('mcp_client_tokens', function (Blueprint $table) {
            $table->dropColumn('rate_limit_per_minute');
        });
    }
};
