<?php

namespace JsonMachine;

class Lexer implements \IteratorAggregate
{
    /** @var resource */
    private $bytesIterator;

    private $position = 0;

    /**
     * Lexer constructor.
     * @param \Traversable $bytesIterator
     */
    public function __construct(\Traversable $bytesIterator)
    {
        $this->bytesIterator = $bytesIterator;
    }
    
    /**
     * @return \Generator
     */
    public function getIterator()
    {
        $inString = false;
        $tokenBuffer = '';
        $isEscaping = false;

        ${' '} = 0;
        ${"\n"} = 0;
        ${"\r"} = 0;
        ${"\t"} = 0;
        ${'{'} = 1;
        ${'}'} = 1;
        ${'['} = 1;
        ${']'} = 1;
        ${':'} = 1;
        ${','} = 1;

        foreach ($this->bytesIterator as $bytes) {
            $bytesLength = strlen($bytes);
            for ($i = 0; $i < $bytesLength; ++$i) {
                $byte = $bytes[$i];
                ++$this->position;

                if ($inString) {
                    if ($byte === '"' && !$isEscaping) {
                        $inString = false;
                    }
                    $isEscaping = ($byte =='\\' && !$isEscaping);
                    $tokenBuffer .= $byte;
                    continue;
                }

                if (isset($$byte)) {
                    if ($tokenBuffer !== '') {
                        yield $tokenBuffer;
                        $tokenBuffer = '';
                    }
                    if ($$byte) { // is not whitespace
                        yield $byte;
                    }
                } else {
                    if ($byte === '"') {
                        $inString = true;
                    }
                    $tokenBuffer .= $byte;
                }
            }
        }
        if ($tokenBuffer !== '') {
            yield $tokenBuffer;
        }
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}
