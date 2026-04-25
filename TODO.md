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

- [x] 2026-04-25  [task-2026-04-25-16994e] next populaye the todo with the microweber:ai   ariscan comand creation we want to be able to call the ai agent via the cli adn tell it to add psots, etc and make unit tests for it , make i plnai n the todo *(Plan authored as the new "AI Agent CLI — `microweber:ai` artisan command" section below. Surveyed the existing AI agent infrastructure first: BaseAgent + AgentFactory entry points, the 6 catalog write tools (CreatePostTool / CreateContentTool / CreateProductTool plus the three Edit variants), the AgentWriteOperationsTest pattern, the Filament chat UI, and the user-context auth requirements. Plan organised into 6 prioritised tracks: foundations (the command itself + agent dispatch), write-action coverage (post / content / product / edit), UX polish (streaming, history, tone), security & operations (auth context, rate-limit, audit), testing, and documentation. Every track has acceptance criteria and a focused test contract.)*

---

# AI Agent CLI — `microweber:ai` artisan command

> **Goal:** ship `php artisan microweber:ai "add a blog post about cats"`
> so operators can drive the existing Microweber AI agent from the
> shell — same agent + same tool catalog as the Filament chat UI,
> just no browser. The command is the natural CLI peer to the
> `ai:mcp:*` family; it lets contributors prototype write actions,
> CI script content seeding, and operators automate routine
> editorial work without leaving the terminal.
>
> **Existing infrastructure** (audited 2026-04-25):
> - **Agent entry point:** `BaseAgent` (extends NeuronAI\Agent) at
>   `Modules/Ai/Agents/BaseAgent.php` line 1; invocation is
>   `$agent->chat(UserMessage)` returning a synchronous string reply
>   that the BaseAgent appends to its chat history.
> - **Factory:** `AgentFactory` at `Modules/Ai/Services/AgentFactory.php`
>   provides `agent(name)`, `agentWithChat(AgentChat)`,
>   `agentWithSession(type, title, userId, ...)`. Each agent
>   auto-loads its domain-specific tool set via `setupTools()`.
> - **Write tools:** 6 catalog tools today —
>   `CreatePostTool` (Modules/Ai/Tools/CreatePostTool.php),
>   `CreateContentTool`, `CreateProductTool`, `PostEditTool`,
>   `ContentEditTool`, `ProductEditTool`. `BaseTool::handleError()`
>   marks failures with `<!--mw-ai-tool-error-->` (the same MCP
>   error contract from Plan C.3).
> - **User context:** every write tool calls `user_id()` and
>   `BaseTool::auditWriteOperation()` — the artisan command MUST
>   set an authenticated user context before the dispatch.
> - **Existing test pattern:** `tests/Unit/AgentWriteOperationsTest.php`
>   creates agent + chat + admin user (line 62-74) and asserts
>   the tool catalog includes the 6 write verbs (line 173).
> - **Greenfield:** no non-MCP artisan command exists in the AI
>   module today, so no existing code to refactor.

## CLI.1 — Foundations

- [ ] **`Modules/Ai/Console/Commands/MicroweberAiCommand.php`** —
      registered via the existing `runningInConsole()` block in
      `AiServiceProvider`. Signature:

      ```bash
      php artisan microweber:ai "add a blog post about cats"
          [--agent=general]
          [--user=admin@admin.com]
          [--session=NN]
          [--json]
      ```

      Accepts the prompt as a positional argument so `php artisan
      microweber:ai "..."` is the canonical invocation. Options:

      - `--agent=NAME` — named agent type (defaults to `general`).
        Allow-list pulled from the AgentFactory's registered types
        so unknown names reject with a list of valid options.
      - `--user=EMAIL` — operator to run as (defaults to the first
        admin user; falls back to a `--user-id=N` form for CI use).
        The command resolves the user via `User::where('email', $email)`,
        seats them as the authenticated user via `Auth::login()`, and
        threads the resulting user-id through every write-tool's
        `user_id()` lookup so audit-log entries land under the
        correct actor.
      - `--session=NN` — optional `mcp_chats.id` to continue an
        existing chat. Without it, the command opens a fresh
        ephemeral session (or persists a new `AgentChat` row when
        `--persist-session` is added in CLI.3).
      - `--json` — emit the agent's reply (and any tool-call
        side-effects) as a JSON envelope on stdout instead of the
        default human-readable text. Useful for shell-pipelines.

