<?php
/**
 *
 * User: Martin Halamíček
 * Date: 13.4.12
 * Time: 16:05
 *
 */

namespace Keboola\Csv;

class Exception extends \Exception
{
	const FILE_NOT_EXISTS = 1;
	const INVALID_PARAM = 2;
	const WRITE_ERROR = 3;

	protected $_stringCode;

	protected $_contextParams;

	public function __construct($message = null, $code = null, $previous = null, $stringCode = null, $params = null)
	{
		$this->setStringCode($stringCode);
		$this->setContextParams($params);
		parent::__construct($message, $code, $previous);
	}


	public function getStringCode()
	{
		return $this->_stringCode;
	}

	/**
	 * @param $stringCode
	 * @return Exception
	 */
	public function setStringCode($stringCode)
	{
		$this->_stringCode = (string) $stringCode;
		return $this;
	}

	public function getContextParams()
	{
		return $this->_contextParams;
	}

	/**
	 * @param array $contextParams
	 * @return Exception
	 */
	public function setContextParams($contextParams)
	{
		$this->_contextParams = (array) $contextParams;
		return $this;
	}

}
