<?php

namespace Modules\Ai\Filament\Resources\AgentChatResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Modules\Ai\Filament\Resources\AgentChatResource;
use Modules\Ai\Models\AgentChatMessage;

class ViewAgentChat extends ViewRecord
{
    protected static string $resource = AgentChatResource::class;

    public function getView(): string
    {
        return 'modules.ai::filament.resources.agent-chat-resource.pages.view-agent-chat';
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);
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
                ->modalHeading('Clear Chat')
                ->modalDescription('This will delete all messages in this chat. This action cannot be undone.')
                ->action(function () {
                    $this->record->messages()->delete();
                    $this->record->searches()->delete();

                    // Refresh the Livewire component
                    $this->dispatch('refresh-messages');

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
                    $messages = $this->record->messages()
                        ->orderBy('created_at')
                        ->get();

                    $export = [];
                    foreach ($messages as $message) {
                        $export[] = [
                            'role' => $message->role,
                            'content' => $message->content,
                            'created_at' => $message->created_at->toISOString(),
                        ];
                    }

                    $json = json_encode($export, JSON_PRETTY_PRINT);
                    $filename = 'chat-' . $this->record->id . '-' . now()->format('Y-m-d') . '.json';

                    return response()->streamDownload(function () use ($json) {
                        echo $json;
                    }, $filename, [
                        'Content-Type' => 'application/json',
                    ]);
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

        $agentFactory = app(\Modules\Ai\Services\AgentFactory::class);
        $agent = $agentFactory->agentWithChat($this->record);

        try {
            foreach ($toolCalls as $toolCall) {
                if (isset($toolCall['tool'])) {
                    $tools = $agent->getTools() ?? [];
                    foreach ($tools as $tool) {
                        if ($tool->getName() === $toolCall['tool']) {
                            $result = $tool->execute($toolCall['arguments'] ?? []);

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

            // Refresh the Livewire component
            $this->dispatch('refresh-messages');

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

            $this->dispatch('refresh-messages');
        }
    }
}
