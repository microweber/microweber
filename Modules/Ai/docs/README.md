# `Ai` module

> **Slug:** `ai`
> **Tier:** 1
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

### `agent_chats` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `title` | `string` | — |
  | `description` | `text` | nullable |
  | `agent_type` | `string` | has-default |
  | `user_id` | `unsignedBigInteger` | nullable |
  | `metadata` | `json` | nullable |
  | `is_active` | `boolean` | has-default |
  | `timestamps` | `timestamps` | — |
  | `status` | `string` | has-default |
  | `tags` | `json` | nullable |
  | `(unnamed)` | `dropColumn` | — |

### `agent_chat_messages` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `chat_id` | `unsignedBigInteger` | — |
  | `role` | `enum` | — |
  | `content` | `longText` | — |
  | `metadata` | `json` | nullable |
  | `agent_type` | `string` | nullable |
  | `processed_at` | `timestamp` | nullable |
  | `timestamps` | `timestamps` | — |
  | `chat_id` | `foreign` | — |

### `agent_chat_searches` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `chat_id` | `unsignedBigInteger` | — |
  | `message_id` | `unsignedBigInteger` | nullable |
  | `query` | `string` | — |
  | `results` | `longText` | nullable |
  | `metadata` | `json` | nullable |
  | `relevance_score` | `float` | nullable |
  | `timestamps` | `timestamps` | — |
  | `(unnamed)` | `fullText` | — |

### `mcp_clients` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `uuid` | `uuid` | unique |
  | `name` | `string` | — |
  | `slug` | `string` | unique |
  | `description` | `text` | nullable |
  | `allowed_scopes` | `json` | nullable |
  | `allowed_tools` | `json` | nullable |
  | `allowed_modules` | `json` | nullable |
  | `rate_limit_per_minute` | `unsignedInteger` | nullable |
  | `is_active` | `boolean` | has-default |
  | `last_used_at` | `timestamp` | nullable |
  | `revoked_at` | `timestamp` | nullable |
  | `created_by_user_id` | `unsignedInteger` | nullable |
  | `updated_by_user_id` | `unsignedInteger` | nullable |
  | `timestamps` | `timestamps` | — |
  | `created_by_user_id` | `foreign` | — |
  | `updated_by_user_id` | `foreign` | — |
  | `last_used_at` | `index` | — |

### `mcp_client_tokens` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `uuid` | `uuid` | unique |
  | `mcp_client_id` | `foreignId` | foreign-key |
  | `rotated_from_token_id` | `foreignId` | nullable, foreign-key |
  | `name` | `string` | — |
  | `token_hash` | `string` | — |
  | `token_last_eight` | `string` | — |
  | `abilities` | `json` | nullable |
  | `last_used_at` | `timestamp` | nullable |
  | `last_used_ip` | `string` | nullable |
  | `last_used_user_agent` | `text` | nullable |
  | `expires_at` | `timestamp` | nullable |
  | `revoked_at` | `timestamp` | nullable |
  | `revocation_reason` | `text` | nullable |
  | `created_by_user_id` | `unsignedInteger` | nullable |
  | `revoked_by_user_id` | `unsignedInteger` | nullable |
  | `timestamps` | `timestamps` | — |
  | `created_by_user_id` | `foreign` | — |
  | `revoked_by_user_id` | `foreign` | — |
  | `expires_at` | `index` | — |
  | `last_used_at` | `index` | — |
  | `rate_limit_per_minute` | `unsignedInteger` | — |
  | `rate_limit_per_minute` | `dropColumn` | — |

### `mcp_client_token_events` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `mcp_client_id` | `foreignId` | foreign-key |
  | `mcp_client_token_id` | `foreignId` | nullable, foreign-key |
  | `actor_user_id` | `unsignedInteger` | nullable |
  | `action` | `string` | — |
  | `ip_address` | `string` | nullable |
  | `user_agent` | `text` | nullable |
  | `metadata` | `json` | nullable |
  | `timestamps` | `timestamps` | — |
  | `actor_user_id` | `foreign` | — |

## Models

### `Modules\Ai\Models\AgentChat`

Source: `Models/AgentChat.php`. 

