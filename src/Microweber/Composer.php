<?php
namespace Microweber;
class Composer

{

    public static function postUpdate($event)
    {
        self::postInstall($event);
    }

    public static function postInstall(Event $event)
    {
        $composer = $event->getComposer();
        // do stuff
    }

    public static function postPackageInstall(Event $event)
    {
        $installedPackage = $event->getOperation()->getPackage();
        // do stuff
    }

    public static function warmCache(Event $event)
    {
        // make cache toasty
    }

}