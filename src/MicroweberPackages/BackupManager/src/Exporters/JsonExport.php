<?php
namespace Microweber\Utils\Backup\Exporters;

use Microweber\Utils\Backup\EncodingFix;

class JsonExport extends DefaultExport
{
	/**
	 * The type of export
	 * @var string
	 */
	public $type = 'json';
	
	public function start()
	{
		$dump = $this->getDump();
		$jsonFilename = $this->_generateFilename();
		
		file_put_contents($jsonFilename['filepath'], $dump);

		return array("files"=>array($jsonFilename));
	}

	public function getDump()
	{
		return json_encode(EncodingFix::encode($this->data));
	}
	
}


