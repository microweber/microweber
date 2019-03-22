<?php

declare(strict_types=1);

namespace JsonStreamingParser\Listener;

/**
 * Base listener which does nothing.
 */
class IdleListener implements ListenerInterface
{
    public function startDocument(): void
    {
    }

    public function endDocument(): void
    {
    }

    public function startObject(): void
    {
    }

    public function endObject(): void
    {
    }

    public function startArray(): void
    {
    }

    public function endArray(): void
    {
    }

    public function key(string $key): void
    {
    }

    public function value($value): void
    {
    }

    public function whitespace(string $whitespace): void
    {
    }
}
