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
     * @deprecated
     */
    public function registerSettings($moduleName, $componentAlias): void
    {
        $this->settings[$moduleName] = $componentAlias;
    }


    /**
     * @deprecated
     */
    public function getSettings($moduleName)
    {
        return $this->settings[$moduleName] ?? null;
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


}
