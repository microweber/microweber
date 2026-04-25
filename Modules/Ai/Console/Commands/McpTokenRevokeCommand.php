<?php

declare(strict_types=1);

namespace Modules\Ai\Console\Commands;

use Illuminate\Console\Command;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;

/**
 * Revoke a single MCP token in place — same as
 * {@see \Modules\Ai\Console\Commands\McpTokenRotateCommand} but
 * without issuing a replacement. Useful when:
 *
 *   - A token was leaked but the client itself is being retired
 *     (so a replacement isn't wanted).
 *   - An audit policy says inactive tokens get revoked after N
 *     days.
 *
 * The token row is preserved so the middleware can audit-log the
 * denial reason on any leaked-token reuse — set the optional
 * --reason flag to record context for the audit trail.
 */
class McpTokenRevokeCommand extends Command
{
    protected $signature = 'ai:mcp:token:revoke
        {token-id : Numeric id of the McpClientToken row to revoke}
        {--reason= : Optional reason recorded on the audit-log entry (default: "CLI revoke")}';

    protected $description = 'Revoke an MCP client token without issuing a replacement.';

    public function handle(McpClientTokenManager $tokenManager): int
    {
        $tokenId = (int) $this->argument('token-id');
        if ($tokenId <= 0) {
            $this->error('token-id must be a positive integer.');
            return self::FAILURE;
        }

        /** @var McpClientToken|null $token */
        $token = McpClientToken::find($tokenId);
        if ($token === null) {
            $this->error("MCP token #{$tokenId} not found.");
            return self::FAILURE;
        }

        if ($token->isRevoked()) {
            $this->warn("MCP token #{$tokenId} is already revoked at {$token->revoked_at?->toIso8601String()}.");
            return self::SUCCESS;
        }

        $reason = (string) ($this->option('reason') ?: 'CLI revoke');
        $tokenManager->revokeToken($token, null, $reason);

        $this->info("Revoked token #{$tokenId} ('{$token->name}'). Reason: {$reason}");

        return self::SUCCESS;
    }
}
