<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Modules\Ai\Services\Mcp\McpToolCatalog;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Plan G — pin the MCP tool catalog inventory.
 *
 * Catalog adds/removes are operator-visible: every change to
 * {@see McpToolCatalog::allDefinitions()} affects what AI clients
 * (Claude Desktop, Cursor, Cline) discover via `tools/list`. A
 * silent removal — refactor that drops a tool by accident — would
 * present as that tool simply not existing on the next run, with no
 * loud failure pointing at the regression.
 *
 * This test pins:
 *
 *   1. The expected tool inventory — the 39-tool catalog as of
 *      2026-04-25. A removal fails this test loudly with a
 *      pointed message about which tool went missing.
 *   2. Every tool entry has the three required keys
 *      (`tool`, `module`, `title`).
 *   3. Every tool name follows the `<module>.<verb>` convention so
 *      tools/list responses stay parseable by clients that filter
 *      by namespace prefix.
 *   4. The number of tools matches the expected inventory exactly
 *      — duplicate-key registrations would silently overwrite
 *      entries without changing the count.
 *
 * Adding a tool:
 *   1. Register it in `McpToolCatalog::allDefinitions()`.
 *   2. Add its name to `EXPECTED_TOOLS` below.
 *   3. Run `php vendor/bin/phpunit Modules/Ai/tests/Feature/McpToolCatalogContractTest.php`.
 *
 * Removing a tool (deliberate deprecation):
 *   1. Document the removal in `Modules/Ai/CHANGELOG.md` (when it
 *      exists) or in the commit body.
 *   2. Remove it from `McpToolCatalog::allDefinitions()`.
 *   3. Remove it from `EXPECTED_TOOLS` below.
 *   4. Run the test to confirm.
 */
class McpToolCatalogContractTest extends TestCase
{
    /**
     * @var list<string>
     */
    private const EXPECTED_TOOLS = [
        'analytics.audience_breakdown',
        'analytics.top_pages',
        'analytics.traffic_referrers',
        'analytics.traffic_summary',
        'billing.account_status',
        'billing.invoice_customer_history',
        'billing.invoice_detail',
        'billing.invoice_lookup',
        'billing.invoice_unpaid_summary',
        'billing.metrics_summary',
        'billing.payment_detail',
        'billing.payment_lookup',
        'billing.payment_provider_health',
        'billing.payment_webhook_health',
        'billing.plan_summary',
        'billing.subscription_lookup',
        'content.create',
        'category.list',
        'comments.list',
        'content.get',
        'content.lookup',
        'menu.list',
        'testimonials.list',
        'forms.activity_summary',
        'forms.form_lookup',
        'forms.submission_detail',
        'forms.submission_search',
        'layouts.active_template',
        'layouts.asset_summary',
        'layouts.layout_lookup',
        'media.asset_detail',
        'media.lookup',
        'media.storage_health',
        'newsletter.automation_status',
        'newsletter.campaign_lookup',
        'newsletter.subscriber_lookup',
        'newsletter.template_lookup',
        'order.lookup',
        'product.create',
        'product.lookup',
        'settings.read',
        'shipping.method_lookup',
        'shipping.zone_summary',
        'tax.preview',
        'tax.rule_lookup',
    ];

    private function actualToolNames(): array
    {
        $catalog = app(McpToolCatalog::class);
        $names = array_keys($catalog->allDefinitions());
        sort($names);
        return $names;
    }

    #[Test]
    public function actual_catalog_matches_expected_inventory_exactly(): void
    {
        $actual = $this->actualToolNames();
        $expected = self::EXPECTED_TOOLS;
        sort($expected);

        $missing = array_values(array_diff($expected, $actual));
        $unexpected = array_values(array_diff($actual, $expected));

        $this->assertSame(
            [],
            $missing,
            'Plan G drift — these tools are listed in EXPECTED_TOOLS but no longer '
            . "registered in McpToolCatalog::allDefinitions(). Removing a tool is\n"
            . 'operator-visible (every AI client that listed it via tools/list now '
            . 'sees it disappear without a deprecation notice). '
            . "If the removal is deliberate, drop the entry from EXPECTED_TOOLS in\n"
            . 'this test and document the removal in the commit body. Missing: '
            . json_encode($missing, JSON_UNESCAPED_SLASHES)
        );

        $this->assertSame(
            [],
            $unexpected,
            'Plan G drift — these tools are registered in '
            . "McpToolCatalog::allDefinitions() but not pinned in EXPECTED_TOOLS.\n"
            . 'When adding a new tool, add its canonical name to EXPECTED_TOOLS '
            . 'so the inventory stays explicit. Unpinned: '
            . json_encode($unexpected, JSON_UNESCAPED_SLASHES)
        );
    }

