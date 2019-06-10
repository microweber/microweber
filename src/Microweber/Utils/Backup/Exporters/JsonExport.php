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
		return json_encode($this->data, JSON_PRETTY_PRINT);
	}
}