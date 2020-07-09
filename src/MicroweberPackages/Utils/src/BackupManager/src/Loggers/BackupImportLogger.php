<?php
namespace MicroweberPackages\Utils\BackupManager\Loggers;

use MicroweberPackages\Utils\BackupManager\Loggers\BackupDefaultLogger;

final class BackupImportLogger extends BackupDefaultLogger
{
	public static $logName = 'Importing';
	public static $logFileName = 'backup-import-session.log';
	
}