<?php

namespace MicroweberPackages\Microweber\Traits;

trait HasMicroweberModule
{

    public static function getName(): string
    {
        if (isset(static::$name)) {
            return static::$name;
        }

        return '';
    }

    public static function getIcon(): string
    {
        if (isset(static::$icon)) {
            return static::$icon;
        }

        return '';
    }

    public static function getPosition(): int
    {
        if (isset(static::$position)) {
            return static::$position;
        }
        return 0;
    }
    public static function getSettingsComponent(): string
    {
        if (isset(static::$settingsComponent)) {
            return static::$settingsComponent;
        }
        return '';
    }

}
