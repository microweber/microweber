<?php

declare(strict_types=1);

namespace Modules\Ai\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use MicroweberPackages\User\Models\User;
use Modules\Ai\Database\Factories\McpClientFactory;

class McpClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'allowed_scopes',
        'allowed_tools',
        'allowed_modules',
        'rate_limit_per_minute',
        'is_active',
        'last_used_at',
        'revoked_at',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    protected $casts = [
        'allowed_scopes' => 'array',
        'allowed_tools' => 'array',
        'allowed_modules' => 'array',
        'rate_limit_per_minute' => 'integer',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    protected static function newFactory(): McpClientFactory
    {
        return McpClientFactory::new();
    }

    protected static function booted(): void
    {
        static::creating(function (self $client): void {
            $client->uuid ??= (string) Str::uuid();
        });
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(McpClientToken::class);
    }

    public function tokenEvents(): HasMany
    {
        return $this->hasMany(McpClientTokenEvent::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNull('revoked_at');
    }

    public function isRevoked(): bool
    {
        return $this->revoked_at !== null;
    }

    public function isAvailable(): bool
    {
        return $this->is_active && ! $this->isRevoked();
    }

    public function allowsTool(string $tool): bool
    {
        return $this->allowsValue($this->allowed_tools, $tool);
    }

    public function allowsModule(string $module): bool
    {
        return $this->allowsValue($this->allowed_modules, $module);
    }

    public function allowsScope(string $scope): bool
    {
        return $this->allowsValue($this->allowed_scopes, $scope);
    }

    private function allowsValue(?array $allowedValues, string $candidate): bool
    {
        if (empty($allowedValues)) {
            return false;
        }

        return in_array('*', $allowedValues, true) || in_array($candidate, $allowedValues, true);
    }
}
