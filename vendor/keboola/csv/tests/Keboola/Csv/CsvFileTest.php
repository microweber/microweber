<?php
/**
 *
 * User: Martin Halamíček
 * Date: 16.4.12
 * Time: 9:55
 *
 */

use Keboola\Csv\CsvFile;

class Keboola_CsvFileTest extends PHPUnit_Framework_TestCase
{

	public function testExistingFileShouldBeCreated()
	{
		$this->assertInstanceOf('Keboola\Csv\CsvFile', new CsvFile(__DIR__ . '/_data/test-input.csv'));
	}

	public function testExceptionShouldBeThrownOnNotExistingFile()
	{
		$this->setExpectedException('Keboola\Csv\Exception');
		$csv = new CsvFile(__DIR__ . '/something.csv');
		$csv->getHeader();
	}

	public function testColumnsCount()
	{
		$csv = new CsvFile(__DIR__ . '/_data/test-input.csv');

		$this->assertEquals(9, $csv->getColumnsCount());
	}

	/**
	 * @dataProvider validCsvFiles
	 * @param $fileName
	 */
	public function testRead($fileName, $delimiter)
	{
		$csvFile = new \Keboola\Csv\CsvFile(__DIR__ . '/_data/' . $fileName, $delimiter, '"');

		$expected = array(
				"id",
				"idAccount",
				"date",
				"totalFollowers",
				"followers",
				"totalStatuses",
				"statuses",
				"kloutScore",
				"timestamp",
		);
		$this->assertEquals($expected, $csvFile->getHeader());
	}

	public function validCsvFiles()
	{
		return array(
			array('test-input.csv', ','),
			array('test-input.win.csv', ','),
			array('test-input.tabs.csv', "\t"),
			array('test-input.tabs.csv', "	"),
		);
	}

	public function testParse()
	{
		$csvFile = new \Keboola\Csv\CsvFile(__DIR__ . '/_data/escaping.csv', ",", '"');

		$rows = array();
		foreach ($csvFile as $row) {
			$rows[] = $row;
		}

		$expected = array(
			array(
				'col1', 'col2',
			),
			array(
				'line without enclosure', 'second column',
			),
			array(
				'enclosure " in column', 'hello \\',
			),
			array(
				'line with enclosure', 'second column',
			),
			array(
				'column with enclosure ", and comma inside text', 'second column enclosure in text "',
			),
			array(
				"columns with\nnew line", "columns with\ttab",
			),
			array(
				"Columns with WINDOWS\r\nnew line", "second",
			),
			array(
				'column with \n \t \\\\', 'second col',
			),
		);

		$this->assertEquals($expected, $rows);
	}


	public function testEmptyHeader()
	{
		$csvFile = new CsvFile(__DIR__ . '/_data/test-input.empty.csv', ',', '"');

		$this->assertEquals(array(), $csvFile->getHeader());
	}

	/**
	 * @dataProvider invalidDelimiters
	 * @expectedException Keboola\Csv\InvalidArgumentException
	 * @param $delimiter
	 */
	public function testInvalidDelimiterShouldThrowException($delimiter)
	{
		new CsvFile(__DIR__ . '/_data/test-input.csv', $delimiter);
	}

	public function invalidDelimiters()
	{
		return array(
			array('aaaa'),
			array('ob g'),
			array(''),
		);
	}

	public function testInitInvalidFileShouldNotThrowException()
	{
		try {
			$csvFile = new CsvFile(__DIR__ . '/_data/dafadfsafd.csv');
		} catch (\Exception $e) {
			$this->fail('Exception should not be thrown');
		}
	}

	/**
	 * @dataProvider invalidEnclosures
	 * @expectedException Keboola\Csv\InvalidArgumentException
	 * @param $enclosure
	 */
	public function testInvalidEnclosureShouldThrowException($enclosure)
	{
		new CsvFile(__DIR__ . '/_data/test-input.csv', ",", $enclosure);
	}

	public function invalidEnclosures()
	{
		return array(
			array('aaaa'),
			array('ob g'),
		);
	}

	/**
	 * @param $file
	 * @param $lineBreak
	 * @dataProvider validLineBreaksData
	 */
	public function testLineEndingsDetection($file, $lineBreak, $lineBreakAsText)
	{
		$csvFile = new \Keboola\Csv\CsvFile(__DIR__ . '/_data/' . $file);
		$this->assertEquals($lineBreak, $csvFile->getLineBreak());
		$this->assertEquals($lineBreakAsText, $csvFile->getLineBreakAsText());
	}

	public function validLineBreaksData()
	{
		return array(
			array('test-input.csv', "\n",'\n'),
			array('test-input.win.csv', "\r\n", '\r\n'),
			array('escaping.csv', "\n", '\n'),
			array('just-header.csv', "\n", '\n'), // default
		);
	}

	/**
	 * @expectedException Keboola\Csv\InvalidArgumentException
	 * @dataProvider invalidLineBreaksData
	 */
	public function testInvalidLineBreak($file)
	{
		$csvFile = new \Keboola\Csv\CsvFile(__DIR__ . '/_data/' . $file);
		$csvFile->validateLineBreak();
	}

	public function invalidLineBreaksData()
	{
		return array(
			array('test-input.mac.csv'),
		);
	}


	public function testWrite()
	{
		$fileName = __DIR__ . '/_data/_out.csv';
		if (file_exists($fileName)) {
			unlink($fileName);
		}

		$csvFile = new \Keboola\Csv\CsvFile($fileName);

		$rows = array(
			array(
				'col1', 'col2',
			),
			array(
				'line without enclosure', 'second column',
			),
			array(
				'enclosure " in column', 'hello \\',
			),
			array(
				'line with enclosure', 'second column',
			),
			array(
				'column with enclosure ", and comma inside text', 'second column enclosure in text "',
			),
			array(
				"columns with\nnew line", "columns with\ttab",
			),
			array(
				'column with \n \t \\\\', 'second col',
			)
		);

		foreach ($rows as $row) {
			$csvFile->writeRow($row);
		}

	}

	public function testIterator()
	{
		$csvFile = new CsvFile(__DIR__ . '/_data/test-input.csv');

		$expected = array(
			"id",
			"idAccount",
			"date",
			"totalFollowers",
			"followers",
			"totalStatuses",
			"statuses",
			"kloutScore",
			"timestamp",
		);

		// header line
		$csvFile->rewind();
		$this->assertEquals($expected, $csvFile->current());

		// first line
		$csvFile->next();
		$this->assertTrue($csvFile->valid());

		// second line
		$csvFile->next();
		$this->assertTrue($csvFile->valid());

		// file end
		$csvFile->next();
		$this->assertFalse($csvFile->valid());
	}

}
