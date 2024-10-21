<?php

namespace MicroweberPackages\Microweber\Traits;


use MicroweberPackages\Microweber\Abstract\BaseModule;

trait ManagesModules
{
    public array $modules = [];

    public function module($type, $moduleClass): void
    {
        $this->modules[$type] = $moduleClass;
    }

    public function getModules(): array
    {
        return $this->modules;
    }

    public function hasModule($type): bool
    {
        return isset($this->modules[$type]);
    }

    public function renderModule($type, $params)
    {
        if (!$this->hasModule($type)) {
            return '';
        }
        /** @var BaseModule $module */

        $module = new ($this->modules[$type])($type, $params);

        return $module->render();
    }

    public function getSettingsComponents()
    {
        $modules = $this->getModules();
        $settings = [];
        foreach ($modules as $type=> $module) {
            /** @var BaseModule $module */
            $settings[$type] = $module::getSettingsComponent();
        }
        return $settings;
    }

}
