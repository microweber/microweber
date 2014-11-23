<?php

namespace Goodby\CSV\Import\Tests\Protocol;

use Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException;

/**
 * unit test for Interface of the Interpreter
 *
 */
class InterpreterTest extends \PHPUnit_Framework_TestCase
{
    public function testInterpreterInterface()
    {
        $line = array();

        $interpreter = $this->getMock('\Goodby\CSV\Import\Protocol\InterpreterInterface');

        $interpreter->expects($this->once())
                    ->method('interpret')
                    ->with($this->identicalTo($line));

        $interpreter->interpret($line);
    }

    /**
     * @expectedException \Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException
     */
    public function testInterpreterInterfaceWillThrownInvalidLexicalException()
    {
        $interpreter = $this->getMock('\Goodby\CSV\Import\Protocol\InterpreterInterface');

        $interpreter->expects($this->once())
                    ->method('interpret')
                    ->will($this->throwException(new InvalidLexicalException()));

        $line = "INVALID LEXICAL";

        $interpreter->interpret($line);
    }
}
