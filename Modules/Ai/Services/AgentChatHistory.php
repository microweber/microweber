<?php

declare(strict_types=1);

namespace Modules\Ai\Services;

use Illuminate\Support\Facades\DB;
use JsonSerializable;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Models\AgentChatMessage;
use NeuronAI\Chat\Enums\MessageRole;
use NeuronAI\Chat\History\ChatHistoryInterface;
use NeuronAI\Chat\Messages\Message;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Chat\Messages\ToolCallMessage;
use NeuronAI\Chat\Messages\ToolResultMessage;

class AgentChatHistory implements ChatHistoryInterface, JsonSerializable
{
    protected AgentChat $chat;
    protected int $contextWindow;
    protected array $messagesCache = [];
    protected bool $cacheLoaded = false;

    public function __construct(
        AgentChat $chat,
        int $contextWindow = 50000
    ) {
        $this->chat = $chat;
        $this->contextWindow = $contextWindow;
    }

    public function add(Message $message): void
    {
        $this->messagesCache[] = $message;
        
        // Persist to database
        $this->persistMessage($message);
        
        // Trim messages if context window is exceeded
        $this->trimToContextWindow();
    }

    public function addMessage(Message $message): ChatHistoryInterface
    {
        $this->add($message);
        return $this;
    }

    public function getMessages(): array
    {
        if (!$this->cacheLoaded) {
            $this->loadFromDatabase();
        }
        
        return $this->messagesCache;
    }

    public function getLastMessage(): Message|false
    {
        if (!$this->cacheLoaded) {
            $this->loadFromDatabase();
        }
        
        $lastMessage = end($this->messagesCache);
        return $lastMessage !== false ? $lastMessage : false;
    }

    public function setMessages(array $messages): ChatHistoryInterface
    {
        $this->messagesCache = $messages;
        $this->cacheLoaded = true;
        
        // Clear existing messages and save new ones
        $this->chat->messages()->delete();
        foreach ($messages as $message) {
            $this->persistMessage($message);
        }
        
        $this->trimToContextWindow();
        return $this;
    }

    public function clear(): ChatHistoryInterface
    {
        $this->messagesCache = [];
        $this->cacheLoaded = true;
        
        // Clear database messages for this chat
        $this->chat->messages()->delete();
        return $this;
    }

    public function isEmpty(): bool
    {
        if (!$this->cacheLoaded) {
            $this->loadFromDatabase();
        }
        
        return empty($this->messagesCache);
    }

    public function getContextWindow(): int
    {
        return $this->contextWindow;
    }

    public function count(): int
    {
        if (!$this->cacheLoaded) {
            $this->loadFromDatabase();
        }
        
        return count($this->messagesCache);
    }

    public function toArray(): array
    {
        return $this->getMessages();
    }

    public function flushAll(): ChatHistoryInterface
    {
        // Clear all chat history for all chats (dangerous operation)
        // In our implementation, we'll just clear this specific chat
        return $this->clear();
    }

    public function calculateTotalUsage(): int
    {
        if (!$this->cacheLoaded) {
            $this->loadFromDatabase();
        }

        $totalTokens = 0;
        foreach ($this->messagesCache as $message) {
            $totalTokens += $this->estimateTokens($message->getContent());
        }

        return $totalTokens;
    }

    public function jsonSerialize(): array
    {
        return [
            'chat_id' => $this->chat->id,
            'agent_type' => $this->chat->agent_type,
            'context_window' => $this->contextWindow,
            'message_count' => $this->count(),
            'estimated_tokens' => $this->calculateTotalUsage(),
            'messages' => array_map(function($message) {
                return [
                    'role' => $this->getNeuronRoleAsString($message->getRole()),
                    'content' => $message->getContent(),
                    'timestamp' => now()->toISOString(),
                ];
            }, $this->getMessages()),
        ];
    }

