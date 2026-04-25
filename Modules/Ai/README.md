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

## MCP server

The module ships a JSON-RPC MCP server at `POST /api/mcp`. Enable it via
`AI_ENABLED=true` + `AI_MCP_ENABLED=true` (both default to `false`).
Authentication uses bearer tokens issued through `McpClientResource` or the
`McpClientTokenManager` service.

> **Full operator manual: [`docs/mcp/README.md`](../../docs/mcp/README.md)**
> covers enabling the server, issuing clients + tokens (CLI + Filament UI),
> the wire-protocol (handshake / tools/list / tools/call + utility methods
> like `ping` and `notifications/*` and JSON-RPC 2.0 batching), the seven
> `php artisan ai:mcp:*` commands, the Read-only-by-design rationale and
> on-ramp for future write tools, security posture (CSRF / CORS / what
> never lands in logs), the audit-log retention story, and connection
> snippets for Claude Desktop / Cursor / Cline.

The module ships a 39-tool catalog spanning content, products, orders,
settings, media, layouts, analytics, forms, billing, shipping, tax, and
newsletter modules. The pinned inventory lives in
`Modules/Ai/tests/Feature/McpToolCatalogContractTest::EXPECTED_TOOLS`;
list it from the CLI with `php artisan ai:mcp:tools:list`.

## Agent CLI (`microweber:ai`)

Drive the same agent the Filament chat UI uses, from the shell:

```bash
php artisan microweber:ai "add a blog post about cats"
php artisan microweber:ai "summarise our top 5 products" --agent=shop --json
php artisan microweber:ai "draft a homepage" --user=editor@example.com --session=42
```

Foundations are the `MicroweberAiCommand` shipped under
`Modules/Ai/Console/Commands/MicroweberAiCommand.php`. Use cases:
operator automation, CI seed scripts, contributor prototyping. The
command resolves an admin user (or the operator-supplied `--user` /
`--user-id`), wraps the prompt in a `UserMessage`, dispatches to the
named agent (default: `general`), and prints the agent's reply.
Detects `BaseTool::ERROR_OUTPUT_MARKER` in the reply and exits 1
when a tool error fires so CI can fail fast. The full plan
(write-action sub-commands, REPL mode, dry-run, audit retention)
lives in `TODO.md` under "AI Agent CLI — `microweber:ai` artisan
command".

### Allow-list semantics

Each MCP client carries three independent allow-lists — `allowed_tools`,
`allowed_modules`, `allowed_scopes`. They share one contract:

| Value                | Meaning                                              |
|----------------------|------------------------------------------------------|
| `null`               | **Unrestricted** — allow any candidate.              |
| `[]` (empty array)   | **Explicit deny-all** — reject every candidate.      |
| `['*', ...]`         | Wildcard — allow any candidate.                      |
| `['foo', 'bar']`     | Allow only the listed values.                        |

The `null` ↔ `[]` distinction lets an operator persist the difference
between "I haven't narrowed this client" (null) and "I narrowed it to
nothing" (empty array). Authoritative reference: the inline contract on
`McpClient::allowsValue()`.

### Endpoint summary

| Method   | Description                                       |
|----------|---------------------------------------------------|
| `initialize`  | Handshake. Returns the negotiated protocol version + server info + capabilities. |
| `tools/list`  | Enumerate the tools allowed for the calling client / token.                       |
| `tools/call`  | Execute a tool by name with the schema-declared arguments.                        |

Each request is gated by `mcp:access` scope by default; tools / modules
listed in `AI_MCP_ADMIN_ONLY_TOOLS` / `AI_MCP_ADMIN_ONLY_MODULES` also
require the `mcp:admin` scope on the calling token.

## Usage

```php
$response = app('ai')->generate('Write a product description for...');
$imageUrl = app('ai.images')->generate('A modern kitchen interior');
$agent = app('ai.agents')->make('content');
```
