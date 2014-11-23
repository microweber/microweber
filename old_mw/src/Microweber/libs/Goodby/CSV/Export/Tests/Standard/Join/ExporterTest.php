<?php

namespace Goodby\CSV\Export\Tests\Standard\Join;

use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;
use Goodby\CSV\Export\Standard\Exception\StrictViolationException;
use Goodby\CSV\Export\Protocol\Exception\IOException;

use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;

class ExporterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->root = vfsStream::setup('output');
    }

    public function testExport()
    {
        $config = new ExporterConfig();
        $exporter = new Exporter($config);

        $this->assertFileNotExists('vfs://output/data.csv');
        $exporter->export('vfs://output/data.csv', array(
            array('ID', 'name',  'email'),
            array('1',  'alice', 'alice@example.com'),
            array('2',  'bob',   'bob@example.com'),
        ));

        $this->assertFileExists('vfs://output/data.csv');
        $expectedContents = "ID,name,email\r\n";
        $expectedContents .= "1,alice,alice@example.com\r\n";
        $expectedContents .= "2,bob,bob@example.com\r\n";
        $this->assertSame($expectedContents, file_get_contents('vfs://output/data.csv'));
    }

    public function test_export_with_carriage_return()
    {
        $config = new ExporterConfig();
        $config->setNewline("\r");
        $exporter = new Exporter($config);
        $exporter->unstrict();

        $this->assertFileNotExists('vfs://output/data.csv');
        $exporter->export('vfs://output/data.csv', array(
            array('aaa', 'bbb', 'ccc', 'dddd'),
            array('123', '456', '789'),
            array('"aaa"', '"bbb"', '', ''),
        ));

        $this->assertFileExists('vfs://output/data.csv');
        $expectedContents = "aaa,bbb,ccc,dddd\r";
        $expectedContents .= "123,456,789\r";
        $expectedContents .= '"""aaa""","""bbb""",,'."\r";
        $this->assertSame($expectedContents, file_get_contents('vfs://output/data.csv'));
    }

    public function testUnstrict()
    {
        $config = new ExporterConfig();
        $exporter = new Exporter($config);
        $this->assertAttributeSame(true, 'strict', $exporter);
        $exporter->unstrict();
        $this->assertAttributeSame(false, 'strict', $exporter);
    }

    /**
     * @expectedException \Goodby\CSV\Export\Standard\Exception\StrictViolationException
     */
    public function testStrict()
    {
        $config = new ExporterConfig();
        $exporter = new Exporter($config);

        $exporter->export('vfs://output/data.csv', array(
            array('a', 'b', 'c'),
            array('a', 'b', 'c'),
            array('a', 'b'),
        ));
    }

    /**
     * @requires PHP 5.4
     */
    public function test_throwing_IOException_when_failed_to_write_file()
    {
        $noWritableCsv = 'vfs://output/no-writable.csv';
        touch($noWritableCsv);
        chmod($noWritableCsv, 0444);

        $this->assertFalse(is_writable($noWritableCsv));

        $config = new ExporterConfig();
        $exporter = new Exporter($config);

        $e = null;

        try {
            $exporter->export($noWritableCsv, array(
                array('a', 'b', 'c'),
            ));
        } catch ( IOException $e ) {

        }

        $this->assertTrue($e instanceof IOException);
        $this->assertContains('failed to open', $e->getMessage());
    }

    public function test_encoding()
    {
        $csv = 'vfs://output/euc.csv';
        $this->assertFileNotExists($csv);

        $config = new ExporterConfig();
        $config->setToCharset('EUC-JP');
        $config->setNewline("\n");
        $exporter = new Exporter($config);

        $exporter->export($csv, array(
            array('あ', 'い', 'う', 'え', 'お'),
        ));

        $this->assertFileEquals(__DIR__.'/csv_files/euc-jp.csv', $csv);
    }

    public function test_without_encoding()
    {
        $csv = 'vfs://output/utf-8.csv';
        $this->assertFileNotExists($csv);

        $config = new ExporterConfig();
        $config->setNewline("\n");
        $exporter = new Exporter($config);

        $exporter->export($csv, array(
            array('✔', '✔', '✔'),
            array('★', '★', '★'),
        ));

        $this->assertFileEquals(__DIR__.'/csv_files/utf-8.csv', $csv);
    }

    public function test_unseekable_wrapper_and_custom_newline_code()
    {
        $config = new ExporterConfig();
        $config->setNewline("\r\n");
        $exporter = new Exporter($config);

        ob_start();
        $exporter->export('php://output', array(
            array('a', 'b', 'c'),
            array('1', '2', '3'),
        ));
        $output = ob_get_clean();

        $expectedCount = "a,b,c\r\n1,2,3\r\n";
        $this->assertSame($expectedCount, $output);
    }

    public function test_multiple_line_columns()
    {
        $csv = 'vfs://output/multiple-lines.csv';
        $this->assertFileNotExists($csv);

        $config = new ExporterConfig();
        $config->setNewline("\r\n");
        $exporter = new Exporter($config);

        $exporter->export($csv, array(
            array("line1\r\nline2\r\nline3", "single-line"),
            array("line1\r\nline2\r\nline3", "single-line"),
            array("line1\r\nline2\r\nline3", "single-line"),
        ));

        $this->assertFileEquals(__DIR__.'/csv_files/multiple-lines.csv', $csv);
    }
}
