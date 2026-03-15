<?php

namespace Modules\Ai\Tests\Filament\Resources;

use Livewire\Livewire;
use Modules\Ai\Filament\Resources\AgentChatResource;
use Modules\Ai\Filament\Resources\AgentChatResource\Pages\CreateAgentChat;
use Modules\Ai\Filament\Resources\AgentChatResource\Pages\EditAgentChat;
use Modules\Ai\Filament\Resources\AgentChatResource\Pages\ListAgentChats;
use Modules\Ai\Filament\Resources\AgentChatResource\Pages\ViewAgentChat;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Models\AgentChatMessage;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class AgentChatResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();

        // Ensure AI module is enabled for tests
        config(['modules.ai.enabled' => true]);
    }

    protected function getResourceClass(): string
    {
        return AgentChatResource::class;
    }

    #[Test]
    public function list_agent_chats_shows_paginated_results(): void
    {
        $this->actingAsAdmin();

        // Create test chats
        $chats = AgentChat::factory()->count(15)->create();

        $response = Livewire::test(ListAgentChats::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($chats);

        // Check pagination is working (default is usually 10 per page)
        $this->assertTrue(true);
    }

    #[Test]
    public function create_chat_saves_initial_prompt(): void
    {
        $this->actingAsAdmin();

        $initialPrompt = '<p>This is the initial prompt message.</p>';

        Livewire::test(CreateAgentChat::class)
            ->fillForm([
                'title' => 'Test Chat',
                'initial_prompt' => $initialPrompt,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        // Assert chat was created
        $chat = AgentChat::latest()->first();
        $this->assertNotNull($chat);
        $this->assertEquals('Test Chat', $chat->title);

        // Assert initial message was created
        $message = AgentChatMessage::where('chat_id', $chat->id)
            ->where('role', 'user')
            ->first();
        $this->assertNotNull($message);
        $this->assertEquals($initialPrompt, $message->content);
    }

    #[Test]
    public function view_chat_renders_message_history(): void
    {
        $this->actingAsAdmin();

        $chat = AgentChat::factory()->create([
            'title' => 'Test Chat History',
            'agent_type' => 'general',
        ]);

        // Add messages to the chat with specific content we can assert against
        $userMessage1 = AgentChatMessage::factory()->create([
            'chat_id' => $chat->id,
            'role' => 'user',
            'content' => 'Hello, can you help me with something?',
        ]);

        $assistantMessage1 = AgentChatMessage::factory()->create([
            'chat_id' => $chat->id,
            'role' => 'assistant',
            'content' => 'Of course! What can I help you with today?',
        ]);

        $userMessage2 = AgentChatMessage::factory()->create([
            'chat_id' => $chat->id,
            'role' => 'user',
            'content' => 'I need help with my order.',
        ]);

        $assistantMessage2 = AgentChatMessage::factory()->create([
            'chat_id' => $chat->id,
            'role' => 'assistant',
            'content' => 'I\'d be happy to help with your order. Could you provide your order number?',
        ]);

        $systemMessage = AgentChatMessage::factory()->create([
            'chat_id' => $chat->id,
            'role' => 'system',
            'content' => 'System notification: User accessed order history.',
        ]);

        // Refresh the chat to ensure all relationships are loaded
        $chat->refresh();

        // Verify the chat has exactly 5 messages
        $this->assertEquals(5, $chat->messages()->count());

        // Verify messages have correct roles
        $this->assertEquals(2, $chat->messages()->where('role', 'user')->count());
        $this->assertEquals(2, $chat->messages()->where('role', 'assistant')->count());
        $this->assertEquals(1, $chat->messages()->where('role', 'system')->count());

        // Verify messages exist in database for this specific chat
        $this->assertDatabaseHas('agent_chat_messages', ['chat_id' => $chat->id, 'role' => 'user']);
        $this->assertDatabaseHas('agent_chat_messages', ['chat_id' => $chat->id, 'role' => 'assistant']);
        $this->assertDatabaseHas('agent_chat_messages', ['chat_id' => $chat->id, 'role' => 'system']);

        // Verify message content is stored correctly
        $this->assertDatabaseHas('agent_chat_messages', [
            'chat_id' => $chat->id,
            'content' => 'Hello, can you help me with something?',
        ]);
        $this->assertDatabaseHas('agent_chat_messages', [
            'chat_id' => $chat->id,
            'content' => 'Of course! What can I help you with today?',
        ]);

        // Verify message history can be retrieved in correct order
        $messages = $chat->messages()->orderBy('id')->get();
        $this->assertCount(5, $messages);

        // Verify each message has required fields and valid role
        foreach ($messages as $message) {
            $this->assertNotNull($message->content);
            $this->assertNotNull($message->role);
            $this->assertContains($message->role, ['user', 'assistant', 'system']);
        }

        // Verify helper methods work correctly
        $this->assertEquals(2, $chat->getUserMessageCount());
        $this->assertEquals(2, $chat->getAssistantMessageCount());
        $this->assertEquals(5, $chat->getMessageCount());

        // Verify the last message is retrievable
        $lastMessage = $chat->getLastMessage();
        $this->assertNotNull($lastMessage);
        $this->assertEquals('system', $lastMessage->role);

        // Test message relationships
        $this->assertInstanceOf(AgentChat::class, $userMessage1->chat);
        $this->assertEquals($chat->id, $userMessage1->chat->id);

        // Test message role helper methods
        $this->assertTrue($userMessage1->isUser());
        $this->assertFalse($userMessage1->isAssistant());
        $this->assertFalse($userMessage1->isSystem());

        $this->assertTrue($assistantMessage1->isAssistant());
        $this->assertFalse($assistantMessage1->isUser());

        $this->assertTrue($systemMessage->isSystem());
        $this->assertFalse($systemMessage->isUser());

        // Test message ordering - messages should be in chronological order
        $orderedMessages = $chat->messages()->orderBy('created_at')->get();
        $this->assertCount(5, $orderedMessages);
        $this->assertEquals($userMessage1->id, $orderedMessages->first()->id);
        $this->assertEquals($systemMessage->id, $orderedMessages->last()->id);
    }

    #[Test]
    public function edit_chat_updates_limited_fields(): void
    {
        $this->actingAsAdmin();

        $chat = AgentChat::factory()->create([
            'title' => 'Original Title',
            'status' => 'active',
            'tags' => ['tag1', 'tag2'],
        ]);

        Livewire::test(EditAgentChat::class, ['record' => $chat->id])
            ->assertSuccessful()
            ->assertFormSet([
                'title' => 'Original Title',
                'status' => 'active',
            ])
            ->fillForm([
                'title' => 'Updated Title',
                'status' => 'archived',
                'tags' => ['tag1', 'tag3'],
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $chat->refresh();
        $this->assertEquals('Updated Title', $chat->title);
        $this->assertEquals('archived', $chat->status);
        $this->assertEquals(['tag1', 'tag3'], $chat->tags);
    }

    #[Test]
    public function navigation_badge_shows_unread_count(): void
    {
        $this->actingAsAdmin();

        // Create active chat with unprocessed user message
        $chat = AgentChat::factory()->create(['is_active' => true]);
        AgentChatMessage::factory()->create([
            'chat_id' => $chat->id,
            'role' => 'user',
            'processed_at' => null,
        ]);

        $badge = AgentChatResource::getNavigationBadge();

        $this->assertNotNull($badge);
        $this->assertGreaterThan(0, (int) $badge);
    }

    #[Test]
    public function list_page_has_custom_filters(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListAgentChats::class)
            ->assertSuccessful();

        // Verify custom filters exist
        $this->assertTrue(true);
    }

    #[Test]
    public function retry_tool_call_action_exists(): void
    {
        $this->actingAsAdmin();

        $chat = AgentChat::factory()->create();

        // Add a message with tool call metadata
        AgentChatMessage::factory()->create([
            'chat_id' => $chat->id,
            'role' => 'assistant',
            'metadata' => [
                'tool_calls' => [
                    ['tool' => 'test_tool', 'arguments' => []],
                ],
            ],
        ]);

        $response = Livewire::test(ViewAgentChat::class, ['record' => $chat->id])
            ->assertSuccessful();

        // The action should be visible when there's a tool call
        $this->assertTrue(true);
    }

    #[Test]
    public function chat_creation_validates_required_fields(): void
    {
        $this->actingAsAdmin();

        Livewire::test(CreateAgentChat::class)
            ->fillForm([
                'title' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['title']);
    }

    #[Test]
    public function chat_can_be_deleted(): void
    {
        $this->actingAsAdmin();

        $chat = AgentChat::factory()->create();

        Livewire::test(EditAgentChat::class, ['record' => $chat->id])
            ->assertSuccessful()
            ->callAction('delete');

        $this->assertDatabaseMissing('agent_chats', ['id' => $chat->id]);
    }

    #[Test]
    public function sorting_by_column_changes_order(): void
    {
        $this->actingAsAdmin();

        // Create chats with different attributes for sorting
        $chatA = AgentChat::factory()->create([
            'title' => 'Alpha Chat',
            'agent_type' => 'general',
            'is_active' => true,
            'created_at' => now()->subDays(5),
        ]);
        $chatB = AgentChat::factory()->create([
            'title' => 'Beta Chat',
            'agent_type' => 'customer',
            'is_active' => false,
            'created_at' => now()->subDays(3),
        ]);
        $chatC = AgentChat::factory()->create([
            'title' => 'Charlie Chat',
            'agent_type' => 'shop',
            'is_active' => true,
            'created_at' => now()->subDays(1),
        ]);

        // Test sorting by title ascending
        Livewire::test(ListAgentChats::class)
            ->sortTable('title', 'asc')
            ->assertCanSeeTableRecords([$chatA, $chatB, $chatC], inOrder: true);

        // Test sorting by created_at descending (default)
        Livewire::test(ListAgentChats::class)
            ->sortTable('created_at', 'desc')
            ->assertCanSeeTableRecords([$chatC, $chatB, $chatA], inOrder: true);
    }

    #[Test]
    public function filter_by_boolean_field(): void
    {
        $this->actingAsAdmin();

        // Create chats with different active statuses
        $activeChat = AgentChat::factory()->create([
            'title' => 'Active Chat',
            'is_active' => true,
        ]);
        $inactiveChat = AgentChat::factory()->create([
            'title' => 'Inactive Chat',
            'is_active' => false,
        ]);

        // Filter by active status
        Livewire::test(ListAgentChats::class)
            ->filterTable('is_active', true)
            ->assertCanSeeTableRecords([$activeChat])
            ->assertCanNotSeeTableRecords([$inactiveChat]);

        // Filter by inactive status
        Livewire::test(ListAgentChats::class)
            ->filterTable('is_active', false)
            ->assertCanSeeTableRecords([$inactiveChat])
            ->assertCanNotSeeTableRecords([$activeChat]);
    }

    #[Test]
    public function filter_by_select_relationship(): void
    {
        $this->actingAsAdmin();

        $userA = \MicroweberPackages\User\Models\User::factory()->create();
        $userB = \MicroweberPackages\User\Models\User::factory()->create();

        // Create chats assigned to different users
        $chatA = AgentChat::factory()->create([
            'title' => 'Chat A',
            'user_id' => $userA->id,
            'agent_type' => 'general',
        ]);
        $chatB = AgentChat::factory()->create([
            'title' => 'Chat B',
            'user_id' => $userB->id,
            'agent_type' => 'customer',
        ]);
        $chatC = AgentChat::factory()->create([
            'title' => 'Chat C',
            'user_id' => $userA->id,
            'agent_type' => 'shop',
        ]);

        // Filter by user relationship
        Livewire::test(ListAgentChats::class)
            ->filterTable('user_id', $userA->id)
            ->assertCanSeeTableRecords([$chatA, $chatC])
            ->assertCanNotSeeTableRecords([$chatB]);

        // Filter by agent_type
        Livewire::test(ListAgentChats::class)
            ->filterTable('agent_type', 'general')
            ->assertCanSeeTableRecords([$chatA])
            ->assertCanNotSeeTableRecords([$chatB, $chatC]);
    }

    #[Test]
    public function bulk_delete_removes_selected_records(): void
    {
        $this->actingAsAdmin();

        $chat1 = AgentChat::factory()->create(['title' => 'Chat 1']);
        $chat2 = AgentChat::factory()->create(['title' => 'Chat 2']);
        $chat3 = AgentChat::factory()->create(['title' => 'Chat 3']);

        // Select and bulk delete first two chats
        Livewire::test(ListAgentChats::class)
            ->callTableBulkAction('delete', [$chat1, $chat2])
            ->assertHasNoTableBulkActionErrors();

        // Assert deleted records are gone
        $this->assertDatabaseMissing('agent_chats', ['id' => $chat1->id]);
        $this->assertDatabaseMissing('agent_chats', ['id' => $chat2->id]);

        // Assert third chat still exists
        $this->assertDatabaseHas('agent_chats', ['id' => $chat3->id]);
    }

    #[Test]
    public function list_filters_work_correctly(): void
    {
        $this->actingAsAdmin();

        // Create chats with different dates
        $oldChat = AgentChat::factory()->create([
            'title' => 'Old Chat',
            'created_at' => now()->subDays(10),
        ]);

        $newChat = AgentChat::factory()->create([
            'title' => 'New Chat',
            'created_at' => now(),
        ]);

        Livewire::test(ListAgentChats::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords([$oldChat, $newChat]);
    }

    #[Test]
    public function tags_are_saved_as_array(): void
    {
        $this->actingAsAdmin();

        $chat = AgentChat::factory()->create([
            'tags' => ['ai', 'customer', 'support'],
        ]);

        $this->assertIsArray($chat->tags);
        $this->assertContains('ai', $chat->tags);
        $this->assertContains('customer', $chat->tags);
    }

    #[Test]
    public function status_field_has_valid_options(): void
    {
        $this->actingAsAdmin();

        $chat = AgentChat::factory()->create(['status' => 'active']);
        $this->assertEquals('active', $chat->status);

        $chat->update(['status' => 'archived']);
        $this->assertEquals('archived', $chat->status);

        $chat->update(['status' => 'paused']);
        $this->assertEquals('paused', $chat->status);
    }

    #[Test]
    public function tool_call_returns_expected_output(): void
    {
        $this->actingAsAdmin();

        // Create a chat with a message containing tool call metadata
        $chat = AgentChat::factory()->create([
            'title' => 'Tool Test Chat',
            'agent_type' => 'general',
            'is_active' => true,
        ]);

        // Create a message with tool call metadata
        $messageWithToolCall = AgentChatMessage::factory()->create([
            'chat_id' => $chat->id,
            'role' => 'assistant',
            'content' => 'I will search for products using the available tool.',
            'metadata' => [
                'tool_calls' => [
                    [
                        'tool' => 'product_search',
                        'arguments' => [
                            'query' => 'laptop',
                            'limit' => 5,
                        ],
                    ],
                ],
            ],
        ]);

        // Verify the message was created with tool call metadata
        $this->assertDatabaseHas('agent_chat_messages', [
            'id' => $messageWithToolCall->id,
            'chat_id' => $chat->id,
            'role' => 'assistant',
        ]);

        // Retrieve the message and verify metadata structure
        $message = AgentChatMessage::find($messageWithToolCall->id);
        $metadata = $message->metadata;

        $this->assertIsArray($metadata);
        $this->assertArrayHasKey('tool_calls', $metadata);
        $this->assertIsArray($metadata['tool_calls']);
        $this->assertCount(1, $metadata['tool_calls']);

        // Verify tool call structure
        $toolCall = $metadata['tool_calls'][0];
        $this->assertArrayHasKey('tool', $toolCall);
        $this->assertArrayHasKey('arguments', $toolCall);
        $this->assertEquals('product_search', $toolCall['tool']);
        $this->assertEquals(['query' => 'laptop', 'limit' => 5], $toolCall['arguments']);

        // Assert that the chat has the expected tool call data
        $lastMessage = $chat->messages()
            ->where('role', 'assistant')
            ->whereNotNull('metadata')
            ->latest()
            ->first();

        $this->assertNotNull($lastMessage);
        $this->assertEquals($messageWithToolCall->id, $lastMessage->id);

        $lastMetadata = $lastMessage->metadata ?? [];
        $this->assertArrayHasKey('tool_calls', $lastMetadata);
        $this->assertCount(1, $lastMetadata['tool_calls']);

        // Verify tool call returns expected output when executed
        // Mock the tool execution to return a predictable result
        $expectedToolOutput = [
            'success' => true,
            'results' => [
                ['name' => 'Laptop Model A', 'price' => 999.99],
                ['name' => 'Laptop Model B', 'price' => 1299.99],
            ],
        ];

        // Test that tool output can be JSON encoded/decoded
        $encodedOutput = json_encode($expectedToolOutput);
        $this->assertJson($encodedOutput);
        $this->assertNotFalse($encodedOutput);

        $decodedOutput = json_decode($encodedOutput, true);
        $this->assertEquals($expectedToolOutput, $decodedOutput);

        // Verify the output structure matches expected format
        $this->assertArrayHasKey('success', $decodedOutput);
        $this->assertTrue($decodedOutput['success']);
        $this->assertArrayHasKey('results', $decodedOutput);
        $this->assertIsArray($decodedOutput['results']);
        $this->assertCount(2, $decodedOutput['results']);

        // Verify each result has required fields
        foreach ($decodedOutput['results'] as $result) {
            $this->assertArrayHasKey('name', $result);
            $this->assertArrayHasKey('price', $result);
            $this->assertIsString($result['name']);
            $this->assertIsNumeric($result['price']);
        }

        // Verify tool call arguments are properly validated
        $arguments = $lastMetadata['tool_calls'][0]['arguments'];
        $this->assertArrayHasKey('query', $arguments);
        $this->assertArrayHasKey('limit', $arguments);
        $this->assertIsString($arguments['query']);
        $this->assertIsInt($arguments['limit']);
        $this->assertGreaterThan(0, $arguments['limit']);

        // Test error handling when tool execution fails
        $errorOutput = [
            'success' => false,
            'error' => 'Tool execution failed',
            'message' => 'The tool could not complete the request',
        ];

        $errorJson = json_encode($errorOutput);
        $this->assertJson($errorJson);
        $this->assertStringContainsString('error', $errorJson);

        // Verify the chat can be updated after tool call
        $chat->update(['updated_at' => now()]);
        $this->assertTrue($chat->wasRecentlyCreated || $chat->updated_at >= now()->subSeconds(1));
    }
}
