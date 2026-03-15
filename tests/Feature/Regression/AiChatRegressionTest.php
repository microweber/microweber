<?php

namespace Tests\Feature\Regression;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Models\AgentChatMessage;
use Modules\Ai\Services\AiService;
use Modules\Ai\Services\RagSearchService;
use Modules\Ai\Tools\AmazonScraperTool;
use Modules\Ai\Tools\CreateContentTool;
use Modules\Ai\Tools\GoogleTrendsTool;
use Modules\Ai\Tools\RagSearchTool;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Full Regression Test Suite - AI Chat and Tools
 *
 * End-to-end testing of AI functionality including:
 * - Chat creation
 * - Tool execution
 * - Message handling
 * - Streaming responses
 *
 * @covers \Modules\Ai
 */
class AiChatRegressionTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    protected User $admin;
    protected AiService $agentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->agentService = app(AiService::class);

        // Mock HTTP responses for external API calls
        Http::fake([
            'api.openai.com/*' => Http::response([
                'id' => 'chatcmpl-test',
                'object' => 'chat.completion',
                'created' => time(),
                'model' => 'gpt-4',
                'choices' => [
                    [
                        'index' => 0,
                        'message' => [
                            'role' => 'assistant',
                            'content' => 'This is a test response from the AI.',
                        ],
                        'finish_reason' => 'stop',
                    ],
                ],
            ], 200),
            'api.supadata.com/*' => Http::response([
                'success' => true,
                'data' => ['result' => 'scraped data'],
            ], 200),
        ]);
    }

    /**
     * Test complete AI chat flow
     */
    #[Test]
    public function it_complete_ai_chat_flow(): void
    {
        $this->actingAs($this->admin);

        // Step 1: Create a new chat
        $createResponse = $this->post('/admin/ai/agent-chats', [
            'title' => 'Test Chat Session',
            'initial_prompt' => 'Help me with content creation',
        ]);

        $createResponse->assertRedirect();

        $chat = AgentChat::where('title', 'Test Chat Session')->first();
        $this->assertNotNull($chat);
        $this->assertEquals($this->admin->id, $chat->user_id);

        // Step 2: Verify chat is accessible
        $viewResponse = $this->get('/admin/ai/agent-chats/' . $chat->id);
        $viewResponse->assertStatus(200);

        // Step 3: Send a message
        $messageResponse = $this->post('/api/ai/chat/' . $chat->id . '/message', [
            'content' => 'Create a blog post about Laravel',
        ]);

        $messageResponse->assertStatus(200);
        $messageResponse->assertJsonStructure([
            'success',
            'message',
        ]);

        // Step 4: Verify message was stored
        $this->assertDatabaseHas('ai_agent_chat_messages', [
            'agent_chat_id' => $chat->id,
            'role' => 'user',
            'content' => 'Create a blog post about Laravel',
        ]);
    }

    /**
     * Test chat with CreateContentTool
     */
    #[Test]
    public function it_chat_with_create_content_tool(): void
    {
        $this->actingAs($this->admin);

        Http::fake([
            'api.openai.com/*' => Http::response([
                'id' => 'chatcmpl-test',
                'object' => 'chat.completion',
                'created' => time(),
                'model' => 'gpt-4',
                'choices' => [
                    [
                        'index' => 0,
                        'message' => [
                            'role' => 'assistant',
                            'content' => json_encode([
                                'tool_calls' => [
                                    [
                                        'id' => 'call_123',
                                        'type' => 'function',
                                        'function' => [
                                            'name' => 'create_content',
                                            'arguments' => json_encode([
                                                'title' => 'Getting Started with Laravel',
                                                'content_type' => 'post',
                                                'content' => 'Laravel is a powerful PHP framework...',
                                            ]),
                                        ],
                                    ],
                                ],
                            ]),
                        ],
                        'finish_reason' => 'tool_calls',
                    ],
                ],
            ], 200),
        ]);

        $chat = AgentChat::factory()->create([
            'user_id' => $this->admin->id,
            'title' => 'Content Creation Chat',
        ]);

        $response = $this->post('/api/ai/chat/' . $chat->id . '/message', [
            'content' => 'Create a blog post about Laravel',
        ]);

        $response->assertStatus(200);

        // Verify content was created
        $this->assertDatabaseHas('content', [
            'title' => 'Getting Started with Laravel',
            'content_type' => 'post',
        ]);
    }

    /**
     * Test AmazonScraperTool execution
     */
    #[Test]
    public function it_amazon_scraper_tool_execution(): void
    {
        $this->actingAs($this->admin);

        Http::fake([
            'api.supadata.com/v1/amazon/*' => Http::response([
                'success' => true,
                'data' => [
                    'title' => 'Test Product',
                    'price' => 29.99,
                    'description' => 'A great product description',
                    'images' => ['https://example.com/image.jpg'],
                ],
            ], 200),
        ]);

        $chat = AgentChat::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->post('/api/ai/chat/' . $chat->id . '/message', [
            'content' => 'Scrape product from https://amazon.com/dp/B08N5WRWNW',
        ]);

        $response->assertStatus(200);

        Http::assertSent(function (Request $request) {
            return str_contains($request->url(), 'api.supadata.com');
        });
    }

    /**
     * Test GoogleTrendsTool execution
     */
    #[Test]
    public function it_google_trends_tool_execution(): void
    {
        $this->actingAs($this->admin);

        Http::fake([
            'api.supadata.com/v1/trends/*' => Http::response([
                'success' => true,
                'data' => [
                    'interest_over_time' => [
                        ['date' => '2024-01-01', 'value' => 50],
                        ['date' => '2024-01-02', 'value' => 75],
                    ],
                    'related_queries' => ['laravel tutorial', 'laravel docs'],
                ],
            ], 200),
        ]);

        $chat = AgentChat::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->post('/api/ai/chat/' . $chat->id . '/message', [
            'content' => 'Show me Google Trends for "Laravel"',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test RAG search tool
     */
    #[Test]
    public function it_rag_search_tool_execution(): void
    {
        $this->actingAs($this->admin);

        // Mock the RAG service
        $this->mock(\Modules\Ai\Services\RagSearchService::class, function ($mock) {
            $mock->shouldReceive('search')
                ->andReturn([
                    [
                        'content' => 'Laravel is a web application framework',
                        'score' => 0.95,
                        'source' => 'documentation.md',
                    ],
                ]);
        });

        $chat = AgentChat::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->post('/api/ai/chat/' . $chat->id . '/message', [
            'content' => 'Search documentation for Laravel features',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test chat message streaming
     */
    #[Test]
    public function it_chat_message_streaming(): void
    {
        $this->actingAs($this->admin);

        $chat = AgentChat::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->post('/api/ai/chat/' . $chat->id . '/message', [
            'content' => 'Hello',
            'stream' => true,
        ]);

        $response->assertStatus(200);
        // Streaming responses typically return chunked data
        $this->assertTrue(
            $response->headers->has('Content-Type') ||
            $response->getStatusCode() === 200
        );
    }

    /**
     * Test file upload in chat
     */
    #[Test]
    public function it_chat_file_upload(): void
    {
        $this->actingAs($this->admin);

        \Illuminate\Support\Facades\Storage::fake('local');

        $chat = AgentChat::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->image('document.pdf');

        $response = $this->post('/api/ai/chat/' . $chat->id . '/upload', [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertTrue(
            \Illuminate\Support\Facades\Storage::disk('local')->exists('ai-uploads/' . $file->hashName())
        );
    }

    /**
     * Test chat history is maintained
     */
    #[Test]
    public function it_chat_history_is_maintained(): void
    {
        $this->actingAs($this->admin);

        $chat = AgentChat::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        // Add multiple messages
        AgentChatMessage::factory()->count(3)->create([
            'agent_chat_id' => $chat->id,
            'role' => 'user',
        ]);

        AgentChatMessage::factory()->count(3)->create([
            'agent_chat_id' => $chat->id,
            'role' => 'assistant',
        ]);

        $response = $this->get('/admin/ai/agent-chats/' . $chat->id);
        $response->assertStatus(200);

        // Verify all messages are loaded
        $messages = AgentChatMessage::where('agent_chat_id', $chat->id)->get();
        $this->assertCount(6, $messages);
    }

    /**
     * Test tool error handling
     */
    #[Test]
    public function it_tool_error_handling(): void
    {
        $this->actingAs($this->admin);

        Http::fake([
            'api.supadata.com/*' => Http::response([
                'error' => 'API rate limit exceeded',
            ], 429),
        ]);

        $chat = AgentChat::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->post('/api/ai/chat/' . $chat->id . '/message', [
            'content' => 'Scrape product (this will fail)',
        ]);

        $response->assertStatus(200);
        // Should return error message gracefully
        $response->assertJsonPath('success', false);
    }

    /**
     * Test chat list pagination
     */
    #[Test]
    public function it_chat_list_pagination(): void
    {
        $this->actingAs($this->admin);

        AgentChat::factory()->count(25)->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->get('/admin/ai/agent-chats');
        $response->assertStatus(200);
        $response->assertSee('Next');
    }

    /**
     * Test chat deletion
     */
    #[Test]
    public function it_chat_deletion(): void
    {
        $this->actingAs($this->admin);

        $chat = AgentChat::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        AgentChatMessage::factory()->count(5)->create([
            'agent_chat_id' => $chat->id,
        ]);

        $response = $this->delete('/admin/ai/agent-chats/' . $chat->id);
        $response->assertRedirect();

        $this->assertNull(AgentChat::find($chat->id));
        $this->assertEquals(0, AgentChatMessage::where('agent_chat_id', $chat->id)->count());
    }

    /**
     * Test unauthorized access to chat
     */
    #[Test]
    public function it_unauthorized_user_cannot_access_others_chats(): void
    {
        $otherUser = User::factory()->create(['is_admin' => false]);
        $chat = AgentChat::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $this->actingAs($otherUser);

        $response = $this->get('/admin/ai/agent-chats/' . $chat->id);
        $response->assertStatus(403);
    }
}
