<?php

namespace Modules\Ai\Filament\Resources\AgentChatResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Modules\Ai\Filament\Resources\AgentChatResource;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Models\AgentChatMessage;
use Modules\Ai\Services\AgentFactory;
use NeuronAI\Chat\Messages\UserMessage;

class ViewAgentChat extends ViewRecord
{
    protected static string $resource = AgentChatResource::class;

    #[Validate('required|string|min:1|max:2000')]
    public string $userMessage = '';
    public bool $isProcessing = false;
    public array $chatMessages = [];

    public function getView(): string
    {
        return 'modules.ai::filament.resources.agent-chat-resource.pages.view-agent-chat';
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);
        $this->loadChatMessages();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit Chat Settings'),

            Actions\Action::make('clearChat')
                ->label('Clear Messages')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->messages()->delete();
                    $this->record->searches()->delete();
                    $this->loadChatMessages();

                    Notification::make()
                        ->title('Chat Cleared')
                        ->body('All messages have been deleted.')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('exportChat')
                ->label('Export Chat')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    // TODO: Implement chat export functionality
                    Notification::make()
                        ->title('Export Coming Soon')
                        ->body('Chat export functionality will be available soon.')
                        ->info()
                        ->send();
                }),

            Actions\Action::make('retryLastToolCall')
                ->label('Retry Last Tool Call')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(function () {
                    // Show only if there's a failed tool call in recent messages
                    $lastMessage = $this->record->messages()
                        ->where('role', 'assistant')
                        ->whereNotNull('metadata')
                        ->latest()
                        ->first();

                    if (!$lastMessage) {
                        return false;
                    }

                    $metadata = $lastMessage->metadata ?? [];
                    return isset($metadata['tool_calls']) || isset($metadata['error']);
                })
                ->requiresConfirmation()
                ->modalHeading('Retry Last Tool Call')
                ->modalDescription('This will attempt to retry the last failed tool call. Continue?')
                ->action(function () {
                    $this->retryLastToolCall();
                }),
        ];
    }

    public function sendMessage(): void
    {
        $this->validate();

        if ($this->isProcessing || !$this->record->is_active) {
            return;
        }

        $this->isProcessing = true;

      //  try {
            // Get the appropriate agent with chat history
            $agentFactory = app(AgentFactory::class);
            $agent = $agentFactory->agentWithChat($this->record);

            // Debug: Check if agent has tools
            $toolsCount = count($agent->getTools() ?? []);

            // Set up workflow state with chat context
            $state = new \NeuronAI\Workflow\WorkflowState();
            $state->set('chat_id', $this->record->id);
            $state->set('user_id', auth()->id());

            if (method_exists($agent, 'setState')) {
                $agent->setState($state);
            }

            // Create user message for the agent and process it
            // The agent's chat history will automatically save both user message and response
            $message = new UserMessage($this->userMessage);
            $response = $agent->chat($message);

            // Clear the input
            $this->userMessage = '';

            // Reload messages to show the conversation
            $this->loadChatMessages();
        $this->isProcessing = false;

     //   } catch (\Exception $e) {

//
//            Notification::make()
//                ->title('Error')
//                ->body('Sorry, there was an error processing your message: ' . $e->getMessage())
//                ->danger()
//                ->send();
//
//            // Add error message to chat manually since agent failed
//            AgentChatMessage::create([
//                'chat_id' => $this->record->id,
//                'role' => 'system',
//                'content' => 'Error: Unable to process message. Please try again.',
//                'metadata' => [
//                    'error' => $e->getMessage(),
//                    'timestamp' => now()->toISOString(),
//                ],
//            ]);
//
//            $this->loadChatMessages();
//        } finally {
//            $this->isProcessing = false;
//        }
    }

    protected function getErrorResponse(string $error): string
    {
        return '
        <div class="alert alert-danger">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Processing Error</h6>
            <p>I encountered an error while processing your message:</p>
            <p><code>' . htmlspecialchars($error) . '</code></p>
            <p>Please try:</p>
            <ul>
                <li>Rephrasing your question</li>
                <li>Being more specific in your request</li>
                <li>Checking if the AI service is properly configured</li>
            </ul>
        </div>';
    }

    public function loadChatMessages(): void
    {
        $messages = $this->record->messages()
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
    }

    #[On('refresh-messages')]
    public function refreshMessages(): void
    {
        $this->loadChatMessages();
    }

    public function getTitle(): string
    {
        return $this->record->title . ' - AI Chat';
    }

    public function retryLastToolCall(): void
    {
        $lastMessage = $this->record->messages()
            ->where('role', 'assistant')
            ->whereNotNull('metadata')
            ->latest()
            ->first();

        if (!$lastMessage) {
            Notification::make()
                ->title('No Tool Call Found')
                ->body('No recent tool calls to retry.')
                ->warning()
                ->send();
            return;
        }

        $metadata = $lastMessage->metadata ?? [];
        $toolCalls = $metadata['tool_calls'] ?? [];

        if (empty($toolCalls)) {
            Notification::make()
                ->title('No Tool Calls')
                ->body('The last message does not contain any tool calls.')
                ->warning()
                ->send();
            return;
        }

        $this->isProcessing = true;

        try {
            // Get the agent with chat history
            $agentFactory = app(AgentFactory::class);
            $agent = $agentFactory->agentWithChat($this->record);

            // Retry the tool calls
            foreach ($toolCalls as $toolCall) {
                if (isset($toolCall['tool'])) {
                    // Find and execute the tool
                    $tools = $agent->getTools() ?? [];
                    foreach ($tools as $tool) {
                        if ($tool->getName() === $toolCall['tool']) {
                            $result = $tool->execute($toolCall['arguments'] ?? []);

                            // Add system message with retry result
                            AgentChatMessage::create([
                                'chat_id' => $this->record->id,
                                'role' => 'system',
                                'content' => "Tool '{$toolCall['tool']}' retried successfully. Result: " . json_encode($result),
                                'agent_type' => $this->record->agent_type,
                                'metadata' => [
                                    'tool_retry' => true,
                                    'original_tool' => $toolCall['tool'],
                                    'result' => $result,
                                ],
                            ]);
                            break;
                        }
                    }
                }
            }

            $this->loadChatMessages();

            Notification::make()
                ->title('Tool Call Retried')
                ->body('The last tool call has been successfully retried.')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Retry Failed')
                ->body('Failed to retry tool call: ' . $e->getMessage())
                ->danger()
                ->send();

            // Log the error
            AgentChatMessage::create([
                'chat_id' => $this->record->id,
                'role' => 'system',
                'content' => 'Error retrying tool call: ' . $e->getMessage(),
                'agent_type' => $this->record->agent_type,
                'metadata' => [
                    'error' => $e->getMessage(),
                    'tool_retry_failed' => true,
                ],
            ]);
        } finally {
            $this->isProcessing = false;
            $this->loadChatMessages();
        }
    }
}
