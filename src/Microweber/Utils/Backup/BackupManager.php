<?php
namespace Microweber\Utils\Backup;

class BackupManager {

	public $exportType = 'json';
	public $importType = 'json';
	
	public function __construct() {
		
		ini_set('memory_limit', '512M');
		set_time_limit(0);
		
	}

	public function setExportType($type) {
		$this->exportType = $type;
	}
	
	public function setImportType($type) {
		$this->importType = $type;
	}
	
	public function export() {
		
		$export = new Export();
		$export->setType($this->exportType);
		
		return $export->getContent();
	}
	
	public function import() {
		
		$import = new Import();
		$import->setType($this->importType);
		
		return $import->readContent();
	}
}