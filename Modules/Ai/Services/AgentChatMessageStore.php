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
        // v4's ChatHistory validates the message sequence (no leading non-user,
        // no two consecutive same-role turns). Our table can hold `system` UI
        // notices and duplicate user rows (the chat UI persists a user message
        // and the agent also appends one via this store), so return a sanitized,
        // strictly-alternating user/assistant sequence for the model context.
        return $this->toValidSequence(
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

        // De-duplicate the UI's pre-persisted turn: the chat component/controller
        // save the user message directly for an immediate echo, then the agent
        // appends the same turn through this store. Skip it when the thread's
        // newest row already has this exact role + content (no message_id of its
        // own), so the conversation isn't stored twice.
        $newest = AgentChatMessage::where('chat_id', $chat->id)->orderByDesc('id')->first();
        if ($newest !== null && $newest->role === $role && (string) $newest->content === $content) {
            return;
        }

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

    /**
     * Build a model-context sequence v4's HistoryTrimmer accepts: drop `system`
     * notices, require the first turn to be `user`, and collapse consecutive
     * same-role rows (keeping the latest of a run).
     *
     * @param Collection<int, AgentChatMessage> $rows
     * @return Message[]
     */
    protected function toValidSequence(Collection $rows): array
    {
        $kept = [];
        foreach ($rows as $row) {
            if ($row->role === 'system') {
                continue;
            }
            $role = in_array($row->role, ['user', 'assistant'], true) ? $row->role : 'assistant';

            if ($kept === [] && $role !== 'user') {
                continue; // history must open with a user turn
            }
            if ($kept !== [] && end($kept)->role === $role) {
                array_pop($kept); // collapse a consecutive same-role run, keep the latest
            }
            $row->role = $role;
            $kept[] = $row;
        }

        // The prior context must end on a completed exchange. A trailing `user`
        // turn is an unanswered/failed message; drop it so the current turn's
        // user message (appended by the agent) doesn't become user-after-user.
        while ($kept !== [] && end($kept)->role === 'user') {
            array_pop($kept);
        }

        return $this->toMessages(collect($kept));
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
