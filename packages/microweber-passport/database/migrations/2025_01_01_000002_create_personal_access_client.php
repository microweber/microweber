<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;

/**
 * Provisions a Passport personal-access OAuth client.
 *
 * Passport 12+ removed the auto-created personal access client, and
 * createToken() throws RuntimeException("Personal access client not found")
 * without one. This migration creates it exactly once (idempotent) — replacing
 * a former per-request `booted()` DB check in the service provider.
 *
 * Runs after 2016_06_01_000004_create_oauth_clients_table (loaded from the
 * vendor Passport migrations) so the table exists.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('oauth_clients')) {
            return;
        }

        $provider = config('auth.guards.api.provider', 'users');

        $exists = DB::table('oauth_clients')
            ->whereJsonContains('grant_types', 'personal_access')
            ->where('provider', $provider)
            ->where('revoked', false)
            ->exists();

        if ($exists) {
            return;
        }

        // Use the Passport client model so id generation (UUID vs auto-inc) and
        // the array→JSON casts on redirect_uris/grant_types are handled correctly.
        Passport::client()->forceFill([
            'name'          => 'Microweber Personal Access Client',
            'secret'        => null,
            'redirect_uris' => ['http://localhost'],
            'grant_types'   => ['personal_access'],
            'provider'      => $provider,
            'revoked'       => false,
        ])->save();
    }

    public function down(): void
    {
        if (!Schema::hasTable('oauth_clients')) {
            return;
        }

        DB::table('oauth_clients')
            ->where('name', 'Microweber Personal Access Client')
            ->whereJsonContains('grant_types', 'personal_access')
            ->delete();
    }

    public function getConnection(): ?string
    {
        return $this->connection ?? config('passport.connection');
    }
};
