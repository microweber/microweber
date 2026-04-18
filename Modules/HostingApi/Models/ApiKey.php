<?php

namespace Modules\HostingApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use HasFactory;

    protected $table = 'hosting_api_keys';

    protected $fillable = [
        'name',
        'api_key',
        'api_secret',
        'is_active',
        'whitelisted_ips',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    protected $hidden = [
        'api_secret',
    ];

    /**
     * Generate a new API key pair.
     */
    public static function generateKeyPair(string $name): self
    {
        return static::create([
            'name' => $name,
            'api_key' => 'mw_' . Str::random(32),
            'api_secret' => Str::random(64),
            'is_active' => true,
        ]);
    }

    /**
     * Find by key and secret combination.
     */
    public static function findByCredentials(string $key, string $secret): ?self
    {
        return static::where('api_key', $key)
            ->where('api_secret', $secret)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Check if an IP is whitelisted (if whitelisting is enabled).
     */
    public function isIpAllowed(string $ip): bool
    {
        if (empty($this->whitelisted_ips)) {
            return true;
        }

        $allowedIps = array_map('trim', explode(',', $this->whitelisted_ips));

        return in_array($ip, $allowedIps);
    }

    /**
     * Record usage timestamp.
     */
    public function recordUsage(): void
    {
        $this->update(['last_used_at' => now()]);
    }
}
