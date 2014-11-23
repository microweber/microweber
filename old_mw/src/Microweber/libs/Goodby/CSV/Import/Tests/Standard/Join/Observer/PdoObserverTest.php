<?php

namespace Goodby\CSV\Import\Tests\Standard\Join\Observer;

use Mockery as m;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Observer\PdoObserver;

use Goodby\CSV\TestHelper\DbManager;

/**
 * unit test for pdo observer
 *
 */
class PdoObserverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Goodby\CSV\TestHelper\DbManager
     */
    private $manager = null;

    public function setUp()
    {
        $this->manager = new DbManager();

        $pdo = $this->manager->getPdo();

        $stmt = $pdo->prepare("CREATE TABLE test (id INT, name VARCHAR(32), age INT, flag TINYINT, flag2 TINYINT, status VARCHAR(32), contents TEXT)");
        $stmt->execute();
    }

    public function tearDown()
    {
        unset($this->manager);
    }

    public function testUsage()
    {
        $interpreter = new Interpreter();

        $table = 'test';

        $dsn = $this->manager->getDsn();
        $options = array('user' => $this->manager->getUser(), 'password' => $this->manager->getPassword());

        $sqlObserver = new PdoObserver($table, array('id', 'name', 'age', 'flag', 'flag2', 'status', 'contents'), $dsn, $options);

        $interpreter->addObserver(array($sqlObserver, 'notify'));

        $interpreter->interpret(array('123', 'test', '28', 'true', 'false', 'null', 'test"test'));

        $pdo = $this->manager->getPdo();

        $stmt = $pdo->prepare("SELECT * FROM " . $table);
        $stmt->execute();

        $result = $stmt->fetch();

        $this->assertEquals(123, $result[0]);
        $this->assertEquals('test', $result[1]);
        $this->assertEquals(28, $result[2]);
        $this->assertEquals(1, $result[3]);
        $this->assertEquals(0, $result[4]);
        $this->assertEquals('NULL', $result[5]);
        $this->assertEquals('test"test', $result[6]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage value is invalid: array
     */
    public function testInvalidLine()
    {
        $interpreter = new Interpreter();

        $table = 'test';

        $options = array('user' => $this->manager->getUser(), 'password' => $this->manager->getPassword());

        $sqlObserver = new PdoObserver($table, array('id', 'name'), $this->manager->getDsn(), $options);

        $interpreter->addObserver(array($sqlObserver, 'notify'));

        $interpreter->interpret(array('123', array('test', 'test')));
    }
}
