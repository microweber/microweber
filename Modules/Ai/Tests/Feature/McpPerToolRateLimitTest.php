<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Plan D.1 — pin the per-tool rate-limit gate.
 *
 * Token-level rate-limit caps the per-minute budget across all
 * tools; per-tool rate-limit caps a specific expensive tool
 * (analytics summaries, newsletter campaign queries) so a single
 * client can't burn the entire budget on one slow query.
 *
 * The middleware checks the per-tool gate FIRST, then the
 * token-level gate. Both gates increment on a successful pass.
 */
class McpPerToolRateLimitTest extends TestCase
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

        if (Schema::hasTable('mcp_client_tokens') && ! Schema::hasColumn('mcp_client_tokens', 'rate_limit_per_minute')) {
            Artisan::call('migrate', [
                '--path' => base_path('Modules/Ai/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        DB::table('mcp_client_token_events')->delete();
        DB::table('mcp_client_tokens')->delete();
        DB::table('mcp_clients')->delete();

        config([
            'modules.ai.enabled' => true,
            'modules.ai.mcp.enabled' => true,
            'modules.ai.mcp.client_token_prefix' => 'mcp_',
            'modules.ai.mcp.auth.required_abilities' => ['mcp:access'],
            'modules.ai.mcp.auth.admin_scope' => 'mcp:admin',
            'modules.ai.mcp.auth.admin_only_tools' => [],
            'modules.ai.mcp.auth.admin_only_modules' => [],
        ]);

        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);
        $this->client = $manager->createClient([
            'name' => 'Per-tool rate Test Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            // Token-level cap is high so the per-tool gate is the
            // binding constraint.
            'rate_limit_per_minute' => 600,
            'is_active' => true,
        ]);

        $this->generated = $manager->issueToken(
            client: $this->client,
            name: 'per-tool-test',
            abilities: ['mcp:access', 'mcp:admin'],
        );

        // Reset both rate-limit buckets so prior tests don't bleed in.
        RateLimiter::clear('mcp-client-token:' . $this->generated->token->id);
        RateLimiter::clear('mcp-client-token:' . $this->generated->token->id . ':tool:settings.read');
    }

    #[Test]
    public function per_tool_cap_rejects_after_threshold_then_token_level_unaffected(): void
    {
        // Pin a per-tool cap of 2 invocations / 60s on settings.read.
        config(['modules.ai.mcp.per_tool_rate_limits' => [
            'settings.read' => 2,
        ]]);

        $authHeader = ['Authorization' => 'Bearer ' . $this->generated->plainTextToken];
        $payload = [
            'jsonrpc' => '2.0',
            'id' => 'rl',
            'method' => 'tools/call',
            'params' => ['name' => 'settings.read', 'arguments' => ['option_group' => 'website']],
        ];

        // Two successful calls.
        $this->withHeaders($authHeader)->postJson(route('api.ai.mcp'), $payload)->assertOk();
        $this->withHeaders($authHeader)->postJson(route('api.ai.mcp'), $payload)->assertOk();

        // Third call must trip the per-tool gate.
        $rejected = $this->withHeaders($authHeader)->postJson(route('api.ai.mcp'), $payload);
        $rejected->assertStatus(429);
        $rejected->assertJsonPath('error', 'Too many requests');
        $rejected->assertJsonPath('message', 'MCP per-tool rate limit exceeded for settings.read.');

        // Token-level gate is unaffected — a different tool call
        // can still go through. content.lookup isn't in
        // per_tool_rate_limits so its only constraint is the
        // 600/min token-level cap.
        $other = $this->withHeaders($authHeader)->postJson(route('api.ai.mcp'), [
            'jsonrpc' => '2.0',
            'id' => 'rl-other',
            'method' => 'tools/call',
            'params' => ['name' => 'content.lookup', 'arguments' => ['search_term' => 'home', 'limit' => 1]],
        ]);
        $other->assertOk();
    }

    #[Test]
    public function tool_not_in_config_is_unaffected_by_per_tool_gate(): void
    {
        // settings.read has a 2/min cap; content.lookup has none.
        config(['modules.ai.mcp.per_tool_rate_limits' => [
            'settings.read' => 2,
        ]]);

        $authHeader = ['Authorization' => 'Bearer ' . $this->generated->plainTextToken];

        // Five content.lookup calls — should all pass because the
        // tool is not in the per-tool config map (only the
        // 600/min token-level cap applies, and we're nowhere near it).
        for ($i = 0; $i < 5; $i++) {
            $response = $this->withHeaders($authHeader)->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'unl-' . $i,
                'method' => 'tools/call',
                'params' => ['name' => 'content.lookup', 'arguments' => ['search_term' => 'home', 'limit' => 1]],
            ]);
            $response->assertOk();
        }
    }

    #[Test]
    public function per_tool_audit_event_records_scope(): void
    {
        config(['modules.ai.mcp.per_tool_rate_limits' => [
            'settings.read' => 1,
        ]]);

        $authHeader = ['Authorization' => 'Bearer ' . $this->generated->plainTextToken];
        $payload = [
            'jsonrpc' => '2.0',
            'id' => 'rl-audit',
            'method' => 'tools/call',
            'params' => ['name' => 'settings.read', 'arguments' => ['option_group' => 'website']],
        ];

        $this->withHeaders($authHeader)->postJson(route('api.ai.mcp'), $payload)->assertOk();
        $this->withHeaders($authHeader)->postJson(route('api.ai.mcp'), $payload)->assertStatus(429);

        // Audit event records `scope=per_tool` so operators can
        // distinguish per-tool vs token-level denials in the
        // Filament events viewer.
        $auditRow = DB::table('mcp_client_token_events')
            ->where('action', 'token.rate_limited')
            ->orderByDesc('id')
            ->first();

        $this->assertNotNull($auditRow);
        $metadata = json_decode($auditRow->metadata, true);
        $this->assertSame(
            'per_tool',
            $metadata['scope'] ?? null,
            'Per-tool rate-limit denials must record scope=per_tool in the audit '
            . 'metadata so operators can distinguish them from token-level denials. '
            . 'A regression that drops the scope field would silently merge the two '
            . 'failure modes in the Filament events viewer.'
        );
        $this->assertSame('settings.read', $metadata['tool'] ?? null);
    }
}
