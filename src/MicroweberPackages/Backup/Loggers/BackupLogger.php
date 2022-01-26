<?php

namespace MicroweberPackages\Backup\Loggers;

class BackupLogger extends DefaultLogger
{
    public static $logName = 'Backup';
    public static $logFileName = 'backup-session.log';

    public static function getLogFilepath()
    {
        $filepath = userfiles_path() . 'cache' . DS . 'backup' . DS . static::$logFileName;
        if (!is_dir($filepath)) {
            mkdir_recursive($filepath);
        }

        return $filepath;
    }

}
