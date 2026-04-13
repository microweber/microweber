# Ai

## Run module migrations

```sh
php artisan module:migrate Ai
```

## Publish module assets

```sh
php artisan module:publish Ai
```

### module config values

```php
config('modules.ai.name')
```

### Module views

Extend master layout

```php
@extends('modules.ai::layouts.master')
```

Use Module view

```php
view('modules.ai::example')
```

## Chat Completion with Functions Support

The AI module provides a flexible chat completion interface with support for OpenAI functions. Here's how to use it:

```php
$messages = [
    ['role' => 'system', 'content' => 'You are a helpful assistant that can schedule tasks.'],
    ['role' => 'user', 'content' => 'I need help planning my week.']
];

$response = $aiService->sendToChat($messages, [
    'functions' => [
        [
            'name' => 'llm_functions_scheduling',
            'description' => 'Helper for scheduling tasks in personal calendar',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'tasks' => [
                        'type' => 'array',
                        'description' => 'List of tasks to schedule',
                        'items' => [
                            'date' => 'string',
                            'description' => 'string'
                        ]
                    }
                ],
                'required' => ['tasks']
            ]
        ]
    ],
    'function_call' => 'auto', // Optional: force specific function
    'model' => 'gpt-3.5-turbo-0613', // Optional
    'temperature' => 0, // Optional
    'max_tokens' => 1000 // Optional
]);

// Handle function call response
if (is_array($response) && isset($response['function_call'])) {
    $functionName = $response['function_call']->name;
    $arguments = json_decode($response['function_call']->arguments, true);
    
    // Execute the function and get result
    $result = call_user_func($functionName, $arguments);
    
    // Continue the conversation with the function result
    $messages[] = [
        'role' => 'assistant',
        'content' => null,
        'function_call' => [
            'name' => $functionName,
            'arguments' => json_encode($arguments)
        ]
    ];
    $messages[] = [
        'role' => 'function',
        'name' => $functionName,
        'content' => json_encode($result)
    ];
    
    // Get final response
    $finalResponse = $aiService->sendToChat($messages);
}
```

### Message Format

Messages should be provided as an array of message objects, each with:
- `role`: One of 'system', 'user', 'assistant', or 'function'
- `content`: The message content
- `name`: Required for function messages, the name of the function that was called
- `function_call`: Optional for assistant messages, contains function call details

### Available Options

- `functions`: Array of function definitions that the AI can call
- `function_call`: Optional parameter to force a specific function call
- `model`: OpenAI model to use (default: gpt-3.5-turbo)
- `temperature`: Sampling temperature (default: 0.7)
- `max_tokens`: Maximum tokens in response (default: 1000)

## MCP endpoint

The first MCP surface lives inside `Modules/Ai` and is exposed as a JSON-RPC endpoint at `POST /api/mcp`.

Authentication model:

- MCP uses dedicated MCP client bearer tokens issued from the `mcp_client_tokens` table.
- Clients must send `Authorization: Bearer <token>`.
- Tokens are shown once on creation, stored hashed at rest, and must include the configured required scopes (default: `mcp:access`).
- Middleware now verifies token hashes, checks client scope/tool/module access, enforces per-client rate limits, updates last-used metadata, and logs MCP audit events.
- Existing Passport/OpenAPI integration remains untouched and can continue serving OAuth/OpenAPI documentation concerns separately; MCP transport auth now uses the dedicated MCP client-token model.

Secret storage:

- AI provider API keys can be backed by the Unix `pass` password store.
- When `AI_SECRET_STORE_ENABLED=true`, provider secrets are persisted as `pass://...` references in options storage and resolved through `pass` at runtime.
- Legacy plaintext option values are migrated to `pass` references the next time AI config is loaded while the secret store is enabled.

MCP client model:

- MCP clients are now modeled in the database via `mcp_clients`, `mcp_client_tokens`, and `mcp_client_token_events`.
- Client records carry allowed scopes, allowed tools, allowed modules, per-client rate limits, last-used timestamps, and revocation state.
- MCP tokens are shown once when issued, stored hashed at rest, support rotation/revocation, and write audit events for creation, use, rotation, and revocation.
- Admins can manage MCP clients from the Filament `McpClientResource`, including issuing/revoking keys, testing MCP health, and checking whether expected `pass://...` provider references exist without exposing secret values.

