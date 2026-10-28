<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Unit;

use Modules\Ai\Services\Mcp\McpSseSessionStore;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * {@see McpSseSessionStore} is the cross-request bridge for the legacy
 * MCP HTTP+SSE transport: the POST /messages handler enqueues a response
 * and the open GET /sse stream drains it. These pin that contract —
 * minting, existence, FIFO enqueue/drain, drain-clears-the-queue,
 * teardown, and SSE frame formatting.
 */
class McpSseSessionStoreTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // In-process store so enqueue + drain share state within the test.
        config([
            'cache.default' => 'array',
            'modules.ai.mcp.sse.max_seconds' => 25,
            'modules.ai.mcp.sse.cache_store' => null,
        ]);
    }

    private function store(): McpSseSessionStore
    {
        return app(McpSseSessionStore::class);
    }

    #[Test]
    public function open_mints_a_session_that_exists_until_closed(): void
    {
        $store = $this->store();

        $id = $store->open();
        $this->assertNotSame('', $id);
        $this->assertTrue($store->exists($id));

        $store->close($id);
        $this->assertFalse($store->exists($id));
    }

    #[Test]
    public function an_unknown_session_does_not_exist(): void
    {
        $this->assertFalse($this->store()->exists('not-a-session'));
        $this->assertFalse($this->store()->exists(''));
    }

    #[Test]
    public function enqueue_then_drain_returns_messages_in_fifo_order(): void
    {
        $store = $this->store();
        $id = $store->open();

        $store->enqueue($id, ['jsonrpc' => '2.0', 'id' => 1, 'result' => ['first' => true]]);
        $store->enqueue($id, ['jsonrpc' => '2.0', 'id' => 2, 'result' => ['second' => true]]);

        $drained = $store->drain($id);

        $this->assertCount(2, $drained);
        $this->assertSame(1, $drained[0]['id']);
        $this->assertSame(2, $drained[1]['id']);
    }

    #[Test]
    public function drain_clears_the_queue(): void
    {
        $store = $this->store();
        $id = $store->open();
        $store->enqueue($id, ['jsonrpc' => '2.0', 'id' => 1, 'result' => []]);

        $this->assertCount(1, $store->drain($id));
        // A second drain with nothing newly enqueued is empty — a message is
        // delivered exactly once over the stream.
        $this->assertSame([], $store->drain($id));
    }

    #[Test]
    public function draining_an_empty_session_is_an_empty_list(): void
    {
        $store = $this->store();
        $id = $store->open();

        $this->assertSame([], $store->drain($id));
    }

    #[Test]
    public function frame_formats_a_spec_valid_sse_event(): void
    {
        $frame = $this->store()->frame('endpoint', '/api/mcp/messages?sessionId=abc');

        $this->assertSame(
            "event: endpoint\ndata: /api/mcp/messages?sessionId=abc\n\n",
            $frame,
        );
    }

    #[Test]
    public function frame_splits_multiline_data_across_data_lines(): void
    {
        $frame = $this->store()->frame('message', "line-one\nline-two");

        $this->assertSame(
            "event: message\ndata: line-one\ndata: line-two\n\n",
            $frame,
        );
    }
}
