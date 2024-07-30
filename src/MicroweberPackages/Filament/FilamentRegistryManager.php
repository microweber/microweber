<?php

namespace MicroweberPackages\Filament;


class FilamentRegistryManager
{


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
