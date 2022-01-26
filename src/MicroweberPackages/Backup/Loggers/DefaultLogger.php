<?php
namespace MicroweberPackages\Backup\Loggers;

abstract class DefaultLogger
{

	protected static $debug = false;
	protected static $logger;

	public static function setLogger($logger) {
		self::$logger = $logger;
	}

	public static function clearLog()
	{
		file_put_contents(self::_getLogFilename(), false);
	}

	public static function setLogInfo($log) {

		if (self::$logger) {
			$loggerClass = new self::$logger();
			if (method_exists($loggerClass, 'log')) {
				return $loggerClass->log($log);
			}
		}

		if (is_ajax()) {
			self::$debug = false;
		}

		if (self::$debug) {
			echo $log . PHP_EOL;
		}

		self::addNew(self::_getLogFilename(), $log, 45);
	}

	public static function addNew($fileName, $line, $max = 15) {

	    if (is_file($fileName)) {
            $countLines = file_get_contents($fileName);
            $countLines = substr_count($countLines, "\n");;

            if ($countLines > $max) {
                @file_put_contents($fileName, '');
            }
        }

		@file_put_contents($fileName, $line."\n", FILE_APPEND);
	}

	protected static function _getLogFilename()
	{
		return userfiles_path() . static::$logFileName;
	}

}
