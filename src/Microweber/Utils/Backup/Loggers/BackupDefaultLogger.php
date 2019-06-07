<?php
namespace Microweber\Utils\Backup\Loggers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

abstract class BackupDefaultLogger
{

	protected static $debug = false;
	protected static $logger;
	/* 
	public static $logName = 'Default';
	public static $logFileName = 'backup-default-session.log';
	 */
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

	protected static function _getLogFilename()
	{
		return userfiles_path() . static::$logFileName;
	}

	protected static function _getLogger()
	{
		self::$logger = new Logger(static::$logName);
		self::$logger->pushHandler(new StreamHandler(self::_getLogFilename()));
	}
}