<?php

namespace MicroweberPackages\Filament\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Filament\FilamentRegistryManager;

/**
 * Class FilamentRegistry
 *
 * @method static array registerResource(string $resource, string $scope = \MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin')
 * @method static array getResources(string $scope = \MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin')
 * @method static array registerPage(string $page, string $scope = \MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin')
 * @method static array getPages(string $scope = \MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin')
 * @method static array registerWidget(string $widget, string $scope = \MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin')
 * @method static array getWidgets(string $scope = \MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin')
 * @method static array registerPlugin(string $plugin, string $scope = \MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin')
 * @method static array getPlugins(string $scope = \MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin')
 * @method static array registerCluster(string $plugin, string $scope = \MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin')
 * @method static array getClusters(string $scope = \MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin')
 *
 * @mixin \MicroweberPackages\Filament\FilamentRegistryManager
 * @see \MicroweberPackages\Filament\FilamentRegistryManager
 */
class FilamentRegistry extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return FilamentRegistryManager::class;
    }
}
