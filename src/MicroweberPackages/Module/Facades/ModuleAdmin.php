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

 *
 * @method static string|null getSettings(string $moduleType)
 * @method static string|null getSkinSettings(string $moduleName, string $skinName)
 *
 *
 * For live Edit modules
 * @method static string|null registerLiveEditSettingsUrl(string $moduleName, string $url)
 * @method static string|null getLiveEditSettingsUrl(string $moduleName)
 * @method static array|null getLiveEditSettingsUrls()
 *
 * For admin modules
 * @method static void registerAdminUrl($module, $url)
 * @method static string|null getAdminUrl(string $moduleName)
 * @method static array getAdminUrls()
 *
 *
 *
 * @mixin \MicroweberPackages\Module\ModuleAdminManager
 * @see \MicroweberPackages\Module\ModuleAdminManager
 *
 * @method static void registerSettingsComponent(string $moduleType, string $componentName)
 * @method static void registerSettings(string $moduleType, string $componentAlias)
 *
 */
class ModuleAdmin extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     * @see \MicroweberPackages\Module\ModuleAdminManager
     */
    protected static function getFacadeAccessor()
    {
        return 'module_admin_manager';
    }
}
