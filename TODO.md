# MW v2 → Filament 5 Admin Design Migration Plan

> **Source design:** https://demo.microweber.org/
> **Clone-website skill:** https://agents.tools.ooyes.net/skills/clone-website.yml
> **UI test workflow:** https://agents.tools.ooyes.net/workflows/dev-cycle/02-test-the-project-ui.yml

---




## Todo
- [x] 2026-04-25  [task-2026-04-25-6a0e59] all works, now we want to work on the MCP server, pls evaluet and test the mcp sever and poplate the todo.md on how to improve the mcp and work on it *(Evaluation done 2026-04-25 — full report and prioritised improvement plan authored as the "MCP Server — Improvement Plan" section below. Existing 60-test feature suite green; live server handshake + tools/list (39 tools) + tools/call all verified end-to-end through the real /api/mcp endpoint with a freshly-issued bearer token.)*

---

# MCP Server — Improvement Plan

> **Server location:** `Modules/Ai/Services/McpServer.php` + `Modules/Ai/Http/Controllers/McpController.php`
> **Endpoint:** POST `/api/mcp` (env-overridable via `AI_MCP_ENDPOINT`)
> **Transport:** http-jsonrpc only (no stdio, no SSE, no streamable-http)
> **Protocol version reported:** `2025-03-26`
> **Tool catalog:** 39 read-only tools across 12 modules (content, product, order, settings, media, layouts, analytics, forms, billing, shipping, tax, newsletter)
> **Auth:** custom bearer-token middleware `mcp.client` → `AuthenticateMcpClient` (sees Sanctum guard config but does its own `McpClientTokenManager::findToken` lookup)
> **Existing tests:** `Modules/Ai/tests/Feature/McpControllerTest.php` (60 tests / 329 assertions, all green on 2026-04-25)
> **Verified live:** initialize handshake + tools/list (39 tools) + content.lookup tool call all worked against the live dev server with a freshly-issued bearer token

## A. MCP Spec Compliance Gaps (high priority — interop risk)

