<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Traits;

/**
 * Common static metadata accessors for Microweber modules.
 *
 * Expects the using class to define optional static properties:
 * `$name`, `$module`, `$icon`, `$position`, `$settingsComponent`,
 * `$isStaticElement`, `$shouldRegisterNavigation`.
 *
 * @phpstan-require-extends \MicroweberPackages\ModuleRegistry\Abstract\BaseModule
 */
trait HasMicroweberModule
{
    public static function getName(): string
    {
        return static::$name;
    }

    public static function getModuleType(): string
    {
        return static::$module;
    }

    public static function getIcon(): string
    {
        return static::$icon;
    }

    public static function getPosition(): int
    {
        return static::$position;
    }

    public static function getSettingsComponent(): string
    {
        return static::$settingsComponent;
    }

    public static function isStaticElement(): bool
    {
        return static::$isStaticElement;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::$shouldRegisterNavigation;
    }

    /**
     * @deprecated Use {@see shouldRegisterNavigation()} — kept for BC of the original typo.
     */
    public static function shouldRegisterNavigtion(): bool
    {
        return static::shouldRegisterNavigation();
    }
}
