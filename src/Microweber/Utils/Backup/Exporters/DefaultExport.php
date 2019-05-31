<?php
namespace Microweber\Utils\Backup\Exporters;

use Microweber\Utils\Backup\Exporters\Interfaces\ExportInterface;
use Microweber\Utils\Backup\BackupManager;

class DefaultExport implements ExportInterface
{

	protected $type = 'json';
	protected $data;

	public function __construct($data)
	{
		$this->data = $data;
	}
	
	public function setType($type) {
		$this->type = $type;
	}

	public function start()
	{
		// start exporting
	}

	protected function _generateFilename()
	{
		$backupManager = new BackupManager();

		$exportLocation = $backupManager->getBackupLocation();

		$exportFilename = 'backup_export_' . date("Y-m-d-his") . '.'.$this->type;

		return $exportLocation . $exportFilename;
	}
}