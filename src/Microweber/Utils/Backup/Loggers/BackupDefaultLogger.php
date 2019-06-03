<?php
namespace Microweber\Utils\Backup\Loggers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class BackupDefaultLogger
{

	private static $debug = true;
	private static $logger;

	protected static $logName = 'Default';
	protected static $logFileName = 'backup-default-session.log';
	
	public static function setLogInfo($log)
	{
		if (is_ajax()) {
			self::$debug = false;
		}

		if (self::$debug) {
			echo $log . PHP_EOL;
		}

		if (! self::$logger) {
			self::_getLogger();
		}
		self::$logger->info($log);
	}

	public static function clearLog()
	{
		file_put_contents(self::_getLogFilename(), false);
	}

	private static function _getLogFilename()
	{
		return userfiles_path() . self::$logFileName;
	}

	private static function _getLogger()
	{
		self::$logger = new Logger(self::$logName);
		self::$logger->pushHandler(new StreamHandler(self::_getLogFilename()));
	}
}