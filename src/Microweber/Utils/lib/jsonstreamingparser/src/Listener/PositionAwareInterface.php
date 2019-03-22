<?php

declare(strict_types=1);

namespace JsonStreamingParser\Listener;

interface PositionAwareInterface
{
    public function setFilePosition(int $lineNumber, int $charNumber): void;
}