**Fillable:** `title`, `description`, `agent_type`, `user_id`, `metadata`, `is_active`, `status`, `tags`

**Casts:**

  - `metadata` → `array`
  - `is_active` → `boolean`
  - `tags` → `array`

### `Modules\Ai\Models\AgentChatMessage`

Source: `Models/AgentChatMessage.php`. 

**Fillable:** `chat_id`, `role`, `content`, `metadata`, `agent_type`, `processed_at`

**Casts:**

  - `metadata` → `array`
  - `processed_at` → `datetime`

### `Modules\Ai\Models\AgentChatSearch`

Source: `Models/AgentChatSearch.php`. 

**Fillable:** `chat_id`, `message_id`, `query`, `results`, `metadata`, `relevance_score`

**Casts:**

  - `metadata` → `array`
  - `relevance_score` → `float`

### `Modules\Ai\Models\McpClient`

Source: `Models/McpClient.php`. 

**Fillable:** `name`, `slug`, `description`, `allowed_scopes`, `allowed_tools`, `allowed_modules`, `rate_limit_per_minute`, `is_active`, `last_used_at`, `revoked_at`, `created_by_user_id`, `updated_by_user_id`

**Casts:**

  - `allowed_scopes` → `array`
  - `allowed_tools` → `array`
  - `allowed_modules` → `array`
  - `rate_limit_per_minute` → `integer`
  - `is_active` → `boolean`
  - `last_used_at` → `datetime`
  - `revoked_at` → `datetime`

### `Modules\Ai\Models\McpClientToken`

Source: `Models/McpClientToken.php`. 

**Fillable:** `mcp_client_id`, `rotated_from_token_id`, `name`, `token_hash`, `token_last_eight`, `abilities`, `rate_limit_per_minute`, `last_used_at`, `last_used_ip`, `last_used_user_agent`, `expires_at`, `revoked_at`, `revocation_reason`, `created_by_user_id`, `revoked_by_user_id`

**Casts:**

  - `abilities` → `array`
  - `rate_limit_per_minute` → `integer`
  - `last_used_at` → `datetime`
  - `expires_at` → `datetime`
  - `revoked_at` → `datetime`

### `Modules\Ai\Models\McpClientTokenEvent`

Source: `Models/McpClientTokenEvent.php`. 

**Fillable:** `mcp_client_id`, `mcp_client_token_id`, `actor_user_id`, `action`, `ip_address`, `user_agent`, `metadata`

**Casts:**

  - `metadata` → `array`

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `POST` | `api/mcp` | `McpController::handle` |
  | `POST` | `api/ai/chat` | `AiController::chat` |
  | `POST` | `api/ai/generateImage` | `AiController::generateImage` |
  | `POST` | `api/ai/agent-chat` | `AiController::agentChat` |
  | `GET` | `api/ai/chat-history/{chatId}` | `AiController::getChatHistory` |
  | `GET` | `api/ai/user-chats` | `AiController::getUserChats` |

## Controllers

### `Modules\Ai\Http\Controllers\AiController`

Source: `Http/Controllers/AiController.php`.

  - `generateImage(Request $request)`
  - `chat(Request $request)`
  - `agentChat(Request $request)`
  - `getChatHistory(Request $request, int $chatId)`
  - `getUserChats(Request $request)`

### `Modules\Ai\Http\Controllers\McpController`

Source: `Http/Controllers/McpController.php`.

  - `handle(Request $request): BaseResponse`

## Service classes

### `Modules\Ai\Services\AgentChatHistory`

Source: `Services/AgentChatHistory.php`.

  - `add(Message $message): void`
  - `addMessage(Message $message): ChatHistoryInterface`
  - `getMessages(): array`
  - `getLastMessage(): Message|false`
  - `setMessages(array $messages): ChatHistoryInterface`
  - `clear(): ChatHistoryInterface`
  - `isEmpty(): bool`
  - `getContextWindow(): int`
  - `count(): int`
  - `toArray(): array`
  - `flushAll(): ChatHistoryInterface`
  - `calculateTotalUsage(): int`
  - `jsonSerialize(): array`
  - `getChat(): AgentChat`
  - `loadConversation(array $messages): void`
  - `getStats(): array`

