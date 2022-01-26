<?php

namespace MicroweberPackages\Backup\Loggers;

class BackupLogger extends DefaultLogger
{
    public static $logName = 'Backup';
    public static $logFileName = 'backup-session.log';

    public static function getLogFilepath()
    {
        return userfiles_path() . DS . 'cache' . DS . 'backup' . DS . static::$logFileName;
    }

}
