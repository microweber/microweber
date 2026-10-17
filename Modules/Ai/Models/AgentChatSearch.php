<?php

namespace Modules\Ai\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentChatSearch extends Model
{
    use FiresModelLifecycleEvents;

    protected $fillable = [
        'chat_id',
        'message_id',
        'query',
        'results',
        'metadata',
        'relevance_score',
    ];

    protected $casts = [
        'metadata' => 'array',
        'relevance_score' => 'float',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(AgentChat::class, 'chat_id');
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(AgentChatMessage::class, 'message_id');
    }

    public function scopeByQuery($query, string $searchQuery)
    {
        return $query->where('query', 'like', '%' . $searchQuery . '%');
    }

    public function scopeByRelevance($query, float $minScore = 0.5)
    {
        return $query->where('relevance_score', '>=', $minScore);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
