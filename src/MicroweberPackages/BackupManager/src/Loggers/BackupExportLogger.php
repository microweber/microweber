<?php
namespace Microweber\Utils\Backup\Loggers;

use Microweber\Utils\Backup\Loggers\BackupDefaultLogger;

class BackupExportLogger extends BackupDefaultLogger
{
	public static $logName = 'Exporting';
	public static $logFileName = 'backup-export-session.log';
	
}