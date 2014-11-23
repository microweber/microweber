<?php

namespace Goodby\CSV\Import\Tests\Standard\Unit\Observer;

use Mockery as m;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Observer\SqlObserver;

/**
 * unit test for sql observer
 */
class SqlObserverTest extends \PHPUnit_Framework_TestCase
{
    public function testUsage()
    {
        $interpreter = new Interpreter();

        $tempDir = sys_get_temp_dir();

        $path = $tempDir . DIRECTORY_SEPARATOR . 'test.sql';

        if (file_exists($path)) {
            unlink($path);
        }

        $sqlObserver = new SqlObserver('test', array('id', 'name', 'age', 'flag', 'flag2', 'status', 'contents'), $path);

        $interpreter->addObserver(array($sqlObserver, 'notify'));

        $interpreter->interpret(array('123', 'test', '28', 'true', 'false', 'null', 'test"test'));

        $expectedSql = 'INSERT INTO test(id, name, age, flag, flag2, status, contents) VALUES(123, "test", 28, true, false, NULL, "test\"test");';

        $this->assertEquals($expectedSql, file_get_contents($path));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidLine()
    {
        $interpreter = new Interpreter();

        $sqlObserver = new SqlObserver('test', array('id', 'name'), 'dummy');

        $interpreter->addObserver(array($sqlObserver, 'notify'));

        $interpreter->interpret(array('123', array('test')));
    }
}
