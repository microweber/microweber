# Microweber MCP Server

This document describes the **Model Context Protocol (MCP)** server
that ships with the AI module. It exposes 39 read-only tools across
12 modules (content, products, orders, settings, media, layouts,
analytics, forms, billing, shipping, tax, newsletter) so external AI
clients — Claude Desktop, Cursor, Cline, Continue, the Anthropic
SDK, custom integrations — can interact with a Microweber install
through a standardised protocol.

> Authoritative server source:
> [`Modules/Ai/Services/McpServer.php`](../../Modules/Ai/Services/McpServer.php)
> — every method below maps directly to a handler in that file.

## Enabling the server

```env
# .env
AI_ENABLED=true
AI_MCP_ENABLED=true
```

After flipping these flags, clear the config cache:

```bash
php artisan config:clear
```

Other relevant env vars:

| Var                                     | Default                                            | Purpose                                                       |
|-----------------------------------------|----------------------------------------------------|---------------------------------------------------------------|
| `AI_MCP_ENDPOINT`                       | `/api/mcp`                                         | Path the JSON-RPC endpoint listens on                         |
| `AI_MCP_TRANSPORT`                      | `http-jsonrpc`                                     | Wire format (only http-jsonrpc today; streamable HTTP planned)|
| `AI_MCP_PROTOCOL_VERSION`               | `2025-03-26`                                       | Server's preferred MCP protocol version                       |
| `AI_MCP_SUPPORTED_PROTOCOL_VERSIONS`    | `2024-11-05,2025-03-26,2025-06-18`                 | Comma-separated versions the server can speak                 |
| `AI_MCP_REQUIRED_ABILITIES`             | `mcp:access`                                       | Token abilities required for any call                         |
| `AI_MCP_ADMIN_SCOPE`                    | `mcp:admin`                                        | Scope required for admin-only tools / modules                 |
| `AI_MCP_ADMIN_ONLY_TOOLS`               | (empty)                                            | Comma-separated tools that require `mcp:admin`                |
| `AI_MCP_ADMIN_ONLY_MODULES`             | (empty)                                            | Comma-separated modules that require `mcp:admin`              |

When MCP is disabled, the endpoint returns HTTP 503 with a JSON-RPC
`-32000 MCP server is disabled.` envelope.

## Issuing a client + token

There are two paths — pick whichever fits the workflow:

### CLI (recommended for scripting)

```bash
php artisan ai:mcp:client:create \
    --name="Cursor IDE" \
    --scopes=mcp:access \
    --tools='*' --modules='*' \
    --rate-limit=120 \
    --token-name=cursor-laptop \
    --token-ttl-days=30
```

The plain-text bearer token is printed once on stdout — capture it
on the spot; the database stores only the hash.

Other CLI commands:

| Command                                              | Purpose                                                              |
|------------------------------------------------------|----------------------------------------------------------------------|
| `php artisan ai:mcp:tools:list [--module=foo]`       | List the tool catalog (name / module / title). Same source the wire's `tools/list` reads from. |
| `php artisan ai:mcp:health [--base-url=URL]`         | Issue an ephemeral 5-min-TTL token, run `initialize` → `ping` → `tools/list` against the configured base URL, report a green / red verdict. Revokes the ephemeral token in `finally`. |
| `php artisan ai:mcp:token:rotate <token-id>`         | Revoke a token in place and issue a fresh secret under the same client. The old row is revoked, not deleted, so the middleware can audit-log denial reasons on any leaked-token reuse. |

### Filament admin UI

Navigate to **AI → MCP Clients**. The `McpClientResource` exposes
the same fields as the CLI: name, allow-lists (scopes / tools /
modules), rate limit. Tokens are issued as a relation manager on
the client's edit page.

## Allow-list semantics

Each MCP client carries three independent allow-lists.

| Value                | Meaning                                              |
|----------------------|------------------------------------------------------|
| `null`               | **Unrestricted** — allow any candidate.              |
| `[]` (empty array)   | **Explicit deny-all** — reject every candidate.      |
| `['*', ...]`         | Wildcard — allow any candidate.                      |
| `['foo', 'bar']`     | Allow only the listed values.                        |