### `Modules\Ai\Services\AgentFactory`

Source: `Services/AgentFactory.php`.

  - `register(string $name, string $agentClass): void`
  - `agent(string $name, ?string $providerName = null, ?string $model = null): BaseAgent`
  - `agentWithChat(AgentChat $agentChat, ?string $providerName = null, ?string $model = null): BaseAgent`
  - `createOrGetChat(string $agentType, string $title, ?int $userId = null, ?string $description = null, array $metadata = []): AgentChat`
  - `agentWithSession(string $agentType, string $title, ?int $userId = null, ?string $description = null, array $metadata = [], ?string $providerName = null, ?string $model = null): BaseAgent`
  - `getRegisteredAgents(): array`
  - `getAgentsByDomain(): array`
  - `getAgentForDomain(string $domain): ?string`

### `Modules\Ai\Services\AiService`

Source: `Services/AiService.php`.

  - `sendToChat(array $messages, array $options = []): string|array`
  - `getActiveDriver(): string`
  - `setActiveDriver(string $driver): void`

### `Modules\Ai\Services\AiServiceImages`

Source: `Services/AiServiceImages.php`.

  - `generateImage(array $messages, array $options = []): string|array`
  - `getActiveDriver(): string`
  - `setActiveDriver(string $driver): void`

### `Modules\Ai\Services\AmazonScraperService`

Source: `Services/AmazonScraperService.php`.

### `Modules\Ai\Services\Drivers\AiChatServiceInterface`

Source: `Services/Drivers/AiChatServiceInterface.php`.

  - `sendToChat(array $messages, array $options = []): string|array`
  - `getActiveDriver(): string`

### `Modules\Ai\Services\Drivers\AiImageServiceInterface`

Source: `Services/Drivers/AiImageServiceInterface.php`.

  - `generateImage(array $messages, array $options = []): string|array`
  - `getDriverName(): string`

### `Modules\Ai\Services\Drivers\AiParseJsonTrait`

Source: `Services/Drivers/AiParseJsonTrait.php`.

  - `parseJson($content)`

### `Modules\Ai\Services\Drivers\AiServiceInterface`

Source: `Services/Drivers/AiServiceInterface.php`.

  - `sendToChat(array $messages, array $options = []): string|array`
  - `getActiveDriver(): string`
  - `setActiveDriver(string $driver): void`

### `Modules\Ai\Services\Drivers\BaseDriver`

Source: `Services/Drivers/BaseDriver.php`.

  - `getActiveDriver(): string`
  - `setActiveDriver(string $driver): void`
  - `getDriverName(): string`

### `Modules\Ai\Services\Drivers\FalAiDriver`

Source: `Services/Drivers/FalAiDriver.php`.

  - `getDriverName(): string`
  - `generateImage(array $messages, array $options = []): array`
  - `sendToChat(array $messages, array $options = []): string|array`

### `Modules\Ai\Services\Drivers\GeminiAiDriver`

Source: `Services/Drivers/GeminiAiDriver.php`.

  - `getDriverName(): string`
  - `sendToChat(array $messages, array $options = []): string|array`
  - `processImageWithPrompt(string $prompt, string $imageBase64, array $options = []): array`

### `Modules\Ai\Services\Drivers\OllamaAiDriver`

Source: `Services/Drivers/OllamaAiDriver.php`.

  - `getDriverName(): string`
  - `sendToChat(array $messages, array $options = []): string|array`

### `Modules\Ai\Services\Drivers\OpenAiDriver`

Source: `Services/Drivers/OpenAiDriver.php`.

  - `sendToChat(array $messages, array $options = []): string|array`
  - `getDriverName(): string`

### `Modules\Ai\Services\Drivers\OpenRouterAiDriver`

Source: `Services/Drivers/OpenRouterAiDriver.php`.

  - `getDriverName(): string`
  - `sendToChat(array $messages, array $options = []): string|array`

### `Modules\Ai\Services\Drivers\ReplicateAiDriver`

