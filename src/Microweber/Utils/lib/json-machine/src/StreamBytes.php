<?php

namespace JsonMachine;

use JsonMachine\Exception\InvalidArgumentException;

class StreamBytes implements \IteratorAggregate
{
    private $stream;

    /**
     * StreamBytes constructor.
     * @param $stream
     */
    public function __construct($stream)
    {
        if ( ! is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new InvalidArgumentException("Argument \$stream must be a valid stream resource.");
        }
        $this->stream = $stream;
    }

    /**
     * @return \Generator
     */
    public function getIterator()
    {
        while ('' !== ($bytes = fread($this->stream, 1024 * 8))) {
            yield $bytes;
        }
    }
}
