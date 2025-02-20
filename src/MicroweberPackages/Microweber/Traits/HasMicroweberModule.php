<?php

namespace MicroweberPackages\Microweber\Traits;

/**
 * Trait HasMicroweberModule
 *
 * Provides common functionality for Microweber modules, such as retrieving
 * module-specific properties like name, icon, position, and settings component.
 */
trait HasMicroweberModule
{

    /**
     * Get the name of the module.
     *
     * @return string The name of the module, or an empty string if not set.
     */
    public static function getName(): string
    {
        if (isset(static::$name)) {
            return static::$name;
        }

        return '';
    }

    /**
     * Get the type of the module.
     *
     * @return string The name of the module, or an empty string if not set.
     */
    public static function getModuleType(): string
    {
        if (isset(static::$module)) {
            return static::$module;
        }

        return '';
    }

    /**
     * Get the icon of the module.
     *
     * @return string The icon of the module, or an empty string if not set.
     */
    public static function getIcon(): string
    {
        if (isset(static::$icon)) {
            return static::$icon;
        }

        return '';
    }

    /**
     * Get the position of the module.
     *
     * @return int The position of the module, or 0 if not set.
     */
    public static function getPosition(): int
    {
        if (isset(static::$position)) {
            return static::$position;
        }
        return 0;
    }

    /**
     * Get the settings component of the module.
     *
     * @return string The settings component of the module, or an empty string if not set.
     */
    public static function getSettingsComponent(): string
    {
        if (isset(static::$settingsComponent)) {
            return static::$settingsComponent;
        }
        return '';
    }


    public static function isStaticElement(): bool
    {
        if (isset(static::$isStaticElement)) {
            return static::$isStaticElement;
        }
        return false;
    }
}
