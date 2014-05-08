<?php
/**
 *
 * User: Martin Halamíček
 * Date: 13.4.12
 * Time: 15:31
 *
 */

namespace Keboola\Csv;

class CsvFile extends \SplFileInfo implements \Iterator
{
	const DEFAULT_DELIMITER = ',';
	const DEFAULT_ENCLOSURE = '"';

	protected $_delimiter;
	protected $_enclosure;
	protected $_escapedBy;

	protected $_filePointer;
	protected $_rowCounter = 0;
	protected $_currentRow;
	protected $_lineBreak;

	public function __construct($fileName, $delimiter = self::DEFAULT_DELIMITER, $enclosure = self::DEFAULT_ENCLOSURE, $escapedBy = "")
	{
		parent::__construct($fileName);

		$this->_escapedBy = $escapedBy;
		$this->_setDelimiter($delimiter);
		$this->_setEnclosure($enclosure);

	}

	/**
	 * @param $delimiter
	 * @return CsvFile
	 */
	protected function _setDelimiter($delimiter)
	{
		$this->_validateDelimiter($delimiter);
		$this->_delimiter = $delimiter;
		return $this;
	}

	protected function _validateDelimiter($delimiter)
	{
		if (strlen($delimiter) > 1) {
			throw new InvalidArgumentException("Delimiter must be a single character. \"$delimiter\" received",
				Exception::INVALID_PARAM, NULL, 'invalidParam');
		}

		if (strlen($delimiter) == 0) {
			throw new InvalidArgumentException("Delimiter cannot be empty.",
				Exception::INVALID_PARAM, NULL, 'invalidParam');
		}
	}

	public function getDelimiter()
	{
		return $this->_delimiter;
	}

	public function getEnclosure()
	{
		return $this->_enclosure;
	}

	public function getEscapedBy()
	{
		return $this->_escapedBy;
	}

	/**
	 * @param $enclosure
	 * @return CsvFile
	 */
	protected  function _setEnclosure($enclosure)
	{
		$this->_validateEnclosure($enclosure);
		$this->_enclosure = $enclosure;
		return $this;
	}

	protected function _validateEnclosure($enclosure)
	{
		if (strlen($enclosure) > 1) {
			throw new InvalidArgumentException("Enclosure must be a single character. \"$enclosure\" received",
				Exception::INVALID_PARAM, NULL, 'invalidParam');
		}
	}


	public function getColumnsCount()
	{
		return count($this->getHeader());
	}

	public function getHeader()
	{
		$this->rewind();
		$current = $this->current();
		if (is_array($current)) {
			return $current;
		}

		return array();
	}

	public function writeRow(array $row)
	{
		$str = $this->rowToStr($row);
		$ret = fwrite($this->_getFilePointer('w+'), $str);

		/* According to http://php.net/fwrite the fwrite() function
		 should return false on error. However not writing the full 
		 string (which may occur e.g. when disk is full) is not considered 
		 as an error. Therefore both conditions are necessary. */
		if (($ret === false) || (($ret === 0) && (strlen($str) > 0)))  {
				throw new Exception("Cannot open file $this",
				Exception::WRITE_ERROR, NULL, 'writeError');
		}
	}

	public function rowToStr(array $row)
	{
		$return = array();
		foreach ($row as $column) {
			$return[] = $this->getEnclosure()
				. str_replace($this->getEnclosure(), str_repeat($this->getEnclosure(), 2), $column) . $this->getEnclosure();
		}
		return implode($this->getDelimiter(), $return) . "\n";
	}

	public function getLineBreak()
	{
		if (!$this->_lineBreak) {
			$this->_lineBreak = $this->_detectLineBreak();
		}
		return $this->_lineBreak;
	}

	public function getLineBreakAsText()
	{
		return trim(json_encode($this->getLineBreak()), '"');
	}

	public function validateLineBreak()
	{
		$lineBreak = $this->getLineBreak();
		if (in_array($lineBreak, array("\r\n", "\n"))) {
			return $lineBreak;
		}

		throw new InvalidArgumentException("Invalid line break. Please use unix \\n or win \\r\\n line breaks.",
			Exception::INVALID_PARAM, NULL, 'invalidParam');
	}

	protected  function _detectLineBreak()
	{
		rewind($this->_getFilePointer());
		$sample = fread($this->_getFilePointer(), 10000);
		rewind($this->_getFilePointer());

		$possibleLineBreaks = array(
			"\r\n", // win
			"\r", // mac
			"\n", // unix
		);

		$lineBreaksPositions = array();
		foreach($possibleLineBreaks as $lineBreak) {
			$position = strpos($sample, $lineBreak);
			if ($position === false) {
				continue;
			}
			$lineBreaksPositions[$lineBreak] = $position;
		}


		asort($lineBreaksPositions);
		reset($lineBreaksPositions);

		return empty($lineBreaksPositions) ? "\n" : key($lineBreaksPositions);
	}

	protected function _closeFile()
	{
		if (is_resource($this->_filePointer)) {
			fclose($this->_filePointer);
		}
	}

	public function __destruct()
	{
		$this->_closeFile();
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Return the current element
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 */
	public function current()
	{
		return $this->_currentRow;
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Move forward to next element
	 * @link http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 */
	public function next()
	{
		$this->_currentRow = $this->_readLine();
		$this->_rowCounter++;
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Return the key of the current element
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return scalar scalar on success, integer
	 * 0 on failure.
	 */
	public function key()
	{
		return $this->_rowCounter;
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Checks if current position is valid
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 */
	public function valid()
	{
		return $this->_currentRow !== false;
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Rewind the Iterator to the first element
	 * @link http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 */
	public function rewind()
	{
		rewind($this->_getFilePointer());
		$this->_currentRow = $this->_readLine();
		$this->_rowCounter = 0;
	}

	protected function _readLine()
	{
		$this->validateLineBreak();

		// allow empty enclosure hack
		$enclosure = !$this->getEnclosure() ? chr(0) : $this->getEnclosure();
		$escapedBy = !$this->_escapedBy ? chr(0) : $this->_escapedBy;
		return fgetcsv($this->_getFilePointer(), null, $this->getDelimiter(), $enclosure, $escapedBy);
	}

	protected function _getFilePointer($mode = 'r')
	{
		if (!is_resource($this->_filePointer)) {
			$this->_openFile($mode);
		}
		return $this->_filePointer;
	}

	protected function _openFile($mode)
	{
		if ($mode == 'r' && !is_file($this->getPathname())) {
			throw new Exception("Cannot open file $this",
					Exception::FILE_NOT_EXISTS, NULL, 'fileNotExists');
		}
		$this->_filePointer = fopen($this->getPathname(), $mode);
		if (!$this->_filePointer) {
			throw new Exception("Cannot open file $this",
				Exception::FILE_NOT_EXISTS, NULL, 'fileNotExists');
		}
	}

}
