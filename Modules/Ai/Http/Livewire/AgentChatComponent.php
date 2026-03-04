<?php

namespace Modules\Ai\Http\Livewire;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Models\AgentChatMessage;
use Modules\Ai\Services\AgentFactory;
use NeuronAI\Chat\Messages\UserMessage;

class AgentChatComponent extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $chatId;

    #[Validate('required|string|min:1|max:2000')]
    public string $userMessage = '';

    public bool $isProcessing = false;
    public bool $isStreaming = false;
    public bool $isOffline = false;
    public array $chatMessages = [];
    public ?string $errorMessage = null;
    public ?string $rateLimitMessage = null;

    // File upload properties
    public $attachments = [];
    public array $uploadedFiles = [];

    // Streaming placeholder content
    public string $streamingContent = '';

    // Auto-scroll control
    public bool $shouldScrollToBottom = true;

    protected AgentChat $chat;

    public function mount(int $chatId): void
    {
        $this->chatId = $chatId;
        $this->chat = AgentChat::findOrFail($chatId);
        $this->loadChatMessages();
        $this->checkOnlineStatus();
    }

    public function getChatProperty(): AgentChat
    {
        return $this->chat ??= AgentChat::findOrFail($this->chatId);
    }

    public function loadChatMessages(): void
    {
        $messages = $this->chat->messages()
            ->orderBy('created_at')
            ->get();

        $this->chatMessages = $messages->map(function (AgentChatMessage $message) {
            return [
                'id' => $message->id,
                'role' => $message->role,
                'content' => $message->content,
                'agent_type' => $message->agent_type,
                'created_at' => $message->created_at->format('H:i'),
                'processing_time' => $message->getProcessingTime(),
                'metadata' => $message->metadata,
            ];
        })->toArray();

        $this->shouldScrollToBottom = true;
    }

    public function checkOnlineStatus(): void
    {
        $this->isOffline = !$this->isOnline();
    }

    protected function isOnline(): bool
    {
        return !app()->environment('testing') && @fsockopen('www.google.com', 80, $errno, $errstr, 5) !== false;
    }

    #[On('refresh-messages')]
    public function refreshMessages(): void
    {
        $this->loadChatMessages();
    }

    public function sendMessage(): void
    {
        $this->validate();
        $this->errorMessage = null;
        $this->rateLimitMessage = null;

        if ($this->isProcessing || !$this->chat->is_active) {
            return;
        }

        // Check rate limiting
        $key = 'ai-chat:' . auth()->id();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            $this->rateLimitMessage = "Rate limit exceeded. Please wait {$seconds} seconds.";
            Notification::make()
                ->title('Rate Limit')
                ->body($this->rateLimitMessage)
                ->warning()
                ->send();
            return;
        }
        RateLimiter::hit($key, 60);

        $this->isProcessing = true;

        try {
            // Create user message in database first
            $userMessage = $this->createUserMessage();

            // Append to chat immediately for responsiveness
            $this->appendMessage([
                'id' => $userMessage->id,
                'role' => 'user',
                'content' => $this->userMessage,
                'agent_type' => $this->chat->agent_type,
                'created_at' => $userMessage->created_at->format('H:i'),
                'processing_time' => null,
                'metadata' => null,
            ]);

            // Clear input
            $this->userMessage = '';
            $this->uploadedFiles = [];

            // Show streaming placeholder
            $this->isStreaming = true;
            $this->streamingContent = '';

            // Get agent and process
            $agentFactory = app(AgentFactory::class);
            $agent = $agentFactory->agentWithChat($this->chat);

            // Set up workflow state
            $state = new \NeuronAI\Workflow\WorkflowState();
            $state->set('chat_id', $this->chat->id);
            $state->set('user_id', auth()->id());

            if (method_exists($agent, 'setState')) {
                $agent->setState($state);
            }

            // Process the message
            $message = new UserMessage($userMessage->content);
            $response = $agent->chat($message);

            // Append assistant response
            $assistantMessage = $this->chat->messages()
                ->where('role', 'assistant')
                ->latest()
                ->first();

            if ($assistantMessage) {
                $this->appendMessage([
                    'id' => $assistantMessage->id,
                    'role' => 'assistant',
                    'content' => $assistantMessage->content,
                    'agent_type' => $assistantMessage->agent_type,
                    'created_at' => $assistantMessage->created_at->format('H:i'),
                    'processing_time' => $assistantMessage->getProcessingTime(),
                    'metadata' => $assistantMessage->metadata,
                ]);
            }

            // Clear streaming state
            $this->isStreaming = false;
            $this->streamingContent = '';

        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleRateLimitError($e);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $this->handleOfflineError();
        } catch (\Exception $e) {
            $this->handleGeneralError($e);
        } finally {
            $this->isProcessing = false;
            $this->isStreaming = false;
            $this->shouldScrollToBottom = true;
        }
    }

    protected function createUserMessage(): AgentChatMessage
    {
        $content = $this->userMessage;

        // Add file references if any
        if (!empty($this->uploadedFiles)) {
            $fileNames = collect($this->uploadedFiles)->pluck('name')->toArray();
            $content .= "\n\n[Attached files: " . implode(', ', $fileNames) . "]";
        }

        return AgentChatMessage::create([
            'chat_id' => $this->chat->id,
            'role' => 'user',
            'content' => $content,
            'agent_type' => $this->chat->agent_type,
            'metadata' => [
                'has_attachments' => !empty($this->uploadedFiles),
                'attachments' => $this->uploadedFiles,
            ],
        ]);
    }

    protected function appendMessage(array $message): void
    {
        $this->chatMessages[] = $message;
        $this->dispatch('message-added');
    }

    protected function handleRateLimitError(\Illuminate\Http\Client\RequestException $e): void
    {
        $this->errorMessage = 'Rate limit exceeded. Please try again in a moment.';
        $this->rateLimitMessage = $this->errorMessage;

        Notification::make()
            ->title('Rate Limited')
            ->body($this->errorMessage)
            ->warning()
            ->send();

        // Add system message
        AgentChatMessage::create([
            'chat_id' => $this->chat->id,
            'role' => 'system',
            'content' => 'Rate limit exceeded. Please wait before sending more messages.',
            'agent_type' => $this->chat->agent_type,
            'metadata' => [
                'error_type' => 'rate_limit',
                'error_message' => $e->getMessage(),
            ],
        ]);

        $this->loadChatMessages();
    }

    protected function handleOfflineError(): void
    {
        $this->errorMessage = 'Connection lost. Please check your internet connection.';
        $this->isOffline = true;

        Notification::make()
            ->title('Connection Error')
            ->body($this->errorMessage)
            ->danger()
            ->send();
    }

    protected function handleGeneralError(\Exception $e): void
    {
        $this->errorMessage = 'Sorry, there was an error processing your message: ' . $e->getMessage();

        Notification::make()
            ->title('Error')
            ->body($this->errorMessage)
            ->danger()
            ->send();

        // Add error message to chat
        AgentChatMessage::create([
            'chat_id' => $this->chat->id,
            'role' => 'system',
            'content' => 'Error: Unable to process message. Please try again.',
            'agent_type' => $this->chat->agent_type,
            'metadata' => [
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString(),
            ],
        ]);

        $this->loadChatMessages();
    }

    public function updatedAttachments(): void
    {
        $this->validate([
            'attachments.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf,txt,doc,docx',
        ]);

        foreach ($this->attachments as $file) {
            if ($file instanceof TemporaryUploadedFile) {
                $this->uploadedFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'size' => $this->formatFileSize($file->getSize()),
                    'type' => $file->getMimeType(),
                    'path' => $file->getRealPath(),
                    'temporaryUrl' => $file->temporaryUrl(),
                ];
            }
        }

        $this->attachments = [];
    }

    public function formatFileSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 1) . ' KB';
        } else {
            return round($bytes / (1024 * 1024), 1) . ' MB';
        }
    }

    public function removeAttachment(int $index): void
    {
        unset($this->uploadedFiles[$index]);
        $this->uploadedFiles = array_values($this->uploadedFiles);
    }

    public function retryLastMessage(): void
    {
        if (empty($this->chatMessages)) {
            return;
        }

        $lastUserMessage = collect($this->chatMessages)
            ->where('role', 'user')
            ->last();

        if ($lastUserMessage) {
            $this->userMessage = $lastUserMessage['content'];
            $this->sendMessage();
        }
    }

    #[On('connection-restored')]
    public function onConnectionRestored(): void
    {
        $this->isOffline = false;
        $this->errorMessage = null;

        Notification::make()
            ->title('Connection Restored')
            ->body('Your connection is back. You can continue chatting.')
            ->success()
            ->send();

        $this->loadChatMessages();
    }

    #[On('connection-lost')]
    public function onConnectionLost(): void
    {
        $this->isOffline = true;
    }

    public function render()
    {
        return view('modules.ai::livewire.agent-chat-component');
    }
}
