<?php
namespace Microweber\Utils\Backup;

use Microweber\Utils\Backup\Readers\JsonReader;
use Microweber\Utils\Backup\Readers\CsvReader;
use Microweber\Utils\Backup\Readers\XmlReader;
use Microweber\App\Providers\Illuminate\Support\Facades\Cache;
use Microweber\Utils\Backup\Traits\BackupLogger;

class Import
{
	use BackupLogger;
	
	public $type;
	public $file;

	public function setType($type) {
		$this->type = $type;
	}
	
	public function setFile($file) {
		$this->file = $file;
	}
	
	public function importAsType($data) {
		
		$reader = $this->_getReader($data);
		
		if ($reader) {
			
			$this->setLogInfo('Reading data from file ' . basename($this->file));
			
			$readedData = $reader->readData();
			
			if (!empty($readedData)) {
				
				$successMessages = count($readedData, COUNT_RECURSIVE) . ' items are readed.';
				
				$this->setLogInfo($successMessages);
				
				return array(
					'success' => $successMessages,
					'imoport_type' => $this->type,
					'data' => $readedData
				);
			}
		}
		
		$formatNotSupported = 'Import format not supported.';
		
		$this->setLogInfo($formatNotSupported);
		
		return array(
			'error' => $formatNotSupported
		);
	}
	
	public function readContentWithCache()  {
		
		return Cache::rememberForever(md5($this->file . $this->type), function() {
			$this->setLogInfo('Start importing session..');
			return $this->importAsType($this->file);
		});
		
	}
	
	private function _getReader($data = array()) {

		$reader = false;

		switch ($this->type) {
			case 'json':
				$reader = new JsonReader($data);
				break;
			case 'csv':
				$reader = new CsvReader($data);
				break;
			case 'xml':
				$reader = new XmlReader($data);
				break;
			// Don't forget a break
		}
		
		return $reader;
	}
}
