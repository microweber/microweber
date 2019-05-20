<?php
namespace Microweber\Utils\Backup\Readers;

use Microweber\Utils\Backup\Traits\BackupLogger;

class ZipReader extends DefaultReader
{

	use BackupLogger;
	
	public function readData()
	{
		$this->setLogInfo('Unzipping userfiles...');
		

		
		
		echo 1;
		die();
	}
}