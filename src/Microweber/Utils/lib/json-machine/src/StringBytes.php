<?php

namespace JsonMachine;

class StringBytes implements \IteratorAggregate
{
    /** @var string */
    private $string;

    /** @var int */
    private $chunkSize;

    /**
     * StringBytes constructor.
     * @param string $string
     * @param int $chunkSize
     */
    public function __construct($string, $chunkSize = 1024 * 8)
    {
        $this->string = $string;
        $this->chunkSize = $chunkSize;
    }

    /**
     * @return \Generator
     */
    public function getIterator()
    {
        $len = strlen($this->string);
        for ($i=0; $i<$len; $i += $this->chunkSize) {
            yield substr($this->string, $i, $this->chunkSize);
        }
    }
}
