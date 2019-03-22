<?php

declare(strict_types=1);

namespace JsonStreamingParser\Exception;

class ParsingException extends \Exception
{
    public function __construct(int $line, int $char, string $message)
    {
        parent::__construct(sprintf('Parsing error in [%d:%d]. %s', $line, $char, $message));
    }
}
