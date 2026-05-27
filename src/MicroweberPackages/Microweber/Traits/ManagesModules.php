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
                'as_element' => $module::isStaticElement() ?? null,
                'registers_in_navigation' => $module::shouldRegisterNavigtion() ?? null,
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
        $ready = [];
        $moduleClass = $this->getModuleClass($moduleType);

        if (!$activeSiteTemplate) {
            $activeSiteTemplate = template_name();
        }

        $templateParent = template_parent($activeSiteTemplate);
        if ($templateParent and $templateParent != $activeSiteTemplate) {
            $activeSiteTemplate = $templateParent;
        }

        if ($moduleClass && class_exists($moduleClass) && method_exists($moduleClass, 'getTemplatesNamespace')) {

            $templatesNamespace = $moduleClass::getTemplatesNamespace();

            // 1. Scan base module templates
            $scanTemplates = new \MicroweberPackages\Microweber\Support\ScanForBladeTemplates();
            $baseModuleSkins = $scanTemplates->scan($templatesNamespace, $moduleType);

            // 2. Scan active site template
            $activeTemplateSkins = [];
            $activeTemplateLowerName = '';
            if ($activeSiteTemplate) {
                $checkIfActiveSiteTemplate = app()->templates->find($activeSiteTemplate);
                if ($checkIfActiveSiteTemplate) {
                    $activeTemplateLowerName = $checkIfActiveSiteTemplate->getLowerName();
                    $ns = str_replace('::', '.', $templatesNamespace);
                    $ns = 'templates.' . $activeTemplateLowerName . '::' . $ns;
                    $scanner = new \MicroweberPackages\Microweber\Support\ScanForBladeTemplates();
                    $activeTemplateSkins = $scanner->scan($ns, $moduleType, $activeSiteTemplate, $activeTemplateLowerName) ?: [];
                }
            }

            // 3. Scan other installed templates for additional layouts
            $otherTemplateSkins = [];
            $allTemplates = app()->templates->all();
            foreach ($allTemplates as $otherTemplate) {
                $otherLowerName = $otherTemplate->getLowerName();
                if ($activeTemplateLowerName && $otherLowerName === $activeTemplateLowerName) {
                    continue;
                }
                $ns = str_replace('::', '.', $templatesNamespace);
                $ns = 'templates.' . $otherLowerName . '::' . $ns;
                $scanner = new \MicroweberPackages\Microweber\Support\ScanForBladeTemplates();
                $scanned = $scanner->scan($ns, $moduleType, $otherTemplate->getName(), $otherLowerName);
                if ($scanned) {
                    $otherTemplateSkins = array_merge($otherTemplateSkins, $scanned);
                }
            }

            // 4. Merge with priority: active template > other templates > base module
            $seenLayoutFiles = [];
            foreach ($activeTemplateSkins as $skin) {
                if (isset($skin['layout_file'])) {
                    $ready[] = $skin;
                    $seenLayoutFiles[$skin['layout_file']] = true;
                }
            }
            foreach ($otherTemplateSkins as $skin) {
                if (isset($skin['layout_file']) && !isset($seenLayoutFiles[$skin['layout_file']])) {
                    $ready[] = $skin;
                    $seenLayoutFiles[$skin['layout_file']] = true;
                }
            }
            foreach ($baseModuleSkins as $skin) {
                if (isset($skin['layout_file']) && !isset($seenLayoutFiles[$skin['layout_file']])) {
                    $ready[] = $skin;
                    $seenLayoutFiles[$skin['layout_file']] = true;
                }
            }
        }

        if ($ready) {
            $ready = array_intersect_key($ready, array_unique(array_map(function ($el) {
                return $el['layout_file'];
            }, $ready)));
        }

        return $ready ?: [];
    }

}
