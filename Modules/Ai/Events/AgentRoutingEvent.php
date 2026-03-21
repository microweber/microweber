<?php

declare(strict_types=1);

namespace Modules\Ai\Events;

use NeuronAI\Workflow\Event;

class AgentRoutingEvent implements Event
{
    public function __construct(
        public readonly string $userQuery,
        public readonly string $detectedDomain,
        public readonly float $confidence
    ) {
    }
}
