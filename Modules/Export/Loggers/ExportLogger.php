<?php
namespace Modules\Export\Loggers;


use Modules\Backup\Loggers\DefaultLogger;

class ExportLogger extends DefaultLogger
{
	public static $logName = 'Exporting';
	public static $logFileName = 'backup-export-session.log';

}