Current supported methods:

- `initialize`
- `tools/list`
- `tools/call`

Initial MCP tools:

- `content.lookup`
- `content.get`
- `product.lookup`
- `order.lookup`
- `settings.read`
- `media.lookup`
- `media.asset_detail`
- `media.storage_health`
- `analytics.traffic_summary`
- `analytics.top_pages`
- `analytics.traffic_referrers`
- `analytics.audience_breakdown`
- `forms.form_lookup`
- `forms.submission_search`
- `forms.submission_detail`
- `forms.activity_summary`
- `billing.subscription_lookup`
- `billing.plan_summary`
- `billing.account_status`
- `billing.metrics_summary`
- `billing.invoice_lookup`
- `billing.invoice_detail`
- `billing.invoice_unpaid_summary`
- `billing.invoice_customer_history`
- `billing.payment_lookup`
- `billing.payment_detail`
- `billing.payment_provider_health`
- `billing.payment_webhook_health`
- `shipping.method_lookup`
- `shipping.zone_summary`
- `tax.rule_lookup`
- `tax.preview`
- `newsletter.campaign_lookup`
- `newsletter.subscriber_lookup`
- `newsletter.template_lookup`
- `newsletter.automation_status`

### MCP contract

Transport:

- Endpoint: `POST /api/mcp`
- Content type: `application/json`
- Protocol envelope: JSON-RPC 2.0
- Authentication: `Authorization: Bearer <mcp client token>`

Base request shape:

```json
{
  "jsonrpc": "2.0",
  "id": "request-1",
  "method": "initialize",
  "params": {}
}
```

Validation rules currently enforced by `Modules/Ai/Http/Requests/McpRequest.php`:

- `jsonrpc` is required and must be `"2.0"`.
- `method` is required and must be a string.
- `params` is optional and must be an object/associative array when present.
- `params.name` is required for `tools/call`.
- `params.arguments` is optional for `tools/call` and must be an object/associative array when present.

#### `initialize`

Example request:

```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "method": "initialize",
  "params": {
    "clientInfo": {
      "name": "my-mcp-client",
      "version": "1.0.0"
    }
  }
}
```

Example response:

```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "result": {
    "protocolVersion": "2025-03-26",
    "serverInfo": {
      "name": "Microweber AI MCP",
      "version": "0.1.0"
    },
    "capabilities": {
      "tools": {
        "listChanged": false
      }
    },
    "transport": "http-jsonrpc"
  }
}
```

#### `tools/list`

Example request:

```json
{
  "jsonrpc": "2.0",
  "id": "tools-1",
  "method": "tools/list"
}
```

Example response shape:

```json
{
  "jsonrpc": "2.0",
  "id": "tools-1",
  "result": {
    "tools": [
      {
        "name": "content.lookup",
        "description": "Search Microweber content by keyword and type.",
        "inputSchema": {
          "type": "object",
          "properties": {
            "search_term": {
              "type": "string",
              "description": "Search term to find in content titles, descriptions, or content body. Use keywords related to the content you are looking for."
            },
            "content_type": {
              "type": "string",
              "description": "Type of content to search for. Options: \"page\", \"post\", \"product\", \"category\", or \"all\" for all types."
            },
            "limit": {
              "type": "integer",
              "description": "Maximum number of results to return (1-50). Default is 10."
            }
          },
          "required": [
            "search_term"
          ],
          "additionalProperties": false
        },
        "annotations": {
          "module": "content",
          "domain": "content",
          "readOnlyHint": true
        }
      }
    ]
  }
}
```

Notes:

- The returned tool list is filtered by the authenticated MCP client's allowed tools/modules.
- Tools that require the configured admin scope are hidden when the token does not include that scope.

#### `tools/call`

Example request:

```json
{
  "jsonrpc": "2.0",
  "id": 14,
  "method": "tools/call",
  "params": {
    "name": "content.lookup",
    "arguments": {
      "search_term": "MCP Knowledge",
      "content_type": "page"
    }
  }
}
```

Example success response:

