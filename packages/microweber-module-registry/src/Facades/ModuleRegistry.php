<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
use MicroweberPackages\ModuleRegistry\ModuleRegistryManager;

/**
 * @see ModuleRegistryManager
 *
 * @method static void module(class-string $moduleClass)
 * @method static array<string, class-string<BaseModule>> getModules()
 * @method static bool hasModule(string $type)
 * @method static class-string<BaseModule>|'' getModuleClass(string $type)
 * @method static mixed render(string $type, array<string, mixed> $params)
 * @method static BaseModule make(string $type, array<string, mixed> $params)
 * @method static array<string, string> getSettingsComponents()
 * @method static array<string, list<string>> getTranslatableOptionKeys()
 * @method static list<array<string, mixed>> getModulesDetails()
 * @method static list<array<string, mixed>> getTemplates(string $moduleType, string|false $activeSiteTemplate = false)
 * @method static string siteUrl(string|false $path = false)
 * @method static string siteHostname()
 * @method static mixed contentGetById(int|string $id)
 * @method static mixed contentGet(array<string, mixed> $params)
 * @method static mixed contentGetByURL(string $url)
 * @method static mixed contentGetByTitle(string $title)
 * @method static mixed contentSave(array<string, mixed> $data)
 * @method static mixed contentUnpublish(int|string $id)
 * @method static mixed contentPublish(int|string $id)
 *
 * @mixin ModuleRegistryManager
 */
class ModuleRegistry extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'microweber';
    }
}
