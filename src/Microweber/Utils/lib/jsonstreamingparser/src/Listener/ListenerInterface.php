<?php

declare(strict_types=1);

namespace JsonStreamingParser\Listener;

interface ListenerInterface
{
    public function startDocument(): void;

    public function endDocument(): void;

    public function startObject(): void;

    public function endObject(): void;

    public function startArray(): void;

    public function endArray(): void;

    public function key(string $key): void;

    /**
     * @param mixed $value the value as read from the parser, it may be a string, integer, boolean, etc
     */
    public function value($value);

    public function whitespace(string $whitespace): void;
}
