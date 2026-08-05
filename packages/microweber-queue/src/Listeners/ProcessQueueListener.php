<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Listeners;

use MicroweberPackages\Queue\Services\QueueProcessor;

class ProcessQueueListener
{
    public function __construct(
        protected QueueProcessor $processor,
    ) {
    }

    public function handle(mixed $event = null): void
    {
        $this->processor->process();
    }
}
