<?php
namespace Microweber\Utils\Backup\Loggers;

abstract class BackupDefaultLogger
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
		
		self::addNew(self::_getLogFilename(), $log, 3);
	}
	
	public static function addNew($fileName, $line, $max = 15) {


		file_put_contents($fileName, $line."\n", FILE_APPEND);



		/*	if (!is_file($fileName)) {
                file_put_contents($fileName, '');
            }

            // Remove Empty Spaces
            $file = array_filter(array_map("trim", file($fileName)));

            // Make Sure you always have maximum number of lines
            $file = array_slice($file, 0, $max);

            // Remove any extra line
            count($file) >= $max and array_shift($file);

            // Add new Line
            array_push($file, $line);

            // Save Result
            file_put_contents($fileName, implode(PHP_EOL, array_filter($file)));*/
	}

	protected static function _getLogFilename()
	{
		return userfiles_path() . static::$logFileName;
	}

}