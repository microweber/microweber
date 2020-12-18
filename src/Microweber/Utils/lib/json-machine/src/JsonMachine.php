<?php

namespace JsonMachine;

class JsonMachine implements \IteratorAggregate
{
    /**
     * @var \Traversable
     */
    private $bytesIterator;

    /**
     * @var string
     */
    private $jsonPointer;

    /**
     * JsonMachine constructor.
     * @param \Traversable
     * @param string $jsonPointer
     */
    public function __construct(\Traversable $bytesIterator, $jsonPointer = '')
    {
        $this->bytesIterator = $bytesIterator;
        $this->jsonPointer = $jsonPointer;
    }

    /**
     * @param $string
     * @param string $jsonPointer
     * @return self
     */
    public static function fromString($string, $jsonPointer = '')
    {
        return new static(new StringBytes($string), $jsonPointer);
    }

    /**
     * @param string $file
     * @param string $jsonPointer
     * @return self
     */
    public static function fromFile($file, $jsonPointer = '')
    {
        return new static(new StreamBytes(fopen($file, 'r')), $jsonPointer);
    }

    /**
     * @param resource $stream
     * @param string $jsonPointer
     * @return self
     */
    public static function fromStream($stream, $jsonPointer = '')
    {
        return new static(new StreamBytes($stream), $jsonPointer);
    }

    public function getIterator()
    {
        return new Parser(new Lexer($this->bytesIterator), $this->jsonPointer);
    }
}
