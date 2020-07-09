<?php
namespace MicroweberPackages\Utils\BackupManager\Loggers;

use MicroweberPackages\Utils\BackupManager\Loggers\BackupDefaultLogger;

class BackupExportLogger extends BackupDefaultLogger
{
	public static $logName = 'Exporting';
	public static $logFileName = 'backup-export-session.log';
	
}