<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Module\Facades;

use Illuminate\Support\Facades\Facade;
/**
 * Class ModuleAdmin
 *
 * @method static void registerSettingsComponent(string $moduleType, string $componentName)
 * @method static void registerSkinSettingsComponent(string $moduleType, string $skinName, string $componentName)
 * @method static string|null getSettingsComponent(string $moduleName)
 * @method static string|null getSkinSettingsComponent(string $moduleName, string $skinName)
 * @see \MicroweberPackages\Module\ModuleAdminManager
 */
class ModuleAdmin extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'module_admin_manager';
    }
}