The `null` ↔ `[]` distinction lets an operator persist the
difference between *"I haven't narrowed this client"* (null) and
*"I narrowed it to nothing"* (empty array). Authoritative reference:
the inline contract on
[`McpClient::allowsValue()`](../../Modules/Ai/Models/McpClient.php).

The Filament form requires at least one selection per list so admin-
created clients always carry an explicit, audit-friendly policy. CLI
clients (`ai:mcp:client:create` with `--tools` and `--modules` left
unset) get null = unrestricted by default.

## Wire protocol — JSON-RPC 2.0

Every call is a POST to `/api/mcp` with an
`Authorization: Bearer <token>` header and a JSON-RPC 2.0 envelope.

### Handshake

```bash
curl -s -X POST https://your.site/api/mcp \
  -H "Authorization: Bearer mcp_42|secret..." \
  -H "Content-Type: application/json" \
  -d '{
    "jsonrpc": "2.0",
    "id": 1,
    "method": "initialize",
    "params": {
      "protocolVersion": "2025-03-26",
      "clientInfo": {"name": "my-client", "version": "1.0.0"}
    }
  }'
```

Response:

```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "result": {
    "protocolVersion": "2025-03-26",
    "serverInfo": {"name": "Microweber AI MCP", "version": "0.1.0"},
    "capabilities": {"tools": {"listChanged": false}},
    "transport": "http-jsonrpc"
  }
}
```

The server echoes back the client's `protocolVersion` if it appears
in `AI_MCP_SUPPORTED_PROTOCOL_VERSIONS`; otherwise falls back to
`AI_MCP_PROTOCOL_VERSION`.

### Listing tools

```bash
curl -s -X POST https://your.site/api/mcp \
  -H "Authorization: Bearer mcp_42|secret..." \
  -H "Content-Type: application/json" \
  -d '{"jsonrpc": "2.0", "id": 2, "method": "tools/list"}'
```

Returns one `inputSchema`-decorated entry per tool the calling token
is allowed to invoke (after applying the client's allow-lists and
the per-tool admin-scope gate).

### Calling a tool

```bash
curl -s -X POST https://your.site/api/mcp \
  -H "Authorization: Bearer mcp_42|secret..." \
  -H "Content-Type: application/json" \
  -d '{
    "jsonrpc": "2.0",
    "id": 3,
    "method": "tools/call",
    "params": {
      "name": "content.lookup",
      "arguments": {"search_term": "home", "limit": 3}
    }
  }'
```

Response:

```json
{
  "jsonrpc": "2.0",
  "id": 3,
  "result": {
    "content": [{"type": "text", "text": "Search results..."}],
    "isError": false
  }
}
```

The `isError: true` flag flips when the tool emits the
`<!--mw-ai-tool-error-->` marker via `BaseTool::handleError()`, or
when the legacy `class="alert alert-danger"` opening-tag fallback
matches.

#### Output format

Every tool in the catalog today returns one `content[0].text` item
carrying HTML-stripped, whitespace-collapsed plain text. The
`tools/list` response advertises this via the
`annotations.outputFormat = "text"` field per tool, so MCP
2025-06-18 clients can reason about the response shape without a
per-tool `outputSchema`. When a future tool starts emitting
structured JSON, that tool should swap the annotation for a real
`outputSchema` covering the JSON shape — the
`McpToolCatalogContractTest` pins the current contract so the
swap can't happen silently.

### Utility methods

| Method                            | Purpose                                                                                  |
|-----------------------------------|------------------------------------------------------------------------------------------|
| `ping`                            | Liveness probe. Returns `result: {}`.                                                    |
| `notifications/initialized`       | Client → server, post-handshake. Server returns HTTP 204 (per JSON-RPC 2.0 §4.1).        |
| `notifications/*` (any other)     | Same as above — every notification gets HTTP 204, no JSON-RPC error envelope.            |
| Batch (array body)                | Per JSON-RPC 2.0 §6 — array of envelopes, response is an array of corresponding replies. |

### Error envelopes

