<?php

declare(strict_types=1);

namespace Modules\Ai\Services\Mcp;

use Modules\Ai\Models\McpClientToken;

readonly class GeneratedMcpClientToken
{
    public function __construct(
        public McpClientToken $token,
        public string $plainTextToken,
    ) {
    }
}
