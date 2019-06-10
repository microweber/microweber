<?php
namespace Microweber\Utils\Backup\Loggers;

use Microweber\Utils\Backup\Loggers\BackupDefaultLogger;

final class BackupImportLogger extends BackupDefaultLogger
{
	protected static $logName = 'Importing';
	protected static $logFileName = 'backup-import-session.log';
}