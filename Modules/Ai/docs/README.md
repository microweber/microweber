# `Ai` module

> **Slug:** `ai`
> **Tier:** 1
>
> Tier-1 module — owns its own data + exposes a public API.
>
> *(Auto-generated from filesystem survey on 2026-04-25;
> hand-edit to add operator-side context. The canonical
> shape lives in [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md);
> use `Modules/Settings/docs/README.md` as the
> hand-curated example.)*

## Domain

*Hand-edit this section to describe what the module does
operationally and which sibling modules it interacts
with.*

## Data model

Migrations under `Modules/Ai/database/migrations/`:

  - `database/migrations/2024_01_01_000001_create_agent_chat_tables.php`
  - `database/migrations/2025_03_04_000000_add_tags_and_status_to_agent_chats.php`
  - `database/migrations/2026_04_13_184400_create_mcp_client_tables.php`
  - `database/migrations/2026_04_25_000000_add_rate_limit_per_minute_to_mcp_client_tokens.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Ai\Models\AgentChat` | `Models/AgentChat.php` |
| `Modules\Ai\Models\AgentChatMessage` | `Models/AgentChatMessage.php` |
| `Modules\Ai\Models\AgentChatSearch` | `Models/AgentChatSearch.php` |
| `Modules\Ai\Models\McpClient` | `Models/McpClient.php` |
| `Modules\Ai\Models\McpClientToken` | `Models/McpClientToken.php` |
| `Modules\Ai\Models\McpClientTokenEvent` | `Models/McpClientTokenEvent.php` |

## API endpoints

Route files:

  - `routes/api.php`
  - `routes/web.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Ai\Http\Controllers\AiController`
  - `Modules\Ai\Http\Controllers\McpController`

## Service classes

  - `Modules\Ai\Services\AgentChatHistory`
  - `Modules\Ai\Services\AgentFactory`
  - `Modules\Ai\Services\AiService`
  - `Modules\Ai\Services\AiServiceImages`
  - `Modules\Ai\Services\AmazonScraperService`
  - `Modules\Ai\Services\Drivers\AiChatServiceInterface`
  - `Modules\Ai\Services\Drivers\AiImageServiceInterface`
  - `Modules\Ai\Services\Drivers\AiParseJsonTrait`
  - `Modules\Ai\Services\Drivers\AiServiceInterface`
  - `Modules\Ai\Services\Drivers\BaseDriver`
  - `Modules\Ai\Services\Drivers\FalAiDriver`
  - `Modules\Ai\Services\Drivers\GeminiAiDriver`
  - `Modules\Ai\Services\Drivers\OllamaAiDriver`
  - `Modules\Ai\Services\Drivers\OpenAiDriver`
  - `Modules\Ai\Services\Drivers\OpenRouterAiDriver`
  - `Modules\Ai\Services\Drivers\ReplicateAiDriver`
  - `Modules\Ai\Services\GoogleTrendsService`
  - `Modules\Ai\Services\Mcp\GeneratedMcpClientToken`
  - `Modules\Ai\Services\Mcp\McpAdminInspector`
  - `Modules\Ai\Services\Mcp\McpClientTokenManager`
  - `Modules\Ai\Services\Mcp\McpRequestContext`
  - `Modules\Ai\Services\Mcp\McpToolCatalog`
  - `Modules\Ai\Services\McpServer`
  - `Modules\Ai\Services\RagSearchService`
  - `Modules\Ai\Services\Secrets\PassCommandRunner`
  - `Modules\Ai\Services\Secrets\PassSecretStore`

## Events

  - `Modules\Ai\Events\AgentRoutingEvent`
  - `Modules\Ai\Events\ProgressEvent`
  - `Modules\Ai\Events\SpecializedAgentExecutionEvent`

## Filament admin

  - `Modules\Ai\Filament\Pages\AiSettingsPage`
  - `Modules\Ai\Filament\Resources\AgentChatResource`
  - `Modules\Ai\Filament\Resources\AgentChatResource\Pages\CreateAgentChat`
  - `Modules\Ai\Filament\Resources\AgentChatResource\Pages\EditAgentChat`
  - `Modules\Ai\Filament\Resources\AgentChatResource\Pages\ListAgentChats`
  - `Modules\Ai\Filament\Resources\AgentChatResource\Pages\ViewAgentChat`
  - `Modules\Ai\Filament\Resources\McpClientResource`
  - `Modules\Ai\Filament\Resources\McpClientResource\Pages\CreateMcpClient`
  - `Modules\Ai\Filament\Resources\McpClientResource\Pages\EditMcpClient`
  - `Modules\Ai\Filament\Resources\McpClientResource\Pages\ListMcpClients`
  - `Modules\Ai\Filament\Resources\McpClientResource\Pages\ViewMcpClient`
  - `Modules\Ai\Filament\Resources\McpClientResource\RelationManagers\McpClientTokenEventsRelationManager`
  - `Modules\Ai\Filament\Resources\McpClientResource\RelationManagers\McpClientTokensRelationManager`

## Tests

Run: `php vendor/bin/phpunit Modules/Ai/Tests`

Test files:

  - `tests/Drivers/SslVerificationTest.php`
  - `tests/Feature/McpAuditSamplingTest.php`
  - `tests/Feature/McpClientAllowlistSemanticsTest.php`
  - `tests/Feature/McpClientTokenManagerTest.php`
  - `tests/Feature/McpConsoleCommandsTest.php`
  - `tests/Feature/McpControllerTest.php`
  - `tests/Feature/McpPerToolRateLimitTest.php`
  - `tests/Feature/McpServerErrorDetectionTest.php`
  - `tests/Feature/McpSpecComplianceTest.php`
  - `tests/Feature/McpToolCallLoggingTest.php`
  - `tests/Feature/McpToolCatalogContractTest.php`
  - `tests/Feature/McpToolInputSchemaRegressionTest.php`
  - `tests/Feature/MicroweberAiCommandTest.php`
  - `tests/Filament/AiResourceTest.php`
  - `tests/Filament/Resources/AgentChatResourceAuthorizationTest.php`
  - `tests/Filament/Resources/AgentChatResourceTest.php`
  - `tests/Livewire/AgentChatComponentTest.php`
  - `tests/Tools/AmazonScraperToolTest.php`
  - `tests/Tools/ContentGenerationToolsTest.php`
  - `tests/Tools/CreateContentToolTest.php`
  - `tests/Tools/GoogleTrendsToolTest.php`
  - `tests/Tools/RagSearchToolTest.php`
  - `tests/Tools/ToolTestCase.php`
  - `tests/Unit/AgentChatOllamaTest.php`
  - `tests/Unit/AgentCrossDomainQueryTest.php`
  - `tests/Unit/AgentDomainRoutingTest.php`
  - `tests/Unit/AgentWriteOperationsTest.php`
  - `tests/Unit/AiServiceProviderSecretStoreTest.php`
  - `tests/Unit/Filament/McpClientResourceTest.php`
  - `tests/Unit/PassSecretStoreTest.php`

## Service providers

  - `Modules\Ai\Providers\AiServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