| Code     | Meaning                                                                                         |
|----------|-------------------------------------------------------------------------------------------------|
| `-32000` | MCP server is disabled (returns HTTP 503).                                                      |
| `-32600` | Invalid Request (missing `method`, wrong `jsonrpc`, malformed body) — returns HTTP 400.         |
| `-32601` | Method not found.                                                                               |
| `-32602` | Invalid params (unknown tool name in `tools/call`).                                             |
| `-32603` | Internal error (tool dispatch threw an exception).                                              |

## Connecting AI clients

### Claude Desktop

Add to `~/Library/Application Support/Claude/claude_desktop_config.json`
(macOS) or the equivalent on Windows / Linux:

```json
{
  "mcpServers": {
    "microweber": {
      "transport": {
        "type": "http",
        "url": "https://your.site/api/mcp",
        "headers": {
          "Authorization": "Bearer mcp_42|secret..."
        }
      }
    }
  }
}
```

> Claude Desktop's HTTP-transport support landed with the
> 2025-03-26 protocol revision. Older builds default to stdio —
> we don't ship an stdio shim today (see Plan F's stdio task).

### Cursor / Cline / Continue

These clients each accept a JSON snippet matching the same
"transport: http + URL + bearer header" shape. Consult the upstream
docs for the exact path; the snippet is identical except for the
top-level wrapper key.

## Tool catalog reference

The full catalog lives at runtime in
`McpToolCatalog::allDefinitions()`. The latest pinned inventory is
in
[`Modules/Ai/tests/Feature/McpToolCatalogContractTest.php`](../../Modules/Ai/tests/Feature/McpToolCatalogContractTest.php)'s
`EXPECTED_TOOLS` constant — a regression test fails if the catalog
drifts away from that list, so the constant doubles as an
auto-checked reference.

To browse the catalog from the CLI:

```bash
php artisan ai:mcp:tools:list                 # all tools
php artisan ai:mcp:tools:list --module=billing  # filter to one module
```

## Rate limiting

Each client carries a `rate_limit_per_minute` (nullable, default
60). Hits are accumulated against
`mcp-client-token:<token-id>` keys via Laravel's RateLimiter; a
60-second fixed window applies. When the limit is exhausted, the
middleware returns HTTP 429 with the standard JSON-RPC error
envelope — clients should back off and retry.

Set `rate_limit_per_minute` to `0` (CLI) or NULL (database) to
disable rate limiting for a high-volume integration.

## Audit log

Every authentication, denial, tool call, and rotation lands in
`mcp_client_token_events` with an `action` enum
(`token.used`, `token.denied`, `token.revoked`, `token.rotated`,
`client.created`, etc.) plus a JSON `metadata` blob. The Filament
resource exposes the events as a relation on each token.

## Testing the server locally

```bash
# 1. enable
echo 'AI_ENABLED=true' >> .env
echo 'AI_MCP_ENABLED=true' >> .env
php artisan config:clear

# 2. verify wiring
php artisan ai:mcp:health --base-url=http://127.0.0.1:8000

# 3. issue a token + run a manual check
php artisan ai:mcp:client:create --name="Local Smoke" --scopes=mcp:access \
    --tools='*' --modules='*'
TOKEN="<paste from step 3 output>"
curl -s -X POST http://127.0.0.1:8000/api/mcp \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"jsonrpc":"2.0","id":1,"method":"tools/list"}' | jq .
```

For the automated regression suite:

```bash
php vendor/bin/phpunit \
    Modules/Ai/tests/Feature/McpControllerTest.php \
    Modules/Ai/tests/Feature/McpSpecComplianceTest.php \
    Modules/Ai/tests/Feature/McpClientAllowlistSemanticsTest.php \
    Modules/Ai/tests/Feature/McpConsoleCommandsTest.php \
    Modules/Ai/tests/Feature/McpServerErrorDetectionTest.php \
    Modules/Ai/tests/Feature/McpToolCatalogContractTest.php \
    Modules/Ai/tests/Feature/McpToolInputSchemaRegressionTest.php
```

## Further work

The full prioritised improvement plan lives in
[`TODO.md`](../../TODO.md) under the `MCP Server — Improvement Plan`
section.
