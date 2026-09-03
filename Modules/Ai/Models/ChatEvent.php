<?php

declare(strict_types=1);

namespace Modules\Ai\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A per-chat/per-session AI event or piece of state (see the ai_chat_events
 * migration). Used for the autonomous loop's cross-compaction handoff
 * (type='loop_state') and general event logging.
 */
class ChatEvent extends Model
{
    protected $table = 'ai_chat_events';

    protected $fillable = [
        'chat_id',
        'session_id',
        'type',
        'key',
        'data',
    ];

    /**
     * Upsert the single current row for a (session, type[, key]) so callers can
     * keep one live value (e.g. the loop handoff) rather than appending forever.
     */
    public static function put(?string $sessionId, string $type, string $data, ?string $key = null, ?int $chatId = null): self
    {
        $row = static::query()
            ->where('type', $type)
            ->where('session_id', $sessionId)
            ->when($key !== null, fn ($q) => $q->where('key', $key))
            ->orderByDesc('id')
            ->first();

        if (!$row) {
            $row = new static();
            $row->session_id = $sessionId;
            $row->type = $type;
            $row->key = $key;
        }
        $row->chat_id = $chatId ?? $row->chat_id;
        $row->data = $data;
        $row->save();

        return $row;
    }

    /**
     * Read the latest value for a type, preferring the given session but falling
     * back to the most recent row of that type across sessions.
     */
    public static function latest_value(string $type, ?string $sessionId = null): ?string
    {
        $q = static::query()->where('type', $type);
        if ($sessionId) {
            $row = (clone $q)->where('session_id', $sessionId)->orderByDesc('id')->first();
            if ($row) {
                return $row->data;
            }
        }
        $row = $q->orderByDesc('id')->first();
        return $row?->data;
    }
}