    protected function loadFromDatabase(): void
    {
        $this->messagesCache = [];
        
        $dbMessages = $this->chat->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($dbMessages as $dbMessage) {
            $message = $this->convertDbMessageToNeuronMessage($dbMessage);
            if ($message) {
                $this->messagesCache[] = $message;
            }
        }
        
        $this->cacheLoaded = true;
        $this->trimToContextWindow();
    }

    protected function persistMessage(Message $message): void
    {
        $role = $this->getNeuronRoleAsString($message->getRole());
        $content = $message->getContent() ?? '';
        $metadata = [];

        // Handle special message types
        if ($message instanceof ToolCallMessage) {
            // Store the tool call information in metadata
            $metadata['message_type'] = 'tool_call';
            // Try to get tool call data if available
            try {
                $metadata['tool_calls'] = $message->getTools();
                // For tool calls, if content is empty, create a summary
                if (empty($content)) {
                    $toolNames = array_map(function($tool) {
                        // Handle different ways tool names might be stored
                        if (is_object($tool) && method_exists($tool, 'getName')) {
                            return $tool->getName();
                        } elseif (is_object($tool) && property_exists($tool, 'name')) {
                            return $tool->name;
                        } elseif (is_array($tool) && isset($tool['name'])) {
                            return $tool['name'];
                        } else {
                            return 'tool';
                        }
                    }, $metadata['tool_calls']);
                    $content = 'Tool calls: ' . implode(', ', $toolNames);
                }
            } catch (\Exception $e) {
                $metadata['tool_call_error'] = $e->getMessage();
                $content = $content ?: 'Tool call execution';
            }
        } elseif ($message instanceof ToolResultMessage) {
            // Store the tool result information in metadata
            $metadata['message_type'] = 'tool_result';
            // Try to get tool result data if available
            try {
                $metadata['tool_result'] = $message->getTools();
                // For tool results, if content is empty, create a summary
                if (empty($content)) {
                    $toolCount = count($metadata['tool_result']);
                    $content = "Tool results: {$toolCount} tool(s) executed";
                }
            } catch (\Exception $e) {
                $metadata['tool_result_error'] = $e->getMessage();
                $content = $content ?: 'Tool result processing';
            }
        }

        // Ensure content is never null or empty
        if (empty($content)) {
            $content = 'Message content unavailable';
        }

        AgentChatMessage::create([
            'chat_id' => $this->chat->id,
            'role' => $role,
            'content' => $content,
            'metadata' => $metadata,
            'agent_type' => $this->chat->agent_type,
            'processed_at' => now(),
        ]);

        // Update chat's last activity
        $this->chat->touch();
    }

    protected function convertDbMessageToNeuronMessage(AgentChatMessage $dbMessage): ?Message
    {
        $role = $this->getStringRoleAsNeuronRole($dbMessage->role);
        $content = $dbMessage->content;
        $metadata = $dbMessage->metadata ?? [];

        // Handle special message types based on metadata
        // Note: We don't reconstruct ToolCallMessage/ToolResultMessage from database
        // because they require actual ToolInterface objects, not serialized arrays.
        // Instead, we create regular messages and preserve tool info in metadata.
        if (isset($metadata['message_type'])) {
            switch ($metadata['message_type']) {
                case 'tool_call':
                    // Create a regular assistant message with tool call info in content
                    if (isset($metadata['tool_calls']) && is_array($metadata['tool_calls'])) {
                        $toolNames = array_map(function($tool) {
                            return $tool['name'] ?? 'Unknown tool';
                        }, $metadata['tool_calls']);
                        $enhancedContent = $content ?: ('Tool calls: ' . implode(', ', $toolNames));
                        return new Message(MessageRole::ASSISTANT, $enhancedContent);
                    }
                    break;
                case 'tool_result':
                    // Create a regular user message with tool result info
                    if (isset($metadata['tool_result'])) {
                        $enhancedContent = $content ?: 'Tool execution results';
                        return new UserMessage($enhancedContent);
                    }
                    break;
            }
        }

        // Create standard message based on role
        switch ($role) {
            case MessageRole::USER:
                return new UserMessage($content);
            case MessageRole::ASSISTANT:
                return new Message(MessageRole::ASSISTANT, $content);
            case MessageRole::SYSTEM:
                return new Message(MessageRole::SYSTEM, $content);
            default:
                return new Message($role, $content);
        }
    }

