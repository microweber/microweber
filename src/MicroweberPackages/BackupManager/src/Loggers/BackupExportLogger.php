<?php
namespace MicroweberPackages\Utils\Backup\Loggers;

use MicroweberPackages\Utils\Backup\Loggers\BackupDefaultLogger;

class BackupExportLogger extends BackupDefaultLogger
{
	public static $logName = 'Exporting';
	public static $logFileName = 'backup-export-session.log';
	
}