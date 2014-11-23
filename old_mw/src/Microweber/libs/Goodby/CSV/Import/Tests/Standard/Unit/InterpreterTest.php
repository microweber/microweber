<?php

namespace Goodby\CSV\Import\Tests\Standard\Unit;

use Mockery as m;

use Goodby\CSV\Import\Standard\Interpreter;

/**
 * unit test for Standard Implementation of the Interpreter
 *
 */
class InterpreterTest extends \PHPUnit_Framework_TestCase
{
    private $expectedLine;

    public function setUp()
    {
        $this->expectedLine = null;
    }

    /**
     * @requires PHP 5.4
     */
    public function testStandardInterpreterWithClosure()
	{
        $this->expectedLine = array('test', 'test', 'test');

        $interpreter = new Interpreter();
        $interpreter->addObserver(function($line) {
            $this->assertEquals($this->expectedLine, $line);
        });

        $interpreter->interpret($this->expectedLine);
	}

    public function testStandardInterpreterWithObject()
    {
        $this->expectedLine = array('test', 'test', 'test');

        $object = m::mock('stdClass');
        $object->shouldReceive('callback')->with($this->expectedLine)->once();

        $interpreter = new Interpreter();
        $interpreter->addObserver(array($object, 'callback'));

        $interpreter->interpret($this->expectedLine);
    }

    /**
     * @expectedException \Goodby\CSV\Import\Standard\Exception\StrictViolationException
     */
    public function testInconsistentColumns()
    {
        $lines[] = array('test', 'test', 'test');
        $lines[] = array('test', 'test');

        $interpreter = new Interpreter();

        foreach ($lines as $line) {
            $interpreter->interpret($line);
        }
    }

    /**
     * @expectedException \Goodby\CSV\Import\Standard\Exception\StrictViolationException
     */
    public function testInconsistentColumnsLowToHigh()
    {
        $lines[] = array('test', 'test');
        $lines[] = array('test', 'test', 'test');

        $interpreter = new Interpreter();

        foreach ($lines as $line) {
            $interpreter->interpret($line);
        }
    }

    public function testConsistentColumns()
    {
        $lines[] = array('test', 'test', 'test');
        $lines[] = array('test', 'test', 'test');

        $interpreter = new Interpreter();

        foreach ($lines as $line) {
            $interpreter->interpret($line);
        }
    }

    /**
     * use un-strict won't throw exception with inconsistent columns
     *
     */
    public function testInconsistentColumnsWithUnStrict()
    {
        $lines[] = array('test', 'test', 'test');
        $lines[] = array('test', 'test');

        $interpreter = new Interpreter();
        $interpreter->unstrict();

        foreach ($lines as $line) {
            $interpreter->interpret($line);
        }
    }

    /**
     * @expectedException \Goodby\CSV\Import\Protocol\Exception\InvalidLexicalException
     */
    public function testStandardInterpreterWithInvalidLexical()
    {
        $this->expectedLine = '';

        $interpreter = new Interpreter();

        $interpreter->interpret($this->expectedLine);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCallable()
    {
        $interpreter = new Interpreter();

        $interpreter->addObserver('dummy');

        $interpreter->interpret($this->expectedLine);
    }
}
