<?php

declare(strict_types=1);

namespace Modules\Ai\Services\Mcp;

use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;

readonly class McpRequestContext
{
    public function __construct(
        public McpClient $client,
        public McpClientToken $token,
        public ?string $method = null,
        public ?string $toolName = null,
        public ?string $moduleName = null,
    ) {
    }
}
