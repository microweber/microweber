<?php
namespace Microweber\Utils\Backup\Loggers;

use Microweber\Utils\Backup\Loggers\BackupDefaultLogger;

final class BackupExportLogger extends BackupDefaultLogger
{
	protected static $logName = 'Exporting';
	protected static $logFileName = 'backup-export-session.log';
}