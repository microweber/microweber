<?php
namespace Microweber\Utils\Backup;

class BackupManager
{
	
	public $exportAllData = false;
	public $exportData = ['categoryIds'=>[], 'contentIds'=>[], 'tables'=>[]];
	public $exportType = 'json';
	public $importType = false;
	public $importFile = false;
	public $importBatch = true;
	
	public function __construct()
	{
		if (php_can_use_func('ini_set')) {
			ini_set('memory_limit', '-1');
		}

		if (php_can_use_func('set_time_limit')) {
			set_time_limit(0);
		}
	}

	/**
	 * Set export full
	 * @param string $type
	 */
	public function setExportAllData($exportAllData = true) {
		$this->exportAllData = $exportAllData;
	}
	
	/**
	 * Set export file format
	 * @param string $type
	 */
	public function setExportType($type)
	{
		$this->exportType = $type;
	}
	
	/**
	 * Set wich data want to export
	 * @param array $data
	 */
	public function setExportData($dataType, $dataIds) {
		$this->exportData[$dataType] = $dataIds;
	}

	/**
	 * Set import file format
	 * @param string $type
	 */
	public function setImportType($type) 
	{
		$this->importType = $type;
	}
	
	public function setImportBatch($importBatch) {
		$this->importBatch = $importBatch;
	}

	/**
	 * Set import file path
	 * @param string $file
	 */
	public function setImportFile($file) 
	{
		if (! is_file($file)) {
			return array('error' => 'Backup Manager: You have not provided a existing backup to restore.');
		}
		
		$this->setImportType(pathinfo($file, PATHINFO_EXTENSION));
		$this->importFile = $file;
	}

	/**
	 * Start exporting
	 * @return string[]
	 */
	public function startExport() 
	{
		try {
			// If we want export media
			if (in_array('media', $this->exportData['tables']) || $this->exportAllData == true) {
				$this->exportType = 'zip';
			}
			
			$export = new Export();
			$export->setType($this->exportType);
			$export->setExportData($this->exportData);
			$export->setExportAllData($this->exportAllData);
			
			return $export->start();
		
		} catch (\Exception $e) {
			return array("error"=>$e->getMessage(), "file"=>$e->getFile(), "code"=>$e->getCode(), "line"=>$e->getLine());
		}

	}

	/**
	 * Start importing
	 * @return array
	 */
	public function startImport() 
	{
		try {
			$import = new Import();
			$import->setType($this->importType);
			$import->setFile($this->importFile);
			
			$content = $import->readContentWithCache();
			if (isset($content['error'])) {
				return $content;
			}
			
			$writer = new DatabaseWriter();
			$writer->setContent($content['data']);
			
			if ($this->importBatch) {
				$writer->runWriterWithBatch();
			} else {
				$writer->runWriter();
			}
			
			return $writer->getImportLog();
			
		} catch (\Exception $e) {
			return array("file"=>$e->getFile(), "line"=>$e->getLine(), "error"=>$e->getMessage());
		}
	}

	/**
	 * Get backup location path.
	 * @return string
	 */
	public function getBackupLocation() 
	{
		$backupContent = storage_path() . '/backup_content/' . \App::environment(). '/';
		
		if (! is_dir($backupContent)) {
			mkdir_recursive($backupContent);
			$htaccess = $backupContent . '.htaccess';
			if (! is_file($htaccess)) {
				touch($htaccess);
				file_put_contents($htaccess, 'Deny from all');
			}
		}

		return $backupContent;
	}
}