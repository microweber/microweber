<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Multisite\MultisiteManager;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * MCP server behaviour under the multisite package.
 *
 * Multisite ({@see MultisiteManager}) isolates cache, sessions, userfiles
 * and APP_URL per site — its `applyIsolation()` repoints `config('app.url')`
 * at each request's own host. It deliberately does NOT isolate the database,
 * so the `mcp_clients` / `mcp_client_tokens` tables are install-global.
 *
 * Two consequences the MCP server must honour, pinned here:
 *
 *   1. Every advertised connection URL is derived from `config('app.url')`,
 *      never a hardcoded host — so an agent pointed at site B is handed
 *      site B's `/api/mcp` endpoint, matching the host multisite will have
 *      repointed APP_URL to.
 *   2. A single issued token authenticates on every site of the install
 *      (the auth tables are shared), so operators mint one credential per
 *      agent, not one per domain.
 */
class McpMultisiteTest extends TestCase
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
        ]);
    }

    #[Test]
    public function the_connection_url_follows_the_active_sites_app_url(): void
    {
        // applyIsolation() points APP_URL at site A's host for site A's
        // requests; the connect helper must advertise that exact endpoint.
        config(['app.url' => 'https://alpha-site.test']);
        Artisan::call('ai:mcp:connect', ['--no-token' => true]);
        $alpha = Artisan::output();

        $this->assertStringContainsString('https://alpha-site.test/api/mcp', $alpha);
        $this->assertStringNotContainsString('beta-site.test', $alpha);

        // A request on site B repoints APP_URL to B's host — the same
        // command must now advertise B's endpoint, not a baked-in host.
        config(['app.url' => 'https://beta-site.test']);
        Artisan::call('ai:mcp:connect', ['--no-token' => true]);
        $beta = Artisan::output();

        $this->assertStringContainsString('https://beta-site.test/api/mcp', $beta);
        $this->assertStringNotContainsString('alpha-site.test', $beta);
    }

    #[Test]
    public function an_operator_can_override_the_host_per_site_with_base_url(): void
    {
        // Even when APP_URL is the primary host, an operator serving the MCP
        // endpoint under a specific site's public domain can pin it.
        config(['app.url' => 'https://primary.test']);

        Artisan::call('ai:mcp:connect', [
            '--no-token' => true,
            '--base-url' => 'https://tenant-7.example',
        ]);
        $output = Artisan::output();

        $this->assertStringContainsString('https://tenant-7.example/api/mcp', $output);
        $this->assertStringNotContainsString('primary.test/api/mcp', $output);
    }

    #[Test]
    public function one_token_authenticates_across_different_site_hosts(): void
    {
        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);

        $client = $manager->createClient([
            'name' => 'zz-multisite-agent',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            'rate_limit_per_minute' => 120,
            'is_active' => true,
        ]);
        $token = $manager->issueToken(
            client: $client,
            name: 'multisite-connect',
            abilities: ['mcp:access', 'mcp:admin'],
        );

        $bearer = 'Bearer ' . $token->plainTextToken;
        $payload = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'initialize',
            'params' => ['protocolVersion' => '2025-03-26'],
        ];

        // The route carries no domain constraint, so posting to two distinct
        // hosts genuinely exercises two sites. The shared auth table resolves
        // the same bearer on both — no per-domain credential needed.
        $onAlpha = $this->withHeader('Authorization', $bearer)
            ->postJson('http://alpha-site.test/api/mcp', $payload);
        $onAlpha->assertOk();
        $onAlpha->assertJsonPath('result.protocolVersion', '2025-03-26');

        $onBeta = $this->withHeader('Authorization', $bearer)
            ->postJson('http://beta-site.test/api/mcp', $payload);
        $onBeta->assertOk();
        $onBeta->assertJsonPath('result.protocolVersion', '2025-03-26');
    }

    #[Test]
    public function multisite_isolation_does_not_fork_the_mcp_client_store_per_site(): void
    {
        // Prove the isolation primitives are per-site (so we're genuinely in
        // a multisite-capable env) …
        $manager = new MultisiteManager();
        $manager->setSite('beta-site.test');

        $this->assertSame('userfiles/beta-site.test', $manager->userfilesFolder());
        $this->assertStringContainsString('beta', $manager->cachePrefix());
        $this->assertStringContainsString('beta', $manager->sessionCookie());

        // … yet a client created once is visible while a site is active,
        // because the DB (and thus the MCP auth tables) is NOT isolated.
        app(McpClientTokenManager::class)->createClient([
            'name' => 'zz-shared-store-probe',
            'allowed_scopes' => ['mcp:access'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);

        $this->assertSame(1, McpClient::where('name', 'zz-shared-store-probe')->count());
    }
}
