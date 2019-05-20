<?php
namespace Microweber\Utils\Backup\Traits;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

trait BackupLogger
{
	private $logger;
	private $importLogName = 'Backup importing';
	private $importLogFileName = 'import-session.log';
	
	public function setLogInfo($log) {
		if (!$this->logger) {
			$this->_getLogger();
		}
		$this->logger->info($log);
	}
	
	private function _getLogger()
	{
		$this->logger = new Logger($this->importLogName);
		$this->logger->pushHandler(new StreamHandler(userfiles_path() . $this->importLogFileName));
	}
}