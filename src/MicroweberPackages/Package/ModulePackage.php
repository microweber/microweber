<?php

namespace MicroweberPackages\Package;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;

class ModulePackage
{

    public string $type = '';

    public function type(string $moduleType): static
    {
        $this->type = $moduleType;
        return $this;


    }

    public function hasLiveEditSettings(string $componentName, $params = []): static
    {

        ModuleAdmin::registerSettingsComponent($this->type, $componentName);

        return $this;
    }


    public function hasFilamentPage(string $page, string $scope = \MicroweberPackages\Admin\Providers\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin'): static
    {
        FilamentRegistry::registerPage($page, $scope, $panelId);
        return $this;
    }

    public function hasFilamentResource(string $resource, string $scope = \MicroweberPackages\Admin\Providers\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin'): static
    {

        FilamentRegistry::registerResource($resource, $scope, $panelId);
        return $this;
    }

    public function hasFilamentPlugin(string $plugin, string $scope = \MicroweberPackages\Admin\Providers\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin'): static
    {
        FilamentRegistry::registerPlugin($plugin, $scope, $panelId);
        return $this;
    }

    public function hasFilamentWidget(string $widget, string $scope = \MicroweberPackages\Admin\Providers\Filament\FilamentAdminPanelProvider::class, string $panelId = 'admin'): static
    {
        FilamentRegistry::registerWidget($widget, $scope, $panelId);
        return $this;
    }

    public function hasFrontendController(string $componentName): static
    {
        return $this;
    }


}
