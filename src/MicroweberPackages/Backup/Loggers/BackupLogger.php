<?php
namespace MicroweberPackages\Backup\Loggers;

class BackupLogger extends DefaultLogger
{
    public static $logName = 'Backup';
    public static $logFileName = 'backup-session.log';
}
