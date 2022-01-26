<?php

namespace MicroweberPackages\Backup\Loggers;

abstract class DefaultLogger
{
    protected static $debug = false;
    protected static $logger;
    protected static $logFileName = 'default-log.log';

    public static function setLogger($logger)
    {
        static::$logger = $logger;
    }

    public static function clearLog()
    {
        file_put_contents(static::getLogFilepath(), false);
    }

    public static function setLogInfo($log)
    {

        if (static::$logger) {
            $loggerClass = new static::$logger();
            if (method_exists($loggerClass, 'log')) {
                return $loggerClass->log($log);
            }
        }

        if (is_ajax()) {
            static::$debug = false;
        }

        if (static::$debug) {
            echo $log . PHP_EOL;
        }

        static::addNew(static::getLogFilepath(), $log, 45);
    }

    public static function addNew($fileName, $line, $max = 15)
    {

        $logPath = dirname($fileName);
        if (!is_dir($logPath)) {
            mkdir_recursive($logPath);
        }

        if (is_file($fileName)) {
            $countLines = file_get_contents($fileName);
            $countLines = substr_count($countLines, "\n");;

            if ($countLines > $max) {
                @file_put_contents($fileName, '');
            }
        }

        @file_put_contents($fileName, $line . "\n", FILE_APPEND);
    }

    public static function getLogFilepath()
    {
        return userfiles_path() . DS . 'cache' . DS . 'logs' . DS . static::$logFileName;
    }

}
