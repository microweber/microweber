<?php

declare(strict_types=1);

namespace Modules\Ai\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use MicroweberPackages\User\Models\User;
use Modules\Ai\Database\Factories\McpClientTokenFactory;

class McpClientToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'mcp_client_id',
        'rotated_from_token_id',
        'name',
        'token_hash',
        'token_last_eight',
        'abilities',
        'last_used_at',
        'last_used_ip',
        'last_used_user_agent',
        'expires_at',
        'revoked_at',
        'revocation_reason',
        'created_by_user_id',
        'revoked_by_user_id',
    ];

    protected $casts = [
        'abilities' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    protected static function newFactory(): McpClientTokenFactory
    {
        return McpClientTokenFactory::new();
    }

    protected static function booted(): void
    {
        static::creating(function (self $token): void {
            $token->uuid ??= (string) Str::uuid();
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(McpClient::class, 'mcp_client_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(McpClientTokenEvent::class, 'mcp_client_token_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function revokedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by_user_id');
    }

    public function rotatedFrom(): BelongsTo
    {
        return $this->belongsTo(self::class, 'rotated_from_token_id');
    }

    public function rotatedTo(): HasMany
    {
        return $this->hasMany(self::class, 'rotated_from_token_id');
    }

    public function scopeActive($query)
    {
        return $query
            ->whereNull('revoked_at')
            ->where(function ($builder) {
                $builder->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function isRevoked(): bool
    {
        return $this->revoked_at !== null;
    }

    public function isExpired(): bool
    {
        return $this->expires_at instanceof Carbon && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return ! $this->isRevoked() && ! $this->isExpired();
    }

    public function allowsScope(string $scope): bool
    {
        $abilities = $this->abilities ?? [];

        if ($abilities === []) {
            return false;
        }

        return in_array('*', $abilities, true) || in_array($scope, $abilities, true);
    }
}
