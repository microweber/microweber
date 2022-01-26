<?php
namespace MicroweberPackages\Backup\Loggers;

use MicroweberPackages\Backup\Loggers\DefaultLogger;

final class ImportLogger extends DefaultLogger
{
	public static $logName = 'Importing';
	public static $logFileName = 'backup-import-session.log';

}
