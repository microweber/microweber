<?php
namespace Microweber\Utils\Backup\Exporters;

use Microweber\Utils\Backup\Exporters\Interfaces\ExportInterface;
use Microweber\Utils\Backup\BackupManager;

class DefaultExport implements ExportInterface
{
	public $type = 'json';
	public $data;

	public function __construct($data = array())
	{
		$this->data = $data;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	public function start()
	{
		// start exporting
	}

	protected function _generateFilename($name = false)
	{
		$backupManager = new BackupManager();
		$exportLocation = $backupManager->getBackupLocation();
		
		if ($name) {
			$exportFilename = $name . '.' . $this->type;
		} else {
			$exportFilename = 'backup_export_' . date("Y-m-d-his") . '.' . $this->type;
		}
		
		return array(
			'download' => api_url('Microweber/Utils/BackupV2/download?file=' . $exportFilename),
			'filepath' => $exportLocation . $exportFilename,
			'filename' => $exportFilename
		);
	}
}