Source: `Services/Drivers/ReplicateAiDriver.php`.

  - `getDriverName(): string`
  - `generateImage(array $messages, array $options = []): array`
  - `sendToChat(array $messages, array $options = []): string|array`

### `Modules\Ai\Services\GoogleTrendsService`

Source: `Services/GoogleTrendsService.php`.

### `Modules\Ai\Services\Mcp\GeneratedMcpClientToken`

Source: `Services/Mcp/GeneratedMcpClientToken.php`.

### `Modules\Ai\Services\Mcp\McpAdminInspector`

Source: `Services/Mcp/McpAdminInspector.php`.

  - `scopeOptions(): array`
  - `moduleOptions(): array`
  - `toolOptions(): array`
  - `providerSecretStatuses(): array`
  - `connectionHealth(McpClient $client): array`
  - `providerSecretSummary(string $optionKey): string`
  - `providerSecretColor(string $optionKey): string`

### `Modules\Ai\Services\Mcp\McpClientTokenManager`

Source: `Services/Mcp/McpClientTokenManager.php`.

  - `createClient(array $attributes, ?User $actor = null): McpClient`
  - `issueToken(McpClient $client, string $name, array $abilities = [], ?CarbonInterface $expiresAt = null, ?User $actor = null, ?int $rotatedFromTokenId = null, ?int $rateLimitPerMinute = null,): GeneratedMcpClientToken`
  - `rotateToken(McpClientToken $token, ?string $name = null, ?array $abilities = null, ?CarbonInterface $expiresAt = null, ?User $actor = null,): GeneratedMcpClientToken`
  - `revokeToken(McpClientToken $token, ?User $actor = null, ?string $reason = null): void`
  - `findToken(string $plainTextToken): ?McpClientToken`
  - `recordUsage(McpClientToken $token, ?string $ipAddress = null, ?string $userAgent = null): void`
  - `recordAuditEvent(McpClient $client, ?McpClientToken $token, string $action, ?array $metadata = null, ?string $ipAddress = null, ?string $userAgent = null,): McpClientTokenEvent`

### `Modules\Ai\Services\Mcp\McpRequestContext`

Source: `Services/Mcp/McpRequestContext.php`.

### `Modules\Ai\Services\Mcp\McpToolCatalog`

Source: `Services/Mcp/McpToolCatalog.php`.

  - `allDefinitions(): array`
  - `listTools(McpRequestContext $context): array`
  - `hasTool(string $name): bool`
  - `callTool(string $name, array $arguments): string`

### `Modules\Ai\Services\McpServer`

Source: `Services/McpServer.php`.

  - `handle(array $payload, ?McpRequestContext $context = null): ?array`

### `Modules\Ai\Services\RagSearchService`

Source: `Services/RagSearchService.php`.

  - `search(string $query, array $options = []): array`
  - `saveSearchResult(int $chatId, ?int $messageId, string $query, array $results, array $metadata = []): AgentChatSearch`

### `Modules\Ai\Services\Secrets\PassCommandRunner`

Source: `Services/Secrets/PassCommandRunner.php`.

  - `run(array $arguments, ?string $input = null): string`
  - `succeeds(array $arguments): bool`

### `Modules\Ai\Services\Secrets\PassSecretStore`

Source: `Services/Secrets/PassSecretStore.php`.

  - `isEnabled(): bool`
  - `aiProviderSecretMap(): array`
  - `isAiProviderSecret(string $optionKey): bool`
  - `isReference(?string $value): bool`
  - `storeAiProviderSecret(string $optionKey, string $secret): string`
  - `resolveAiProviderSecret(string $optionKey, ?string $storedValue, ?callable $persistReference = null): ?string`
  - `store(string $namespace, string $name, string $secret): string`
  - `get(string $reference): ?string`
  - `delete(string $reference): void`
  - `exists(string $reference): bool`
  - `path(string $namespace, string $name): string`
  - `reference(string $path): string`
  - `pathFromReference(string $reference): string`

