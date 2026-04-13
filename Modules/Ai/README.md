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
