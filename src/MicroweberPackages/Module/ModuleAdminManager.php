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


    /**
     * @deprecated
     */
    public array $adminPanelPages = [];
    /**
     * @deprecated
     */
    public array $adminPanelPagesWithLocation = [];

    /**
     * @deprecated
     */
    public array $adminPanelWidgets = [
        'default' => [],
    ];

    /**
     * @deprecated
     */
    public $panelResources = [];

    /**
     * @deprecated
     */
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


    /**
     * @deprecated
     */
    public function registerPanelPage($page, $location = null): void
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

    /**
     * @deprecated
     */
    public function getPanelPages($location = null): array
    {
        if ($location) {
            return $this->adminPanelPagesWithLocation[$location] ?? [];
        }

        return $this->adminPanelPages;
    }


    public array $adminPanelPlugins = [];
    public array $adminPanelPluginsWithLocation = [];

    /**
     * @deprecated
     */
    public function registerPanelPlugin($plugin, $panelName = null): void
    {

        if ($panelName) {
            if (!isset($this->adminPanelPluginsWithLocation[$panelName])) {
                $this->adminPanelPluginsWithLocation[$panelName] = [];
            }

            $this->adminPanelPluginsWithLocation[$panelName][] = $plugin;
        } else {
            $this->adminPanelPlugins[] = $plugin;
        }
    }

    /**
     * @deprecated
     */
    public function getPanelPlugins($location = null): array
    {
        if ($location) {
            return $this->adminPanelPluginsWithLocation[$location] ?? [];
        }

        return $this->adminPanelPlugins;
    }

    /**
     * @deprecated
     */
    public function registerLiveEditPanelPage($page): void
    {
        $this->liveEditPanelPages[] = $page;
    }

    /**
     * @deprecated
     */
    public function getLiveEditPanelPages(): array
    {
        return $this->liveEditPanelPages;
    }

    /**
     * @deprecated
     */
    public function registerAdminPanelWidget($widget, $location = 'default'): void
    {
        if (!isset($this->adminPanelWidgets[$location])) {
            $this->adminPanelWidgets[$location] = [];
        }

        $this->adminPanelWidgets[$location][] = $widget;
    }


    /**
     * @deprecated
     */
    public function getAdminPanelWidgets($location = 'default'): array
    {
        return $this->adminPanelWidgets[$location] ?? [];
    }

    /**
     * @deprecated
     */
    public function registerAdminUrl($module, $url): void
    {
        $this->modulesAdminUrls[$module] = $url;
    }

    /**
     * @deprecated
     */
    public function getAdminUrl($module)
    {
        return $this->modulesAdminUrls[$module] ?? null;
    }

    /**
     * @deprecated
     */
    public function getAdminUrls(): array
    {
        return $this->modulesAdminUrls;
    }

    /**
     * @deprecated
     */
    public function registerPanelResource($resource): void
    {
        $this->panelResources[] = $resource;
    }

    /**
     * @deprecated
     */
    public function getPanelResources(): array
    {
        return $this->panelResources;
    }


    public array $filamentResourceRegistry = [];


    public function registerFilamentResource($resource, $location = '', $panelId = 'admin'): array
    {

        return $this->filamentResourceRegistry[$panelId][] = [
            'resource' => $resource,
            'location' => $location,
        ];
    }

    public function getFilamentResources($location = false, $panelId = 'admin'): array
    {
        if ($this->filamentResourceRegistry[$panelId] and !empty($this->filamentResourceRegistry[$panelId])) {
            $results = [];
            if ($location) {
                foreach ($this->filamentResourceRegistry[$panelId] as $resource) {
                    if ($resource['location'] == $location) {
                        $results[] = $resource;
                    }
                }
                return $results;
            } else {
                foreach ($this->filamentResourceRegistry[$panelId] as $resource) {
                    if ($resource['location'] === '') {
                        $results[] = $resource;
                    }
                }
                return $results;
            }
        }
        return [];
    }


    public array $filamentPageRegistry = [];

    public function registerFilamentPage($page, $location = '', $panelId = 'admin'): array
    {
        return $this->filamentPageRegistry[$panelId][] = [
            'page' => $page,
            'location' => $location,
        ];
    }

    public function getFilamentPages($location = false, $panelId = 'admin'): array
    {
        if (isset($this->filamentPageRegistry[$panelId]) and !empty($this->filamentPageRegistry[$panelId])) {
            $results = [];
            if ($location) {
                foreach ($this->filamentPageRegistry[$panelId] as $page) {
                    if ($page['location'] == $location) {
                        $results[] = $page['page'];
                    }
                }
                return $results;
            } else {
                foreach ($this->filamentPageRegistry[$panelId] as $page) {
                    if ($page['location'] === '') {
                        $results[] = $page['page'];
                    }
                }
                return $results;
            }
        }
        return [];
    }


    public array $filamentWidgetRegistry = [];

    public function registerFilamentWidget($widget, $location = '', $panelId = 'admin'): array
    {
        return $this->filamentWidgetRegistry[$panelId][] = [
            'widget' => $widget,
            'location' => $location,
        ];
    }

    public function getFilamentWidgets($location = false, $panelId = 'admin'): array
    {
        if (isset($this->filamentWidgetRegistry[$panelId]) and !empty($this->filamentWidgetRegistry[$panelId])) {
            $results = [];
            if ($location) {
                foreach ($this->filamentWidgetRegistry[$panelId] as $widget) {
                    if ($widget['location'] == $location) {
                        $results[] = $widget['widget'];
                    }
                }
                return $results;
            } else {
                foreach ($this->filamentWidgetRegistry[$panelId] as $widget) {
                    if ($widget['location'] === '') {
                        $results[] = $widget['widget'];
                    }
                }
                return $results;
            }
        }
        return [];
    }


    public array $filamentPluginRegistry = [];

    public function registerFilamentPlugin($plugin, $location = '', $panelId = 'admin'): array
    {
        return $this->filamentPluginRegistry[$panelId][] = [
            'plugin' => $plugin,
            'location' => $location,
        ];
    }

    public function getFilamentPlugins($location = false, $panelId = 'admin'): array
    {
        if (isset($this->filamentPluginRegistry[$panelId]) and !empty($this->filamentPluginRegistry[$panelId])) {
            $results = [];
            if ($location) {
                foreach ($this->filamentPluginRegistry[$panelId] as $plugin) {
                    if ($plugin['location'] == $location) {
                        $results[] = $plugin['plugin'];
                    }
                }
                return $results;
            } else {
                foreach ($this->filamentPluginRegistry[$panelId] as $plugin) {
                    if ($plugin['location'] === '') {
                        $results[] = $plugin['plugin'];
                    }
                }
                return $results;
            }
        }
        return [];
    }

}
