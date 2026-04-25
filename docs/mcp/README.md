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
| `php artisan ai:mcp:token:revoke <token-id>`         | Revoke a single token without issuing a replacement. Idempotent on already-revoked tokens (re-revoke prints a warning, exits 0). |
| `php artisan ai:mcp:client:list [--all]`             | Tabular client overview with token counts (active/total) + last-used. `--all` includes disabled clients. |
| `php artisan ai:mcp:prune-audit [--older-than=N] [--dry-run]` | Prune `mcp_client_token_events` older than N days (default 90). Use `--dry-run` to preview. |
| `php artisan ai:mcp:serve --token=...`               | Run the server over **stdio** so Claude Desktop / Cursor / Cline can launch it as a subprocess. One JSON-RPC envelope per line on STDIN, response on STDOUT. See "Connecting AI clients → stdio" below. |

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

Two paths: HTTP (newer Claude Desktop builds, 2025-03-26+) or
stdio (older builds, also Cursor / Cline default).

Add to `~/Library/Application Support/Claude/claude_desktop_config.json`
(macOS) or the equivalent on Windows / Linux:

#### HTTP (preferred)

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

#### stdio (older clients)

```json
{
  "mcpServers": {
    "microweber": {
      "command": "php",
      "args": [
        "/path/to/microweber/artisan",
        "ai:mcp:serve",
        "--token=mcp_42|secret..."
      ]
    }
  }
}
```

The stdio command (`ai:mcp:serve`) reads JSON-RPC envelopes one
per line from STDIN, dispatches each through the same
`McpServer::handle()` pipeline the HTTP controller uses, and
writes the response envelope one per line on STDOUT.
Notifications (`notifications/*`) emit no STDOUT line, matching
the HTTP 204 behaviour. Authentication uses the same bearer
token format; pass it via `--token=` or the `MW_MCP_TOKEN` env
var.

### Cursor / Cline / Continue

These clients each accept a JSON snippet matching the same
"transport: http + URL + bearer header" shape. Consult the upstream
docs for the exact path; the snippet is identical except for the
top-level wrapper key.

## Read-only by design

Every tool in the catalog today is **strictly read-only** —
every entry advertises `annotations.readOnlyHint = true` in its
`tools/list` row. AI clients (Claude Desktop, Cursor, Cline)
honour this hint and never auto-execute the tool without
explicit operator approval, but the hint is defence-in-depth;
the underlying tool implementations also can't write to the DB.

This is a deliberate choice for the current release:

  * **Smaller blast radius for leaked tokens.** A leaked
    read-only token can exfiltrate data — bad — but cannot
    delete content, edit pages, or send newsletters.
  * **No prompt-injection write surface.** Indirect prompt
    injection through user-generated content (a content row
    pasted into a chat) cannot be turned into a write call,
    because no write call exists.
  * **Operator-side confidence in early adoption.** Operators
    deploying MCP for the first time can audit a token for
    weeks before granting write capabilities.

When the first write tool ships, it must:

  1. Declare `readOnlyHint => false` in its catalog definition.
     The catalog already supports the override
     (`McpToolCatalog::listTools()` reads
     `definition.readOnlyHint` instead of hard-coding `true`),
     so this is a one-line flip per tool.
  2. Land in the new `AI_MCP_ADMIN_ONLY_TOOLS` env list (or
     `AI_MCP_ADMIN_ONLY_MODULES` if the entire module flips
     to write). Tokens without the `mcp:admin` scope then
     can't see or invoke it.
  3. Update the `EXPECTED_TOOLS` constant in
     `McpToolCatalogContractTest` and add a focused write-
     path test (write success + write rejection without
     admin scope + write idempotency).
  4. Surface the write capability in the Filament
     "Allow-list semantics" form description so admins
     creating new clients see the new tool in the
     allowed-tools picker.

A non-exhaustive list of high-value future write tools lives
in `TODO.md` under `MCP Server — Improvement Plan → C.1`.

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
60). Per-token overrides via `rate_limit_per_minute` on the
token row let one client issue both a low-rate "browse" token
and a high-rate "service" token without splitting clients —
see `McpClientToken::effectiveRateLimitPerMinute()`. The
override resolves first; falls back to the client-level value
when null.

