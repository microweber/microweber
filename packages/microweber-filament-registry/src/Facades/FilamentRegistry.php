<?php

namespace MicroweberPackages\FilamentRegistry\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\FilamentRegistry\FilamentRegistryManager;

/**
 * Class FilamentRegistry
 *
 * @method static static setDefaultScope(string $scope)
 * @method static string getDefaultScope()
 * @method static static setDefaultPanelId(string $panelId)
 * @method static string getDefaultPanelId()
 * @method static array registerResource(string $resource, ?string $scope = null, string $panelId = 'admin')
 * @method static array getResources(?string $scope = null, string $panelId = 'admin')
 * @method static array registerPage(string $page, ?string $scope = null, string $panelId = 'admin')
 * @method static array getPages(?string $scope = null, string $panelId = 'admin')
 * @method static array registerWidget(string $widget, ?string $scope = null, string $panelId = 'admin')
 * @method static array getWidgets(?string $scope = null, string $panelId = 'admin')
 * @method static array registerPlugin(string $plugin, ?string $scope = null, string $panelId = 'admin')
 * @method static array getPlugins(?string $scope = null, string $panelId = 'admin')
 * @method static array registerCluster(string $cluster, ?string $scope = null, string $panelId = 'admin')
 * @method static array getClusters(?string $scope = null, string $panelId = 'admin')
 * @method static void registerGlobalSearchEntry(string $title, string $url, array $keywords = [], string $group = 'Settings', array $details = [], ?string $icon = null)
 * @method static array getGlobalSearchEntries()
 * @method static static flush()
 * @method static array all(?string $scope = null, string $panelId = 'admin')
 *
 * @mixin \MicroweberPackages\FilamentRegistry\FilamentRegistryManager
 * @see \MicroweberPackages\FilamentRegistry\FilamentRegistryManager
 */
class FilamentRegistry extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FilamentRegistryManager::class;
    }
}