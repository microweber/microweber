<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Pins `ai:mcp:connect` — the "so an external agent can connect" helper.
 *
 * It must (a) emit the right config blob for each client (OpenCode remote
 * + local, Claude/Cursor stdio + mcp-remote) filled in with this install's
 * endpoint URL, (b) mint a token that actually authenticates, and (c)
 * honour --no-token as a side-effect-free docs mode. A regression here
 * leaves the server reachable but undiscoverable — operators can't tell an
 * agent how to reach it.
 */
class McpConnectCommandTest extends TestCase
{
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

        config([
            'modules.ai.enabled' => true,
            'modules.ai.mcp.enabled' => true,
            'modules.ai.mcp.endpoint' => '/api/mcp',
            'modules.ai.mcp.protocol_version' => '2025-03-26',
            'modules.ai.mcp.client_token_prefix' => 'mcp_',
            'app.url' => 'https://example-cms.test',
        ]);
    }

    #[Test]
    public function it_refuses_when_mcp_is_disabled(): void
    {
        config(['modules.ai.mcp.enabled' => false]);

        $exit = Artisan::call('ai:mcp:connect', ['--no-token' => true]);

        $this->assertSame(1, $exit);
        $this->assertStringContainsString('MCP is disabled', Artisan::output());
    }

    #[Test]
    public function no_token_mode_emits_every_client_config_and_mints_nothing(): void
    {
        $exit = Artisan::call('ai:mcp:connect', [
            '--no-token' => true,
            '--server-name' => 'microweber',
        ]);

        $this->assertSame(0, $exit);
        $output = Artisan::output();

        // Endpoint URL derived from app.url + endpoint.
        $this->assertStringContainsString('https://example-cms.test/api/mcp', $output);
        // Placeholder, not a real token.
        $this->assertStringContainsString('<YOUR_TOKEN>', $output);
        // OpenCode remote (Streamable HTTP) + local (stdio) blocks.
        $this->assertStringContainsString('"type": "remote"', $output);
        $this->assertStringContainsString('"type": "local"', $output);
        // stdio invocation + mcp-remote bridge for stdio-only clients.
        $this->assertStringContainsString('ai:mcp:serve', $output);
        $this->assertStringContainsString('mcp-remote', $output);

        // --no-token must not touch the database.
        $this->assertSame(0, McpClient::count());
        $this->assertSame(0, McpClientToken::count());
    }

    #[Test]
    public function default_mode_mints_a_working_token_on_a_fresh_client(): void
    {
        $exit = Artisan::call('ai:mcp:connect', ['--server-name' => 'microweber']);

        $this->assertSame(0, $exit);
        $output = Artisan::output();

        $this->assertSame(1, McpClient::count(), 'A fresh client should be created when --client is omitted.');
        $this->assertSame(1, McpClientToken::count(), 'Exactly one token should be minted.');

        // The printed token must be the real, resolvable bearer.
        $this->assertMatchesRegularExpression('/mcp_\d+\|[A-Za-z0-9]+/', $output);
        preg_match('/mcp_\d+\|[A-Za-z0-9]+/', $output, $m);
        $resolved = app(McpClientTokenManager::class)->findToken($m[0]);
        $this->assertNotNull($resolved, 'The token printed by ai:mcp:connect must authenticate.');

        // It warns the token is shown once.
        $this->assertStringContainsString('shown ONCE', $output);
    }

    #[Test]
    public function it_issues_on_an_existing_client_without_creating_a_new_one(): void
    {
        $existing = app(McpClientTokenManager::class)->createClient([
            'name' => 'Existing Agent Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);

        $exit = Artisan::call('ai:mcp:connect', ['--client' => (string) $existing->id]);

        $this->assertSame(0, $exit);
        $this->assertSame(1, McpClient::count(), 'No new client should be created when --client targets an existing one.');
        $this->assertSame(1, McpClientToken::where('mcp_client_id', $existing->id)->count());
    }

    #[Test]
    public function it_errors_on_an_unknown_client(): void
    {
        $exit = Artisan::call('ai:mcp:connect', ['--client' => 'does-not-exist']);

        $this->assertSame(1, $exit);
        $this->assertStringContainsString('No MCP client found', Artisan::output());
        $this->assertSame(0, McpClientToken::count(), 'A failed lookup must not mint a token.');
    }
}
