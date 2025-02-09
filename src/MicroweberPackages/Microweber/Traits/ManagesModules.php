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
     * @param string $moduleClass The class of the module.
     */
    public function module($moduleClass): void
    {


        if (class_exists($moduleClass)) {
            /** @var BaseModule $moduleClass */
            if (method_exists($moduleClass, 'getModuleType')) {
                $type = $moduleClass::getModuleType();
                if ($type) {
                    $this->modules[$type] = $moduleClass;
                }
            }
        }
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

    public function getModuleClass($type): string
    {
        return isset($this->modules[$type]) ? $this->modules[$type] : '';
    }

    /**
     * Render a module of a specific type with given parameters.
     *
     * @param string $type The type of the module.
     * @param array $params The parameters for rendering the module.
     * @return string The rendered module output.
     */
    public function render($type, $params)
    {
        if (!$this->hasModule($type)) {
            return '';
        }
        $module = $this->make($type, $params);
        return $module->render();
    }

    public function make($type, $params): BaseModule
    {
        /** @var BaseModule $module */
        $module = new $this->modules[$type]($params);
        return $module;
    }

    /**
     * Retrieve the settings components for all registered modules.
     *
     * @return array The settings components for the modules.
     */
    public function getSettingsComponents(): array
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
     * Retrieve the settings components for all registered modules.
     *
     * @return array The settings components for the modules.
     */
    public function getTranslatableOptionKeys(): array
    {
        $modules = $this->getModules();
        $settings = [];
        foreach ($modules as $type => $module) {
            /** @var BaseModule $module */
            $settings[$type] = $module::getTranslatableOptionKeys();
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

    public function getTemplates($moduleType, $activeSiteTemplate = false): array
    {
        $templatesForModule = [];
        $foldersForScan = [];
        $ready = [];
        $moduleClass = $this->getModuleClass($moduleType);

        if(!$activeSiteTemplate){
            $activeSiteTemplate = template_name();
        }

        if ($moduleClass) {
            if (class_exists($moduleClass)) {
                /** @var BaseModule $moduleClass */
                if (method_exists($moduleClass, 'getTemplatesNamespace')) {


                    $templatesNamespace = $moduleClass::getTemplatesNamespace();
                    $scanTemplates = new \MicroweberPackages\Microweber\Support\ScanForBladeTemplates();
                    $templatesForModule = $scanTemplates->scan($templatesNamespace);

                    if ($activeSiteTemplate) {
                        // we will check for module templates in the active site template
                        $checkIfActiveSiteTemplate = app()->templates->find($activeSiteTemplate);
                        if ($checkIfActiveSiteTemplate) {
                            $checkIfActiveSiteTemplateLowerName = $checkIfActiveSiteTemplate->getLowerName();
                            $templatesNamespaceInActiveSiteTemplate = str_replace('::', '.', $templatesNamespace);
                            $templatesNamespaceInActiveSiteTemplate = 'templates.' . $checkIfActiveSiteTemplateLowerName . '::' . $templatesNamespaceInActiveSiteTemplate;


                            $scanTemplatesInActiveSiteTemplate = new \MicroweberPackages\Microweber\Support\ScanForBladeTemplates();
                            $templatesForModuleInActiveSiteTemplate = $scanTemplatesInActiveSiteTemplate->scan($templatesNamespaceInActiveSiteTemplate);
                            if ($templatesForModuleInActiveSiteTemplate) {
                                foreach ($templatesForModuleInActiveSiteTemplate as $templatesForModuleInActiveSiteTemplateKey => $templatesForModuleInActiveSiteTemplateValue) {
                                    if (!$templatesForModule) {
                                        continue;
                                    }
                                    foreach ($templatesForModule as $templatesForModuleKey => $templatesForModuleValue) {
                                        //check if layout_file is the same as in the module and unset it
                                        if (isset($templatesForModuleValue['layout_file']) && isset($templatesForModuleInActiveSiteTemplateValue['layout_file']) && $templatesForModuleValue['layout_file'] == $templatesForModuleInActiveSiteTemplateValue['layout_file']) {
                                            $ready[] = $templatesForModuleInActiveSiteTemplateValue;
                                            //    unset($templatesForModule[$templatesForModuleKey]);
                                            //  unset($templatesForModuleInActiveSiteTemplate[$templatesForModuleInActiveSiteTemplateKey]);
                                            continue(2);
                                        }
                                    }
                                    if (isset($templatesForModuleInActiveSiteTemplate[$templatesForModuleInActiveSiteTemplateKey])) {
                                        $ready[] = $templatesForModuleInActiveSiteTemplate[$templatesForModuleInActiveSiteTemplateKey];
                                    }

                                }

                            } else {
                                $ready = $templatesForModule;
                            }
                        }
                    } else {
                        $ready = $templatesForModule;
                    }
                }
            }
            if ($ready) {
                return $ready;
            }
        }
        return [];
    }

}