## Events

  - `Modules\Ai\Events\AgentRoutingEvent`
  - `Modules\Ai\Events\ProgressEvent`
  - `Modules\Ai\Events\SpecializedAgentExecutionEvent`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Ai\Filament\Pages\AiSettingsPage` | System Settings | — |
  | `Modules\Ai\Filament\Resources\AgentChatResource` | System Settings | — |
  | `Modules\Ai\Filament\Resources\AgentChatResource\Pages\CreateAgentChat` | — | — |
  | `Modules\Ai\Filament\Resources\AgentChatResource\Pages\EditAgentChat` | — | — |
  | `Modules\Ai\Filament\Resources\AgentChatResource\Pages\ListAgentChats` | — | — |
  | `Modules\Ai\Filament\Resources\AgentChatResource\Pages\ViewAgentChat` | — | — |
  | `Modules\Ai\Filament\Resources\McpClientResource` | System Settings | — |
  | `Modules\Ai\Filament\Resources\McpClientResource\Pages\CreateMcpClient` | — | — |
  | `Modules\Ai\Filament\Resources\McpClientResource\Pages\EditMcpClient` | — | — |
  | `Modules\Ai\Filament\Resources\McpClientResource\Pages\ListMcpClients` | — | — |
  | `Modules\Ai\Filament\Resources\McpClientResource\Pages\ViewMcpClient` | — | — |
  | `Modules\Ai\Filament\Resources\McpClientResource\RelationManagers\McpClientTokenEventsRelationManager` | — | — |
  | `Modules\Ai\Filament\Resources\McpClientResource\RelationManagers\McpClientTokensRelationManager` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Ai/Tests`

### `tests/Feature/McpClientAllowlistSemanticsTest.php`

  - `empty_array_allowlists_resolve_to_deny_all`

### `tests/Feature/McpConsoleCommandsTest.php`

  - `tools_list_command_renders_the_catalog_in_tabular_form`
  - `token_revoke_command_marks_the_row_revoked`

### `tests/Feature/McpServerErrorDetectionTest.php`

  - `output_with_alert_danger_opening_tag_falls_back_to_error_flag`
  - `clean_tool_output_is_not_flagged`
  - `base_tool_handle_error_emits_the_marker`

### `tests/Feature/MicroweberAiCommandTest.php`

  - `command_rejects_empty_prompt`

### `tests/Filament/AiResourceTest.php`

  - `it_can_render_mcp_clients_list_page`
  - `it_agent_chat_resource_has_model`

### `tests/Filament/Resources/AgentChatResourceAuthorizationTest.php`

  - `it_admin_can_access_resource_list`
  - `it_admin_can_edit_any_chat`
  - `it_canaccesspanel_behavior`

### `tests/Filament/Resources/AgentChatResourceTest.php`

  - `create_chat_saves_initial_prompt`
  - `retry_tool_call_action_exists`

### `tests/Livewire/AgentChatComponentTest.php`

  - `it_attachment_removal`
  - `it_chat_with_file_upload_stores_media`

### `tests/Tools/ContentGenerationToolsTest.php`

  - `tools_output_valid_html`

### `tests/Unit/AgentChatOllamaTest.php`

  - `it_general_agent_has_tools_registered`

### `tests/Unit/AgentCrossDomainQueryTest.php`

  - `it_has_tools_for_content_plus_analytics_cross_domain`
  - `it_has_tools_for_newsletter_plus_analytics_cross_domain`
  - `it_system_prompt_encourages_multi_tool_usage`
  - `it_handles_cross_domain_query`

### `tests/Unit/AgentDomainRoutingTest.php`

  - `it_has_all_content_tools`
  - `it_has_all_media_tools`
  - `it_covers_all_15_domains`
  - `it_has_no_duplicate_tool_names`
  - `it_routes_domain_query_to_correct_tools`

### `tests/Unit/AgentWriteOperationsTest.php`

  - `it_has_content_creation_tool`
  - `it_has_content_edit_tool`
  - `it_has_product_edit_tool`
  - `it_has_seo_metadata_tool`
  - `it_system_prompt_mentions_seo_tools`
  - `it_all_write_tools_are_present`

### `tests/Unit/Filament/McpClientResourceTest.php`

  - `it_creates_an_mcp_client_from_the_filament_form`

## Service providers

  - `Modules\Ai\Providers\AiServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
