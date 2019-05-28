<?php
namespace Microweber\Utils\Backup\Traits;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

trait BackupLogger
{

	private $debug = false;
	private $logger;
	private $importLogName = 'Importing';
	private $importLogFileName = 'backup-import-session.log';

	public function setLogInfo($log)
	{
		if ($this->debug) {
			echo $log . PHP_EOL;
		}
		
		if (! $this->logger) {
			$this->_getLogger();
		}
		$this->logger->info($log);
	}

	public function clearLog()
	{
		file_put_contents($this->_getLogFilename(), false);
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