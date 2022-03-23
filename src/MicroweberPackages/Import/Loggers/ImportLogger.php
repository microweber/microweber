<?php
namespace MicroweberPackages\Import\Loggers;

use MicroweberPackages\Backup\Loggers\DefaultLogger;

final class ImportLogger extends DefaultLogger
{
	public static $logName = 'Importing';
	public static $logFileName = 'import-session.log';

}
