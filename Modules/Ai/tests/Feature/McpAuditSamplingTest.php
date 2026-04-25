<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Plan D.2 — pin the `token.used` audit-event sampler.
 *
 * For a busy AI integration, recording one `token.used` row per
 * request floods `mcp_client_token_events` with ~10k rows/day.
 * The sampler lets operators trade audit-fidelity for table size:
 *
 *   - sample_used=1.0  → log every request (full fidelity, default).
 *   - sample_used=0.1  → log ~10% (1-in-10).
 *   - sample_used=0.0  → never log token.used.
 *
 * Only the high-volume `token.used` event respects the sampler.
 * Lifecycle events (`token.issued`, `token.revoked`,
 * `token.denied`, etc.) are always recorded — they're rare and
 * security-relevant.
 */
class McpAuditSamplingTest extends TestCase
{
    private McpClient $client;
    private GeneratedMcpClientToken $generated;

    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('mcp_clients') || ! Schema::hasTable('mcp_client_tokens') || ! Schema::hasTable('mcp_client_token_events')) {
            DB::table('migrations')
                ->where('migration', '2026_04_13_184400_create_mcp_client_tables')
                ->delete();

            Artisan::call('migrate', [
                '--path' => base_path('Modules/Ai/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        DB::table('mcp_client_token_events')->delete();
        DB::table('mcp_client_tokens')->delete();
        DB::table('mcp_clients')->delete();

        config(['modules.ai.mcp.client_token_prefix' => 'mcp_']);

        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);
        $this->client = $manager->createClient([
            'name' => 'Sampling Test Client',
            'allowed_scopes' => ['mcp:access'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            'rate_limit_per_minute' => 600,
            'is_active' => true,
        ]);

        $this->generated = $manager->issueToken(
            client: $this->client,
            name: 'sampling-token',
            abilities: ['mcp:access'],
        );

        // Truncate the events table after issuance — the
        // token.issued / client.created rows aren't what we're
        // testing here.
        DB::table('mcp_client_token_events')->delete();
    }

    private function recordUsageOnce(): void
    {
        app(McpClientTokenManager::class)->recordUsage(
            $this->generated->token->fresh(),
            '127.0.0.1',
            'test-agent'
        );
    }

    #[Test]
    public function sample_used_one_records_every_invocation(): void
    {
        config(['modules.ai.mcp.audit.sample_used' => 1.0]);

        for ($i = 0; $i < 5; $i++) {
            $this->recordUsageOnce();
        }

        $this->assertSame(
            5,
            (int) DB::table('mcp_client_token_events')->where('action', 'token.used')->count(),
            'sample_used=1.0 must record one row per invocation — historic behaviour. '
            . 'A regression that drops rows here would leave operators with an '
            . 'incomplete audit trail by default.'
        );
    }

    #[Test]
    public function sample_used_zero_skips_every_invocation_but_keeps_last_used(): void
    {
        config(['modules.ai.mcp.audit.sample_used' => 0.0]);

        $beforeUsedAt = $this->generated->token->fresh()->last_used_at;
        $this->assertNull(
            $beforeUsedAt,
            'Pre-recordUsage: last_used_at should still be null — issueToken does '
            . 'not set it.'
        );

        for ($i = 0; $i < 3; $i++) {
            $this->recordUsageOnce();
        }

        $this->assertSame(
            0,
            (int) DB::table('mcp_client_token_events')->where('action', 'token.used')->count(),
            'sample_used=0.0 must skip every token.used row — operators who opt out '
            . 'of the volume completely should never see one.'
        );

        // Last-used timestamp updates regardless — operators rely
        // on it to spot inactive tokens whether or not audit-row
        // recording is enabled.
        $this->assertNotNull(
            $this->generated->token->fresh()->last_used_at,
            'last_used_at must always update — sampling controls audit-table volume, '
            . 'not last-used tracking. A regression here would silently break the '
            . '`ai:mcp:client:list` last_used column for operators on sample_used=0.'
        );
    }

    #[Test]
    public function lifecycle_events_are_never_sampled_even_when_token_used_is_disabled(): void
    {
        config(['modules.ai.mcp.audit.sample_used' => 0.0]);

        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);

        // Issuing under sample_used=0 must still record a
        // token.issued event — the sampler only gates token.used.
        $newToken = $manager->issueToken(
            client: $this->client,
            name: 'lifecycle-pin',
            abilities: ['mcp:access'],
        );

        $issuedRows = DB::table('mcp_client_token_events')
            ->where('action', 'token.issued')
            ->where('mcp_client_token_id', $newToken->token->id)
            ->count();
        $this->assertSame(
            1,
            (int) $issuedRows,
            'token.issued must always record regardless of sample_used — lifecycle '
            . 'events are rare and security-relevant. A regression that piped them '
            . 'through the sampler would silently lose the audit trail for token '
            . 'creation, the most important event in the security model.'
        );

        // Revoking too.
        $manager->revokeToken($newToken->token, null, 'pin test');
        $revokedRows = DB::table('mcp_client_token_events')
            ->where('action', 'token.revoked')
            ->where('mcp_client_token_id', $newToken->token->id)
            ->count();
        $this->assertSame(1, (int) $revokedRows);
    }
}
