<?php
namespace MicroweberPackages\Backup\Loggers;

use MicroweberPackages\Backup\Loggers\DefaultLogger;

class BackupLogger extends DefaultLogger
{
	public static $logName = 'Backup';
	public static $logFileName = 'backup-session.log';

}
