<?php
namespace Microweber\Utils\Backup\Loggers;

use Microweber\Utils\Backup\Loggers\BackupDefaultLogger;

final class BackupImportLogger extends BackupDefaultLogger
{
	public static $logName = 'Importing';
	public static $logFileName = 'backup-import-session.log';
	
}