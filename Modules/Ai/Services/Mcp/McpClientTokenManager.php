<?php

declare(strict_types=1);

namespace Modules\Ai\Services\Mcp;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use MicroweberPackages\User\Models\User;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Models\McpClientTokenEvent;

class McpClientTokenManager
{
    public function createClient(array $attributes, ?User $actor = null): McpClient
    {
        return DB::transaction(function () use ($attributes, $actor): McpClient {
            $client = new McpClient();
            $client->fill($attributes);
            $client->slug = $this->generateUniqueSlug($attributes['slug'] ?? $attributes['name']);
            $client->created_by_user_id = $actor?->id;
            $client->updated_by_user_id = $actor?->id;
            $client->save();

            $this->recordEvent(
                client: $client,
                token: null,
                action: 'client.created',
                actor: $actor,
                metadata: [
                    'allowed_scopes' => $client->allowed_scopes,
                    'allowed_tools' => $client->allowed_tools,
                    'allowed_modules' => $client->allowed_modules,
                    'rate_limit_per_minute' => $client->rate_limit_per_minute,
                ],
            );

            return $client;
        });
    }

    public function issueToken(
        McpClient $client,
        string $name,
        array $abilities = [],
        ?CarbonInterface $expiresAt = null,
        ?User $actor = null,
        ?int $rotatedFromTokenId = null,
        ?int $rateLimitPerMinute = null,
    ): GeneratedMcpClientToken {
        // Apply the configured default-TTL when the caller didn't
        // pin an expiry. AI_MCP_TOKEN_DEFAULT_TTL_DAYS=0 disables
        // the default — tokens issued under that config stay
        // forever-valid. Caller-supplied expiresAt always wins.
        if ($expiresAt === null) {
            $defaultTtlDays = (int) config('modules.ai.mcp.token_default_ttl_days', 90);
            if ($defaultTtlDays > 0) {
                $expiresAt = \Carbon\CarbonImmutable::now()->addDays($defaultTtlDays);
            }
        }

        return DB::transaction(function () use ($client, $name, $abilities, $expiresAt, $actor, $rotatedFromTokenId, $rateLimitPerMinute): GeneratedMcpClientToken {
            $secret = Str::random(64);

            $token = $client->tokens()->create([
                'name' => $name,
                'token_hash' => Hash::make($secret),
                'token_last_eight' => substr($secret, -8),
                'abilities' => array_values(array_unique($abilities !== [] ? $abilities : ($client->allowed_scopes ?? []))),
                'rate_limit_per_minute' => $rateLimitPerMinute,
                'expires_at' => $expiresAt,
                'created_by_user_id' => $actor?->id,
                'rotated_from_token_id' => $rotatedFromTokenId,
            ]);

            $this->recordEvent(
                client: $client,
                token: $token,
                action: $rotatedFromTokenId === null ? 'token.issued' : 'token.rotated',
                actor: $actor,
                metadata: [
                    'token_name' => $token->name,
                    'token_last_eight' => $token->token_last_eight,
                    'abilities' => $token->abilities,
                    'rate_limit_per_minute' => $token->rate_limit_per_minute,
                    'expires_at' => $token->expires_at?->toIso8601String(),
                    'rotated_from_token_id' => $rotatedFromTokenId,
                ],
            );

            return new GeneratedMcpClientToken(
                token: $token,
                plainTextToken: $this->formatPlainTextToken($token->id, $secret),
            );
        });
    }

    public function rotateToken(
        McpClientToken $token,
        ?string $name = null,
        ?array $abilities = null,
        ?CarbonInterface $expiresAt = null,
        ?User $actor = null,
    ): GeneratedMcpClientToken {
        return DB::transaction(function () use ($token, $name, $abilities, $expiresAt, $actor): GeneratedMcpClientToken {
            $token->loadMissing('client');

            // Capture the per-token rate limit before revoking so
            // the rotated replacement inherits the same override.
            // Otherwise rotation would silently widen / narrow the
            // limit to the parent client's value.
            $existingRateLimit = $token->rate_limit_per_minute;

            $this->revokeToken($token, $actor, 'Rotated');

            return $this->issueToken(
                client: $token->client,
                name: $name ?? $token->name,
                abilities: $abilities ?? ($token->abilities ?? []),
                expiresAt: $expiresAt ?? $token->expires_at,
                actor: $actor,
                rotatedFromTokenId: $token->id,
                rateLimitPerMinute: $existingRateLimit,
            );
        });
    }

