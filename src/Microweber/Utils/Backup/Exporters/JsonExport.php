<?php
namespace Microweber\Utils\Backup\Exporters;

use Microweber\Utils\Backup\EncodingFix;

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
		return json_encode(EncodingFix::encode($this->data));
	}
	
}


