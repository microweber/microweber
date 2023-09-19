<?php
namespace MicroweberPackages\Module;


use Livewire\Livewire;
class ModuleAdminManager
{
    /**
     * Store settings components.
     *
     * @var array
     */
    public $settingsComponent = [];

    /**
     * Store skin settings components.
     *
     * @var array
     */
    public $skinSettingsComponent = [];

    /**
     * Register a settings component for a module.
     *
     * @param string $moduleName
     * @param string $componentName
     */
    public function registerSettingsComponent($moduleName, $componentName)
    {
        $this->settingsComponent[$moduleName] = $componentName;
        $livewireComponentName = 'microweber-module-'.$moduleName.'::settings';
        Livewire::component($livewireComponentName, $componentName);
    }

    /**
     * Register a skin settings component for a module and skin.
     *
     * @param string $moduleName
     * @param string $skinName
     * @param string $componentName
     */
    public function registerSkinSettingsComponent($moduleName, $skinName, $componentName)
    {
        if (!isset($this->skinSettingsComponent[$moduleName])) {
            $this->skinSettingsComponent[$moduleName] = [];
        }
        $this->skinSettingsComponent[$moduleName][$skinName] = $componentName;
        $livewireComponentName ='microweber-module-'.$moduleName.'::template-settings-'.$skinName;
        Livewire::component($livewireComponentName, $componentName);


    }

    /**
     * Get the settings component for a module.
     *
     * @param string $moduleName
     *
     * @return string|null
     */
    public function getSettingsComponent($moduleName)
    {
        return isset($this->settingsComponent[$moduleName])
            ? $this->settingsComponent[$moduleName]
            : null;
    }

    /**
     * Get the skin settings component for a module and skin.
     *
     * @param string $moduleName
     * @param string $skinName
     *
     * @return string|null
     */
    public function getSkinSettingsComponent($moduleName, $skinName)
    {
        return isset($this->skinSettingsComponent[$moduleName][$skinName])
            ? $this->skinSettingsComponent[$moduleName][$skinName]
            : null;
    }

    public $settings = [];
    public function registerSettings($moduleName, $componentAlias)
    {
        $this->settings[$moduleName] = $componentAlias;
    }
    public function getSettings($moduleName)
    {
        return isset($this->settings[$moduleName])
            ? $this->settings[$moduleName]
            : null;
    }

    public $skinSettings = [];

    public function registerSkinSettings($moduleName, $skinName, $componentAlias)
    {
        if (!isset($this->skinSettings[$moduleName])) {
            $this->skinSettings[$moduleName] = [];
        }
        $this->skinSettings[$moduleName][$skinName] = $componentAlias;
    }


    public function getSkinSettings($moduleName, $skinName)
    {
        return isset($this->skinSettings[$moduleName][$skinName])
            ? $this->skinSettings[$moduleName][$skinName]
            : null;
    }
}
