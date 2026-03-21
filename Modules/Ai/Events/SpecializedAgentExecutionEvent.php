<?php

declare(strict_types=1);

namespace Modules\Ai\Events;

use NeuronAI\Workflow\Event;

class SpecializedAgentExecutionEvent implements Event
{
    public function __construct(
        public readonly string $agentType,
        public readonly string $userQuery,
        public readonly array $context = []
    ) {
    }
}
