<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Traits;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
use MicroweberPackages\ModuleRegistry\Support\CmsHelpers;
use MicroweberPackages\ModuleRegistry\Support\ScanForBladeTemplates;

/**
 * Register, resolve and render modules; discover blade skins.
 *
 * @phpstan-type ModuleDetail array{
 *     module: string,
 *     name: string,
 *     icon: string,
 *     position: int,
 *     as_element: bool|null,
 *     registers_in_navigation: bool|null
 * }
 */
trait ManagesModules
{
    /** @var array<string, class-string<BaseModule>> */
    public array $modules = [];

    /**
     * Register a module class by its static module type.
     *
     * @param  class-string  $moduleClass
     */
    public function module(string $moduleClass): void
    {
        if (! class_exists($moduleClass)) {
            return;
        }

        if (! method_exists($moduleClass, 'getModuleType')) {
            return;
        }

        /** @var class-string<BaseModule> $moduleClass */
        $type = $moduleClass::getModuleType();
        if ($type !== '') {
            $this->modules[$type] = $moduleClass;
        }
    }

    /**
     * @return array<string, class-string<BaseModule>>
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    public function hasModule(string $type): bool
    {
        return isset($this->modules[$type]);
    }

    /**
     * @return class-string<BaseModule>|''
     */
    public function getModuleClass(string $type): string
    {
        return $this->modules[$type] ?? '';
    }

    /**
     * @param  array<string, mixed>  $params
     * @return \Illuminate\Contracts\View\View|string|mixed
     */
    public function render(string $type, array $params)
    {
        if (! $this->hasModule($type)) {
            return '';
        }

        $module = $this->make($type, $params);

        return $module->render();
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function make(string $type, array $params): BaseModule
    {
        $class = $this->modules[$type];

        return new $class($params);
    }

    /**
     * @return array<string, string>
     */
    public function getSettingsComponents(): array
    {
        $settings = [];
        foreach ($this->getModules() as $type => $module) {
            $settings[$type] = $module::getSettingsComponent();
        }

        return $settings;
    }

    /**
     * @return array<string, list<string>>
     */
    public function getTranslatableOptionKeys(): array
    {
        $settings = [];
        foreach ($this->getModules() as $type => $module) {
            $settings[$type] = $module::getTranslatableOptionKeys();
        }

        return $settings;
    }

    /**
     * @return list<ModuleDetail>
     */
    public function getModulesDetails(): array
    {
        $settings = [];
        foreach ($this->getModules() as $type => $module) {
            $settings[] = [
                'module' => $type,
                'name' => $module::getName(),
                'icon' => $module::getIcon(),
                'position' => $module::getPosition(),
                'as_element' => $module::isStaticElement(),
                'registers_in_navigation' => $module::shouldRegisterNavigation(),
            ];
        }

        if ($settings !== []) {
            usort($settings, static function (array $a, array $b): int {
                return $a['position'] <=> $b['position'];
            });
        }

        return $settings;
    }

    /**
     * Discover blade skins for a module type across base module + site templates.
     *
     * @return list<array<string, mixed>>
     */
    public function getTemplates(string $moduleType, string|false $activeSiteTemplate = false): array
    {
        $ready = [];
        $moduleClass = $this->getModuleClass($moduleType);

        $activeSiteTemplateName = $activeSiteTemplate !== false && $activeSiteTemplate !== ''
            ? $activeSiteTemplate
            : CmsHelpers::templateName();

        $templateParent = CmsHelpers::templateParent($activeSiteTemplateName);
        if ($templateParent !== '' && $templateParent !== $activeSiteTemplateName) {
            $activeSiteTemplateName = $templateParent;
        }

        if ($moduleClass === '' || ! class_exists($moduleClass) || ! method_exists($moduleClass, 'getTemplatesNamespace')) {
            return [];
        }

        $templatesNamespace = $moduleClass::getTemplatesNamespace();
        if ($templatesNamespace === '') {
            return [];
        }

        $scanTemplates = new ScanForBladeTemplates();
        $baseModuleSkins = $scanTemplates->scan($templatesNamespace, $moduleType);

        $activeTemplateSkins = [];
        $activeTemplateLowerName = '';
        if ($activeSiteTemplateName !== '') {
            $checkIfActiveSiteTemplate = CmsHelpers::findTemplate($activeSiteTemplateName);
            if ($checkIfActiveSiteTemplate !== null) {
                $activeTemplateLowerName = CmsHelpers::templateLowerName($checkIfActiveSiteTemplate);
                $ns = str_replace('::', '.', $templatesNamespace);
                $ns = 'templates.' . $activeTemplateLowerName . '::' . $ns;
                $activeTemplateSkins = $scanTemplates->scan(
                    $ns,
                    $moduleType,
                    $activeSiteTemplateName,
                    $activeTemplateLowerName
                );
            }
        }

        $otherTemplateSkins = [];
        foreach (CmsHelpers::allTemplates() as $otherTemplate) {
            $otherLowerName = CmsHelpers::templateLowerName($otherTemplate);
            if ($activeTemplateLowerName !== '' && $otherLowerName === $activeTemplateLowerName) {
                continue;
            }
            $ns = str_replace('::', '.', $templatesNamespace);
            $ns = 'templates.' . $otherLowerName . '::' . $ns;
            $scanned = $scanTemplates->scan(
                $ns,
                $moduleType,
                CmsHelpers::templateDisplayName($otherTemplate),
                $otherLowerName
            );
            if ($scanned !== []) {
                $otherTemplateSkins = array_merge($otherTemplateSkins, $scanned);
            }
        }

        // Priority: active template > other templates > base module; dedup by layout_file and name
        $seenLayoutFiles = [];
        $seenNames = [];

        foreach ([$activeTemplateSkins, $otherTemplateSkins, $baseModuleSkins] as $skinGroup) {
            foreach ($skinGroup as $skin) {
                if (! isset($skin['layout_file']) || ! is_string($skin['layout_file'])) {
                    continue;
                }
                if (isset($seenLayoutFiles[$skin['layout_file']])) {
                    continue;
                }
                $nameKey = strtolower(trim(is_string($skin['name'] ?? null) ? $skin['name'] : ''));
                if ($nameKey !== '' && isset($seenNames[$nameKey])) {
                    continue;
                }
                $ready[] = $skin;
                $seenLayoutFiles[$skin['layout_file']] = true;
                if ($nameKey !== '') {
                    $seenNames[$nameKey] = true;
                }
            }
        }

        return $ready;
    }
}
