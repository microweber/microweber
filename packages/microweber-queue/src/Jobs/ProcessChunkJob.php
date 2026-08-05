<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

/**
 * Generic chunk job: invokes a container-resolved handler with the chunk payload.
 *
 * Prefer dedicated job classes in application code; this is a convenience for
 * simple handlers when building chunks dynamically.
 */
class ProcessChunkJob implements ShouldQueue
{
    use Batchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param  array<int, mixed>  $chunk
     * @param  class-string  $handlerClass  Class resolved from the container; must be invokable or have handle(array $chunk, array $context)
     * @param  array<string, mixed>  $context  Extra context passed to the handler
     */
    public function __construct(
        public array $chunk,
        public string $handlerClass,
        public array $context = [],
    ) {
    }

    /**
     * @throws Throwable
     */
    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $handler = app($this->handlerClass);

        if (is_object($handler) && is_callable($handler)) {
            $handler($this->chunk, $this->context);

            return;
        }

        if (is_object($handler) && method_exists($handler, 'handle')) {
            $handler->handle($this->chunk, $this->context);

            return;
        }

        throw new \RuntimeException(
            "Handler [{$this->handlerClass}] must be invokable or implement handle(array \$chunk, array \$context)."
        );
    }
}
