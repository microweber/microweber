<?php

namespace MicroweberPackages\Microweber\Traits;


use MicroweberPackages\Microweber\Abstract\BaseModule;


/**
 * Trait ManagesModules
 *
 * Provides functionality to manage and render modules.
 */
trait ManagesModules
{
    public array $modules = [];

    /**
     * Register a module with a specific type and class.
     *
     * @param string $type The type of the module.
     * @param string $moduleClass The class of the module.
     */
    public function module($type, $moduleClass): void
    {
        $this->modules[$type] = $moduleClass;
    }

    /**
     * Retrieve all registered modules.
     *
     * @return array The registered modules.
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * Check if a module of a specific type is registered.
     *
     * @param string $type The type of the module.
     * @return bool True if the module is registered, false otherwise.
     */
    public function hasModule($type): bool
    {
        return isset($this->modules[$type]);
    }

    /**
     * Render a module of a specific type with given parameters.
     *
     * @param string $type The type of the module.
     * @param array $params The parameters for rendering the module.
     * @return string The rendered module output.
     */
    public function renderModule($type, $params)
    {
        if (!$this->hasModule($type)) {
            return '';
        }
        /** @var BaseModule $module */

        $module = new $this->modules[$type]($type, $params);

        return $module->render();
    }

    /**
     * Retrieve the settings components for all registered modules.
     *
     * @return array The settings components for the modules.
     */
    public function getSettingsComponents()
    {
        $modules = $this->getModules();
        $settings = [];
        foreach ($modules as $type => $module) {
            /** @var BaseModule $module */
            $settings[$type] = $module::getSettingsComponent();
        }
        return $settings;
    }

    /**
     * Retrieve detailed information about all registered modules.
     *
     * @return array The details of the registered modules.
     */
    public function getModulesDetails(): array
    {
        $modules = $this->getModules();
        $settings = [];
        foreach ($modules as $type => $module) {
            /** @var BaseModule $module */
            $settings[$type] = [
                'module' => $type,
                'name' => $module::getName(),
                'icon' => $module::getIcon(),
                'position' => $module::getPosition() ?? 0,
            ];
        }
        //sort by position
        if (!empty($settings)) {
            usort($settings, function ($a, $b) {
                return $a['position'] <=> $b['position'];
            });
        }

        return $settings;
    }

}
