<?php
namespace MicroweberPackages\BackupManager\Loggers;

use MicroweberPackages\BackupManager\Loggers\BackupDefaultLogger;

class BackupExportLogger extends BackupDefaultLogger
{
	public static $logName = 'Exporting';
	public static $logFileName = 'backup-export-session.log';
	
}