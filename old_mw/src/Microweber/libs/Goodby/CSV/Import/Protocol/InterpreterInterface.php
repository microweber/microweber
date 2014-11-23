<?php

namespace Goodby\CSV\Import\Protocol;

/**
 * Interface of the Interpreter
 */
interface InterpreterInterface
{
    /**
     * @param $line
     * @return void
     */
    public function interpret($line);
}
