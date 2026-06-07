<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Modules\Ai\Services\Mcp\McpToolCatalog;
use Modules\Content\Models\Content;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-07-mcpinvoke
 *
 * Behavioural smoke test for the whole MCP tool catalog.
 *
 * The existing suite pins the catalog STATICALLY — inventory
 * ({@see McpToolCatalogContractTest}), input-schema shape
 * ({@see McpToolInputSchemaRegressionTest}) and annotations. None of
 * them actually INVOKE a tool, so a tool whose `__invoke()` throws (a
 * renamed model, a removed helper, a fatal in the formatter) would
 * pass every contract test and only blow up at runtime when an AI
 * client calls it over `tools/call`.
 *
 * This test closes that gap: it drives every tool through the real
 * dispatch path used by the HTTP/stdio servers —
 * {@see McpToolCatalog::callTool()} — and asserts each one returns a
 * non-empty string WITHOUT throwing. The MCP wire contract is that a
 * tool always returns a string (a normal result OR a graceful
 * `isError` message); an uncaught exception is the one outcome the
 * server cannot turn into a valid `content[0].text` response.
 *
 * Representative arguments are supplied for the tools that declare
 * required params (search_term / content_id / option_group / amount /
 * …); the rest are called with no args, which they must answer with a
 * clean empty-state string rather than a crash. This mirrors the
 * full 41-tool over-the-wire sweep run on 2026-06-07 (0 hard failures).
 *
 * The two write tools (content.create / product.create) are exercised
 * separately in {@see write_tools_persist_root_content_and_clean_up}
 * so the read sweep stays side-effect free.
 */
class McpCatalogInvocationSmokeTest extends TestCase
{
    /**
     * Tools that persist rows — excluded from the read sweep and
     * exercised by the dedicated write test below.
     *
     * @var list<string>
     */
    private const WRITE_TOOLS = ['content.create', 'product.create'];

    /**
     * Representative arguments for tools that validate required
     * params. Anything not listed here is invoked with [] and must
     * return a graceful empty-state/validation string (still a
     * non-empty string, never an exception).
     *
     * @return array<string, array<string, mixed>>
     */
    private function representativeArgs(): array
    {
        return [
            'content.lookup' => ['search_term' => 'a', 'limit' => 3],
            'content.get' => ['content_id' => 1],
            'product.lookup' => ['query' => 'a', 'limit' => 3],
            'order.lookup' => ['limit' => 3],
            'settings.read' => ['option_group' => 'website', 'option_key' => 'website_title'],
            'media.lookup' => ['limit' => 3],
            'media.asset_detail' => ['media_id' => 1],
            'forms.submission_detail' => ['submission_id' => 1],
            'billing.account_status' => ['customer_id' => 1],
            'billing.invoice_detail' => ['invoice_id' => 1],
            'billing.invoice_customer_history' => ['customer_id' => 1],
            'billing.payment_detail' => ['payment_id' => 1],
            'tax.preview' => ['amount' => '100', 'country_code' => 'US'],
        ];
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function readToolNames(): array
    {
        $catalog = new McpToolCatalog();
        $cases = [];

        foreach (array_keys($catalog->allDefinitions()) as $name) {
            if (in_array($name, self::WRITE_TOOLS, true)) {
                continue;
            }
            $cases[$name] = [$name];
        }

        return $cases;
    }

    #[Test]
    #[DataProvider('readToolNames')]
    public function read_tool_returns_a_non_empty_string_without_throwing(string $toolName): void
    {
        $catalog = app(McpToolCatalog::class);
        $args = $this->representativeArgs()[$toolName] ?? [];

        try {
            $output = $catalog->callTool($toolName, $args);
        } catch (\Throwable $e) {
            $this->fail(
                "MCP tool '{$toolName}' threw " . get_class($e) . ' during tools/call: '
                . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine()
                . ' — every catalog tool must return a string (data OR a graceful '
                . 'isError message), never let an exception escape the dispatch path.'
            );
        }

        $this->assertIsString(
            $output,
            "MCP tool '{$toolName}' must return a string from __invoke() so the server "
            . 'can wrap it in a content[0].text response.'
        );
        $this->assertNotSame(
            '',
            trim($output),
            "MCP tool '{$toolName}' returned an empty string — even a no-results read "
            . 'must emit a human-readable empty-state so the AI client has something to relay.'
        );
    }

    #[Test]
    public function every_non_write_tool_is_covered_by_the_read_sweep(): void
    {
        // Guard against the data provider silently shrinking: if a new
        // read tool is added but this provider stops yielding it (a
        // typo in WRITE_TOOLS, a refactor), the sweep above would pass
        // while skipping the new tool. Pin the count explicitly.
        $catalog = app(McpToolCatalog::class);
        $allNames = array_keys($catalog->allDefinitions());
        $expectedReadCount = count(array_diff($allNames, self::WRITE_TOOLS));

        $this->assertSame(
            $expectedReadCount,
            count(self::readToolNames()),
            'The read-sweep data provider must yield every non-write tool. A mismatch '
            . 'means a tool is being skipped by the behavioural smoke test.'
        );
    }

    #[Test]
    public function write_tools_persist_root_content_and_clean_up(): void
    {
        $catalog = app(McpToolCatalog::class);
        $createdContentIds = [];

        try {
            // content.create — a page must persist as root content
            // (parent = 0) so it shows in the admin Pages picker / Live
            // Edit PageChip, which filter on parent = 0.
            $pageSlug = 'mcp-smoke-page-' . uniqid();
            $contentOut = $catalog->callTool('content.create', [
                'title' => 'MCP Smoke Page',
                'content_type' => 'page',
                'url' => $pageSlug,
                'content_body' => '<p>smoke</p>',
                'is_active' => true,
            ]);

            $this->assertIsString($contentOut);
            $this->assertStringContainsString('ID:', $contentOut, 'content.create must report the new row ID.');

            $page = Content::where('url', $pageSlug)->first();
            $this->assertNotNull($page, 'content.create must persist a Content row.');
            $createdContentIds[] = $page->id;
            $this->assertSame(0, (int) $page->parent, 'content.create must persist parent=0 for root content.');
            $this->assertSame('page', (string) $page->content_type);

            // product.create — same root-visibility contract, content_type=product.
            $productSlug = 'mcp-smoke-product-' . uniqid();
            $productOut = $catalog->callTool('product.create', [
                'title' => 'MCP Smoke Product',
                'description' => 'A smoke-test product.',
                'url' => $productSlug,
                'content_body' => '<p>smoke product</p>',
            ]);

            $this->assertIsString($productOut);
            $this->assertStringContainsString('ID:', $productOut, 'product.create must report the new row ID.');

            $product = Product::where('url', $productSlug)->first();
            $this->assertNotNull($product, 'product.create must persist a Product row.');
            $createdContentIds[] = $product->id;
            $this->assertSame(0, (int) $product->parent, 'product.create must persist parent=0 for root products.');
            $this->assertSame('product', (string) $product->content_type);
        } finally {
            // No RefreshDatabase in this suite — clean up the rows we
            // created so the test is repeatable and leaves no residue.
            if ($createdContentIds !== []) {
                Content::whereIn('id', $createdContentIds)->forceDelete();
            }
        }
    }
}