Hits are accumulated against `mcp-client-token:<token-id>` keys
via Laravel's `RateLimiter`; a **60-second fixed window**
applies. When the limit is exhausted, the middleware returns
HTTP 429 with the standard JSON-RPC error envelope — clients
should back off and retry.

Set `rate_limit_per_minute` to `0` (CLI: `--rate-limit=0`) or
NULL (database) to disable rate limiting for a high-volume
integration.

### Per-tool rate limits

Expensive tools can be capped tighter than the token-level
budget so a single client can't burn the entire per-minute
allowance on one slow query (e.g. `analytics.traffic_summary`,
`newsletter.campaign_lookup`). Configure via env:

```env
# 60-second window per (token-id, tool) pair
AI_MCP_TOOL_RATE_ANALYTICS_TRAFFIC_SUMMARY=10
AI_MCP_TOOL_RATE_NEWSLETTER_CAMPAIGN_LOOKUP=5
```

The middleware checks the per-tool gate first, then the
token-level gate. A request that survives both gates increments
both buckets. Per-tool denials record `scope=per_tool` in the
audit-log metadata so operators can distinguish them from
token-level denials in the Filament events viewer. Tools not
listed in the config map fall back to the token-level cap.

### Fixed-window trade-off

The rate limiter uses Laravel's fixed-60-second window because
it's the simplest reliable backing store. The trade-off: a burst
at second 59 followed immediately by another burst at second 0
of the next window can double the per-minute peak. For most
operator-scale integrations (a single Claude Desktop user, a
Cursor project, a few Cline sessions) this is fine — the
absolute peak is still bounded.

For high-throughput service integrations where the doubling at
window boundaries matters, the limit should be set to half the
desired effective rate (e.g. 300/min to enforce a true peak of
~600/min worst-case). A future sliding-window or token-bucket
implementation would close this gap; the contract for that
upgrade is documented as Plan D.1 in `TODO.md` under "Sliding-
window rate limiter".

## Security posture

### CSRF

The endpoint lives under the `api` middleware group. Laravel's
`VerifyCsrfToken` runs only on the `web` group, so `/api/mcp` is
**not** CSRF-protected — the bearer token is the only auth
credential. This matches the pattern Sanctum / Passport
endpoints use, and is intentional: MCP clients don't carry the
session cookie, so a CSRF token check would always fail.

A leaked token is therefore the entire compromise surface; rotate
via `php artisan ai:mcp:token:rotate <id>` (or the Filament
"Rotate" row action) the moment a leak is suspected.

### CORS

`/api/mcp` matches the default `paths` glob in `config/cors.php`
(`api/*`), so the browser CORS preflight runs against the same
allow-list every other API endpoint uses:

```env
# .env
CORS_ALLOWED_ORIGINS=https://your.site,https://admin.your.site
CORS_ALLOWED_ORIGIN_PATTERNS=#^https://.*\.your\.site$#
```

For server-to-server MCP clients (Claude Desktop, Cursor, the
Anthropic SDK) CORS doesn't apply — only browser-based clients
need to appear in the allow-list. **Do not** expose the endpoint
to `*` origins unless you intend any web page to be able to
attempt cross-origin calls (the bearer token is still required
to succeed, but every preflight + body read becomes a mostly-
free probe).

### What never lands in logs

The middleware is audited end-to-end:

  * `Log::warning('mcp.auth.unauthorized', ...)` records
    `{message, ip, user_agent, path}` — no `Authorization`
    header, no token bytes, no inbound JSON-RPC body echo.
  * Every `recordEvent` metadata blob (token-issued / rotated /
    revoked / used / denied) records `token_name` and
    `token_last_eight` only. The plain-text token never lands
    in `mcp_client_token_events.metadata`.
  * The `mcp.tool.call` / `mcp.tool.slow` log lines emitted by
    `McpServer::logToolCall` carry `(tool, duration_ms, status,
    token_id, client_id)` — the token id is a row primary key,
    not the secret.

If you need to grep audit logs for "what happened to token X",
use `token_last_eight` (printed once at issuance) plus the
client/token id surfaced in the Filament "MCP Clients →
[client] → Audit log" relation manager.

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
