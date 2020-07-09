<?php
namespace MicroweberPackages\BackupManager\Loggers;

use MicroweberPackages\BackupManager\Loggers\BackupDefaultLogger;

final class BackupImportLogger extends BackupDefaultLogger
{
	public static $logName = 'Importing';
	public static $logFileName = 'backup-import-session.log';
	
}