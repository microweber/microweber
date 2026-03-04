<?php

namespace Modules\Ai\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Ai\Database\Factories\AgentChatMessageFactory;

class AgentChatMessage extends Model
{
    use HasFactory;

    protected static function newFactory(): AgentChatMessageFactory
    {
        return AgentChatMessageFactory::new();
    }

    protected $fillable = [
        'chat_id',
        'role',
        'content',
        'metadata',
        'agent_type',
        'processed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(AgentChat::class, 'chat_id');
    }

    public function searches(): HasMany
    {
        return $this->hasMany(AgentChatSearch::class, 'message_id');
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByAgentType($query, string $agentType)
    {
        return $query->where('agent_type', $agentType);
    }

    public function scopeProcessed($query)
    {
        return $query->whereNotNull('processed_at');
    }

    public function scopeUnprocessed($query)
    {
        return $query->whereNull('processed_at');
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isAssistant(): bool
    {
        return $this->role === 'assistant';
    }

    public function isSystem(): bool
    {
        return $this->role === 'system';
    }

    public function getProcessingTime(): ?float
    {
        if (!$this->processed_at || !$this->created_at) {
            return null;
        }

        return $this->processed_at->diffInMilliseconds($this->created_at) / 1000;
    }
}