    protected function getNeuronRoleAsString(MessageRole|string $role): string
    {
        if (is_string($role)) {
            return $role;
        }
        
        return match ($role) {
            MessageRole::USER => 'user',
            MessageRole::ASSISTANT => 'assistant',
            MessageRole::SYSTEM => 'system',
            MessageRole::TOOL => 'tool',
        };
    }

    protected function getStringRoleAsNeuronRole(string $role): MessageRole
    {
        return match ($role) {
            'user' => MessageRole::USER,
            'assistant' => MessageRole::ASSISTANT,
            'system' => MessageRole::SYSTEM,
            'tool' => MessageRole::TOOL,
            default => MessageRole::USER,
        };
    }

    protected function trimToContextWindow(): void
    {
        if (empty($this->messagesCache)) {
            return;
        }

        $totalTokens = 0;
        $messagesToKeep = [];
        
        // Iterate from the most recent message backwards
        for ($i = count($this->messagesCache) - 1; $i >= 0; $i--) {
            $message = $this->messagesCache[$i];
            $messageTokens = $this->estimateTokens($message->getContent());
            
            // If adding this message would exceed context window, stop
            if ($totalTokens + $messageTokens > $this->contextWindow) {
                break;
            }
            
            $totalTokens += $messageTokens;
            array_unshift($messagesToKeep, $message);
        }
        
        // If we trimmed messages, update the database to reflect this
        if (count($messagesToKeep) < count($this->messagesCache)) {
            $this->messagesCache = $messagesToKeep;

            // Optional: Remove old messages from database that exceed context window
            // This is commented out to preserve full chat history in database
            // You can enable this if you want to permanently trim old messages
            /*
            $keepMessageCount = count($messagesToKeep);
            $this->chat->messages()
                ->orderBy('created_at', 'desc')
                ->skip($keepMessageCount)
                ->delete();
            */
        }
    }

    protected function estimateTokens(?string $content): int
    {
        // Handle null content
        if ($content === null) {
            return 0;
        }
        
        // Rough estimation: 1 token ≈ 4 characters
        // This is a simple approximation; you might want to use a more accurate tokenizer
        return (int) ceil(strlen($content) / 4);
    }

    /**
     * Get the AgentChat model instance
     */
    public function getChat(): AgentChat
    {
        return $this->chat;
    }

    /**
     * Load messages from an existing conversation array
     */
    public function loadConversation(array $messages): void
    {
        $this->messagesCache = [];
        
        foreach ($messages as $message) {
            if ($message instanceof Message) {
                $this->messagesCache[] = $message;
            }
        }
        
        $this->cacheLoaded = true;
        $this->trimToContextWindow();
    }

    /**
     * Get message statistics
     */
    public function getStats(): array
    {
        if (!$this->cacheLoaded) {
            $this->loadFromDatabase();
        }

        $stats = [
            'total_messages' => count($this->messagesCache),
            'user_messages' => 0,
            'assistant_messages' => 0,
            'system_messages' => 0,
            'tool_messages' => 0,
            'estimated_tokens' => 0,
        ];

        foreach ($this->messagesCache as $message) {
            $stats['estimated_tokens'] += $this->estimateTokens($message->content);
            
            switch ($message->role) {
                case MessageRole::USER:
                    $stats['user_messages']++;
                    break;
                case MessageRole::ASSISTANT:
                    $stats['assistant_messages']++;
                    break;
                case MessageRole::SYSTEM:
                    $stats['system_messages']++;
                    break;
                case MessageRole::TOOL:
                    $stats['tool_messages']++;
                    break;
            }
        }

        return $stats;
    }
}
