<?php

namespace Goodby\CSV\Import\Tests\Standard\Join;

use Mockery as m;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

use Goodby\CSV\Import\Tests\Standard\Join\CSVFiles;

class LexerTest extends \PHPUnit_Framework_TestCase
{
    public function test_shift_jis_CSV()
    {
        $shiftJisCsv = CSVFiles::getShiftJisCsv();
        $lines = array(
            array('あ', 'い', 'う', 'え', 'お'),
            array('日本語', '日本語', '日本語', '日本語', '日本語'),
            array('ぱ', 'ぴ', 'ぷ', 'ぺ', 'ぽ'),
            array('"quoted"', "a'quote'", 'a, b and c', '', ''),
        );

        $interpreter = $this->getMock('Goodby\CSV\Import\Standard\Interpreter', array('interpret'));
        $interpreter->expects($this->at(0))->method('interpret')->with($lines[0]);
        $interpreter->expects($this->at(1))->method('interpret')->with($lines[1]);
        $interpreter->expects($this->at(2))->method('interpret')->with($lines[2]);
        $interpreter->expects($this->at(3))->method('interpret')->with($lines[3]);

        $config = new LexerConfig();
        $config->setToCharset('UTF-8')->setFromCharset('SJIS-win');
        $lexer = new Lexer($config);
        $lexer->parse($shiftJisCsv, $interpreter);
    }

    public function test_mac_excel_csv()
    {
        $csv   = CSVFiles::getMacExcelCsv();
        $lines = CSVFiles::getMacExcelLines();

        $interpreter = $this->getMock('Goodby\CSV\Import\Standard\Interpreter', array('interpret'));
        $interpreter->expects($this->at(0))->method('interpret')->with($lines[0]);
        $interpreter->expects($this->at(1))->method('interpret')->with($lines[1]);

        $config = new LexerConfig();
        $lexer = new Lexer($config);
        $lexer->parse($csv, $interpreter);
    }

    public function test_tab_separated_csv()
    {
        $csv   = CSVFiles::getTabSeparatedCsv();
        $lines = CSVFiles::getTabSeparatedLines();

        $interpreter = $this->getMock('Goodby\CSV\Import\Standard\Interpreter', array('interpret'));
        $interpreter->expects($this->at(0))->method('interpret')->with($lines[0]);
        $interpreter->expects($this->at(1))->method('interpret')->with($lines[1]);

        $config = new LexerConfig();
        $config->setDelimiter("\t");
        $lexer = new Lexer($config);
        $lexer->parse($csv, $interpreter);
    }

    public function test_colon_separated_csv()
    {
        $csv   = CSVFiles::getColonSeparatedCsv();
        $lines = CSVFiles::getColonSeparatedLines();

        $interpreter = $this->getMock('Goodby\CSV\Import\Standard\Interpreter', array('interpret'));
        $interpreter->expects($this->at(0))->method('interpret')->with($lines[0]);
        $interpreter->expects($this->at(1))->method('interpret')->with($lines[1]);

        $config = new LexerConfig();
        $config->setDelimiter(':');
        $lexer = new Lexer($config);
        $lexer->parse($csv, $interpreter);
    }

    public function test_utf8_csv()
    {
        $csv   = CSVFiles::getUtf8Csv();
        $lines = CSVFiles::getUtf8Lines();

        $interpreter = $this->getMock('Goodby\CSV\Import\Standard\Interpreter', array('interpret'));
        $interpreter->expects($this->at(0))->method('interpret')->with($lines[0]);
        $interpreter->expects($this->at(1))->method('interpret')->with($lines[1]);

        $config = new LexerConfig();
        $lexer = new Lexer($config);
        $lexer->parse($csv, $interpreter);
    }

    /**
     * When import CSV file with data in Japanese (2 bytes character),
     * data imported to database with error encoding
     * @link https://github.com/goodby/csv/issues/5
     */
    public function test_issue_5()
    {
        $csvFilename = CSVFiles::getIssue5CSV();

        $csvContents = array();

        $config = new LexerConfig();
        $config
            ->setToCharset('UTF-8')
            ->setFromCharset('UTF-8');
        $lexer = new Lexer($config);
        $interpreter = new Interpreter();
        $interpreter->addObserver(function(array $columns) use (&$csvContents) {
            $csvContents[] = $columns;
        });

        $lexer->parse($csvFilename, $interpreter);
        
        $this->assertSame(array(
            array("ID", "NAME", "MAKER"),
            array("1", "スティック型クリーナ", "alice_updated@example.com"),
            array("2", "bob", "bob@example.com"),
            array("14", "スティック型クリーナ", "tho@eample.com"),
            array("16", "スティック型", "carot@eample.com"),
        ), $csvContents);
    }

    public function test_ignore_header()
    {
        $csvFilename = CSVFiles::getIssue5CSV();

        $config = new LexerConfig();
        $config
          ->setIgnoreHeaderLine(true)
          ->setToCharset('UTF-8')
          ->setFromCharset('UTF-8');

        $lexer = new Lexer($config);

        $interpreter = new Interpreter();
        $interpreter->addObserver(function(array $columns) use (&$csvContents) {
            $csvContents[] = $columns;
        });

        $lexer->parse($csvFilename, $interpreter);
        $this->assertSame(array(
            array("1", "スティック型クリーナ", "alice_updated@example.com"),
            array("2", "bob", "bob@example.com"),
            array("14", "スティック型クリーナ", "tho@eample.com"),
            array("16", "スティック型", "carot@eample.com"),
        ), $csvContents);
    }
}