    #[Test]
    public function every_tool_definition_has_the_required_shape(): void
    {
        $catalog = app(McpToolCatalog::class);

        foreach ($catalog->allDefinitions() as $name => $definition) {
            $this->assertArrayHasKey('tool', $definition, "Tool '{$name}' is missing the 'tool' key.");
            $this->assertArrayHasKey('module', $definition, "Tool '{$name}' is missing the 'module' key.");
            $this->assertArrayHasKey('title', $definition, "Tool '{$name}' is missing the 'title' key.");

            $this->assertIsString($definition['tool']);
            $this->assertIsString($definition['module']);
            $this->assertIsString($definition['title']);

            $this->assertNotSame(
                '',
                $definition['title'],
                "Tool '{$name}' must declare a non-empty title — it surfaces in tools/list "
                . 'responses and the AI side reads it to choose between similar tools.'
            );
        }
    }

    #[Test]
    public function every_tool_name_follows_module_dot_verb_convention(): void
    {
        $catalog = app(McpToolCatalog::class);

        foreach ($catalog->allDefinitions() as $name => $definition) {
            $this->assertMatchesRegularExpression(
                '/^[a-z][a-z0-9_]*\.[a-z][a-z0-9_]*$/',
                $name,
                "Tool '{$name}' must follow the <module>.<verb> naming convention "
                . '(both halves snake_case, lowercase, ASCII). Clients that filter '
                . 'by namespace prefix (e.g. tools/list?prefix=billing.) rely on '
                . 'this consistency.'
            );

            $module = (string) $definition['module'];
            $prefix = explode('.', $name)[0];
            $this->assertSame(
                $module,
                $prefix,
                "Tool '{$name}'s namespace prefix '{$prefix}' must match its declared "
                . "module '{$module}'. A mismatch surfaces as the tool being denied "
                . 'when the calling client narrows allowed_modules to its expected '
                . 'module key.'
            );
        }
    }

    #[Test]
    public function tools_list_response_declares_output_format_for_every_tool(): void
    {
        // Plan C.3 follow-up: every tool's tools/list entry must
        // carry an `annotations.outputFormat` field so MCP
        // 2025-06-18 clients can reason about the response shape
        // without a per-tool outputSchema. Today every tool emits
        // text; a regression that drops the annotation would leave
        // newer clients guessing.
        $catalog = app(McpToolCatalog::class);
        $context = $this->fakeAdminContext();

        $tools = $catalog->listTools($context);

        $this->assertNotEmpty($tools);

        foreach ($tools as $entry) {
            $this->assertArrayHasKey(
                'annotations',
                $entry,
                "Tool '{$entry['name']}' must include an annotations bag in tools/list."
            );
            $this->assertSame(
                'text',
                $entry['annotations']['outputFormat'] ?? null,
                "Tool '{$entry['name']}' must declare annotations.outputFormat='text' "
                . 'until any tool starts emitting structured JSON. A regression that '
                . 'drops the field would silently regress MCP 2025-06-18 clients to '
                . 'guessing the response shape.'
            );
            // task-2026-06-06-mcpwritehint: readOnlyHint is a SAFETY
            // signal — MCP clients gate auto-invocation (no human
            // approval) on it. The catalog carries two genuine write
            // tools (content.create -> Content::create, product.create
            // -> Product::create); they MUST advertise readOnlyHint=false
            // or an AI client treats "create a product" as safe-by-default.
            // Every other tool is strictly read-only and must be true.
            // (Previously this asserted true for EVERY tool, which masked
            // the two write tools shipping mis-annotated as read-only.)
            $writeTools = ['content.create', 'product.create'];
            $expectedReadOnly = ! in_array($entry['name'], $writeTools, true);

            $this->assertSame(
                $expectedReadOnly,
                $entry['annotations']['readOnlyHint'] ?? null,
                $expectedReadOnly
                    ? "Tool '{$entry['name']}' is read-only and must declare annotations.readOnlyHint=true."
                    : "Tool '{$entry['name']}' persists data and MUST declare annotations.readOnlyHint=false — "
                        . 'otherwise MCP clients surface the mutation as safe-by-default and may '
                        . 'auto-invoke it without human approval.'
            );
        }
    }

    /**
     * Minimal admin-shaped context that bypasses the per-token
     * scope gates in McpToolCatalog::listTools so the iteration
     * sees every registered tool.
     */
    private function fakeAdminContext(): \Modules\Ai\Services\Mcp\McpRequestContext
    {
        $client = new \Modules\Ai\Models\McpClient();
        $client->id = 1;
        $client->allowed_tools = ['*'];
        $client->allowed_modules = ['*'];
        $client->allowed_scopes = ['mcp:access', 'mcp:admin'];

        $token = new \Modules\Ai\Models\McpClientToken();
        $token->id = 1;
        $token->abilities = ['mcp:access', 'mcp:admin'];
        $token->setRelation('client', $client);

        return new \Modules\Ai\Services\Mcp\McpRequestContext($client, $token);
    }

    #[Test]
    public function expected_inventory_has_no_duplicates(): void
    {
        $tags = self::EXPECTED_TOOLS;
        $this->assertSame(
            count($tags),
            count(array_unique($tags)),
            'EXPECTED_TOOLS must not contain duplicate names — a duplicate makes the '
            . 'array_diff drift detection above falsely report "no missing" when the '
            . 'first occurrence of a missing tool happens to match a duplicate. '
            . 'Duplicates: '
            . json_encode(array_diff_assoc($tags, array_unique($tags)), JSON_UNESCAPED_SLASHES)
        );
    }
}