```json
{
  "jsonrpc": "2.0",
  "id": 14,
  "result": {
    "content": [
      {
        "type": "text",
        "text": "Content Search Results..."
      }
    ],
    "isError": false
  }
}
```

Example tool-level error response:

```json
{
  "jsonrpc": "2.0",
  "id": 18,
  "result": {
    "content": [
      {
        "type": "text",
        "text": "The setting 'openai_api_key' is sensitive and cannot be read over MCP."
      }
    ],
    "isError": true
  }
}
```

Notes:

- Tool output is normalized into plain text before returning to the client.
- `isError: true` means the tool executed but returned an application/tool error.
- JSON-RPC `error` is reserved for protocol/server-level failures such as unknown methods or missing execution context.

#### Current tool argument schemas

`content.lookup`

```json
{
  "type": "object",
  "properties": {
    "search_term": { "type": "string" },
    "content_type": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "required": ["search_term"],
  "additionalProperties": false
}
```

`content.get`

```json
{
  "type": "object",
  "properties": {
    "content_id": { "type": "integer" },
    "include_meta": { "type": "string" }
  },
  "required": ["content_id"],
  "additionalProperties": false
}
```

`product.lookup`

```json
{
  "type": "object",
  "properties": {
    "search_term": { "type": "string" },
    "min_price": { "type": "number" },
    "max_price": { "type": "number" },
    "category": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`order.lookup`

```json
{
  "type": "object",
  "properties": {
    "search_term": { "type": "string" },
    "status": { "type": "string" },
    "date_from": { "type": "string" },
    "date_to": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`settings.read`

```json
{
  "type": "object",
  "properties": {
    "option_group": { "type": "string" },
    "option_key": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "required": ["option_group"],
  "additionalProperties": false
}
```

`analytics.traffic_summary`

```json
{
  "type": "object",
  "properties": {
    "period": { "type": "string" }
  },
  "additionalProperties": false
}
```

`analytics.top_pages`

```json
{
  "type": "object",
  "properties": {
    "period": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`analytics.traffic_referrers`

```json
{
  "type": "object",
  "properties": {
    "period": { "type": "string" },
    "limit": { "type": "integer" },
    "include_internal": { "type": "string" }
  },
  "additionalProperties": false
}
```

`analytics.audience_breakdown`

```json
{
  "type": "object",
  "properties": {
    "period": { "type": "string" },
    "limit": { "type": "integer" },
    "breakdown": { "type": "string" }
  },
  "additionalProperties": false
}
```

`forms.form_lookup`

```json
{
  "type": "object",
  "properties": {
    "form_id": { "type": "integer" },
    "search_term": { "type": "string" },
    "list_id": { "type": "integer" },
    "is_active": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`forms.submission_search`

```json
{
  "type": "object",
  "properties": {
    "form_id": { "type": "integer" },
    "search_term": { "type": "string" },
    "read_status": { "type": "string" },
    "date_from": { "type": "string" },
    "date_to": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`forms.submission_detail`

```json
{
  "type": "object",
  "properties": {
    "submission_id": { "type": "integer" }
  },
  "required": ["submission_id"],
  "additionalProperties": false
}
```

`forms.activity_summary`

```json
{
  "type": "object",
  "properties": {
    "form_id": { "type": "integer" },
    "period": { "type": "string" }
  },
  "additionalProperties": false
}
```

`billing.subscription_lookup`

```json
{
  "type": "object",
  "properties": {
    "search_term": { "type": "string" },
    "status": { "type": "string" },
    "include_canceled": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`billing.plan_summary`

```json
{
  "type": "object",
  "properties": {
    "group_sku": { "type": "string" },
    "include_inactive": { "type": "string" },
    "currency": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`billing.account_status`

```json
{
  "type": "object",
  "properties": {
    "customer_id": { "type": "integer" },
    "user_id": { "type": "integer" },
    "search_term": { "type": "string" }
  },
  "additionalProperties": false
}
```

`billing.metrics_summary`

```json
{
  "type": "object",
  "properties": {
    "period_days": { "type": "integer" },
    "include_breakdown": { "type": "string" }
  },
  "additionalProperties": false
}
```

`billing.invoice_lookup`

```json
{
  "type": "object",
  "properties": {
    "invoice_id": { "type": "integer" },
    "search_term": { "type": "string" },
    "status": { "type": "string" },
    "paid_status": { "type": "string" },
    "date_from": { "type": "string" },
    "date_to": { "type": "string" },
    "overdue_only": { "type": "string" },
    "customer_id": { "type": "integer" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`billing.invoice_detail`

```json
{
  "type": "object",
  "properties": {
    "invoice_id": { "type": "integer" },
    "invoice_number": { "type": "string" }
  },
  "additionalProperties": false
}
```

`billing.invoice_unpaid_summary`

```json
{
  "type": "object",
  "properties": {
    "overdue_only": { "type": "string" },
    "days_past_due": { "type": "integer" },
    "customer_id": { "type": "integer" },
    "sort_by": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`billing.invoice_customer_history`

```json
{
  "type": "object",
  "properties": {
    "customer_id": { "type": "integer" },
    "search_term": { "type": "string" },
    "include_paid": { "type": "string" },
    "months_back": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`billing.payment_lookup`

```json
{
  "type": "object",
  "properties": {
    "payment_id": { "type": "integer" },
    "search_term": { "type": "string" },
    "status": { "type": "string" },
    "provider": { "type": "string" },
    "rel_type": { "type": "string" },
    "date_from": { "type": "string" },
    "date_to": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`billing.payment_detail`

```json
{
  "type": "object",
  "properties": {
    "payment_id": { "type": "integer" },
    "transaction_id": { "type": "string" }
  },
  "additionalProperties": false
}
```

`billing.payment_provider_health`

```json
{
  "type": "object",
  "properties": {
    "provider": { "type": "string" },
    "period_days": { "type": "integer" },
    "include_breakdown": { "type": "string" }
  },
  "additionalProperties": false
}
```

`billing.payment_webhook_health`

```json
{
  "type": "object",
  "properties": {
    "provider": { "type": "string" },
    "status": { "type": "string" },
    "period_days": { "type": "integer" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`media.lookup`

```json
{
  "type": "object",
  "properties": {
    "media_id": { "type": "integer" },
    "search_term": { "type": "string" },
    "file_type": { "type": "string" },
    "folder_id": { "type": "integer" },
    "is_synced_to_cdn": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`media.asset_detail`

```json
{
  "type": "object",
  "properties": {
    "media_id": { "type": "integer" },
    "include_metadata": { "type": "string" }
  },
  "required": ["media_id"],
  "additionalProperties": false
}
```

`media.storage_health`

```json
{
  "type": "object",
  "properties": {
    "path": { "type": "string" },
    "include_webp_cache": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`shipping.method_lookup`

```json
{
  "type": "object",
  "properties": {
    "provider_id": { "type": "integer" },
    "search_term": { "type": "string" },
    "provider": { "type": "string" },
    "is_active": { "type": "string" },
    "is_default": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`shipping.zone_summary`

```json
{
  "type": "object",
  "properties": {
    "provider_id": { "type": "integer" },
    "provider": { "type": "string" },
    "country": { "type": "string" },
    "include_inactive_zones": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`tax.rule_lookup`

```json
{
  "type": "object",
  "properties": {
    "rule_id": { "type": "integer" },
    "search_term": { "type": "string" },
    "country_code": { "type": "string" },
    "is_active": { "type": "string" },
    "include_legacy": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`tax.preview`

```json
{
  "type": "object",
  "properties": {
    "amount": { "type": "string" },
    "country_code": { "type": "string" },
    "state_code": { "type": "string" },
    "city": { "type": "string" },
    "zip_code": { "type": "string" }
  },
  "required": ["amount"],
  "additionalProperties": false
}
```

`newsletter.campaign_lookup`

```json
{
  "type": "object",
  "properties": {
    "campaign_id": { "type": "integer" },
    "search_term": { "type": "string" },
    "status": { "type": "string" },
    "campaign_type": { "type": "string" },
    "trigger_event": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`newsletter.subscriber_lookup`

```json
{
  "type": "object",
  "properties": {
    "subscriber_id": { "type": "integer" },
    "search_term": { "type": "string" },
    "status": { "type": "string" },
    "list_id": { "type": "integer" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`newsletter.template_lookup`

```json
{
  "type": "object",
  "properties": {
    "template_id": { "type": "integer" },
    "search_term": { "type": "string" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

`newsletter.automation_status`

```json
{
  "type": "object",
  "properties": {
    "view": { "type": "string" },
    "status": { "type": "string" },
    "trigger_event": { "type": "string" },
    "workflow_id": { "type": "integer" },
    "limit": { "type": "integer" }
  },
  "additionalProperties": false
}
```

Media-specific safety notes:

- `media.*` tools are read-only and keep the first rollout focused on asset discovery, per-asset summaries, and aggregate storage health.
- `media.lookup` and `media.asset_detail` return relative media paths, folder labels, and metadata-key summaries only; they intentionally omit raw filesystem paths, direct CDN URLs, and large metadata blobs.
- `media.storage_health` stays aggregate-only, summarizes public-disk usage and media folder distribution safely, and does not expose write permissions or destructive file-manager actions.

Analytics-specific safety notes:

- `analytics.*` tools are read-only and currently expose aggregated reporting data only.
- `analytics.traffic_referrers` summarizes referrers by domain/path instead of returning raw external referrer URLs.
- The analytics period contract intentionally follows the Site Stats dashboard windows: `daily` = last 30 days, `weekly` = last 12 weeks, `monthly` = last 12 months, `yearly` = last 5 years.

Forms-specific safety notes:

- `forms.*` tools are read-only and normalize both legacy `form_values` JSON submissions and newer `forms_data_values` rows.
- `forms.submission_search` and `forms.submission_detail` mask emails, phone numbers, names, source IPs, and uploaded file paths before returning data over MCP.
- `forms.activity_summary` only exposes aggregated submission counts and unread backlog, while `forms.form_lookup` reports recipient counts instead of raw notification addresses.

Billing-specific safety notes:

- `billing.*` tools are read-only and keep the first rollout focused on subscriptions, plans, account state, and aggregate metrics rather than invoices or raw payment/webhook data.
- `billing.subscription_lookup` and `billing.account_status` mask customer emails, payment method last-four details, and provider subscription IDs before returning data over MCP.
- `billing.metrics_summary` is intended to be admin-only via the existing `modules.ai.mcp.auth.admin_only_tools` / `admin_only_modules` config, and only exposes aggregate MRR/churn/count metrics.
- `billing.invoice_lookup`, `billing.invoice_detail`, and `billing.invoice_customer_history` mask customer email addresses and avoid exposing guest `unique_hash` tokens, raw webhook payloads, or payment-provider internals.
- `billing.invoice_unpaid_summary` stays read-only and limits invoice reporting to aging, balances, and customer history rather than payment execution or invoice delivery actions.
- `billing.payment_lookup` and `billing.payment_detail` expose transaction, provider, relation, and status summaries only; they intentionally omit `payment_data`, provider `settings`, API keys, and full payment-instrument details.
- `billing.payment_provider_health` and `billing.payment_webhook_health` stay aggregate/read-only, summarize provider and webhook status safely, and sanitize leaked-looking secrets or long card-number-like digit sequences in error text.

Shipping-specific safety notes:

- `shipping.*` tools are read-only and summarize configured providers and country-zone pricing without returning raw `settings` blobs or checkout-facing instruction text.
- `shipping.method_lookup` reports driver names, default/active flags, and compact configuration summaries instead of the full provider form payload.
- `shipping.zone_summary` limits output to country-level pricing rules, cost caps, and rule types so support teams can inspect configuration safely.

Tax-specific safety notes:

- `tax.*` tools are read-only and expose rule metadata and preview calculations only; they do not toggle tax settings or mutate rules.
- `tax.rule_lookup` summarizes both modern `tax_rates` and legacy `tax_types` fallback rules so operators can understand which calculation path is active.
- `tax.preview` only evaluates a caller-provided subtotal/location and returns the matched rule breakdown; it does not persist carts, addresses, or checkout state.

Newsletter-specific safety notes:

- `newsletter.subscriber_lookup` intentionally masks subscriber email addresses in MCP output.
- `newsletter.automation_status` reports queue/workflow health without exposing raw event payloads or unsanitized secrets from automation errors.

#### Auth header and token format

- Clients must send `Authorization: Bearer <token>`.
- Tokens are issued from the MCP admin UI and are shown exactly once.
- The persisted token format is prefixed with `AI_MCP_CLIENT_TOKEN_PREFIX` (default: `mcp_`) and currently looks like:

```text
mcp_{token_id}|{random_secret}
```

- The database stores only a hash plus the last eight characters for display/audit.

#### Rotation and revocation procedure

Recommended rotation flow:

1. Issue a new key for the same MCP client from the Filament `McpClientResource`.
2. Update the consuming MCP client to use the new bearer token.
3. Confirm `initialize` and `tools/list` succeed with the new key.
4. Revoke the previous key from the client token relation manager.

Revocation semantics:

- Revoked or expired tokens return `401 Unauthorized`.
- Revocation writes a `token.revoked` audit event.
- Rotating a token revokes the old token and writes both revoke/rotate audit events.

#### Error semantics

Transport/auth middleware errors return normal HTTP error responses:

- `401 Unauthorized`
  - missing bearer token
  - invalid token
  - revoked/inactive token
- `403 Forbidden`
  - missing required scope
  - tool not allowed for the client
  - module not allowed for the client
  - missing admin scope for admin-only tools/modules
- `429 Too Many Requests`
  - per-client MCP rate limit exceeded

Protocol/server errors return JSON-RPC `error` objects:

- `-32000` — MCP server is disabled
- `-32601` — JSON-RPC method not found
- `-32602` — MCP tool not found
- `-32603` — server-side execution failure or missing MCP request context

Current non-JSON-RPC middleware error shape:

```json
{
  "error": "Forbidden",
  "message": "MCP token does not have the required scope."
}
```

Rate-limit error shape:

```json
{
  "error": "Too many requests",
  "message": "MCP client rate limit exceeded.",
  "retry_after": 42
}
```

#### `pass` reference examples relevant to the contract

- `pass://microweber/local/ai/openai`
- `pass://microweber/production/ai/anthropic`

These references are not returned by MCP tool calls, but they are part of the operational contract for AI provider secret resolution and are surfaced as health-only metadata in the MCP admin UI.

#### OpenAPI note

The MCP endpoint is currently documented here as the source-of-truth contract for the Microweber implementation. If/when MCP is mirrored into `Modules/OpenApi`, this section should be treated as the baseline for request envelopes, auth headers, tool schemas, and error semantics.

Configuration is available under `config('modules.ai.mcp')` and can be controlled with:

- `AI_MCP_ENABLED`
- `AI_MCP_ENDPOINT`
- `AI_MCP_TRANSPORT`
- `AI_MCP_PROTOCOL_VERSION`
- `AI_MCP_SERVER_NAME`
- `AI_MCP_SERVER_VERSION`
- `AI_MCP_CLIENT_TOKEN_PREFIX`
- `AI_MCP_REQUIRE_ADMIN`
- `AI_MCP_REQUIRED_ABILITIES`
- `AI_MCP_ADMIN_SCOPE`
- `AI_MCP_ADMIN_ONLY_TOOLS`
- `AI_MCP_ADMIN_ONLY_MODULES`
- `AI_SECRET_STORE_DRIVER`
- `AI_SECRET_STORE_ENABLED`
- `AI_SECRET_STORE_BINARY`
- `AI_SECRET_STORE_PATH_PREFIX`
- `AI_SECRET_STORE_ENV`
- `AI_SECRET_STORE_DIR`

### MCP deployment and `pass` bootstrap

Server/runtime prerequisites:

- PHP must be able to execute the configured `pass` binary (`AI_SECRET_STORE_BINARY`, default: `pass`).
- The host must have both `gnupg` and `pass` installed.
- The runtime user that executes PHP / queue workers must have access to the GPG key that decrypts the password store.
- If you want to isolate MCP secrets from the default password store, set `AI_SECRET_STORE_DIR` to a dedicated directory and ensure the same path is available to web and queue processes.

Typical Linux bootstrap flow:

```bash
sudo apt-get update
sudo apt-get install -y gnupg pass

# Optional: keep Microweber secrets in a dedicated store
export PASSWORD_STORE_DIR=/var/lib/microweber/pass-store
mkdir -p "$PASSWORD_STORE_DIR"
chown -R www-data:www-data "$PASSWORD_STORE_DIR"

# Import the server-side private key that should decrypt secrets
sudo -u www-data gpg --batch --import /secure/location/microweber-mcp-private.key

# Initialize pass for the matching key identity or fingerprint
sudo -u www-data env PASSWORD_STORE_DIR="$PASSWORD_STORE_DIR" pass init "Microweber MCP <ops@example.com>"
```

Local/dev bootstrap example:

```bash
export AI_SECRET_STORE_ENABLED=true
export AI_SECRET_STORE_DRIVER=pass
export AI_SECRET_STORE_BINARY=pass
export AI_SECRET_STORE_PATH_PREFIX=microweber
export AI_SECRET_STORE_ENV=local

# Optional custom store
export AI_SECRET_STORE_DIR="$PWD/storage/app/pass-store"
mkdir -p "$AI_SECRET_STORE_DIR"

gpg --list-secret-keys
pass init "Your Local GPG Identity"
```

Example `pass` paths used by the AI module:

- `pass://microweber/local/ai/openai`
- `pass://microweber/production/ai/anthropic`
- `pass://microweber/staging/ai/replicate`

The path format is:

```text
pass://{AI_SECRET_STORE_PATH_PREFIX}/{AI_SECRET_STORE_ENV}/{namespace}/{name}
```

For AI provider keys the namespace is currently `ai`, and the final segment is derived from the provider name (`openai`, `gemini`, `openrouter`, `anthropic`, `replicate`, `supadata`, `tavily`, `fal`).

Non-interactive access strategy:

- Prefer a dedicated server GPG key that is only used for Microweber secrets.
- Import that key for the same OS user that runs PHP-FPM / queues / CLI tasks.
- Ensure the host can decrypt without an interactive desktop prompt. In practice this usually means one of:
  - an already-unlocked GPG agent for the service user,
  - a deployment-time unlock step before starting PHP workers,
  - or a dedicated server key with an operational model that avoids interactive pinentry during normal request handling.
- Test both web and CLI execution paths, because AI settings writes and runtime secret resolution may happen in different processes.

Operational fallback behavior:

- If `AI_SECRET_STORE_ENABLED=false`, Microweber will not call `pass`.
- When the secret store is disabled, legacy plaintext AI option values continue to resolve normally.
- When the secret store is enabled and a plaintext AI provider key is still stored in options, Microweber migrates it into `pass` on the next config load and persists only the resulting `pass://...` reference.
- When a `pass://...` reference exists but the secret store is disabled, AI provider secret resolution returns `null` instead of throwing during boot. This keeps unrelated MCP features from failing hard, but the affected provider will not have a usable secret until `pass` is enabled again.
- When the secret store is enabled but `pass` itself is unavailable or the referenced entry cannot be decrypted, writes/reads fail explicitly through the process runner. The admin MCP client UI surfaces missing references as health warnings without revealing secret values.

Recommended rollout checklist:

1. Install `gnupg` and `pass` on the target host.
2. Import the correct private key for the service user.
3. Run `pass init` for that identity.
4. Configure `AI_SECRET_STORE_ENABLED=true` and optional `AI_SECRET_STORE_DIR`.
5. Re-save AI provider keys from the AI settings page so plaintext values are migrated into `pass`.
6. Use the Filament `McpClientResource` to confirm provider-reference health and issue a test MCP client key.
7. Call `POST /api/mcp` with `initialize` and `tools/list` before exposing the endpoint externally.

### Function Definition Format

Each function in the `functions` array should follow this structure:
```php
[
    'name' => 'function_name',
    'description' => 'Description of what the function does',
    'parameters' => [
        'type' => 'object',
        'properties' => [
            // Function parameters defined in JSON Schema format
        ],
        'required' => ['required_param1', 'required_param2']
    ]
]
```

### Response Handling

The response will be either:
- A string containing the assistant's message content
- An array containing function call details: `['function_call' => object, 'content' => null]`

When handling function calls, you should:
1. Execute the requested function
2. Add the function call and result to the message history
3. Continue the conversation by sending the updated messages back to the AI
