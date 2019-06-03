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
		
		file_put_contents(self::_getLogFilename(), $log);
	}
}