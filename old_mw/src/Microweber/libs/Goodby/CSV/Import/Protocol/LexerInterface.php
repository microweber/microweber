<?php

namespace Goodby\CSV\Import\Protocol;

use Goodby\CSV\Import\Protocol\Exception\CsvFileNotFoundException;

/**
 * Interface of Lexer
 */
interface LexerInterface
{
    /**
     * Parse csv file
     * @param string $filename
     * @param InterpreterInterface $interpreter
     * @return boolean
     * @throws CsvFileNotFoundException
     */
    public function parse($filename, InterpreterInterface $interpreter);
}
