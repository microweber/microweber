<?php

declare(strict_types=1);

namespace MicroweberPackages\Package;

/**
 * Fluent helper for registering CMS module integrations (Live Edit settings,
 * Filament pages/resources/widgets/plugins, view components).
 *
 * Soft-depends on optional packages so this class is safe to load in a
 * standalone Laravel app that does not ship FilamentRegistry or ModuleAdmin:
 *
 * - microweber-packages/filament-registry (FilamentRegistry facade)
 * - CMS ModuleAdmin facade (MicroweberPackages\Module\Facades\ModuleAdmin)
 *
 * When a dependency is missing the corresponding registration is a no-op.
 */
class ModulePackage
{
    public string $type = '';

    public function __construct(?string $moduleType = null)
    {
        if ($moduleType !== null && $moduleType !== '') {
            $this->type($moduleType);
        }
    }

    public function type(string $moduleType): static
    {
        $this->type = $moduleType;

        return $this;
    }

    /**
     * Register a Live Edit settings Livewire/Filament component for this module.
     *
     * @param  class-string|string  $componentName
     * @param  array<string, mixed>  $params
     */
    public function hasLiveEditSettings(string $componentName, array $params = []): static
    {
        unset($params); // reserved for future options

        $facade = 'MicroweberPackages\\Module\\Facades\\ModuleAdmin';
        if ($this->type === '' || ! class_exists($facade)) {
            return $this;
        }

        $facade::registerSettingsComponent($this->type, $componentName);

        return $this;
    }

    /**
     * @param  class-string  $page
     */
    public function hasFilamentPage(string $page, ?string $scope = null, string $panelId = 'admin'): static
    {
        $facade = 'MicroweberPackages\\FilamentRegistry\\Facades\\FilamentRegistry';
        if (! class_exists($facade)) {
            return $this;
        }

        $facade::registerPage($page, $this->resolveScope($scope), $panelId);

        return $this;
    }

    /**
     * @param  class-string  $resource
     */
    public function hasFilamentResource(string $resource, ?string $scope = null, string $panelId = 'admin'): static
    {
        $facade = 'MicroweberPackages\\FilamentRegistry\\Facades\\FilamentRegistry';
        if (! class_exists($facade)) {
            return $this;
        }

        $facade::registerResource($resource, $this->resolveScope($scope), $panelId);

        return $this;
    }

    /**
     * @param  class-string  $plugin
     */
    public function hasFilamentPlugin(string $plugin, ?string $scope = null, string $panelId = 'admin'): static
    {
        $facade = 'MicroweberPackages\\FilamentRegistry\\Facades\\FilamentRegistry';
        if (! class_exists($facade)) {
            return $this;
        }

        $facade::registerPlugin($plugin, $this->resolveScope($scope), $panelId);

        return $this;
    }

    /**
     * @param  class-string  $widget
     */
    public function hasFilamentWidget(string $widget, ?string $scope = null, string $panelId = 'admin'): static
    {
        $facade = 'MicroweberPackages\\FilamentRegistry\\Facades\\FilamentRegistry';
        if (! class_exists($facade)) {
            return $this;
        }

        $facade::registerWidget($widget, $this->resolveScope($scope), $panelId);

        return $this;
    }

    /**
     * @param  class-string|string  $componentName
     */
    public function hasViewComponent(string $componentName, ?string $alias = null): static
    {
        $facade = 'MicroweberPackages\\Module\\Facades\\ModuleAdmin';
        if ($this->type === '' || ! class_exists($facade)) {
            return $this;
        }

        $facade::registerViewComponent($this->type, $componentName, $alias);

        return $this;
    }

    /**
     * Resolve the Filament panel-provider scope, preferring the CMS admin panel
     * when present and falling back to FilamentRegistry defaults / "admin".
     */
    protected function resolveScope(?string $scope): string
    {
        if ($scope !== null && $scope !== '') {
            return $scope;
        }

        $cmsAdminPanel = 'MicroweberPackages\\Admin\\Filament\\FilamentAdminPanelProvider';
        if (class_exists($cmsAdminPanel)) {
            return $cmsAdminPanel;
        }

        return 'admin';
    }
}
