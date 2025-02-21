<?php
namespace Modules\Restore\Loggers;

use Modules\Backup\Loggers\DefaultLogger;

final class RestoreLogger extends DefaultLogger
{
	public static $logName = 'Restore';
	public static $logFileName = 'restore-session.log';

}