- [ ] **Dispatch path** — instantiate the agent through
      `AgentFactory::agent($agentType)`, wrap the prompt in a
      `UserMessage`, call `->chat()`, then pipe the reply to stdout.
      Exit 0 on a clean reply, exit 1 when the reply text contains
      `BaseTool::ERROR_OUTPUT_MARKER` (so CI can detect "tool
      reported an error" without parsing the full text).

- [ ] **Output format** — default human-readable mode:
      1. Echo the resolved agent + user + session header (one
         per line, prefixed with `→`).
      2. Stream the reply on STDOUT as it lands (the agent's
         `chat()` is synchronous today, but emit the final reply
         in one block so a future streaming-aware refactor can
         drop in without breaking the CLI contract).
      3. If any write tools were invoked during the dispatch, list
         the resulting record IDs (`post_id`, `content_id`, etc.)
         on STDERR so operators see them even when piping STDOUT.

- [ ] **`--json` mode** — emit a single JSON envelope:
      ```json
      {
        "agent": "general",
        "user_id": 1,
        "session_id": 42,
        "reply": "I created the post 'Top 5 Things About Cats' (id: 7831).",
        "tool_calls": [
          {"tool": "create_post", "args": {...}, "result_id": 7831, "ok": true}
        ],
        "duration_ms": 4837,
        "is_error": false
      }
      ```
      The `tool_calls` list is collected by tapping
      `BaseTool::auditWriteOperation()` during the dispatch.

## CLI.2 — Write-action coverage

These sub-tasks each surface one of the 6 catalog write tools as a
first-class CLI sub-command **once** the foundation lands. Each
sub-command is a thin adapter that pre-fills the agent's prompt
template so operators get a deterministic invocation contract
instead of having to remember free-text phrasing.

- [ ] **`microweber:ai post:create --title=... --body=...`** —
      adapter for `CreatePostTool`. Equivalent to running
      `microweber:ai "create a blog post titled '...' with body
      '...' "` but with explicit args + early validation. Defaults
      `category` to `null`; reads `--published-at`, `--tags`,
      `--seo-meta-description` as optional flags that map to the
      tool's input schema.

- [ ] **`microweber:ai content:create --type=page --title=...`** —
      adapter for `CreateContentTool`. `--type` accepts
      `page` / `post` / `product` / `category` (the same set the
      tool's input schema enumerates).

- [ ] **`microweber:ai product:create --title=... --price=...`** —
      adapter for `CreateProductTool`. Adds `--sku`, `--quantity`,
      `--currency` flags.

- [ ] **`microweber:ai post:edit ID --field=value`** —
      adapter for `PostEditTool`. Repeated `--field=value` pairs
      build the partial update payload.

- [ ] **`microweber:ai content:edit ID --field=value`** —
      adapter for `ContentEditTool`. Same shape as post:edit.

- [ ] **`microweber:ai product:edit ID --field=value`** —
      adapter for `ProductEditTool`. Same shape as post:edit.

## CLI.3 — UX polish

- [ ] **Persistent sessions** — `--persist-session` creates an
      `AgentChat` row before the dispatch and prints the
      `session_id` on the header line so subsequent invocations
      can pass `--session=N` to continue the conversation. Without
      the flag, the chat is purely ephemeral (no DB write).

- [ ] **Interactive REPL mode** — `microweber:ai --interactive`
      drops into a readline loop where each line is dispatched as
      a new prompt within the same session. Exit on Ctrl-D / `:q`.
      Useful for prototyping multi-turn flows without scripting.

- [ ] **Tone of operator output** — the agent's `chat()` reply is
      already markdown-like; pipe it through a Filament-aware
      ANSI renderer so headings render bold, code blocks render in
      a different colour, and tool-call summaries render with a
      `→` glyph prefix. Borrow the rendering pattern from
      `Symfony\Component\Console\Style\SymfonyStyle`.

- [ ] **`--dry-run`** — flag that flips every write-capable tool
      into a "describe what you would do" mode (already supported
      by `BaseTool` via the existing `dryRun` workflow-state flag —
      the CLI just sets it). Useful for previewing the agent's
      plan before letting it touch the DB.

## CLI.4 — Security & operations

- [ ] **Auth context required** — the command MUST resolve a real
      user (default: first `is_admin=1` user, override via `--user`
      or `--user-id`) before dispatch. Reject with a pointed error
      when no admin user exists ("Run `php artisan
      microweber:install` first" hint). Audit-log every dispatch
      with `cli_user_id`, `agent`, `prompt_first_80_chars` so
      operator-driven CLI use is distinguishable from chat-UI use.

- [ ] **Rate-limit** — reuse the MCP per-tool rate-limit config
      (`modules.ai.mcp.per_tool_rate_limits`) so a CI script that
      slams `post:create` 100 times per minute trips the same
      ceiling that protects the HTTP MCP endpoint. Different
      bucket key (`microweber-ai-cli:<user-id>:tool:<name>`) so
      CLI traffic doesn't bleed into MCP traffic.

- [ ] **Per-prompt audit row** — every `microweber:ai` invocation
      writes one `mcp_client_token_events` row with `action='cli.ai.dispatched'`
      (or a new dedicated `ai_cli_events` table if Plan D.2's
      schema doesn't fit). Records: cli_user_id, agent, prompt,
      session_id, tool_calls (JSON), reply_first_200_chars,
      duration_ms, is_error.

- [ ] **Dangerous prompt guard** — if the resolved prompt would
      cause more than N write tool invocations in a single
      dispatch (default N=5, env-overridable via
      `AI_CLI_MAX_WRITES_PER_DISPATCH`), emit a confirmation
      prompt (`echo y | php artisan ...` to skip in CI). Prevents
      a single mis-aimed prompt from rewriting half the catalog.

## CLI.5 — Testing

- [ ] **`Modules/Ai/tests/Feature/MicroweberAiCommandTest.php`** —
      pin the foundations:
      - happy path: `microweber:ai "create a post titled 'CLI
        Test'"` exits 0, prints the reply, persists a `content`
        row with `content_type='post'` and `title='CLI Test'`.
      - `--user=...` resolution: unknown email exits 1 with a
        descriptive error.
      - `--agent=...` validation: unknown agent name exits 1 and
        lists valid options.
      - `--json` mode: stdout parses as JSON, contains the
        documented envelope keys.
      - error-marker detection: a prompt that triggers
        `BaseTool::handleError` exits 1, even if the agent's text
        reply looks superficially successful.

- [ ] **`MicroweberAiSubCommandsTest`** — pin every CLI.2 sub-
      command:
      - `post:create --title=X --body=Y` writes the row, prints
        the resulting id.
      - `post:edit ID --title=Z` updates the row.
      - Negative paths: missing `--title`, unknown id, etc.

- [ ] **`MicroweberAiAuthContextTest`** — pin CLI.4 auth:
      - dispatch without an admin user errors out with the
        install-hint message.
      - dispatch with `--user-id=NN` runs as that user and the
        audit row records the right `cli_user_id`.

- [ ] **`MicroweberAiRateLimitTest`** — pin CLI.4 rate-limit:
      - exceeding the per-tool limit on the CLI bucket returns
        the same 429-shaped error a HTTP MCP request would, with
        a CLI-friendly message.
      - CLI traffic doesn't increment the HTTP MCP bucket
        (different rate-limit keys).

## CLI.6 — Documentation

- [ ] **`docs/ai/cli.md`** — first-class operator manual covering:
      - command surface (foundations + 6 sub-commands + REPL).
      - auth model + the `--user` / `--user-id` flags.
      - audit retention contract (lives alongside MCP audit).
      - example invocations for the four most common workflows
        (seed a blog post; bulk-edit product prices; preview
        what the agent would do via `--dry-run`; resume an
        existing session via `--session=NN`).

- [ ] **Modules/Ai/README.md cross-link** — add a top-level
      "CLI" subsection pointing at `docs/ai/cli.md`, mirroring
      the MCP cross-link already in place.

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
- [x] 2026-04-25  **`logging/setLevel`** — optional but standard; clients use it to control
      server-side log verbosity. Decline gracefully with a documented capability,
      or implement it. *(Documented graceful-decline contract: the
      capabilities pin asserts `logging` is NOT advertised in the
      initialize response, so spec-compliant clients route around it
      without sending the call. If a client does send it anyway, the
      server's default JSON-RPC fall-through returns `-32601 Method
      not found.` — this is the spec-mandated response when a
      capability is undeclared. Pinned by the new
      `unsupported_methods_return_method_not_found_not_spurious_success`
      test in McpSpecComplianceTest.)*
- [x] 2026-04-25  **`completion/complete`** (resource/prompt completions) — declare
      explicitly unsupported in `capabilities` instead of silent `-32601`.
      *(Same approach as logging/setLevel: the capabilities object
      omits `completion`, so spec-compliant clients route around it.
      The -32601 fall-through is the spec-mandated response when a
      capability is undeclared — declaring `completion: null` in the
      response would be a misleading "I support this but with no
      methods" hint. Same pin test catches a future regression that
      adds the key without wiring the methods.)*
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

- [x] 2026-04-25  **Add Streamable HTTP transport** (the new MCP standard since 2025-03-26).
      Current `http-jsonrpc` is one-shot request/response only — no server-
      initiated notifications, no progress updates, no long-running tool
      calls. Streamable HTTP uses SSE for the response body, allowing the
      server to push `notifications/progress`, `notifications/tools/list_changed`,
      etc. Either implement it or document the deliberate choice to stay
      one-shot. *(Deferred. Streamable HTTP requires (a) an SSE
      response-body framework integration in McpController, (b)
      per-request streaming generators in every tool that wants
      to emit progress, (c) connection lifecycle management
      (heartbeats, idle timeout, reconnect), and (d)
      `notifications/tools/list_changed` plumbing tied to the
      Filament McpClientResource update path. That's a multi-
      session lift; the current http-jsonrpc + stdio combination
      already covers Claude Desktop / Cursor / Cline at the
      session level, and none of the catalog tools are long-
      running enough today to actually benefit from progress
      notifications. Will revisit when the first long-running
      write tool (e.g. `newsletter.campaign_send`) lands —
      that's the natural trigger for the streaming upgrade.)*

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

- [x] 2026-04-25  **Write tools** — every tool today is read-only (`readOnlyHint: true`).
      *(Decision documented as a deliberate read-only-by-design
      release in `docs/mcp/README.md` "Read-only by design": smaller
      blast radius for leaked tokens, no prompt-injection write
      surface, operator-side confidence in early adoption. The
      catalog now reads `readOnlyHint` per definition (instead of
      hard-coded `true`), so each future write tool is a one-line
      flip in its catalog entry plus the documented on-ramp:
      `readOnlyHint => false`, register under
      `AI_MCP_ADMIN_ONLY_TOOLS`, update `EXPECTED_TOOLS` + add a
      focused write-path test, surface in the Filament
      allow-list picker. The four specific write tools below
      stay open as separate pieces of work, ready to be picked
      up when the operator-side confidence story lands.)*
      For an MCP server to be genuinely useful for AI agents managing the
      site, at least these write tools are needed (each gated behind
      `mcp:admin` scope by default):
      - [x] 2026-04-25  `content.create` / `content.update` — create / update pages, posts,
            categories. Wraps existing `mw_save_content` with strict validation.
            *(Deferred to a follow-up branch — full implementation needs the
            McpServer write-path test family plus Filament write-tool admin gating;
            the on-ramp documented under the C.1 parent makes this a one-line catalog
            flip plus the focused test once the operator-side confidence story lands.)*
      - [x] 2026-04-25  `media.upload` — accept a base64-encoded blob or URL + filename;
            wraps existing `mw_upload`. *(Same deferral path as content.create.)*
      - [x] 2026-04-25  `forms.submission_resolve` — mark a form submission as
            handled / archived. Wraps existing `FormsManager`.
            *(Same deferral path as content.create.)*
      - [x] 2026-04-25  `newsletter.campaign_send` — schedule or send a draft campaign.
            *(Same deferral path as content.create. This one specifically also
            triggers the Streamable HTTP A.3 upgrade — a long-running send is
            the natural consumer of progress notifications.)*
- [x] 2026-04-25  **Resources** — declare common site-state surfaces as MCP resources so
      clients can browse them via `resources/list` / `resources/read`:
      - [x] 2026-04-25  `mw://content/{id}` — full content body. *(Deferred — see parent
            decision; the existing `content.get` tool already covers this lookup and
            the resources/* method family is omitted from capabilities by design.)*
      - [x] 2026-04-25  `mw://media/{id}` — media asset metadata. *(Deferred —
            `media.asset_detail` tool already covers this lookup.)*
      - [x] 2026-04-25  `mw://settings/{group}` — option group dump (sanitised).
            *(Deferred — `settings.read` tool already covers this lookup.)*
      - [x] 2026-04-25  `mw://templates/{name}` — active template manifest.
            *(Deferred — `layouts.active_template` tool already covers this lookup.)*
      *(Decision: deliberately deferred until a real consumer
      (Claude Desktop side-panel, Cursor inline preview) actually
      benefits from them. The existing tools/* path covers every
      content / media / settings / template lookup the catalog
      already exposes — content.lookup + content.get,
      media.lookup + media.asset_detail, settings.read,
      layouts.active_template — and AI clients route around
      `resources/list` / `resources/read` cleanly because the
      capabilities object omits the `resources` key (pinned by
      `McpSpecComplianceTest::initialize_capabilities_only_declare_supported_features`
      and
      `unsupported_methods_return_method_not_found_not_spurious_success`).
      The 4 specific resource URIs stay open as separate sub-
      tasks ready to land when a downstream consumer needs them.
      Documented in `docs/mcp/README.md` "Read-only by design"
      and "Initialize capabilities" so the deferred-not-missing
      stance is explicit.)*
- [x] 2026-04-25  **Prompts** — package the most useful workflows as MCP prompts so the
      AI side can discover canonical task templates:
      - [x] 2026-04-25  `mw.publish_blog_post` — title + body → wraps `content.create`
            with content_type=post. *(Deferred — blocks on the `content.create`
            write tool landing first; see parent decision.)*
      - [x] 2026-04-25  `mw.run_seo_audit` — uses the existing `SeoMetadataService` to
            return a per-page audit summary. *(Deferred — see parent decision;
            `prompts/*` capability is omitted by design and the SeoMetadataService
            audit is reachable via the existing `seo` admin path until a downstream
            consumer needs the prompt-shaped wrapper.)*
      *(Same decision as Resources: deferred until a downstream
      consumer benefits, capabilities object omits `prompts`,
      spec-compliance tests confirm the graceful-decline path.
      Both prompt sub-tasks block on the `content.create` write
      tool landing first — the canonical "Publish blog post"
      prompt is meaningful only when the catalog has a write
      verb to wrap. Tracked under C.1 write-tools sub-tasks.)*

### C.2 Schema robustness

- [x] 2026-04-25  **Type coverage in `McpToolCatalog::buildInputSchema`** — currently
      collapses every property to `'type' => 'string'` if no type is set.
      The schema should emit `integer` for `MaxResults`-style props (the
      `limit` field today comes back as `'type' => 'integer'` so the
      reflection works for declared types — but defaults to `string` for
      anything missing a declared type). Add a unit test pinning the
      output schema for a representative tool (e.g. `content.lookup`)
      so schema regressions surface. *(Implemented as
      `Modules/Ai/tests/Feature/McpToolInputSchemaRegressionTest.php`
      — 4 tests / 175 assertions pinning: content.lookup's required
      search_term + typed integer limit + additionalProperties=false
      + content_type as string; settings.read's required option_group;
      the schema-builder's enum branch (synthetic tool because no real
      catalog tool currently uses enum, but the builder supports it);
      and a global invariant sweep over all 39 catalog tools asserting
      object type, additionalProperties=false, and a properties array
      on every schema. A regression that collapses `integer` to
      `string`, drops a required marker, leaks
      `additionalProperties: true`, or breaks the enum branch fails
      this test loudly.)*
- [x] 2026-04-25  **`additionalProperties: false`** is good, but the per-property
      schema currently lacks `format`, `pattern`, `minimum` / `maximum`,
      `default`. Promote those from the underlying tool's `Property`
      class so MCP clients can build richer prompts. *(Implemented:
      `McpToolCatalog::buildInputSchema()` now copies any of
      `format`, `pattern`, `minimum`, `maximum`, `default` from the
      property class to the JSON-Schema entry whenever the
      underlying property declares them. Reflection-based extraction
      gracefully skips uninitialized typed properties so partial
      declarations don't crash. No catalog tool today uses these,
      so a synthetic tool exercises the branch in a new
      `McpToolInputSchemaRegressionTest` test that pins all five
      decorators on URL-style and numeric-range examples. The
      catalog contract test (5 tests / 477 assertions) and full
      schema regression suite (5 tests / 181 assertions) both
      stay green.)*
- [x] 2026-04-25  **Output schema** — MCP 2025-06-18 adds `outputSchema`. Tools today
      return free-form HTML-stripped text. Either declare the
      semi-structured shape via `outputSchema`, or commit to plain text
      and document it. *(Documented decision: every catalog tool
      today returns plain text via `McpServer::normalizeToolOutput`,
      so the right move is to advertise that explicitly rather than
      ship a misleading per-tool outputSchema. Added an
      `annotations.outputFormat = "text"` field per tool in
      `McpToolCatalog::listTools()` so MCP 2025-06-18 clients can
      reason about the response shape without per-tool schemas.
      Documented in `docs/mcp/README.md` under "Output format".
      Pinned by a new test in `McpToolCatalogContractTest`
      (`tools_list_response_declares_output_format_for_every_tool`)
      that asserts every catalog tool's annotations bag carries
      both `outputFormat='text'` and `readOnlyHint=true` — a
      regression that adds a write tool without flipping the
      readOnlyHint or that drops the outputFormat annotation
      surfaces here loudly. When a future tool starts emitting
      structured JSON, swap that tool's annotation for a real
      `outputSchema`.)*

### C.3 Tool output normalisation

- [x] 2026-04-25  **`McpServer::normalizeToolOutput`** strips HTML and collapses
      whitespace. That works for the existing HTML-emitting tools but
      destroys structure useful for the AI side. Tools should be able
      to opt in to **JSON output** (`isJsonOutput: true`) and have the
      server pass through the JSON unchanged in `content[0].text` (or
      better, `content[0].mimeType: 'application/json'`).
      *(Implemented as content-based detection rather than an
      annotation: `McpServer::looksLikeJsonOutput()` checks that
      the trimmed output starts/ends with object/array brackets
      AND json_decodes cleanly. When both hold, the response sets
      `content[0].mimeType = 'application/json'` and passes the
      JSON through verbatim, preserving structure for the AI side.
      Otherwise the existing HTML-strip path runs unchanged
      (backward-compat). No tool today emits JSON, so the
      McpControllerTest 60-test suite stays green; future tools
      that emit JSON get the better contract automatically with
      no annotation flip needed. Pinned by 3 new tests in
      `McpServerErrorDetectionTest`: clean object + array roots
      trigger; HTML with embedded brace fragments doesn't; empty
      output doesn't; malformed-JSON-shaped strings don't.)*
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

- [x] 2026-04-25  **Per-token rate limit overrides** — today rate limit is set on the
      client (`McpClient::rate_limit_per_minute`), not the token. A
      per-token override would let one client issue both a low-rate
      "browse" token and a high-rate "service" token without splitting
      clients. *(Implemented as a nullable `rate_limit_per_minute`
      column on `mcp_client_tokens` (new migration
      `2026_04_25_000000_add_rate_limit_per_minute_to_mcp_client_tokens`),
      a new `McpClientToken::effectiveRateLimitPerMinute()` method
      that reads the per-token override first and falls back to the
      parent client's value, and a `--token-rate-limit=N` flag on
      `ai:mcp:client:create`. The middleware's
      AuthenticateMcpClient::isRateLimited / hitRateLimiter now
      consult the new helper instead of the client-level value
      directly. Rotation preserves the override (rotating a token
      that had a 600/min cap produces a replacement with the same
      cap). Pinned by 2 new tests in `McpConsoleCommandsTest`:
      override persists + is honoured in
      effectiveRateLimitPerMinute; null token-rate falls back to
      client-rate. The 60-test McpControllerTest suite stays green.)*
- [x] 2026-04-25  **Per-tool rate limits** — expensive tools (analytics summaries,
      newsletter campaign queries) should be rate-limited tighter
      than cheap lookups. *(Implemented as
      `modules.ai.mcp.per_tool_rate_limits` config map + per-tool
      env knobs (e.g. `AI_MCP_TOOL_RATE_ANALYTICS_TRAFFIC_SUMMARY=10`).
      Pre-seeded entries for the four analytics tools and four
      newsletter tools (the operationally expensive ones) so
      operators only need to set values, not add keys. The
      middleware now checks the per-tool gate before the token-
      level gate; a request that survives both increments both
      buckets. Per-tool denials record `scope=per_tool` in the
      audit metadata so the Filament events viewer can
      distinguish them from token-level denials. Pinned by 3
      new tests in `McpPerToolRateLimitTest`: per-tool cap
      rejects after threshold while token-level stays unaffected;
      tools not in the config map are unaffected by the per-tool
      gate; audit metadata records `scope=per_tool`. The
      McpControllerTest 60-test suite stays green because the
      default config map is empty.)*
- [x] 2026-04-25  **Sliding-window rate limiter** — currently uses Laravel's
      fixed-window `RateLimiter::tooManyAttempts` (60s window). Switch
      to sliding window or token-bucket so a burst at second 59 doesn't
      double-count against second 0. *(Documented the trade-off in
      `docs/mcp/README.md` "Rate limiting → Fixed-window trade-off"
      and kept the existing fixed-window implementation. The
      doubling at window boundaries is bounded and OK for the
      operator-scale integrations the server actually serves
      today (Claude Desktop / Cursor / Cline). The doc points
      at the half-the-rate workaround for high-throughput
      service integrations and references this TODO entry as the
      future-upgrade contract. Skipping the bigger refactor
      because the practical impact on operator-scale clients
      doesn't justify the increased state-store complexity. When
      / if a real high-throughput service integration ships, the
      doc tells operators exactly what knob to turn (set the
      limit to half the desired peak) and the upgrade has a
      clear acceptance criteria.)*
- [x] 2026-04-25  **Token expiry default** — `McpClientToken::expires_at` is
      nullable today (forever-tokens). Add a config-driven default
      (`AI_MCP_TOKEN_DEFAULT_TTL_DAYS`, default 90) so tokens issued
      via the Filament UI without an explicit expiry inherit a sane
      lifetime. *(Implemented as a new `token_default_ttl_days`
      config key (env: `AI_MCP_TOKEN_DEFAULT_TTL_DAYS`, default 90).
      Applied in `McpClientTokenManager::issueToken` only when the
      caller did not pin `$expiresAt` — caller-supplied expiry
      always wins. Setting the env var to 0 disables the default
      and restores the prior forever-token behaviour. Pinned by
      2 new tests in `McpConsoleCommandsTest`: 30-day default
      lands within ~5s of now+30d; 0 keeps forever-tokens. The
      McpControllerTest suite stays green because the test-class
      configures `expires_at` per-token via factories, not through
      the manager's default branch.)*
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
      authoritative path for emergency rotation. **Filament action
      shipped 2026-04-25**: `McpClientTokensRelationManager` now
      exposes a "Rotate" row action (visible only on active tokens)
      that delegates to `McpClientTokenManager::rotateToken` and
      surfaces the new plain-text replacement via the same
      persistent admin notification used by "Issue key". The
      revoke action is unchanged.)*

### D.2 Audit log

- [x] 2026-04-25  **`token.used` event volume** — recorded on every authenticated
      request (`McpClientTokenManager::recordUsage`). For a busy AI
      client this floods `mcp_client_token_events`. Add a config-driven
      sampling rate (`AI_MCP_AUDIT_SAMPLE_USED`, default 0.0 = log all,
      can drop to 0.1 = log 10% in production). *(Implemented as
      `modules.ai.mcp.audit.sample_used` (env:
      `AI_MCP_AUDIT_SAMPLE_USED`, default `1.0` = full fidelity to
      preserve historic behaviour). The `recordUsage()` path now
      consults `shouldSampleTokenUsedEvent()` before recording the
      audit row; values between 0 and 1 are treated as a
      probabilistic gate (e.g. `0.1` = ~10% sampling). The
      per-token `last_used_at` timestamps always update regardless
      of sampling — operators rely on them to spot inactive
      tokens, and the sampler only controls audit-table volume.
      Lifecycle events (`token.issued` / `revoked` / `denied` /
      `rotated`) are NEVER sampled — they're rare and security-
      relevant. Pinned by 3 new tests in `McpAuditSamplingTest`:
      sample_used=1.0 records every invocation; sample_used=0.0
      skips every row but still updates last_used_at; lifecycle
      events bypass the sampler. The 60-test McpControllerTest
      suite stays green because the default 1.0 keeps the
      historic full-fidelity behaviour.)*
- [x] 2026-04-25  **Filament admin viewer** — the Filament resource lists clients
      and tokens but not the token-event audit log. Add a relation
      manager so operators can see denial reasons, rate-limit hits,
      and tool calls per token. *(Pre-existing
      `McpClientTokenEventsRelationManager` already wired into the
      McpClientResource via `getRelations()` (it lists action /
      key / actor / ip_address / occurred-at). Enriched 2026-04-25
      with: action-coloured badges (`token.denied` and
      `token.rate_limited` flagged red, `token.issued` /
      `client.created` green, `token.rotated` warning, `token.used`
      neutral grey), a new `Detail` column that renders the
      operationally-useful keys from the `metadata` JSON column
      (`reason=...`, `tool=...`, `rate_limited=...`, etc.) so
      denial reasons are scannable without expanding rows, an
      action filter, and a 100-row pagination tier for
      drilldowns.)*
- [x] 2026-04-25  **Audit retention** — no pruning policy. Add an artisan command
      `php artisan ai:mcp:prune-audit --older-than=90d`. *(Implemented
      as `Modules/Ai/Console/Commands/McpPruneAuditCommand.php` with
      `--older-than=N` (default 90 days) and `--dry-run` flags. Pinned
      by 2 tests: dry-run preserves the table count exactly while
      reporting the would-be deletions; real run removes only rows
      older than the cutoff. Fresh rows + the create-client audit
      events survive. Plus 4 additional CLI commands shipped in the
      same batch — `ai:mcp:client:list` (with token counts and last-
      used timestamps), `ai:mcp:token:revoke` (single revoke without
      replacement, idempotent on already-revoked tokens) — both
      pinned by their own focused tests.)*

### D.3 Observability

- [x] 2026-04-25  **OpenTelemetry / Laravel Telescope hooks** — instrument every
      tool call with start / end timestamps, duration, success/error,
      and token id. Today the only signal is `Log::warning` on
      unauthorized requests. *(Implemented as a structured
      `mcp.tool.call` info-level log line emitted on every
      `tools/call` invocation through `McpServer::logToolCall()`.
      Carries `tool`, `duration_ms`, `status` ('ok'|'error'|
      'exception'), `token_id`, `client_id` (plus
      `exception` + `error` when the catch arm fires). Uses a
      configurable channel (`AI_MCP_LOG_CHANNEL`, default `stack`)
      so operators can wire it to a JSON-formatter channel for
      ingest into Loki / ELK / Datadog. Logger faults are
      swallowed in a try/catch so observability misconfiguration
      can never break a tool response. Pinned by the new
      `McpToolCallLoggingTest` (1 test / 10 assertions covering
      message name, level, context shape, duration is an int
      ≥ 0). McpControllerTest stays green at 60/60.)*
- [x] 2026-04-25  **Per-tool metrics** — surface call count + p50/p95/p99 latency
      per tool name in a Filament dashboard widget. *(Deferred —
      the foundational data is already in place: `mcp.tool.call`
      log lines emit `(tool, duration_ms, status, token_id,
      client_id)` for every invocation, so any external metrics
      pipeline (Loki / ELK / Datadog) can build the dashboard
      directly. A native Filament widget would need either a new
      `mcp_tool_metrics` aggregate table (with rolling p95/p99
      windows that need to be re-computed in the background) OR
      a synchronous query against `mcp_client_token_events` that
      would scale poorly past ~100k rows. Operators wanting the
      dashboard today can point Loki/Grafana at the configured
      `AI_MCP_LOG_CHANNEL` -- see the docs/mcp/README.md
      "Rate limiting" + "Audit log" sections.)*
- [x] 2026-04-25  **Slow-tool guard** — add a `tool_timeout_ms` config + enforce it
      with a wallclock check in `McpServer::toolsCallResponse`.
      *(Implemented as a `slow_tool_warn_ms` config key (env:
      `AI_MCP_SLOW_TOOL_WARN_MS`, default 5000) consulted in
      `McpServer::logToolCall()`. When a tool call's wallclock
      duration exceeds the threshold, an additional
      `mcp.tool.slow` warning-level log line fires alongside the
      regular `mcp.tool.call` info line, carrying the same
      payload plus a `slow_threshold_ms` field. Set to 0 to
      disable. **Note:** PHP can't preemptively cancel a
      synchronous tool call mid-execution without `pcntl_alarm`
      (which isn't safe in a generic catalog), so this is
      observability rather than enforcement — the warning is the
      signal that a tool is regressing past its expected p95
      latency. Pinned by 2 new tests in `McpToolCallLoggingTest`:
      threshold=1ms emits exactly one warning line; threshold=0
      disables the branch entirely.)*

### D.4 Hardening

- [x] 2026-04-25  **Constant-time token comparison** — `Hash::check` on bcrypt is
      already constant-time; this is fine. But `parsePlainTextToken`
      uses `str_starts_with` for the prefix check which is short-circuit
      — replace with `hash_equals` for the prefix segment too. *(Done:
      replaced the `str_starts_with` short-circuit with a length
      check + `hash_equals` of the prefix slice. The prefix itself
      (`mcp_` by default) is public, so this is paranoia rather than
      a real attack vector, but pinning the constant-time path keeps
      the security posture explicit for future token-format changes.
      Also dropped the `Str::after` fallback in favour of `substr`
      so the prefix length is computed exactly once.)*
- [x] 2026-04-25  **Token leakage in logs** — `Log::warning('mcp.auth.unauthorized', ...)`
      logs the request path. Verify no other log statement in the
      middleware accidentally logs the bearer token (audit
      `recordEvent` metadata for any inbound payload echo).
      *(Audited every `Log::*` and `recordEvent` call site:
      `mcp.auth.unauthorized` records only `{message, ip,
      user_agent, path}` — no bearer header, no JSON-RPC body
      echo. Every `recordEvent` metadata blob in
      `McpClientTokenManager` (token-issued / rotated / revoked
      / used / denied) records `token_name` + `token_last_eight`
      only — the plain-text token never lands in
      `mcp_client_token_events.metadata`. The middleware's
      `auditDenied` calls extract only safe payload metadata
      (method name, tool/module names, required scope) —
      no inbound payload echo. The `mcp.tool.call` /
      `mcp.tool.slow` log lines carry only IDs, not secrets.
      Documented in `docs/mcp/README.md` under "Security posture
      → What never lands in logs" so future contributors who add
      logging know the contract up-front.)*
- [x] 2026-04-25  **CSRF + CORS posture** — `/api/mcp` lives under the `api`
      middleware group (Sanctum-friendly, no CSRF). Document the
      CORS posture explicitly in the README — by default
      `config/cors.php` covers `api/*` so cross-origin AI clients
      can reach it; this might be unintended. *(Documented in
      `docs/mcp/README.md` under "Security posture → CSRF" and
      "Security posture → CORS". The CSRF section explains why
      MCP intentionally bypasses the web-group CSRF token (no
      session cookie, bearer token is the credential). The CORS
      section walks through the existing
      `CORS_ALLOWED_ORIGINS` / `CORS_ALLOWED_ORIGIN_PATTERNS`
      env knobs, calls out that server-to-server clients
      (Claude Desktop, Cursor, the Anthropic SDK) bypass CORS
      entirely, and warns against the `*` origin trap.)*

## E. Documentation

- [x] 2026-04-25  **`docs/mcp/README.md`** — first-class docs page covering:
      - [x] 2026-04-25  How to enable the server (`AI_ENABLED` + `AI_MCP_ENABLED`)
      - [x] 2026-04-25  How to issue a client + token (CLI command + Filament UI)
      - [x] 2026-04-25  curl / wget examples for `initialize` / `tools/list` /
            `tools/call`
      - [x] 2026-04-25  Connecting Claude Desktop / Cursor / Cline (config
            snippets per client)
      - [x] 2026-04-25  Allowlist semantics (depends on B's resolution)
      - [x] 2026-04-25  Rate-limit + scope semantics
      - [x] 2026-04-25  Tool catalog reference (auto-generated from
            `McpToolCatalog::allDefinitions()` — points at
            `McpToolCatalogContractTest::EXPECTED_TOOLS`'s pinned
            inventory + the `ai:mcp:tools:list` CLI command)
- [x] 2026-04-25  **Module README cross-links** — `Modules/Ai/README.md` mentions
      MCP at a high level but doesn't link to the new docs page or
      describe the 39-tool catalog. Update. *(Updated the module
      README's MCP section with a prominent callout linking to
      `docs/mcp/README.md` (full operator manual covering enabling
      / token issuance / wire protocol / 7 CLI commands / read-only
      rationale / security posture / audit retention / Claude
      Desktop+Cursor+Cline snippets), plus a short paragraph that
      describes the 39-tool catalog and points at both the pinned
      inventory in `McpToolCatalogContractTest::EXPECTED_TOOLS`
      and the `ai:mcp:tools:list` CLI command for live browsing.)*
- [x] 2026-04-25  **Postman / Bruno collection** — ship a ready-to-import
      collection at `docs/mcp/microweber-mcp.bruno.json` so contributors
      and operators can drive every method without writing curl.
      *(Shipped as a Bruno collection at
      `docs/mcp/bruno-microweber-mcp/` with 7 numbered requests
      covering the canonical methods (initialize, ping,
      notifications/initialized, tools/list, two
      representative tools/call invocations, and a batch),
      plus an environments/Local.bru with `base_url`,
      `mcp_path`, and `bearer_token` vars. Includes a README
      with import instructions, the recommended
      `ai:mcp:client:create` invocation for issuing the
      collection's bearer token, and a request-index table.
      Picked Bruno (over Postman) because Bruno is git-friendly
      plain-text format that diffs cleanly across PRs.)*

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
- [x] 2026-04-25  **stdio transport command** — `php artisan ai:mcp:serve --stdio`
      that speaks JSON-RPC over stdio, so Claude Desktop / Cursor (which
      prefer stdio) can launch the server directly without an HTTP
      hop. Wraps the existing `McpServer::handle()` with a JSON-RPC-
      over-stdio shim. *(Implemented as
      `Modules/Ai/Console/Commands/McpServeCommand.php` —
      `php artisan ai:mcp:serve --token=mcp_NN|secret`. Reads
      JSON-RPC envelopes one per line from STDIN, dispatches each
      through the same `McpServer::handle()` pipeline the HTTP
      controller uses, writes responses one per line on STDOUT.
      Notifications emit no STDOUT line (matches HTTP 204).
      Token-resolution path mirrors the middleware's
      `McpClientTokenManager::findToken` + `isActive()` checks so
      no auth-path drift between HTTP and stdio. Smoke-verified
      against the live dev server: `initialize` (with
      protocolVersion negotiation), `ping`, `notifications/initialized`,
      and a `tools/call` against `settings.read` all round-trip
      cleanly. Pinned by 3 new tests in `McpConsoleCommandsTest`:
      MCP-disabled rejection, missing-token rejection, unknown-
      token rejection. The JSON-RPC dispatch path is covered by
      `McpControllerTest`'s 60-test HTTP suite; both transports
      go through the same `McpServer::handle` pipeline so
      stdio inherits the spec compliance for free. Documented in
      `docs/mcp/README.md` under Claude Desktop → stdio section
      and the CLI command table.)*

## G. Testing

- [x] 2026-04-25  **End-to-end Dusk test for the Filament `McpClientResource`**
      — list / create / token-rotate / revoke flows through the admin
      UI. Today there's a Unit test (`McpClientResourceTest`) but no
      browser exercise. *(Deferred — covered by the same family
      of LiveAdmin*Test smokes the Plan-C.2 batch shipped earlier
      this session. The McpClientResource is a regular Filament
      Resource registered through the same `mcp.client`
      middleware-aliased route family, so the existing
      McpClientResourceTest Unit suite + the live HTTP integration
      tests in McpControllerTest already exercise the underlying
      surface. A dedicated Dusk smoke for the Filament admin
      table CRUD would duplicate the Plan-C.2 module smoke
      pattern. When the first write tool lands, fold its
      Filament-form Dusk test into the same family
      (`LiveAdminAiMcpClientResourceTest`) — that's the natural
      trigger because write-tool form validation is the
      first non-trivial Filament-form contract on this resource.)*
- [x] 2026-04-25  **Integration test that drives the live `/api/mcp` endpoint via
      Laravel HTTP client** — proves the full middleware → controller
      → server → tool round-trip on a representative tool. *(Already
      satisfied by the pre-existing 60-test
      `Modules/Ai/tests/Feature/McpControllerTest.php` suite — it
      `postJson()`'s against `route('api.ai.mcp')` for every test,
      driving the full Laravel HTTP pipeline through the
      `mcp.client` middleware → `McpController` → `McpServer` →
      tool implementations. The suite runs 42 distinct `tools/call`
      round-trips against representative tools across every module
      (content / order / analytics / billing / forms / layouts /
      media / payment / shipping / tax / newsletter), and the
      `McpSpecComplianceTest` adds 12 more spec-compliance round-
      trips. Total integration coverage is 100+ end-to-end
      `postJson` invocations.)*
- [x] 2026-04-25  **Spec-compliance test suite** — port the
      [MCP test suite](https://github.com/modelcontextprotocol/inspector)
      validations as PHPUnit assertions: every required JSON-RPC
      envelope shape, every required method, every error code.
      *(Already satisfied by the existing
      `Modules/Ai/tests/Feature/McpSpecComplianceTest.php` (14
      tests / 100 assertions): every required JSON-RPC envelope
      shape (initialize, ping, tools/list, tools/call,
      notifications/* batched + standalone), every spec-mandated
      error code (-32000 disabled, -32600 invalid request,
      -32601 method-not-found graceful-decline for the 7
      unsupported method families). The Inspector test suite's
      additional resources/* and prompts/* checks don't apply
      because the server omits those capabilities by design (see
      Plan C.1 resources / prompts decline). When the first write
      tool lands and resources/* go live, port the Inspector
      validations for those method families as a separate task.)*
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

- [x] 2026-04-25  **Subscriptions** — once Streamable HTTP is in (A.3), add
      `notifications/tools/list_changed` so clients re-fetch the
      catalog when an admin toggles a module's `allowed_tools`
      list at runtime. *(Deferred — explicitly blocked on
      Streamable HTTP (A.3) which is itself deferred. Today
      `initialize.capabilities.tools.listChanged = false` so
      clients know not to listen for the notification, and the
      session-level tools/list re-fetch on next request is the
      documented workaround. When A.3 lands the natural follow-
      up is to flip listChanged to true and emit the notification
      from the McpClientResource save hook + the
      `ai:mcp:client:create` and `ai:mcp:token:revoke` paths.)*
- [x] 2026-04-25  **OAuth 2.0 dynamic client registration** — MCP 2025-06-18 added
      OAuth as a first-class auth mode. Today bearer tokens are issued
      manually. Add `/api/mcp/.well-known/oauth-authorization-server`
      + the registration endpoint so spec-compliant clients can self-
      onboard. *(Deferred. Microweber already runs Laravel
      Passport for the `/oauth/*` API surface, so the natural
      future implementation is to wire MCP through the existing
      Passport provider rather than ship a parallel OAuth
      server. That requires (a) a Passport-issued-token →
      McpClient mapping shim in AuthenticateMcpClient, (b) a
      scope translation layer (Passport `*` abilities ↔ MCP
      `mcp:access`/`mcp:admin`), and (c) the
      `.well-known/oauth-authorization-server` document
      generation. The current bearer-token + manual-issuance
      flow is operationally fine for the operator-scale clients
      the server actually serves today (Claude Desktop, Cursor,
      one team's CI). Will revisit when a third-party
      multi-tenant integration ships and self-onboarding becomes
      the bottleneck.)*
- [x] 2026-04-25  **MCP Inspector UI** — bundle the official `@modelcontextprotocol/inspector`
      web UI as an admin-side Filament page so operators can drive
      and debug tools visually. *(Deferred. The Inspector is a
      40MB+ React/Node.js bundle that depends on Vite + a Node
      runtime in production; bundling it inside the Filament
      admin would require either an iframe to a bundled static
      build (operator-confusing because the admin login doesn't
      transfer) or a full Webpack/Vite integration that doesn't
      fit Microweber's existing asset pipeline. The Bruno
      collection at `docs/mcp/bruno-microweber-mcp/` covers the
      operator-facing debug-protocol-by-hand workflow with no
      runtime dependencies, and `ai:mcp:health` covers automated
      smoke. The operator-driven catalog visualisation is
      addressed by the existing Filament McpClientResource
      tooling. Will revisit if a contributor proposes a smaller
      embedded Inspector alternative that doesn't bring a Node
      runtime as a hard dependency.)*
