<?php
namespace Microweber\Utils\Backup\Exporters;

class JsonExport extends DefaultExport
{

	public function start()
	{
		$dump = $this->getDump();

		$jsonFilename = $this->_generateFilename();
		
		file_put_contents($jsonFilename['filepath'], $dump);

		return $jsonFilename;
	}

	public function getDump()
	{
		return json_encode($this->_convertFromLatin1ToUtf8($this->data), JSON_PRETTY_PRINT);
	}
	
	/**
	 * Encode array from latin1 to utf8 recursively
	 * @param $dat
	 * @return array|string
	 */
	private function _convertFromLatin1ToUtf8($dat)
	{
		if (is_string($dat)) {
			return utf8_encode($dat);
		} elseif (is_array($dat)) {
			$ret = [];
			foreach ($dat as $i => $d) $ret[ $i ] = $this->_convertFromLatin1ToUtf8($d);
			
			return $ret;
		} elseif (is_object($dat)) {
			foreach ($dat as $i => $d) $dat->$i = $this->_convertFromLatin1ToUtf8($d);
			
			return $dat;
		} else {
			return $dat;
		}
	}
}


