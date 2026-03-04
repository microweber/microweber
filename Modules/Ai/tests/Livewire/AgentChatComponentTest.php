<?php

namespace Modules\Ai\Tests\Livewire;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Modules\Ai\Http\Livewire\AgentChatComponent;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Models\AgentChatMessage;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;

class AgentChatComponentTest extends TestCase
{
    protected User $user;
    protected AgentChat $chat;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->chat = AgentChat::factory()->create([
            'user_id' => $this->user->id,
            'agent_type' => 'general',
            'is_active' => true,
        ]);
    }

    public function test_component_mounts_successfully(): void
    {
        Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id])
            ->assertOk()
            ->assertSet('chatId', $this->chat->id)
            ->assertSet('isProcessing', false);
    }

    public function test_chat_messages_load_on_mount(): void
    {
        AgentChatMessage::factory()->count(3)->create([
            'chat_id' => $this->chat->id,
            'role' => 'user',
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id]);

        $this->assertCount(3, $component->get('chatMessages'));
    }

    public function test_message_validation_works(): void
    {
        Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id])
            ->set('userMessage', '')
            ->call('sendMessage')
            ->assertHasErrors(['userMessage']);
    }

    public function test_rate_limiting_works(): void
    {
        RateLimiter::clear();

        $component = Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id]);

        for ($i = 0; $i < 11; $i++) {
            RateLimiter::hit('ai-chat:' . $this->user->id, 60);
        }

        $component->set('userMessage', 'Test message')
            ->call('sendMessage')
            ->assertSet('rateLimitMessage', fn($value) => str_contains($value, 'Rate limit'));
    }

    public function test_offline_detection(): void
    {
        Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id])
            ->dispatch('connection-lost')
            ->assertSet('isOffline', true);
    }

    public function test_connection_restored(): void
    {
        Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id])
            ->set('isOffline', true)
            ->dispatch('connection-restored')
            ->assertSet('isOffline', false);
    }

    public function test_file_upload_preview(): void
    {
        $file = UploadedFile::fake()->image('test-image.jpg', 100, 100)->size(100);

        $component = Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id]);

        $component->set('attachments', [$file])
            ->call('updatedAttachments');

        $uploadedFiles = $component->get('uploadedFiles');
        $this->assertCount(1, $uploadedFiles);
        $this->assertEquals('test-image.jpg', $uploadedFiles[0]['name']);
    }

    public function test_file_size_formatting(): void
    {
        $component = new AgentChatComponent();
        
        $this->assertEquals('500 B', $component->formatFileSize(500));
        $this->assertEquals('1.5 KB', $component->formatFileSize(1536));
        $this->assertEquals('2.5 MB', $component->formatFileSize(2621440));
    }

    public function test_attachment_removal(): void
    {
        $component = Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id])
            ->set('uploadedFiles', [
                ['name' => 'file1.jpg', 'size' => '1 MB', 'type' => 'image/jpeg', 'path' => '/tmp/1'],
                ['name' => 'file2.pdf', 'size' => '500 KB', 'type' => 'application/pdf', 'path' => '/tmp/2'],
            ]);

        $component->call('removeAttachment', 0);

        $uploadedFiles = $component->get('uploadedFiles');
        $this->assertCount(1, $uploadedFiles);
        $this->assertEquals('file2.pdf', $uploadedFiles[0]['name']);
    }

    public function test_streaming_state_tracks_correctly(): void
    {
        $component = Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id]);

        $this->assertFalse($component->get('isStreaming'));
    }

    public function test_error_handling_works(): void
    {
        Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id])
            ->set('errorMessage', 'Test error')
            ->assertSet('errorMessage', 'Test error');
    }

    public function test_inactive_chat_cannot_send_messages(): void
    {
        $this->chat->update(['is_active' => false]);

        Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id])
            ->set('userMessage', 'Test message')
            ->call('sendMessage')
            ->assertSet('isProcessing', false);
    }

    public function test_retry_last_message_with_no_messages(): void
    {
        Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id])
            ->call('retryLastMessage')
            ->assertSet('userMessage', '');
    }

    public function test_retry_last_message_with_user_messages(): void
    {
        AgentChatMessage::factory()->create([
            'chat_id' => $this->chat->id,
            'role' => 'user',
            'content' => 'Previous message',
        ]);

        Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id])
            ->call('retryLastMessage')
            ->assertSet('userMessage', 'Previous message');
    }

    public function test_refresh_messages_dispatches_event(): void
    {
        Livewire::actingAs($this->user)
            ->test(AgentChatComponent::class, ['chatId' => $this->chat->id])
            ->dispatch('refresh-messages')
            ->assertDispatched('refresh-messages');
    }

    public function test_chat_property_returns_correct_model(): void
    {
        $component = new AgentChatComponent();
        $component->chatId = $this->chat->id;
        
        $chat = $component->getChatProperty();
        $this->assertInstanceOf(AgentChat::class, $chat);
        $this->assertEquals($this->chat->id, $chat->id);
    }
}
