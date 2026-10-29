<?php

declare(strict_types=1);

namespace Modules\Ai\Services;

use Illuminate\Support\Collection;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Models\AgentChatMessage;
use NeuronAI\Chat\Enums\MessageRole;
use NeuronAI\Chat\History\MessageStoreInterface;
use NeuronAI\Chat\Messages\Message;
use NeuronAI\Chat\Messages\ToolCallMessage;
use NeuronAI\Chat\Messages\ToolResultMessage;
use NeuronAI\Chat\Messages\UserMessage;

/**
 * NeuronAI v4 message store backed by AgentChat / AgentChatMessage (replaces the
 * removed ChatHistoryInterface implementation). Stateless: the thread ID is the
 * AgentChat id, passed to every method. Trimming archives rows (archived_at)
 * rather than deleting them.
 */
class AgentChatMessageStore implements MessageStoreInterface
{
    /** @return Message[] */
    public function loadActive(string $threadId): array
    {
        return $this->toMessages(
            $this->query($threadId)->whereNull('archived_at')->orderBy('id')->get()
        );
    }

    /** @return Message[] */
    public function loadAll(string $threadId, ?int $limit = null, ?string $before = null): array
    {
        if ($before !== null) {
            $beforeRow = $this->query($threadId)->where('message_id', $before)->first();
            if ($beforeRow === null) {
                return [];
            }
            $q = $this->query($threadId)->where('id', '<', $beforeRow->id)->orderBy('id', 'desc');
            if ($limit !== null) {
                $q->limit($limit);
            }

            return array_reverse($this->toMessages($q->get()));
        }

        if ($limit !== null) {
            return array_reverse(
                $this->toMessages($this->query($threadId)->orderBy('id', 'desc')->limit($limit)->get())
            );
        }

        return $this->toMessages($this->query($threadId)->orderBy('id')->get());
    }

    public function append(string $threadId, Message $message): void
    {
        $chat = $this->chat($threadId);
        if ($chat === null) {
            return;
        }

        $messageId = $message->getId();
        // Idempotent: a message already stored in this thread is skipped.
        if ($messageId !== '' && AgentChatMessage::where('chat_id', $chat->id)->where('message_id', $messageId)->exists()) {
            return;
        }

        [$role, $content, $metadata] = $this->describe($message);

        AgentChatMessage::create([
            'chat_id' => $chat->id,
            'message_id' => $messageId !== '' ? $messageId : null,
            'role' => $role,
            'content' => $content !== '' ? $content : 'Message content unavailable',
            'metadata' => $metadata,
            'agent_type' => $chat->agent_type,
            'processed_at' => now(),
        ]);
        $chat->touch();
    }

    public function archive(string $threadId, int $count): void
    {
        if ($count <= 0) {
            return;
        }
        $chat = $this->chat($threadId);
        if ($chat === null) {
            return;
        }
        $ids = AgentChatMessage::where('chat_id', $chat->id)
            ->whereNull('archived_at')->orderBy('id')->limit($count)->pluck('id');
        if ($ids->isNotEmpty()) {
            AgentChatMessage::whereIn('id', $ids)->update(['archived_at' => now()]);
        }
    }

    public function clear(string $threadId): void
    {
        $chat = $this->chat($threadId);
        if ($chat !== null) {
            $chat->messages()->delete();
        }
    }

    protected function chat(string $threadId): ?AgentChat
    {
        return AgentChat::find($threadId);
    }

    protected function query(string $threadId)
    {
        return AgentChatMessage::where('chat_id', $threadId);
    }

    /**
     * @param Collection<int, AgentChatMessage> $rows
     * @return Message[]
     */
    protected function toMessages(Collection $rows): array
    {
        $messages = [];
        foreach ($rows as $row) {
            $messages[] = $this->toMessage($row);
        }

        return $messages;
    }

    protected function toMessage(AgentChatMessage $row): Message
    {
        $role = match ($row->role) {
            'assistant' => MessageRole::ASSISTANT,
            'system' => MessageRole::SYSTEM,
            'tool' => MessageRole::TOOL,
            default => MessageRole::USER,
        };

        // Tool call/result rows were stored as plain text (we never reconstruct
        // ToolCallMessage/ToolResultMessage — they need live ToolInterface objects).
        $message = $role === MessageRole::USER
            ? new UserMessage((string) $row->content)
            : new Message($role, (string) $row->content);

        if (!empty($row->message_id)) {
            $message->setId($row->message_id);
        }

        return $message;
    }

    /**
     * @return array{0: string, 1: string, 2: array<string, mixed>}
     */
    protected function describe(Message $message): array
    {
        $role = $message->getRole();
        $content = $message->getContent() ?? '';
        $metadata = [];

        if ($message instanceof ToolCallMessage) {
            $metadata['message_type'] = 'tool_call';
            if ($content === '') {
                $content = 'Tool call';
            }
        } elseif ($message instanceof ToolResultMessage) {
            $metadata['message_type'] = 'tool_result';
            if ($content === '') {
                $content = 'Tool result';
            }
        }

        // The agent_chat_messages.role column only allows user/assistant/system.
        if (!in_array($role, ['user', 'assistant', 'system'], true)) {
            $role = $message instanceof UserMessage ? 'user' : 'assistant';
        }

        return [$role, $content, $metadata];
    }
}
