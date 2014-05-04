<?php
namespace Microweber;

use Composer\Script\Event;

class Composer

{

    public static function postUpdate(Event $event)
    {
        self::postInstall($event);
    }

    public static function postInstall(Event $event)
    {
        //$composer = $event->getComposer();
        // do stuff
    }

    public static function postPackageInstall(Event $event)
    {
        //$installedPackage = $event->getOperation()->getPackage();
        // do stuff
    }

    public static function warmCache(Event $event)
    {
        // make cache toasty
    }

    // copy from
    // https://github.com/barryvdh/laravel-vendor-cleanup/blob/master/src/config/config.php


}