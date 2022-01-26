<?php
namespace MicroweberPackages\Import\Loggers;

use MicroweberPackages\Backup\Loggers\BackupDefaultLogger;

final class ImportLogger extends BackupDefaultLogger
{
	public static $logName = 'Importing';
	public static $logFileName = 'import-session.log';

}
