<?php
namespace Microweber\Utils\Backup\Loggers;

use Microweber\Utils\Backup\Loggers\BackupDefaultLogger;

class BackupExportLogger extends BackupDefaultLogger
{
	public static $logName = 'Exporting';
	public static $logFileName = 'backup-export-session.log';
	
	
	public static function setLogInfo($log) {
		
		if (is_ajax()) {
			self::$debug = false;
		}
		
		if (self::$debug) {
			echo $log . PHP_EOL;
		}
		
		self::addNew(self::_getLogFilename(), $log, 6);
		
	}
	
	public static function addNew($fileName, $line, $max = 3) {
		
		if (!is_file($fileName)) {
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
		@file_put_contents($fileName, implode(PHP_EOL, array_filter($file)));
	}
	
}