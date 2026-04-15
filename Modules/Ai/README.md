# Ai

AI-powered content generation, image creation, and agent-based assistance. Supports multiple AI providers with a pluggable driver architecture and includes an MCP (Model Context Protocol) server for external AI client integration.

## Key Features

- Multi-provider text generation (OpenAI, Anthropic, Gemini, OpenRouter, Ollama)
- Multi-provider image generation (Replicate, Fal)
- Web search via Tavily and data retrieval via Supadata
- Specialized AI agents for content, shop, customer, and media tasks
- MCP server with token-based client authentication
- Secure API key storage via `pass` secret store

## Configuration

Settings are managed via `get_option($key, 'ai')`. Key options:

| Option | Description |
|---|---|
| `enabled` | Enable/disable AI globally |
| `default_driver` | Text driver: `openai`, `anthropic`, `gemini`, `openrouter`, `ollama` |
| `default_driver_images` | Image driver: `replicate`, `fal` |
| `{driver}_enabled` | Toggle individual drivers |
| `{driver}_api_key` | API key (resolved through secret store when available) |
| `{driver}_model` | Model selection per driver |

Environment variables are also supported (`AI_ENABLED`, `OPENAI_API_KEY`, etc.). See `config/config.php`.

## Key Classes

| Class | Purpose |
|---|---|
| `Services\AiService` | Text generation singleton (`app('ai')`) |
| `Services\AiServiceImages` | Image generation singleton (`app('ai.images')`) |
| `Services\AgentFactory` | Registry for specialized agents (`app('ai.agents')`) |
| `Agents\ContentAgent` | Content-focused AI agent |
| `Agents\ShopAgent` | E-commerce AI agent |
| `Services\Mcp\McpToolCatalog` | MCP tool discovery |
| `Services\Secrets\PassSecretStore` | Secure API key resolution |

## Events

- `AgentRoutingEvent` -- dispatched when selecting a specialized agent
- `ProgressEvent` -- streaming progress during generation
- `SpecializedAgentExecutionEvent` -- fired on agent execution

## Database Tables

- `agent_chats` / `agent_chat_messages` -- conversation history
- `mcp_clients` / `mcp_client_tokens` / `mcp_client_token_events` -- MCP client management

## Admin Panel (Filament)

- **AiSettingsPage** -- configure drivers, API keys, models
- **AgentChatResource** -- browse AI chat sessions
- **McpClientResource** -- manage MCP clients and tokens

## Usage

```php
$response = app('ai')->generate('Write a product description for...');
$imageUrl = app('ai.images')->generate('A modern kitchen interior');
$agent = app('ai.agents')->make('content');
```
