<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Contracts;

/**
 * Contract for a registerable Microweber module class.
 *
 * Implemented by {@see \MicroweberPackages\ModuleRegistry\Abstract\BaseModule}
 * via static methods on concrete module classes.
 */
interface ModuleContract
{
    public static function getName(): string;

    public static function getModuleType(): string;

    public static function getIcon(): string;

    public static function getPosition(): int;

    public static function getSettingsComponent(): string;

    public static function isStaticElement(): bool;

    public static function shouldRegisterNavigation(): bool;

    /**
     * @return list<string>
     */
    public static function getTranslatableOptionKeys(): array;

    public static function getTemplatesNamespace(): string;

    /**
     * @return \Illuminate\Contracts\View\View|string|mixed
     */
    public function render();
}
