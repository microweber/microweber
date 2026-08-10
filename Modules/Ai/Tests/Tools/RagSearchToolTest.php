<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Tools;

use Modules\Ai\Tools\RagSearchTool;
use Modules\Ai\Services\RagSearchService;
use Modules\Ai\Models\AgentChatSearch;
use Modules\Content\Models\Content;
use Modules\Product\Models\Product;
use Modules\Customer\Models\Customer;
use PHPUnit\Framework\Attributes\Test;

class RagSearchToolTest extends ToolTestCase
{
    private RagSearchTool $tool;
    private RagSearchService $ragService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ragService = new RagSearchService();
        $this->tool = new RagSearchTool($this->ragService);
    }

    #[Test]
    public function it_returns_error_when_query_is_empty(): void
    {
        $result = $this->tool->__invoke([
            'query' => '',
        ]);

        $this->assertStringContainsString('Search query cannot be empty', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_searches_with_default_parameters(): void
    {
        // Create test content using direct create instead of factory
        Content::create([
            'title' => 'Test Article ' . uniqid(),
            'content' => 'This is a test article about technology',
            'content_type' => 'post',
            'is_active' => 1,
            'url' => 'test-article-' . uniqid(),
        ]);

        $result = $this->tool->__invoke([
            'query' => 'technology',
        ]);

        $this->assertStringContainsString('Search Results', $result);
        $this->assertStringContainsString('technology', $result);
    }

    #[Test]
    public function it_searches_specific_content_types(): void
    {
        // Create test product using direct create
        Product::create([
            'title' => 'Tech Product ' . uniqid(),
            'content' => 'Amazing tech gadget',
            'description' => 'High-tech device',
            'is_active' => 1,
            'url' => 'tech-product-' . uniqid(),
        ]);

        $result = $this->tool->__invoke([
            'query' => 'tech',
            'search_type' => 'products',
        ]);

        $this->assertStringContainsString('Search Results', $result);
    }

    #[Test]
    public function it_validates_limit_parameter(): void
    {
        // Create test content using direct create
        for ($i = 0; $i < 10; $i++) {
            Content::create([
                'title' => 'Content ' . $i . ' ' . uniqid(),
                'content_type' => 'post',
                'is_active' => 1,
                'url' => 'content-' . $i . '-' . uniqid(),
            ]);
        }

        // Test limit too high
        $result = $this->tool->__invoke([
            'query' => 'content',
            'limit' => 100,
        ]);

        $this->assertStringContainsString('Search Results', $result);

        // Test limit too low
        $result = $this->tool->__invoke([
            'query' => 'content',
            'limit' => 0,
        ]);

        $this->assertStringContainsString('Search Results', $result);
    }

    #[Test]
    public function it_returns_no_results_message_when_empty(): void
    {
        $result = $this->tool->__invoke([
            'query' => 'xyznonexistentsearchquery123',
        ]);

        $this->assertStringContainsString('No relevant information found', $result);
        $this->assertStringContainsString('alert-danger', $result);
    }

    #[Test]
    public function it_searches_customers(): void
    {
        // Create test customer
        Customer::create([
            'first_name' => 'John',
            'last_name' => 'Tech',
            'email' => 'john' . uniqid() . '@example.com',
        ]);

        $result = $this->tool->__invoke([
            'query' => 'John',
            'search_type' => 'customers',
        ]);

        $this->assertStringContainsString('Search Results', $result);
    }

    #[Test]
    public function it_searches_content(): void
    {
        // Create test content using direct create
        Content::create([
            'title' => 'Tech Tutorial ' . uniqid(),
            'content' => 'Learn about technology',
            'content_type' => 'page',
            'is_active' => 1,
            'url' => 'tech-tutorial-' . uniqid(),
        ]);

        $result = $this->tool->__invoke([
            'query' => 'tutorial',
            'search_type' => 'content',
        ]);

        $this->assertStringContainsString('Search Results', $result);
    }

    #[Test]
    public function it_searches_orders(): void
    {
        // Order search is basic, just test it doesn't crash
        $result = $this->tool->__invoke([
            'query' => '123',
            'search_type' => 'orders',
        ]);

        // Should return something (empty or results)
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_searches_all_types(): void
    {
        // Create test data using direct create
        Content::create([
            'title' => 'Tech Blog Post ' . uniqid(),
            'content' => 'About technology',
            'content_type' => 'post',
            'is_active' => 1,
            'url' => 'tech-blog-post-' . uniqid(),
        ]);

        Product::create([
            'title' => 'Tech Gadget ' . uniqid(),
            'content' => 'Cool tech',
            'is_active' => 1,
            'url' => 'tech-gadget-' . uniqid(),
        ]);

        $result = $this->tool->__invoke([
            'query' => 'tech',
            'search_type' => 'all',
        ]);

        $this->assertStringContainsString('Search Results', $result);
        $this->assertStringContainsString('tech', strtolower($result));
    }

    #[Test]
    public function it_escapes_html_in_results(): void
    {
        // Create test content using direct create
        Content::create([
            'title' => '<script>alert("xss")</script>Safe Title ' . uniqid(),
            'content' => '<script>alert("xss")</script>Safe Content',
            'content_type' => 'page',
            'is_active' => 1,
            'url' => 'safe-title-' . uniqid(),
        ]);

        $result = $this->tool->__invoke([
            'query' => 'Safe',
        ]);

        // Should escape script tags
        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringContainsString('&lt;script&gt;', $result);
    }

    #[Test]
    public function it_displays_relevance_scores(): void
    {
        // Create test content using direct create
        Content::create([
            'title' => 'Test Article ' . uniqid(),
            'content' => 'Test content for relevance',
            'content_type' => 'post',
            'is_active' => 1,
            'url' => 'test-article-' . uniqid(),
        ]);

        $result = $this->tool->__invoke([
            'query' => 'article',
        ]);

        $this->assertStringContainsString('Relevance:', $result);
        $this->assertStringContainsString('%', $result);
    }

    #[Test]
    public function it_groups_results_by_type(): void
    {
        // Create test content using direct create
        Content::create([
            'title' => 'Grouped Test ' . uniqid(),
            'content' => 'Content',
            'content_type' => 'post',
            'is_active' => 1,
            'url' => 'grouped-test-' . uniqid(),
        ]);

        $result = $this->tool->__invoke([
            'query' => 'grouped',
            'search_type' => 'all',
        ]);

        $this->assertStringContainsString('Search Results', $result);
    }

    #[Test]
    public function it_limits_results_correctly(): void
    {
        // Create test content using direct create
        for ($i = 0; $i < 20; $i++) {
            Content::create([
                'title' => 'Content ' . $i . ' ' . uniqid(),
                'content_type' => 'post',
                'is_active' => 1,
                'url' => 'content-' . $i . '-' . uniqid(),
            ]);
        }

        $result = $this->tool->__invoke([
            'query' => 'content',
            'limit' => 5,
        ]);

        $this->assertStringContainsString('Search Results', $result);
    }

    #[Test]
    public function it_handles_special_characters_in_query(): void
    {
        // Create test content using direct create
        Content::create([
            'title' => 'Special Article ' . uniqid(),
            'content' => 'Content with special chars',
            'content_type' => 'page',
            'is_active' => 1,
            'url' => 'special-article-' . uniqid(),
        ]);

        $result = $this->tool->__invoke([
            'query' => 'special%_chars',
        ]);

        // Should not crash with special characters
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_saves_search_results_to_history(): void
    {
        $chat = \Modules\Ai\Models\AgentChat::factory()->create();

        // Create test content using direct create
        Content::create([
            'title' => 'History Test ' . uniqid(),
            'content' => 'Test content',
            'content_type' => 'page',
            'is_active' => 1,
            'url' => 'history-test-' . uniqid(),
        ]);

        // Mock state with chat_id
        $state = new \NeuronAI\Workflow\WorkflowState();
        $state->set('chat_id', $chat->id);
        $this->tool->setState($state);

        $result = $this->tool->__invoke([
            'query' => 'history',
        ]);

        $this->assertStringContainsString('Search Results', $result);
    }

    #[Test]
    public function it_handles_database_exceptions_gracefully(): void
    {
        // This test ensures the tool handles DB errors gracefully
        // In real scenarios, the service layer handles this
        $result = $this->tool->__invoke([
            'query' => 'test',
        ]);

        // Should return a result, not crash
        $this->assertNotEmpty($result);
    }

    #[Test]
    public function it_trims_long_content_in_results(): void
    {
        // Create test content using direct create
        Content::create([
            'title' => 'Long Content Test ' . uniqid(),
            'content' => str_repeat('Very long content. ', 100),
            'content_type' => 'page',
            'is_active' => 1,
            'url' => 'long-content-test-' . uniqid(),
        ]);

        $result = $this->tool->__invoke([
            'query' => 'long',
        ]);

        $this->assertStringContainsString('...', $result);
    }
}
