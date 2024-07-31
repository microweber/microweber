<?php

namespace MicroweberPackages\Filament;


use MicroweberPackages\Admin\Providers\Filament\FilamentAdminPanelProvider;

class FilamentRegistryManager
{

    public array $filamentResourceRegistry = [];

    public function registerResource(string $resource, string $scope = FilamentAdminPanelProvider::class, string $panelId = 'admin'): array
    {

        return $this->filamentResourceRegistry[$panelId][] = [
            'resource' => $resource,
            'scope' => $scope,
        ];
    }

    public function getResources(string $scope = FilamentAdminPanelProvider::class, string $panelId = 'admin'): array
    {
        if ($this->filamentResourceRegistry[$panelId] and !empty($this->filamentResourceRegistry[$panelId])) {
            $results = [];
            if ($scope) {
                foreach ($this->filamentResourceRegistry[$panelId] as $resource) {
                    if ($resource['scope'] == $scope) {
                        $results[] = $resource[ 'resource'];
                    }
                }

                return $results;
            }
        }
        return [];
    }


    public array $filamentPageRegistry = [];

    public function registerPage(string $page, string $scope = FilamentAdminPanelProvider::class, string $panelId = 'admin'): array
    {
        return $this->filamentPageRegistry[$panelId][] = [
            'page' => $page,
            'scope' => $scope,
        ];
    }

    public function getPages(string $scope = FilamentAdminPanelProvider::class, string $panelId = 'admin'): array
    {
        if (isset($this->filamentPageRegistry[$panelId]) and !empty($this->filamentPageRegistry[$panelId])) {
            $results = [];
            if ($scope) {
                foreach ($this->filamentPageRegistry[$panelId] as $page) {
                    if ($page['scope'] == $scope) {
                        $results[] = $page['page'];
                    }
                }
                return $results;
            }
        }
        return [];
    }


    public array $filamentWidgetRegistry = [];

    public function registerWidget(string $widget, string $scope = FilamentAdminPanelProvider::class, string $panelId = 'admin'): array
    {
        return $this->filamentWidgetRegistry[$panelId][] = [
            'widget' => $widget,
            'scope' => $scope,
        ];
    }

    public function getWidgets(string $scope = FilamentAdminPanelProvider::class, string $panelId = 'admin'): array
    {
        if (isset($this->filamentWidgetRegistry[$panelId]) and !empty($this->filamentWidgetRegistry[$panelId])) {
            $results = [];
            if ($scope) {
                foreach ($this->filamentWidgetRegistry[$panelId] as $widget) {
                    if ($widget['scope'] == $scope) {
                        $results[] = $widget['widget'];
                    }
                }
                return $results;
            }
        }
        return [];
    }


    public array $filamentPluginRegistry = [];

    public function registerPlugin(string $plugin, string $scope = FilamentAdminPanelProvider::class, string $panelId = 'admin'): array
    {
        return $this->filamentPluginRegistry[$panelId][] = [
            'plugin' => $plugin,
            'scope' => $scope,
        ];
    }

    public function getPlugins(string $scope = FilamentAdminPanelProvider::class, $panelId = 'admin'): array
    {
        if (isset($this->filamentPluginRegistry[$panelId]) and !empty($this->filamentPluginRegistry[$panelId])) {
            $results = [];
            if ($scope) {
                foreach ($this->filamentPluginRegistry[$panelId] as $plugin) {
                    if ($plugin['scope'] == $scope) {
                        $results[] = $plugin['plugin'];
                    }
                }
                return $results;
            }
        }
        return [];
    }

}
