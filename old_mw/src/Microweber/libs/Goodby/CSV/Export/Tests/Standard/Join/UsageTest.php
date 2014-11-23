<?php

namespace Goodby\CSV\Export\Tests\Standard\Join;

use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;

use Goodby\CSV\Export\Standard\Collection\PdoCollection;

use Goodby\CSV\TestHelper\DbManager;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Goodby\CSV\Export\Standard\Collection\CallbackCollection;

class UsageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Goodby\CSV\TestHelper\DbManager
     */
    private $manager = null;

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup('output');

        $this->manager = new DbManager();

        $pdo = $this->manager->getPdo();

        $stmt = $pdo->prepare("CREATE TABLE collection_test ( id INT, name VARCHAR(32) )");
        $stmt->execute();

        $pdo->prepare("INSERT INTO collection_test VALUES(1, 'name')")->execute();
        $pdo->prepare("INSERT INTO collection_test VALUES(2, 'name')")->execute();
        $pdo->prepare("INSERT INTO collection_test VALUES(3, 'name')")->execute();
    }

    public function tearDown()
    {
        unset($this->manager);
    }

	public function testUsage()
	{
        $pdo = $this->manager->getPdo();

        $stmt = $pdo->prepare("SELECT * FROM collection_test");
        $stmt->execute();

        $this->assertFileNotExists('vfs://output/data.csv');

        $collection = new PdoCollection($stmt);

        $config = new ExporterConfig();
        $exporter = new Exporter($config);
        $exporter->export('vfs://output/data.csv', $collection);

        $expectedContents  = "1,name\r\n";
        $expectedContents .= "2,name\r\n";
        $expectedContents .= "3,name\r\n";

        $this->assertSame($expectedContents, file_get_contents('vfs://output/data.csv'));
	}

    public function testUsageWithCallbackCollection()
    {
        $this->assertFileNotExists('vfs://output/data.csv');

        $data = array();
        $data[] = array(1, 'name1');
        $data[] = array(2, 'name2');
        $data[] = array(3, 'name3');

        $collection = new CallbackCollection($data, function($row) {
            $row[1] = $row[1] . '!';
            return $row;
        });

        $config = new ExporterConfig();
        $exporter = new Exporter($config);
        $exporter->export('vfs://output/data.csv', $collection);

        $expectedContents  = "1,name1!\r\n";
        $expectedContents .= "2,name2!\r\n";
        $expectedContents .= "3,name3!\r\n";

        $this->assertSame($expectedContents, file_get_contents('vfs://output/data.csv'));
    }
}
