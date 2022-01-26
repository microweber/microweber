<?php
namespace MicroweberPackages\Backup\Loggers;

use MicroweberPackages\Backup\Loggers\BackupDefaultLogger;

class BackupExportLogger extends BackupDefaultLogger
{
	public static $logName = 'Exporting';
	public static $logFileName = 'backup-export-session.log';
	
}