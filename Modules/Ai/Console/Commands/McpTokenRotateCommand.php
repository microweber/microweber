<?php

declare(strict_types=1);

namespace Modules\Ai\Console\Commands;

use Illuminate\Console\Command;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;

/**
 * Rotate an MCP client's bearer token in place. The old token row
 * is revoked and a fresh secret is generated under the same client +
 * the same name / abilities / expiry. Useful when:
 *
 *   - A token was leaked (committed to a repo, posted in a chat)
 *     and needs to be invalidated immediately while the client
 *     keeps its allow-list configuration.
 *   - A scheduled rotation policy says all tokens cycle every N days.
 *
 * The plain-text replacement is printed on stdout once — capture it
 * on the spot, the database only stores the hash.
 */
class McpTokenRotateCommand extends Command
{
    protected $signature = 'ai:mcp:token:rotate
        {token-id : Numeric id of the McpClientToken row to rotate}
        {--name= : Override the token name on the new row (defaults to the old name)}';

    protected $description = 'Revoke an MCP client token and issue a fresh secret under the same client.';

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
            $this->error("MCP token #{$tokenId} is already revoked — issue a fresh token via ai:mcp:client:create instead.");
            return self::FAILURE;
        }

        $oldName = (string) $token->name;
        $generated = $tokenManager->rotateToken(
            token: $token,
            name: $this->option('name'),
        );

        $this->info("Rotated token #{$tokenId} ('{$oldName}') → new token #{$generated->token->id}.");
        $this->line('Token: ' . $generated->plainTextToken);
        $this->newLine();
        $this->warn(
            'The plain-text replacement is shown ONLY on this line — capture it now. '
            . 'The old token has been revoked and any client still using it will start '
            . 'getting 401 responses.'
        );

        return self::SUCCESS;
    }
}
