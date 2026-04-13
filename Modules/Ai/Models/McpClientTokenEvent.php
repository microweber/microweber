<?php

declare(strict_types=1);

namespace Modules\Ai\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MicroweberPackages\User\Models\User;

class McpClientTokenEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'mcp_client_id',
        'mcp_client_token_id',
        'actor_user_id',
        'action',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(McpClient::class, 'mcp_client_id');
    }

    public function token(): BelongsTo
    {
        return $this->belongsTo(McpClientToken::class, 'mcp_client_token_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
