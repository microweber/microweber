<?php

namespace Modules\Ai\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MicroweberPackages\User\Models\User;
use Modules\Ai\Database\Factories\AgentChatFactory;

class AgentChat extends Model
{
    use HasFactory;

    protected static function newFactory(): AgentChatFactory
    {
        return AgentChatFactory::new();
    }

    protected $fillable = [
        'title',
        'description',
        'agent_type',
        'user_id',
        'metadata',
        'is_active',
        'status',
        'tags',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'tags' => 'array',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(AgentChatMessage::class, 'chat_id');
    }

    public function searches(): HasMany
    {
        return $this->hasMany(AgentChatSearch::class, 'chat_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getLastMessage(): ?AgentChatMessage
    {
        return $this->messages()->latest()->first();
    }

    public function getMessageCount(): int
    {
        return $this->messages()->count();
    }

    public function getUserMessageCount(): int
    {
        return $this->messages()->where('role', 'user')->count();
    }

    public function getAssistantMessageCount(): int
    {
        return $this->messages()->where('role', 'assistant')->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByAgentType($query, string $agentType)
    {
        return $query->where('agent_type', $agentType);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get memory/history statistics for this chat
     */
    public function getMemoryStats(): array
    {
        $messages = $this->messages;
        
        $stats = [
            'total_messages' => $messages->count(),
            'user_messages' => $messages->where('role', 'user')->count(),
            'assistant_messages' => $messages->where('role', 'assistant')->count(),
            'system_messages' => $messages->where('role', 'system')->count(),
            'estimated_tokens' => 0,
            'conversation_length' => 0,
            'first_message_at' => null,
            'last_message_at' => null,
            'avg_response_time' => null,
        ];

        if ($messages->count() > 0) {
            // Calculate estimated tokens (rough approximation)
            $totalContent = $messages->pluck('content')->implode(' ');
            $stats['estimated_tokens'] = (int) ceil(strlen($totalContent) / 4);
            
            // Conversation timespan
            $first = $messages->sortBy('created_at')->first();
            $last = $messages->sortByDesc('created_at')->first();
            
            $stats['first_message_at'] = $first->created_at;
            $stats['last_message_at'] = $last->created_at;
            
            if ($first->created_at && $last->created_at) {
                $stats['conversation_length'] = $last->created_at->diffInMinutes($first->created_at);
            }
            
            // Average response time for assistant messages
            $assistantMessages = $messages->where('role', 'assistant')
                ->whereNotNull('processed_at');
            
            if ($assistantMessages->count() > 0) {
                $totalResponseTime = $assistantMessages->sum(function($message) {
                    return $message->getProcessingTime() ?? 0;
                });
                $stats['avg_response_time'] = $totalResponseTime / $assistantMessages->count();
            }
        }

        return $stats;
    }

    /**
     * Check if chat has reached memory limits
     */
    public function isNearMemoryLimit(int $contextWindow = 50000): array
    {
        $stats = $this->getMemoryStats();
        $utilizationPercent = ($stats['estimated_tokens'] / $contextWindow) * 100;
        
        return [
            'is_near_limit' => $utilizationPercent > 80,
            'is_over_limit' => $utilizationPercent > 100,
            'utilization_percent' => round($utilizationPercent, 2),
            'estimated_tokens' => $stats['estimated_tokens'],
            'context_window' => $contextWindow,
            'remaining_tokens' => max(0, $contextWindow - $stats['estimated_tokens']),
        ];
    }
}
