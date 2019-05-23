<?php
namespace Microweber\Utils\Backup\Traits;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

trait BackupLogger
{

	private $logger;
	private $importLogName = 'Backup importing';
	private $importLogFileName = 'backup-import-session.log';

	public function setLogInfo($log)
	{
		if (! $this->logger) {
			$this->_getLogger();
		}
		$this->logger->info($log);
	}

	public function clearLog()
	{
		@unlink($this->_getLogFilename());
	}

	private function _getLogFilename()
	{
		return userfiles_path() . $this->importLogFileName;
	}

	private function _getLogger()
	{
		$this->logger = new Logger($this->importLogName);
		$this->logger->pushHandler(new StreamHandler($this->_getLogFilename()));
	}
}