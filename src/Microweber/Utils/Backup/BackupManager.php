<?php
namespace Microweber\Utils\Backup;

use Microweber\Utils\Backup\Traits\BackupLogger;

class BackupManager
{
	use BackupLogger;
	
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
			throw new \Exception('Backup Manager: You have not provided a existing backup to restore.');
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

		$writer = new DatabaseWriter();
		
		$import = new Import();
		$import->setType($this->importType);
		$import->setFile($this->importFile);
		
		$content = $import->readContent();
		if (isset($content['error'])) {
			return $content;
		}
		
		$writer->setContent($content['data']);

		if ($this->importBatch) {
			$writer->runWriterWithBatch();	
		} else {
			$writer->runWriter();
		}
		
		return $writer->getImportLog();
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