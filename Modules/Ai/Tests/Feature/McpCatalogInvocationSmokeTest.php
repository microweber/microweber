<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Modules\Ai\Services\Mcp\McpToolCatalog;
use Modules\Content\Models\Content;
use Modules\Product\Models\Product;
use Modules\Customer\Models\Customer;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderStatusHistory;
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
    private const WRITE_TOOLS = [
        'content.create', 'product.create', 'post.create',
        'order.update_status', 'customer.create', 'newsletter.subscribe',
    ];

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
            'order.detail' => ['order_id' => 1],
            'cart.lookup' => ['limit' => 3],
            'coupons.validate' => ['code' => 'ZZ-MCP-NONEXISTENT', 'cart_total' => '100'],
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

            // content.edit — mutate the row we just created and confirm the change
            // persists (the edit tools were wired alongside the list tools).
            $editOut = $catalog->callTool('content.edit', [
                'id' => $page->id,
                'title' => 'MCP Smoke Page EDITED',
            ]);
            $this->assertIsString($editOut);
            $this->assertNotSame('', $editOut, 'content.edit must return a result string.');
            $this->assertSame(
                'MCP Smoke Page EDITED',
                (string) Content::find($page->id)->title,
                'content.edit must persist the updated title.'
            );

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

            // product.edit — mutate the product we just created and confirm it persists.
            $prodEditOut = $catalog->callTool('product.edit', [
                'id' => $product->id,
                'title' => 'MCP Smoke Product EDITED',
            ]);
            $this->assertIsString($prodEditOut);
            $this->assertSame(
                'MCP Smoke Product EDITED',
                (string) Product::find($product->id)->title,
                'product.edit must persist the updated title.'
            );

            // post.create — a blog post persists with content_type=post.
            $postSlug = 'mcp-smoke-post-' . uniqid();
            $postOut = $catalog->callTool('post.create', [
                'title' => 'MCP Smoke Post',
                'content_body' => '<p>smoke post body</p>',
                'url' => $postSlug,
            ]);
            $this->assertIsString($postOut);
            $this->assertStringContainsString('ID:', $postOut, 'post.create must report the new row ID.');

            $post = Content::where('url', $postSlug)->first();
            $this->assertNotNull($post, 'post.create must persist a row.');
            $createdContentIds[] = $post->id;
            $this->assertSame('post', (string) $post->content_type);
        } finally {
            // No RefreshDatabase in this suite — clean up the rows we
            // created so the test is repeatable and leaves no residue.
            if ($createdContentIds !== []) {
                Content::whereIn('id', $createdContentIds)->forceDelete();
            }
        }
    }

    #[Test]
    public function write_action_tools_mutate_and_clean_up(): void
    {
        $catalog = app(McpToolCatalog::class);
        $email = 'zz-mcp-' . uniqid() . '@test.local';
        $order = null;

        try {
            // customer.create — persists a customers row (idempotent on email).
            $custOut = $catalog->callTool('customer.create', ['email' => $email, 'first_name' => 'MCP']);
            $this->assertIsString($custOut);
            $this->assertNotNull(Customer::where('email', $email)->first(), 'customer.create must persist a customer.');

            // newsletter.subscribe — persists a newsletter_subscribers row.
            $subOut = $catalog->callTool('newsletter.subscribe', ['email' => $email]);
            $this->assertIsString($subOut);
            $this->assertNotNull(
                NewsletterSubscriber::where('email', $email)->first(),
                'newsletter.subscribe must persist a subscriber.'
            );

            // order.update_status — change a throwaway order's status (history auto-logged).
            $order = Order::create(['email' => $email, 'order_status' => 'new', 'amount' => 10]);
            $statusOut = $catalog->callTool('order.update_status', ['order_id' => $order->id, 'status' => 'completed']);
            $this->assertIsString($statusOut);
            $this->assertSame(
                'completed',
                (string) Order::find($order->id)->order_status,
                'order.update_status must persist the new status.'
            );

            // Invalid status is rejected gracefully (no throw, no change).
            $badOut = $catalog->callTool('order.update_status', ['order_id' => $order->id, 'status' => 'bogus']);
            $this->assertStringContainsString('status must be one of', $badOut);
        } finally {
            Customer::where('email', $email)->delete();
            NewsletterSubscriber::where('email', $email)->delete();
            if ($order) {
                OrderStatusHistory::where('order_id', $order->id)->delete();
                $order->forceDelete();
            }
        }
    }
}
