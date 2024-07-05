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
 * @method static string|null getSettingsComponent(string $moduleName)
 * @method static string|null getSkinSettingsComponent(string $moduleName, string $skinName)
 *
 *
 * For live Edit
 * @method static string|null registerLiveEditSettingsUrl(string $moduleName, string $url)
 * @method static string|null getLiveEditSettingsUrl(string $moduleName)
 * @method static array|null getLiveEditSettingsUrls()
 *
 * For admin
 * @method static void registerAdminUrl($module, $url)
 * @method static string|null getAdminUrl(string $moduleName)
 * @method static array getAdminUrls()
 *
 * For filament
 * @method static void registerPanelPage($page,$location=null)
 * @method static array getPanelPages($location=null)
 * @method static void registerLiveEditPanelPage($page)
 * @method static array getLiveEditPanelPages()
 * @method static void registerPanelResource($resource)
 * @method static array getPanelResources()
 *
 *
 *
 * @method static void registerAdminPanelWidget($widget, $location = 'default')
 * @method static array getAdminPanelWidgets($location = 'default')
 *
 *
 * @method static void registerPanelPlugin($page, $location = null)
 * @method static array getPanelPlugins($location = null)
 *
 *
 * @mixin \MicroweberPackages\Module\ModuleAdminManager
 * @see \MicroweberPackages\Module\ModuleAdminManager
 *
 * @method static void registerSettingsComponent(string $moduleType, string $componentName)
 * @method static void registerSkinSettingsComponent(string $moduleType, string $skinName, string $componentName)
 * @method static void registerSettings(string $moduleType, string $componentAlias)
 * @method static void registerSkinSettings(string $moduleType, string $skinName, string $componentAlias)
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
