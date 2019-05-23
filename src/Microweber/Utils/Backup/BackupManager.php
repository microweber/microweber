<?php
namespace Microweber\Utils\Backup;

use Microweber\Utils\Backup\Traits\BackupLogger;

class BackupManager
{
	use BackupLogger;
	
	public $exportType = 'json';
	public $importType = 'json';
	public $importFile = false;
	
	public function __construct() 
	{
		ini_set('memory_limit', '-1');
		set_time_limit(0);
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
	 * Set import file format
	 * @param string $type
	 */
	public function setImportType($type) 
	{
		$this->importType = $type;
	}

	/**
	 * Set import file path
	 * @param string $file
	 */
	public function setImportFile($file) 
	{
		$this->importFile = $this->getBackupLocation() . $file;
	}

	/**
	 * Start exporting
	 * @return string[]
	 */
	public function startExport() 
	{
		$export = new Export();
		$export->setType($this->exportType);

		$content = $export->getContent();

		if (isset($content['data'])) {

			$exportLocation = $this->getBackupLocation();

			$exportFilename = 'backup_export_' . date("Y-m-d-his") . '.' . $this->exportType;
			$exportPath = $exportLocation . $exportFilename;

			$save = file_put_contents($exportPath, $content['data']);

			if ($save) {
				return array(
					"filename" => $exportPath,
					"success" => "Backup export are saved success."
				);
			} else {
				return array(
					"error" => "File not save"
				);
			}
		}
	}

	/**
	 * Start importing
	 * @return array
	 */
	public function startImport() 
	{
		$import = new Import();
		$import->setType($this->importType);
		$import->setFile($this->importFile);
		
		$content = $import->readContentWithCache();
		
		if (isset($content['error'])) {
			return $content;
		}
		
		$writer = new DatabaseWriter();
		$writer->setContent($content['data']);		$writerResponse = $writer->runWriter();
		
		dd($writerResponse);
	}

	/**
	 * Get backup location path.
	 * @return string
	 */
	public function getBackupLocation() 
	{
		$backupContent = storage_path() . '/backup_content/';

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