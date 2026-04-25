<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Modules\Ai\Models\McpClient;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Regression test pinning the documented allow-list semantics on
 * {@see McpClient::allowsTool()} / ::allowsModule() / ::allowsScope()
 * — see the inline contract on `McpClient::allowsValue()`.
 *
 * The original implementation collapsed `null` and `[]` into the
 * same "deny everything" branch, which surprised every operator who
 * created a client with `allowed_tools = null` expecting "no
 * restriction" and instead got 0 tools back from `tools/list`. This
 * test pins the new contract:
 *
 *   - `null`              → unrestricted (allow any candidate)
 *   - `[]` (empty array)  → explicit deny-all
 *   - `['*', ...]`        → wildcard (allow any candidate)
 *   - `['foo']`           → only `foo`
 *
 * If a future contributor reverts the semantics or introduces a new
 * branch (e.g. `'all'` magic string), this test surfaces the drift
 * loudly.
 */
class McpClientAllowlistSemanticsTest extends TestCase
{
    private function makeClient(?array $allowedTools, ?array $allowedModules, ?array $allowedScopes): McpClient
    {
        // Hydrate without persisting — we only exercise the in-
        // memory accessors. No DB / migrations / fixtures needed.
        $client = new McpClient();
        $client->allowed_tools = $allowedTools;
        $client->allowed_modules = $allowedModules;
        $client->allowed_scopes = $allowedScopes;

        return $client;
    }

    #[Test]
    public function null_allowlists_resolve_to_unrestricted(): void
    {
        $client = $this->makeClient(null, null, null);

        $this->assertTrue(
            $client->allowsTool('content.lookup'),
            'allowed_tools=null must resolve to unrestricted — every operator who '
            . 'leaves the Filament form field empty expects "no restriction" semantics. '
            . 'A regression that flips back to deny-all would silently break every '
            . 'unconfigured client and surface as zero tools in tools/list responses.'
        );
        $this->assertTrue(
            $client->allowsModule('content'),
            'allowed_modules=null must resolve to unrestricted — same contract as '
            . 'allowed_tools above. A regression here would block module routing '
            . 'inside the AuthenticateMcpClient middleware.'
        );
        $this->assertTrue(
            $client->allowsScope('mcp:access'),
            'allowed_scopes=null must resolve to unrestricted — a client with no '
            . 'declared scopes should accept the default mcp:access ability the '
            . 'middleware checks. Otherwise every default-shape client would 401.'
        );
    }

    #[Test]
    public function empty_array_allowlists_resolve_to_deny_all(): void
    {
        $client = $this->makeClient([], [], []);

        $this->assertFalse(
            $client->allowsTool('content.lookup'),
            'allowed_tools=[] (explicit empty array) must deny every candidate — '
            . 'this lets an operator persist "this client has been narrowed to '
            . 'nothing" without the value collapsing to the null-unrestricted '
            . 'branch. A regression here would mean an operator who explicitly '
            . 'cleared the list still gets unrestricted access.'
        );
        $this->assertFalse(
            $client->allowsModule('content'),
            'allowed_modules=[] must deny — same contract as allowed_tools above.'
        );
        $this->assertFalse(
            $client->allowsScope('mcp:access'),
            'allowed_scopes=[] must deny — same contract as allowed_tools above.'
        );
    }

    #[Test]
    public function wildcard_allowlists_resolve_to_unrestricted(): void
    {
        $client = $this->makeClient(['*'], ['*'], ['*']);

        $this->assertTrue(
            $client->allowsTool('content.lookup'),
            'allowed_tools=["*"] must allow every candidate — the wildcard token '
            . 'is the documented way to opt in to unrestricted access via the '
            . 'admin UI when the operator wants the persisted JSON to record the '
            . 'intent (vs. leaving the field null).'
        );
        $this->assertTrue($client->allowsModule('content'));
        $this->assertTrue($client->allowsScope('mcp:access'));

        // Wildcard PLUS a specific entry should still allow every
        // candidate — the wildcard wins as soon as it's present.
        $clientMixed = $this->makeClient(['*', 'content.lookup'], ['*'], ['*']);
        $this->assertTrue(
            $clientMixed->allowsTool('product.lookup'),
            'A wildcard alongside specific entries must still match any candidate '
            . '— the wildcard short-circuits. A regression that requires an exact '
            . 'match in this branch would silently shrink the allowed set.'
        );
    }

    #[Test]
    public function specific_allowlists_match_only_listed_entries(): void
    {
        $client = $this->makeClient(
            ['content.lookup', 'order.lookup'],
            ['content', 'order'],
            ['mcp:access'],
        );

        $this->assertTrue($client->allowsTool('content.lookup'));
        $this->assertTrue($client->allowsTool('order.lookup'));
        $this->assertFalse(
            $client->allowsTool('product.lookup'),
            'A non-wildcard, non-empty allowed_tools list must reject candidates '
            . 'not in the list — this is the core least-privilege contract every '
            . 'production client relies on.'
        );

        $this->assertTrue($client->allowsModule('content'));
        $this->assertFalse($client->allowsModule('product'));

        $this->assertTrue($client->allowsScope('mcp:access'));
        $this->assertFalse(
            $client->allowsScope('mcp:admin'),
            'A client granted only mcp:access must NOT pass an mcp:admin scope '
            . 'check — the admin-only tool gate in AuthenticateMcpClient depends '
            . 'on this being a proper subset check.'
        );
    }
}