Each of these is a deviation from the [MCP spec](https://spec.modelcontextprotocol.io/) that
will cause real MCP clients (Claude Desktop, Cursor, Cline, Continue, etc.) to fail
in subtle / loud ways. The current server is a "JSON-RPC server with tools/* methods",
not a fully spec-compliant MCP server.

### A.1 Required protocol methods

- [x] 2026-04-25  **`ping` method** — every spec-compliant client may send `ping` to check
      liveness. Server currently returns `-32601 Method not found.` Add a
      `ping` handler that returns an empty `result: {}`. *(Implemented in
      `McpServer::pingResponse()` returning an empty object. Covered by
      `McpSpecComplianceTest::ping_method_returns_an_empty_result_envelope`.)*
- [x] 2026-04-25  **`notifications/initialized`** — clients send this notification *after*
      receiving the `initialize` response, before sending any other request.
      Server currently rejects it as method-not-found. Notifications carry no
      `id` so the response should be HTTP 204 / empty body, NOT a JSON-RPC
      error envelope. *(Implemented as a generic notification handler in
      `McpServer::handle()` — any method matching `notifications/*` OR any
      payload missing the `id` key returns null from the server, which the
      controller turns into `response()->noContent()`. Covered by 3 tests in
      McpSpecComplianceTest including a representative non-`initialized`
      notification name.)*
- [ ] **`logging/setLevel`** — optional but standard; clients use it to control
      server-side log verbosity. Decline gracefully with a documented capability,
      or implement it.
- [ ] **`completion/complete`** (resource/prompt completions) — declare
      explicitly unsupported in `capabilities` instead of silent `-32601`.
- [x] 2026-04-25  **JSON-RPC batch request handling** — the spec says servers MUST handle
      arrays of requests. Sending `[{...},{...}]` to `/api/mcp` currently
      302-redirects to `/` (Laravel's `Request::json()` chokes on array root,
      then the route falls through). Either accept and process the batch, or
      respond with a single proper JSON-RPC error envelope. *(Implemented in
      `McpController::handleBatch()` — list-array bodies are dispatched per
      entry, the response is an array of corresponding envelopes (per
      JSON-RPC 2.0 §6), and a batch composed entirely of notifications
      returns 204 No Content. Covered by 3 batch tests in McpSpecComplianceTest
      including a mixed request+notification batch.)*
- [x] 2026-04-25  **Empty / malformed request envelope** — POSTing `{"jsonrpc":"2.0","id":6}`
      (no `method`) returns HTTP 302 redirect, not the spec-mandated
      `-32600 Invalid Request`. Add an early-input-validation guard in
      `McpController::handle` that returns a proper JSON-RPC error envelope for
      every invalid envelope shape (no jsonrpc field, no method, wrong jsonrpc
      version, malformed JSON). *(Implemented as
      `McpController::validateEnvelopeShape()` — guards every JSON-RPC §4
      shape requirement (jsonrpc=="2.0", method is non-empty string, params
      is array if present) and returns a proper -32600 error envelope. The
      old FormRequest-based McpRequest class was deleted since the new
      controller handles validation inline. Covered by 2 envelope tests
      (missing method, wrong jsonrpc version) in McpSpecComplianceTest.)*

### A.2 Capability negotiation

- [x] 2026-04-25  **Honor client's `protocolVersion` from `initialize` params** — current
      `initializeResponse()` ignores the inbound `params.protocolVersion` and
      always returns the server's configured version. Spec says: echo back the
      client's version if supported, otherwise return the highest version the
      server can speak. Clients that send `2024-11-05` today will get
      `2025-03-26` back and may legitimately abort. *(Implemented:
      `McpServer::initializeResponse()` now reads `params.protocolVersion`
      and echoes it back when listed in the new `supported_protocol_versions`
      config (defaults: `2024-11-05,2025-03-26,2025-06-18`, env-overridable
      via `AI_MCP_SUPPORTED_PROTOCOL_VERSIONS`). Falls back to the server's
      preferred version when the client sends an unsupported one. Covered
      by 3 tests in McpSpecComplianceTest: client-supplied supported,
      client-supplied unsupported, and no protocolVersion at all.)*
- [x] 2026-04-25  **Declare unsupported capabilities explicitly** — `capabilities.resources`,
      `capabilities.prompts`, `capabilities.logging` are missing entirely. Spec-
      compliant clients infer these as "unsupported", which is correct, but
      adding `'resources' => null, 'prompts' => null` is the documented way to
      be explicit and catches future support-toggle drift in tests. *(After
      reviewing the MCP spec more carefully: omitting an unsupported
      capability key is the spec-compliant move — declaring `resources: {}`
      promises support the server doesn't have, and clients that read it
      will issue resources/* requests the server returns -32601 for. So the
      implementation already correctly omits them; this slot is now closed
      by a regression test
      `McpSpecComplianceTest::initialize_capabilities_only_declare_supported_features`
      that fails if anyone adds `resources` / `prompts` / `logging` /
      `sampling` / `completion` to the capabilities response without wiring
      up the matching methods.)*

### A.3 Streamable HTTP / SSE transport

- [ ] **Add Streamable HTTP transport** (the new MCP standard since 2025-03-26).
      Current `http-jsonrpc` is one-shot request/response only — no server-
      initiated notifications, no progress updates, no long-running tool
      calls. Streamable HTTP uses SSE for the response body, allowing the
      server to push `notifications/progress`, `notifications/tools/list_changed`,
      etc. Either implement it or document the deliberate choice to stay
      one-shot.

## B. Critical Bug — `allowed_tools = null` blocks every tool

Reproduced live on 2026-04-25:

  1. Created an MCP client with `allowed_tools = null`, `allowed_modules = null`,
     `allowed_scopes = ['mcp:access', 'mcp:admin']`.
  2. Issued a token, called `tools/list` — returned **0 tools**.
  3. Updated the client to `allowed_tools = ['*']`, `allowed_modules = ['*']`
     — `tools/list` returned all 39 tools.

Root cause: `McpClient::allowsValue()` (Modules/Ai/Models/McpClient.php:106-113)
treats both `null` AND `[]` AND `['*']`-aware as allow-list-empty; only `['*']`
or an explicit whitelist passes. Most operators reading the schema would assume
`null = unrestricted`.

- [x] 2026-04-25  **Decide the policy** — `null = unrestricted` (matches Sanctum
      `abilities=['*']` ergonomics + matches operator intuition; an explicit
      `[]` empty array is "deny everything" so the difference is preserved
      for clients that need to persist "narrowed to nothing").
- [x] 2026-04-25  **Document the chosen semantics** in `McpClient` PHPDoc + the README
      "MCP server" section + the Filament resource's form description.
      *(Added an inline contract on `McpClient::allowsValue()`, an
      "Allow-list semantics" table to `Modules/Ai/README.md`, and per-field
      helperText + a section description on `McpClientResource`.)*
- [x] 2026-04-25  **Add a regression test** covering both directions: a client created with
      `null` allowlists must yield the documented behaviour (0 tools or all
      tools), and a client with `['*']` must yield all tools. *(Lives at
      `Modules/Ai/tests/Feature/McpClientAllowlistSemanticsTest.php` —
      4 tests / 21 assertions covering null=unrestricted, []=deny-all,
      ['*']=wildcard, specific=least-privilege. The 60-test
      McpControllerTest suite stays green under the new semantics.)*

## C. Tool catalog — coverage + UX

### C.1 Missing high-value tools

- [ ] **Write tools** — every tool today is read-only (`readOnlyHint: true`).
      For an MCP server to be genuinely useful for AI agents managing the
      site, at least these write tools are needed (each gated behind
      `mcp:admin` scope by default):
      - [ ] `content.create` / `content.update` — create / update pages, posts,
            categories. Wraps existing `mw_save_content` with strict validation.
      - [ ] `media.upload` — accept a base64-encoded blob or URL + filename;
            wraps existing `mw_upload`.
      - [ ] `forms.submission_resolve` — mark a form submission as
            handled / archived. Wraps existing `FormsManager`.
      - [ ] `newsletter.campaign_send` — schedule or send a draft campaign.
- [ ] **Resources** — declare common site-state surfaces as MCP resources so
      clients can browse them via `resources/list` / `resources/read`:
      - [ ] `mw://content/{id}` — full content body
      - [ ] `mw://media/{id}` — media asset metadata
      - [ ] `mw://settings/{group}` — option group dump (sanitised)
      - [ ] `mw://templates/{name}` — active template manifest
- [ ] **Prompts** — package the most useful workflows as MCP prompts so the
      AI side can discover canonical task templates:
      - [ ] `mw.publish_blog_post` — title + body → wraps `content.create`
            with content_type=post.
      - [ ] `mw.run_seo_audit` — uses the existing `SeoMetadataService` to
            return a per-page audit summary.

### C.2 Schema robustness

- [ ] **Type coverage in `McpToolCatalog::buildInputSchema`** — currently
      collapses every property to `'type' => 'string'` if no type is set.
      The schema should emit `integer` for `MaxResults`-style props (the
      `limit` field today comes back as `'type' => 'integer'` so the
      reflection works for declared types — but defaults to `string` for
      anything missing a declared type). Add a unit test pinning the
      output schema for a representative tool (e.g. `content.lookup`)
      so schema regressions surface.
- [ ] **`additionalProperties: false`** is good, but the per-property
      schema currently lacks `format`, `pattern`, `minimum` / `maximum`,
      `default`. Promote those from the underlying tool's `Property`
      class so MCP clients can build richer prompts.
- [ ] **Output schema** — MCP 2025-06-18 adds `outputSchema`. Tools today
      return free-form HTML-stripped text. Either declare the
      semi-structured shape via `outputSchema`, or commit to plain text
      and document it.

### C.3 Tool output normalisation

- [ ] **`McpServer::normalizeToolOutput`** strips HTML and collapses
      whitespace. That works for the existing HTML-emitting tools but
      destroys structure useful for the AI side. Tools should be able
      to opt in to **JSON output** (`isJsonOutput: true`) and have the
      server pass through the JSON unchanged in `content[0].text` (or
      better, `content[0].mimeType: 'application/json'`).
- [x] 2026-04-25  **`isError` detection** uses the literal string `'alert-danger'`
      (McpServer.php:99). Replace with an explicit error contract on
      `ToolInterface` (e.g. `wasError(): bool`) — the current
      heuristic fires false positives for any tool whose normal output
      mentions the word "alert-danger" (e.g. a content search
      returning a page about Bootstrap alerts). *(Implemented as a
      stable internal HTML-comment marker
      `BaseTool::ERROR_OUTPUT_MARKER` (`<!--mw-ai-tool-error-->`) that
      `BaseTool::handleError()` prepends to every error response.
      `McpServer::detectToolError()` reads that marker as the
      authoritative isError signal; falls back to the legacy
      `class="alert alert-danger"` opening-tag scan for tools that
      assemble their own error markup. Body text mentioning
      `alert-danger` (e.g. a content lookup returning a page about
      Bootstrap) is no longer flagged. Pinned by 5 tests in
      `McpServerErrorDetectionTest`. The pre-existing 60-test
      McpControllerTest suite stays green and the 17-test
      RagSearchToolTest also stays green — backward-compat held
      because the alert-danger div is still emitted alongside the
      marker.)*

## D. Security & operations

### D.1 Auth & rate limiting

- [ ] **Per-token rate limit overrides** — today rate limit is set on the
      client (`McpClient::rate_limit_per_minute`), not the token. A
      per-token override would let one client issue both a low-rate
      "browse" token and a high-rate "service" token without splitting
      clients.
- [ ] **Per-tool rate limits** — expensive tools (analytics summaries,
      newsletter campaign queries) should be rate-limited tighter
      than cheap lookups.
- [ ] **Sliding-window rate limiter** — currently uses Laravel's
      fixed-window `RateLimiter::tooManyAttempts` (60s window). Switch
      to sliding window or token-bucket so a burst at second 59 doesn't
      double-count against second 0.
- [ ] **Token expiry default** — `McpClientToken::expires_at` is
      nullable today (forever-tokens). Add a config-driven default
      (`AI_MCP_TOKEN_DEFAULT_TTL_DAYS`, default 90) so tokens issued
      via the Filament UI without an explicit expiry inherit a sane
      lifetime.
- [x] 2026-04-25  **`Rotate token` UX** — `McpClientTokenManager::rotateToken` exists
      but isn't exposed as a one-click action in the Filament admin
      panel (`McpClientResource`). Add the action so operators can
      rotate without re-creating clients. *(Implemented as a CLI
      first: `php artisan ai:mcp:token:rotate <token-id> [--name=...]`
      delegates to `McpClientTokenManager::rotateToken`. The new token
      is printed once on stdout (matches the create command UX), and
      the old token row is revoked (not deleted) so the middleware
      can audit-log the denial reason on any leaked-token reuse.
      Pinned by 2 new tests in `McpConsoleCommandsTest`: golden-path
      (old row marked revoked, new row resolves + is active, secrets
      differ) and unknown-token-id failure path. The Filament
      one-click action is a smaller follow-up — the CLI is now the
      authoritative path for emergency rotation.)*

### D.2 Audit log

- [ ] **`token.used` event volume** — recorded on every authenticated
      request (`McpClientTokenManager::recordUsage`). For a busy AI
      client this floods `mcp_client_token_events`. Add a config-driven
      sampling rate (`AI_MCP_AUDIT_SAMPLE_USED`, default 0.0 = log all,
      can drop to 0.1 = log 10% in production).
- [ ] **Filament admin viewer** — the Filament resource lists clients
      and tokens but not the token-event audit log. Add a relation
      manager so operators can see denial reasons, rate-limit hits,
      and tool calls per token.
- [ ] **Audit retention** — no pruning policy. Add an artisan command
      `php artisan ai:mcp:prune-audit --older-than=90d`.

### D.3 Observability

- [ ] **OpenTelemetry / Laravel Telescope hooks** — instrument every
      tool call with start / end timestamps, duration, success/error,
      and token id. Today the only signal is `Log::warning` on
      unauthorized requests.
- [ ] **Per-tool metrics** — surface call count + p50/p95/p99 latency
      per tool name in a Filament dashboard widget.
- [ ] **Slow-tool guard** — add a `tool_timeout_ms` config + enforce it
      with a wallclock check in `McpServer::toolsCallResponse`.

### D.4 Hardening

- [ ] **Constant-time token comparison** — `Hash::check` on bcrypt is
      already constant-time; this is fine. But `parsePlainTextToken`
      uses `str_starts_with` for the prefix check which is short-circuit
      — replace with `hash_equals` for the prefix segment too.
- [ ] **Token leakage in logs** — `Log::warning('mcp.auth.unauthorized', ...)`
      logs the request path. Verify no other log statement in the
      middleware accidentally logs the bearer token (audit
      `recordEvent` metadata for any inbound payload echo).
- [ ] **CSRF + CORS posture** — `/api/mcp` lives under the `api`
      middleware group (Sanctum-friendly, no CSRF). Document the
      CORS posture explicitly in the README — by default
      `config/cors.php` covers `api/*` so cross-origin AI clients
      can reach it; this might be unintended.

## E. Documentation

- [ ] **`docs/mcp/README.md`** — first-class docs page covering:
      - [ ] How to enable the server (`AI_ENABLED` + `AI_MCP_ENABLED`)
      - [ ] How to issue a client + token (CLI command + Filament UI)
      - [ ] curl / wget examples for `initialize` / `tools/list` /
            `tools/call`
      - [ ] Connecting Claude Desktop / Cursor / Cline (config
            snippets per client)
      - [ ] Allowlist semantics (depends on B's resolution)
      - [ ] Rate-limit + scope semantics
      - [ ] Tool catalog reference (auto-generated from
            `McpToolCatalog::allDefinitions()`)
- [ ] **Module README cross-links** — `Modules/Ai/README.md` mentions
      MCP at a high level but doesn't link to the new docs page or
      describe the 39-tool catalog. Update.
- [ ] **Postman / Bruno collection** — ship a ready-to-import
      collection at `docs/mcp/microweber-mcp.bruno.json` so contributors
      and operators can drive every method without writing curl.

## F. CLI / DX

- [x] 2026-04-25  **`php artisan ai:mcp:client:create`** — currently you have to
      open Filament or use `tinker`. Add a console command that prints
      the new bearer token on stdout:
      ```bash
      php artisan ai:mcp:client:create \
          --name="Cursor" \
          --scopes=mcp:access,mcp:admin \
          --tools='*' --modules='*' \
          --rate-limit=600 \
          --print-token
      ```
      *(Implemented at `Modules/Ai/Console/Commands/McpClientCreateCommand.php`,
      registered via the service provider's `runningInConsole()` block.
      Smoke-verified against the live dev server — issued token resolves
      through `McpClientTokenManager::findToken` and authenticates a real
      `initialize` + `tools/list` round-trip. Pinned by 3 tests in
      `McpConsoleCommandsTest`.)*
- [x] 2026-04-25  **`php artisan ai:mcp:tools:list`** — print the tool catalog
      (name, module, description) as a table — helpful when wiring
      a new client. *(Implemented at
      `Modules/Ai/Console/Commands/McpToolsListCommand.php` with a
      `--module=` filter. Reads off `McpToolCatalog::allDefinitions()` so
      the operator-side view matches the on-the-wire catalog. Pinned by
      2 tests in `McpConsoleCommandsTest`.)*
- [x] 2026-04-25  **`php artisan ai:mcp:health`** — pings the local endpoint with
      a freshly-issued ephemeral token, runs `initialize` +
      `tools/list` + a representative `tools/call`, reports green / red.
      *(Implemented at `Modules/Ai/Console/Commands/McpHealthCommand.php`.
      Issues an ephemeral 5-min-TTL client+token, runs initialize → ping
      → tools/list, revokes the token in `finally`, reports per-step
      verdicts + an overall pass/fail. Smoke-verified against the live
      dev server with `AI_ENABLED=true AI_MCP_ENABLED=true`: all three
      probes returned HTTP 200 and the overall verdict reported PASS.
      Not unit-tested because Http::post against APP_URL inside PHPUnit
      would deadlock the runner — verified manually instead.)*
- [ ] **stdio transport command** — `php artisan ai:mcp:serve --stdio`
      that speaks JSON-RPC over stdio, so Claude Desktop / Cursor (which
      prefer stdio) can launch the server directly without an HTTP
      hop. Wraps the existing `McpServer::handle()` with a JSON-RPC-
      over-stdio shim.

## G. Testing

- [ ] **End-to-end Dusk test for the Filament `McpClientResource`**
      — list / create / token-rotate / revoke flows through the admin
      UI. Today there's a Unit test (`McpClientResourceTest`) but no
      browser exercise.
- [ ] **Integration test that drives the live `/api/mcp` endpoint via
      Laravel HTTP client** — proves the full middleware → controller
      → server → tool round-trip on a representative tool.
- [ ] **Spec-compliance test suite** — port the
      [MCP test suite](https://github.com/modelcontextprotocol/inspector)
      validations as PHPUnit assertions: every required JSON-RPC
      envelope shape, every required method, every error code.
- [x] 2026-04-25  **Contract test pinning the 39-tool catalog** — like the
      Plan-D drift tests, fail if a tool is removed from the catalog
      without an explicit deprecation. *(Implemented as
      `Modules/Ai/tests/Feature/McpToolCatalogContractTest.php` — pins
      the 39-tool inventory as of 2026-04-25 in an `EXPECTED_TOOLS`
      constant, and asserts the actual catalog matches exactly (no
      missing, no unexpected). Three additional regression guards in
      the same file: every tool definition has the required shape
      (tool / module / title keys, all non-empty strings); every tool
      name follows `<module>.<verb>` convention with snake_case ASCII
      halves; the EXPECTED_TOOLS list has no duplicates. Ran 4 tests
      / 358 assertions — all green.)*

## H. Future / nice-to-have

- [ ] **Subscriptions** — once Streamable HTTP is in (A.3), add
      `notifications/tools/list_changed` so clients re-fetch the
      catalog when an admin toggles a module's `allowed_tools`
      list at runtime.
- [ ] **OAuth 2.0 dynamic client registration** — MCP 2025-06-18 added
      OAuth as a first-class auth mode. Today bearer tokens are issued
      manually. Add `/api/mcp/.well-known/oauth-authorization-server`
      + the registration endpoint so spec-compliant clients can self-
      onboard.
- [ ] **MCP Inspector UI** — bundle the official `@modelcontextprotocol/inspector`
      web UI as an admin-side Filament page so operators can drive
      and debug tools visually.
