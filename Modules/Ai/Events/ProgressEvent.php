<?php

declare(strict_types=1);

namespace Modules\Ai\Events;

use NeuronAI\Workflow\Event;

class ProgressEvent implements Event
{
    public function __construct(
        public readonly string $message,
        public readonly array $data = []
    ) {
    }
}
