<?php
namespace Microweber\Utils\Backup\Exporters;

use Microweber\Utils\Backup\Exporters\Interfaces\ExportInterface;
use Microweber\Utils\Backup\BackupManager;

class DefaultExport implements ExportInterface
{
	protected $type = 'json';
	protected $data;

	public function __construct($data = array())
	{
		if (!empty($data)) {
			array_walk_recursive($data, function (&$element) {
				if (is_string($element)) {
					$utf8Chars = explode(' ', 'À Á Â Ã Ä Å Æ Ç È É Ê Ë Ì Í Î Ï Ð Ñ Ò Ó Ô Õ Ö × Ø Ù Ú Û Ü Ý Þ ß à á â ã ä å æ ç è é ê ë ì í î ï ð ñ ò ó ô õ ö');
					foreach ($utf8Chars as $char) {
						$element = str_replace($char, '', $element);
					}
				}
			});
		}
		
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

	protected function _generateFilename()
	{
		$backupManager = new BackupManager();
		$exportLocation = $backupManager->getBackupLocation();
		$exportFilename = 'backup_export_' . date("Y-m-d-his") . '.' . $this->type;

		return array(
			'download' => api_url('Microweber/Utils/BackupV2/download?file=' . $exportFilename),
			'filepath' => $exportLocation . $exportFilename,
			'filename' => $exportFilename
		);
	}
}