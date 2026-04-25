<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Plan F.1 + F.2 — pin the artisan commands' golden-path behaviour.
 *
 *   - `ai:mcp:client:create` writes one mcp_clients + mcp_client_tokens
 *     row with the documented allow-list semantics, then prints the
 *     plain-text bearer token on stdout.
 *   - `ai:mcp:tools:list` enumerates the catalog (non-empty, headers
 *     correct, --module filter works).
 *
 * `ai:mcp:health` lives in a sibling smoke test that hits the live
 * dev server — it is not exercisable in PHPUnit because Http::post
 * to the local APP_URL would deadlock the test runner. The command
 * itself is wired identically to the other two so any registration
 * regression here surfaces it as well.
 */
class McpConsoleCommandsTest extends TestCase
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
            'modules.ai.mcp.client_token_prefix' => 'mcp_',
        ]);
    }

    #[Test]
    public function client_create_command_persists_a_client_with_a_usable_token(): void
    {
        $exitCode = Artisan::call('ai:mcp:client:create', [
            '--name' => 'CLI Test Client',
            '--scopes' => 'mcp:access,mcp:admin',
            '--tools' => '*',
            '--modules' => '*',
            '--rate-limit' => '120',
            '--token-name' => 'cli-test',
        ]);

        $this->assertSame(0, $exitCode, 'ai:mcp:client:create must exit 0 on success.');

        $output = Artisan::output();
        $this->assertStringContainsString('Created MCP client', $output);
        $this->assertStringContainsString('Token:', $output);

        // Extract the printed plain-text token and prove it
        // resolves through the manager.
        $this->assertMatchesRegularExpression(
            '/Token:\s+(mcp_\d+\|[A-Za-z0-9]+)/',
            $output,
            'Command output must contain the prefixed plain-text token in the documented format.'
        );
        preg_match('/Token:\s+(mcp_\d+\|[A-Za-z0-9]+)/', $output, $m);
        $plainTextToken = $m[1];

        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);
        $resolved = $manager->findToken($plainTextToken);

        $this->assertInstanceOf(
            McpClientToken::class,
            $resolved,
            'McpClientTokenManager::findToken must resolve the freshly-printed token — '
            . 'otherwise the command UX is misleading because the operator captures '
            . 'a string that the live middleware will reject.'
        );

        $client = McpClient::where('name', 'CLI Test Client')->first();
        $this->assertNotNull($client);
        $this->assertSame(['*'], $client->allowed_tools);
        $this->assertSame(['*'], $client->allowed_modules);
        $this->assertSame(['mcp:access', 'mcp:admin'], $client->allowed_scopes);
        $this->assertSame(120, (int) $client->rate_limit_per_minute);
    }

    #[Test]
    public function client_create_command_persists_null_allow_lists_when_options_are_omitted(): void
    {
        $exitCode = Artisan::call('ai:mcp:client:create', [
            '--name' => 'Unrestricted Client',
            '--scopes' => 'mcp:access',
        ]);

        $this->assertSame(0, $exitCode);

        $client = McpClient::where('name', 'Unrestricted Client')->first();
        $this->assertNotNull($client);
        $this->assertNull(
            $client->allowed_tools,
            'Omitting --tools must persist null (= unrestricted per the contract on '
            . 'McpClient::allowsValue) — otherwise CLI-created clients would silently '
            . 'collapse to deny-all and surprise the operator the same way the '
            . 'pre-fix Filament path did.'
        );
        $this->assertNull($client->allowed_modules);
    }

    #[Test]
    public function client_create_command_fails_without_a_name(): void
    {
        $exitCode = Artisan::call('ai:mcp:client:create');

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('--name is required', Artisan::output());
    }

    #[Test]
    public function tools_list_command_renders_the_catalog_in_tabular_form(): void
    {
        $exitCode = Artisan::call('ai:mcp:tools:list');

        $this->assertSame(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('content.lookup', $output);
        $this->assertStringContainsString('billing.subscription_lookup', $output);
        $this->assertStringContainsString('tools registered', $output);
    }

    #[Test]
    public function tools_list_command_filters_by_module(): void
    {
        $exitCode = Artisan::call('ai:mcp:tools:list', ['--module' => 'analytics']);

        $this->assertSame(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('analytics.traffic_summary', $output);
        $this->assertStringContainsString('in module analytics', $output);

        // Negative — content tools must NOT leak into the analytics
        // filter result. A regression here would silently break the
        // operator's "give me only this module's tools" expectation.
        $this->assertStringNotContainsString('content.lookup', $output);
    }
}