    public function revokeToken(McpClientToken $token, ?User $actor = null, ?string $reason = null): void
    {
        if ($token->isRevoked()) {
            return;
        }

        $token->forceFill([
            'revoked_at' => now(),
            'revoked_by_user_id' => $actor?->id,
            'revocation_reason' => $reason,
        ])->save();

        $this->recordEvent(
            client: $token->client()->firstOrFail(),
            token: $token,
            action: 'token.revoked',
            actor: $actor,
            metadata: [
                'reason' => $reason,
                'token_name' => $token->name,
            ],
        );
    }

    public function findToken(string $plainTextToken): ?McpClientToken
    {
        [$tokenId, $secret] = $this->parsePlainTextToken($plainTextToken);

        if ($tokenId === null || $secret === null) {
            return null;
        }

        $token = McpClientToken::with('client')->find($tokenId);

        if (! $token || ! Hash::check($secret, $token->token_hash)) {
            return null;
        }

        return $token;
    }

    public function recordUsage(McpClientToken $token, ?string $ipAddress = null, ?string $userAgent = null): void
    {
        $token->loadMissing('client');
        $usedAt = now();

        $token->forceFill([
            'last_used_at' => $usedAt,
            'last_used_ip' => $ipAddress,
            'last_used_user_agent' => $userAgent,
        ])->save();

        $token->client->forceFill([
            'last_used_at' => $usedAt,
        ])->save();

        $this->recordEvent(
            client: $token->client,
            token: $token,
            action: 'token.used',
            actor: null,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            metadata: [
                'token_name' => $token->name,
            ],
        );
    }

    public function recordAuditEvent(
        McpClient $client,
        ?McpClientToken $token,
        string $action,
        ?array $metadata = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
    ): McpClientTokenEvent {
        return $this->recordEvent(
            client: $client,
            token: $token,
            action: $action,
            actor: null,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            metadata: $metadata,
        );
    }

    private function recordEvent(
        McpClient $client,
        ?McpClientToken $token,
        string $action,
        ?User $actor = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?array $metadata = null,
    ): McpClientTokenEvent {
        return McpClientTokenEvent::create([
            'mcp_client_id' => $client->id,
            'mcp_client_token_id' => $token?->id,
            'actor_user_id' => $actor?->id,
            'action' => $action,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'metadata' => $metadata,
        ]);
    }

    private function formatPlainTextToken(int $tokenId, string $secret): string
    {
        $prefix = (string) config('modules.ai.mcp.client_token_prefix', 'mcp_');

        return $prefix . $tokenId . '|' . $secret;
    }

    /**
     * @return array{0: int|null, 1: string|null}
     */
    private function parsePlainTextToken(string $plainTextToken): array
    {
        $prefix = (string) config('modules.ai.mcp.client_token_prefix', 'mcp_');
        $prefixLength = strlen($prefix);

        // hash_equals is constant-time over equal-length inputs;
        // length-mismatch reject still reveals "wrong size" but not
        // which prefix bytes differ. The prefix itself is public
        // (it's `mcp_` by default — clients literally see it in the
        // token they're handed) so this is paranoia rather than a
        // real attack vector, but pinning it keeps the security
        // posture explicit for future-token-format changes.
        if (strlen($plainTextToken) < $prefixLength) {
            return [null, null];
        }
        if (! hash_equals($prefix, substr($plainTextToken, 0, $prefixLength))) {
            return [null, null];
        }

        $payload = substr($plainTextToken, $prefixLength);

        if (! str_contains($payload, '|')) {
            return [null, null];
        }

        [$tokenId, $secret] = explode('|', $payload, 2);

        if (! ctype_digit($tokenId) || $secret === '') {
            return [null, null];
        }

        return [(int) $tokenId, $secret];
    }

    private function generateUniqueSlug(string $value): string
    {
        $baseSlug = Str::slug($value) ?: 'mcp-client';
        $slug = $baseSlug;
        $suffix = 2;

        while (McpClient::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }
}
