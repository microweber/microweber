<?php
namespace MicroweberPackages\Import\Loggers;

use Modules\Backup\Loggers\DefaultLogger;

final class ImportLogger extends DefaultLogger
{
	public static $logName = 'Importing';
	public static $logFileName = 'import-session.log';

}
