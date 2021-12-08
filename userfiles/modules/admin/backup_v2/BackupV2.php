<?php
/**
 * Backup V2
 *
 * Class used to backup and restore the database or the userfiles directory.
 *
 * You can use it to create backup of the site. The backup will contain na sql export of the database
 * and also a zip file with userfiles directory.
 */

api_expose_admin('BackupV2/get');
api_expose_admin('BackupV2/getImportLogAsJson');
api_expose_admin('BackupV2/import');
api_expose_admin('BackupV2/download');
api_expose_admin('BackupV2/upload');
api_expose_admin('BackupV2/delete');
api_expose_admin('BackupV2/export');

if (defined('INI_SYSTEM_CHECK_DISABLED') == false) {
	define('INI_SYSTEM_CHECK_DISABLED', ini_get('disable_functions'));
}

class BackupV2
{
	public $app;
	public $manager;

	public function __construct($app = null)
	{
		if (! defined('USER_IP')) {
			if (isset($_SERVER['REMOTE_ADDR'])) {
				define('USER_IP', $_SERVER['REMOTE_ADDR']);
			} else {
				define('USER_IP', '127.0.0.1');
			}
		}

		if (is_object($app)) {
			$this->app = $app;
		} else {
			$this->app = mw();
		}

		if (! $this->manager) {
			$this->manager = new \MicroweberPackages\Backup\BackupManager();
		}
	}

	public function get()
	{
		if (! is_admin()) {
			error('must be admin');
		}

		$backupLocation = $this->manager->getBackupLocation();

		// Use of undefined constant GLOB_BRACE - assumed 'GLOB_BRACE' (this will throw an Error in a future version of PHP)
		//$backupFiles = glob("$backupLocation{*.sql,*.zip,*.json,*.xml,*.xlsx,*.csv}", GLOB_BRACE);

        $backupFiles = [];


        $files = preg_grep('~\.(sql|zip|json|xml|xlsx|csv|xls)$~', scandir($backupLocation));
        if ($files) {
            foreach ($files as $file) {
                $backupFiles[] = normalize_path($backupLocation. $file,false);
            }
        }

        if (! empty($backupFiles)) {
            usort($backupFiles, function ($a, $b) {
                return filemtime($a) < filemtime($b);
            });
        }
		$backups = array();
		if (! empty($backupFiles)) {
			foreach ($backupFiles as $file) {

				if (is_file($file)) {
					$mtime = filemtime($file);

					$backup = array();
					$backup['filename'] = basename($file);
					$backup['date'] = date('F d Y', $mtime);
					$backup['time'] = str_replace('_', ':', date('H:i:s', $mtime));
					$backup['size'] = filesize($file);

					$backups[] = $backup;
				}
			}
		}

		return $backups;
	}


	public function import($query) {

		must_have_access();

		$fileId = null;
		if (isset($query['id'])) {
			$fileId = $query['id'];
		} elseif (isset($_GET['filename'])) {
			$fileId = $query['filename'];
		} elseif (isset($_GET['file'])) {
			$fileId = $query['file'];
		}

        $this->manager->setImportStep(intval($_GET['step']));

		if (isset($query['import_by_type']) && $query['import_by_type'] == 'overwrite_by_id') {
			$this->manager->setImportOvewriteById(true);
		}

		if (isset($query['import_by_type']) && $query['import_by_type'] == 'delete_all') {
		    $this->manager->setImportOvewriteById(true);
            $this->manager->setToDeleteOldContent(true);
		}

        if (isset($query['installation_language']) && !empty($query['installation_language'])) {
            $this->manager->setImportLanguage($query['installation_language']);
        }

		if (!$fileId) {
			return array('error' => 'You have not provided a file to import.');
		}

		$fileId = str_replace('..', '', $fileId);

		$backupLocation = $this->manager->getBackupLocation();
		$filePath = $backupLocation . $fileId;

		if (!is_file($filePath)) {
			return array('error' => 'You have not provided a existing backup to import.');
		} else {

			if (isset($query['debug'])) {
				$this->manager->setLogger(new BackupV2Logger());
			}

			$this->manager->setImportFile($filePath);
			$importLog = $this->manager->startImport();

			return json_encode($importLog, JSON_PRETTY_PRINT);
		}

		return $query;

	}

	public function export($query) {



	}
}

class BackupV2Logger {

	public function log($log) {
		echo $log . '<br />';
	}

}


