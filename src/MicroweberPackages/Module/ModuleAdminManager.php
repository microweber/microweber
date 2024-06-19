<?php

namespace MicroweberPackages\Module;


use Livewire\Livewire;

class ModuleAdminManager
{
    public array $settings = [];

    public array $settingsComponent = [];

    public array $skinSettingsComponent = [];
    public array $modulesAdminUrls = [];

    public array $liveEditModuleSettingsUrls = [];
    public array $adminPanelPages = [];
    public array $adminPanelPagesWithLocation = [];

    public array $adminPanelWidgets = [
        'default' => [],
    ];

    public $panelResources = [];

    public array $liveEditPanelPages = [];

    public array $skinSettings = [];


    /**
     * Register a settings component for a module.
     *
     * @param string $moduleName
     * @param string $componentName
     * @deprecated
     */
    public function registerSettingsComponent(string $moduleName, string $componentName)
    {
        $this->settingsComponent[$moduleName] = $componentName;
        $livewireComponentName = 'microweber-module-' . $moduleName . '::settings';

        Livewire::component($livewireComponentName, $componentName);
    }

    /**
     * Register a skin settings component for a module and skin.
     *
     * @param string $moduleName
     * @param string $skinName
     * @param string $componentName
     */
    public function registerSkinSettingsComponent(string $moduleName, string $skinName, string $componentName): void
    {
        if (!isset($this->skinSettingsComponent[$moduleName])) {
            $this->skinSettingsComponent[$moduleName] = [];
        }
        $this->skinSettingsComponent[$moduleName][$skinName] = $componentName;
        $livewireComponentName = 'microweber-module-' . $moduleName . '::template-settings-' . $skinName;
        Livewire::component($livewireComponentName, $componentName);


    }

    /**
     * Get the settings component for a module.
     *
     * @param string $moduleName
     *
     * @return string|null
     */
    public function getSettingsComponent(string $moduleName): ?string
    {
        return $this->settingsComponent[$moduleName] ?? null;
    }

    /**
     * Get the skin settings component for a module and skin.
     *
     * @param string $moduleName
     * @param string $skinName
     *
     * @return string|null
     */
    public function getSkinSettingsComponent(string $moduleName, string $skinName): ?string
    {
        return $this->skinSettingsComponent[$moduleName][$skinName] ?? null;
    }


    public function registerSettings($moduleName, $componentAlias): void
    {
        $this->settings[$moduleName] = $componentAlias;
    }


    public function getSettings($moduleName)
    {
        return $this->settings[$moduleName] ?? null;
    }


    public function registerSkinSettings($moduleName, $skinName, $componentAlias): void
    {
        if (!isset($this->skinSettings[$moduleName])) {
            $this->skinSettings[$moduleName] = [];
        }
        $this->skinSettings[$moduleName][$skinName] = $componentAlias;
    }


    public function getSkinSettings($moduleName, $skinName)
    {
        return $this->skinSettings[$moduleName][$skinName] ?? null;
    }


    public function registerLiveEditSettingsUrl($moduleName, $url): void
    {
        $this->liveEditModuleSettingsUrls[$moduleName] = $url;
    }

    public function getLiveEditSettingsUrl($moduleName)
    {
        return $this->liveEditModuleSettingsUrls[$moduleName] ?? null;
    }

    public function getLiveEditSettingsUrls(): array
    {
        return $this->liveEditModuleSettingsUrls;
    }

    public function registerPanelPage($page, $location): void
    {

        if ($location) {
            if (!isset($this->adminPanelPagesWithLocation[$location])) {
                $this->adminPanelPagesWithLocation[$location] = [];
            }

            $this->adminPanelPagesWithLocation[$location][] = $page;
        } else {
            $this->adminPanelPages[] = $page;
        }
    }

    public function getPanelPages($location = null): array
    {
        if ($location) {
            return $this->adminPanelPagesWithLocation[$location] ?? [];
        }

        return $this->adminPanelPages;
    }

    public function registerLiveEditPanelPage($page): void
    {
        $this->liveEditPanelPages[] = $page;
    }

    public function getLiveEditPanelPages(): array
    {
        return $this->liveEditPanelPages;
    }


    public function registerAdminPanelWidget($widget, $location = 'default'): void
    {
        if (!isset($this->adminPanelWidgets[$location])) {
            $this->adminPanelWidgets[$location] = [];
        }

        $this->adminPanelWidgets[$location][] = $widget;
    }

    public function getAdminPanelWidgets($location = 'default'): array
    {
        return $this->adminPanelWidgets[$location] ?? [];
    }



    public function registerAdminUrl($module, $url): void
    {
        $this->modulesAdminUrls[$module] = $url;
    }

    public function getAdminUrl($module)
    {
        return $this->modulesAdminUrls[$module] ?? null;
    }

    public function getAdminUrls(): array
    {
        return $this->modulesAdminUrls;
    }

    public function registerPanelResource($resource): void
    {
        $this->panelResources[] = $resource;
    }

    public function getPanelResources(): array
    {
        return $this->panelResources;
    }

}
