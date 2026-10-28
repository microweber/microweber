<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Unit\Filament;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Modules\Ai\Filament\Resources\McpClientResource;
use Modules\Ai\Filament\Resources\McpClientResource\Pages\CreateMcpClient;
use Modules\Ai\Filament\Resources\McpClientResource\Pages\EditMcpClient;
use Modules\Ai\Filament\Resources\McpClientResource\Pages\ListMcpClients;
use Modules\Ai\Filament\Resources\McpClientResource\Pages\ViewMcpClient;
use Modules\Ai\Filament\Resources\McpClientResource\RelationManagers\McpClientTokensRelationManager;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class McpClientResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();

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
            'modules.ai.mcp.auth.required_abilities' => ['mcp:access'],
            'modules.ai.mcp.auth.admin_scope' => 'mcp:admin',
        ]);
    }

    #[Test]
    public function it_lists_mcp_clients(): void
    {
        // The admin listing intentionally hides Faker fixture rows (AI-1105):
        // clients with both created_by_user_id and last_used_at null are treated
        // as test fixtures and excluded by the table's modifyQueryUsing scope.
        // Create real-looking clients (non-null created_by_user_id) so they are
        // visible in the listing, matching how admins actually create clients.
        $clients = McpClient::factory()->count(2)->create([
            'created_by_user_id' => 1,
        ]);

        Livewire::test(ListMcpClients::class)
            ->assertSuccessful()
            ->loadTable()
            ->assertCanSeeTableRecords($clients)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('allowed_scopes');
    }

    #[Test]
    public function it_creates_an_mcp_client_from_the_filament_form(): void
    {
        Livewire::test(CreateMcpClient::class)
            ->fillForm([
                'name' => 'Docs Client',
                'description' => 'Internal documentation MCP client.',
                'is_active' => true,
                'rate_limit_per_minute' => 42,
                'allowed_scopes' => ['mcp:access'],
                'allowed_modules' => ['content', 'settings'],
                'allowed_tools' => ['content.lookup', 'settings.read'],
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('mcp_clients', [
            'name' => 'Docs Client',
            'rate_limit_per_minute' => 42,
            'is_active' => true,
        ]);

        $client = McpClient::query()->where('name', 'Docs Client')->firstOrFail();
        $this->assertSame(['mcp:access'], $client->allowed_scopes);
        $this->assertSame(['content', 'settings'], $client->allowed_modules);
        $this->assertSame(['content.lookup', 'settings.read'], $client->allowed_tools);
    }

    #[Test]
    public function it_can_issue_a_client_key_from_the_view_page(): void
    {
        $client = McpClient::factory()->create([
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
        ]);

        Livewire::test(ViewMcpClient::class, ['record' => $client->getKey()])
            ->callAction('issueToken', data: [
                'name' => 'Primary key',
                'abilities' => ['mcp:access'],
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('mcp_client_tokens', [
            'mcp_client_id' => $client->id,
            'name' => 'Primary key',
        ]);
        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $client->id,
            'action' => 'token.issued',
        ]);
    }

    #[Test]
    public function it_can_revoke_a_client_key_from_the_relation_manager(): void
    {
        $client = McpClient::factory()->create([
            'allowed_scopes' => ['mcp:access'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
        ]);

        $generatedToken = app(McpClientTokenManager::class)->issueToken($client, 'Revokable', ['mcp:access'], null, auth()->user());

        Livewire::test(McpClientTokensRelationManager::class, [
            'ownerRecord' => $client,
            'pageClass' => EditMcpClient::class,
        ])
            ->assertSuccessful()
            ->callTableAction('revokeToken', $generatedToken->token)
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('mcp_client_tokens', [
            'id' => $generatedToken->token->id,
        ]);
        $this->assertNotNull($generatedToken->token->fresh()->revoked_at);
    }

    #[Test]
    public function it_exposes_the_token_relation_manager(): void
    {
        $relations = McpClientResource::getRelations();

        $this->assertContains(McpClientTokensRelationManager::class, $relations);
    }

    #[Test]
    public function the_connection_config_action_mounts_without_minting_a_token(): void
    {
        config(['app.url' => 'https://cms.example']);

        $client = McpClient::factory()->create([
            'slug' => 'docs-agent',
            'allowed_scopes' => ['mcp:access'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
        ]);

        Livewire::test(ViewMcpClient::class, ['record' => $client->getKey()])
            ->assertActionExists('connectionConfig');

        // The "Connect" action is a read-only config viewer — it carries no
        // form/submit and must never issue a key as a side effect.
        $action = McpClientResource::connectionConfigAction();
        $this->assertSame('connectionConfig', $action->getName());
        $this->assertFalse($action->getModalSubmitAction() ?: false);
        $this->assertSame(0, DB::table('mcp_client_tokens')->where('mcp_client_id', $client->id)->count());
    }

    #[Test]
    public function the_connection_config_view_data_renders_per_site_snippets_for_the_client(): void
    {
        config([
            'app.url' => 'https://cms.example',
            'modules.ai.mcp.endpoint' => '/api/mcp',
        ]);

        $client = McpClient::factory()->create(['slug' => 'docs-agent']);

        $data = McpClientResource::connectionConfigViewData($client);

        $this->assertSame('https://cms.example/api/mcp', $data['endpoint']);
        $this->assertFalse($data['hasToken']);
        $this->assertSame('docs-agent', $data['serverName']);

        // The server key inside the emitted OpenCode block is the client slug,
        // so an operator managing several clients gets distinct config keys.
        $bodies = implode("\n", array_column($data['blocks'], 'body'));
        $this->assertStringContainsString('"docs-agent"', $bodies);
        $this->assertStringContainsString('<YOUR_TOKEN>', $bodies);

        // The modal view must compile and server-render with this data.
        $html = view('modules.ai::filament.mcp-connection-config', $data)->render();
        $this->assertStringContainsString('https://cms.example/api/mcp', $html);
        $this->assertStringContainsString('OpenCode — remote (Streamable HTTP)', $html);
    }
}
