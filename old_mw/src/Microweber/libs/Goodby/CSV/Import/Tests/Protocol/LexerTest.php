<?php

namespace Goodby\CSV\Import\Tests\Protocol;

use Mockery as m;

/**
 * unit test for CSV Lexer
 */
class LexerTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $lexer = m::mock('\Goodby\CSV\Import\Protocol\LexerInterface');
        $interpreter = m::mock('\Goodby\CSV\Import\Protocol\InterpreterInterface');

        $path = 'dummy.csv';

        $lexer->shouldReceive('parse')->with($path, $interpreter);

        $lexer->parse($path, $interpreter);
    }

    /**
     * @expectedException \Goodby\CSV\Import\Protocol\Exception\CsvFileNotFoundException
     */
    public function testCsvFileNotFound()
    {
        $lexer       = m::mock('\Goodby\CSV\Import\Protocol\LexerInterface');
        $interpreter = m::mock('\Goodby\CSV\Import\Protocol\InterpreterInterface');

        $path = 'invalid_dummy.csv';

        $lexer->shouldReceive('parse')
              ->with($path, $interpreter)
              ->andThrow('Goodby\CSV\Import\Protocol\Exception\CsvFileNotFoundException');

        $lexer->parse($path, $interpreter);
    }
}
