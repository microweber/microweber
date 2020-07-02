<?php
namespace MicroweberPackages\Utils\Backup\Loggers;

use MicroweberPackages\Utils\Backup\Loggers\BackupDefaultLogger;

final class BackupImportLogger extends BackupDefaultLogger
{
	public static $logName = 'Importing';
	public static $logFileName = 'backup-import-session.log';
	
}