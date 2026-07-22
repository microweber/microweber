<?php

namespace MicroweberPackages\FilamentRegistry;

/**
 * FilamentRegistryManager — a standalone registry for Filament panel components.
 *
 * Manages registration and retrieval of resources, pages, widgets, plugins
 * and clusters. Each component is scoped by a panel-provider class and a panel ID,
 * enabling multiple panels in the same application to maintain isolated registries.
 *
 * This class is designed to be used as a singleton via the FilamentRegistry facade
 * or resolved from the Laravel container.
 */
class FilamentRegistryManager
{
    /**
     * The default scope class used when none is provided.
     * Override via setDefaultScope() for standalone apps that don't use
     * the Microweber admin panel provider.
     */
    protected string $defaultScope = 'admin';

    /**
     * The default panel ID.
     */
    protected string $defaultPanelId = 'admin';

    // ── Registries ──────────────────────────────────────────────────

    public array $filamentResourceRegistry = [];
    public array $filamentPageRegistry = [];
    public array $filamentWidgetRegistry = [];
    public array $filamentPluginRegistry = [];
    public array $filamentClusterRegistry = [];

    /**
     * Global-search entries registered by modules (settings pages, deep links, etc.).
     * Each entry: ['title' => …, 'url' => …, 'keywords' => [...], 'group' => …, 'details' => [...]]
     */
    public array $globalSearchEntries = [];

    // ── Configuration ───────────────────────────────────────────────

    /**
     * Set the default scope (panel provider class) for registrations.
     */
    public function setDefaultScope(string $scope): static
    {
        $this->defaultScope = $scope;
        return $this;
    }

    /**
     * Get the current default scope.
     */
    public function getDefaultScope(): string
    {
        return $this->defaultScope;
    }

    /**
     * Set the default panel ID.
     */
    public function setDefaultPanelId(string $panelId): static
    {
        $this->defaultPanelId = $panelId;
        return $this;
    }

    /**
     * Get the current default panel ID.
     */
    public function getDefaultPanelId(): string
    {
        return $this->defaultPanelId;
    }

    // ── Resources ───────────────────────────────────────────────────

    public function registerResource(string $resource, ?string $scope = null, string $panelId = 'admin'): array
    {
        $scope = $scope ?? $this->defaultScope;

        return $this->filamentResourceRegistry[$panelId][] = [
            'resource' => $resource,
            'scope' => $scope,
        ];
    }

    public function getResources(?string $scope = null, string $panelId = 'admin'): array
    {
        $scope = $scope ?? $this->defaultScope;

        if (isset($this->filamentResourceRegistry[$panelId]) && !empty($this->filamentResourceRegistry[$panelId])) {
            $results = [];
            if ($scope) {
                foreach ($this->filamentResourceRegistry[$panelId] as $resource) {
                    if ($resource['scope'] == $scope) {
                        $results[] = $resource['resource'];
                    }
                }
                return $results;
            }
        }
        return [];
    }

    // ── Pages ───────────────────────────────────────────────────────

    public function registerPage(string $page, ?string $scope = null, string $panelId = 'admin'): array
    {
        $scope = $scope ?? $this->defaultScope;

        return $this->filamentPageRegistry[$panelId][] = [
            'page' => $page,
            'scope' => $scope,
        ];
    }

    public function getPages(?string $scope = null, string $panelId = 'admin'): array
    {
        $scope = $scope ?? $this->defaultScope;

        if (isset($this->filamentPageRegistry[$panelId]) && !empty($this->filamentPageRegistry[$panelId])) {
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

    // ── Widgets ──────────────────────────────────────────────────────

    public function registerWidget(string $widget, ?string $scope = null, string $panelId = 'admin'): array
    {
        $scope = $scope ?? $this->defaultScope;

        return $this->filamentWidgetRegistry[$panelId][] = [
            'widget' => $widget,
            'scope' => $scope,
        ];
    }

    public function getWidgets(?string $scope = null, string $panelId = 'admin'): array
    {
        $scope = $scope ?? $this->defaultScope;

        if (isset($this->filamentWidgetRegistry[$panelId]) && !empty($this->filamentWidgetRegistry[$panelId])) {
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

    // ── Plugins ──────────────────────────────────────────────────────

    public function registerPlugin(string $plugin, ?string $scope = null, string $panelId = 'admin'): array
    {
        $scope = $scope ?? $this->defaultScope;

        return $this->filamentPluginRegistry[$panelId][] = [
            'plugin' => $plugin,
            'scope' => $scope,
        ];
    }

    public function getPlugins(?string $scope = null, string $panelId = 'admin'): array
    {
        $scope = $scope ?? $this->defaultScope;

        if (isset($this->filamentPluginRegistry[$panelId]) && !empty($this->filamentPluginRegistry[$panelId])) {
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

    // ── Clusters ─────────────────────────────────────────────────────

    public function registerCluster(string $cluster, ?string $scope = null, string $panelId = 'admin'): array
    {
        $scope = $scope ?? $this->defaultScope;

        return $this->filamentClusterRegistry[$panelId][] = [
            'cluster' => $cluster,
            'scope' => $scope,
        ];
    }

    public function getClusters(?string $scope = null, string $panelId = 'admin'): array
    {
        $scope = $scope ?? $this->defaultScope;

        if (isset($this->filamentClusterRegistry[$panelId]) && !empty($this->filamentClusterRegistry[$panelId])) {
            $results = [];
            if ($scope) {
                foreach ($this->filamentClusterRegistry[$panelId] as $cluster) {
                    if ($cluster['scope'] == $scope) {
                        $results[] = $cluster['cluster'];
                    }
                }
                return $results;
            }
        }
        return [];
    }

    // ── Global Search ────────────────────────────────────────────────

    /**
     * Register a static entry (settings page, admin page, deep-link)
     * that should appear in Filament's global search when keywords match.
     *
     * @param string       $title    Human-readable title shown in search results
     * @param string       $url      Absolute or relative admin URL
     * @param array        $keywords Lowercase keyword phrases for matching
     * @param string       $group    Category heading in the search results dropdown
     * @param array        $details  Key-value detail pairs shown under the title
     * @param string|null  $icon     Optional Heroicon name
     */
    public function registerGlobalSearchEntry(
        string $title,
        string $url,
        array  $keywords = [],
        string $group = 'Settings',
        array  $details = [],
        ?string $icon = null,
    ): void {
        $this->globalSearchEntries[] = [
            'title'    => $title,
            'url'      => $url,
            'keywords' => array_map('mb_strtolower', $keywords),
            'group'    => $group,
            'details'  => $details,
            'icon'     => $icon,
        ];
    }

    /**
     * Retrieve all registered global-search entries.
     *
     * @return array<int, array{title: string, url: string, keywords: list<string>, group: string, details: array, icon: string|null}>
     */
    public function getGlobalSearchEntries(): array
    {
        return $this->globalSearchEntries;
    }

    // ── Utility ──────────────────────────────────────────────────────

    /**
     * Clear all registries. Useful for testing.
     */
    public function flush(): static
    {
        $this->filamentResourceRegistry = [];
        $this->filamentPageRegistry = [];
        $this->filamentWidgetRegistry = [];
        $this->filamentPluginRegistry = [];
        $this->filamentClusterRegistry = [];
        $this->globalSearchEntries = [];
        return $this;
    }

    /**
     * Get all registered items across all types for a given scope and panel.
     */
    public function all(?string $scope = null, string $panelId = 'admin'): array
    {
        return [
            'resources' => $this->getResources($scope, $panelId),
            'pages' => $this->getPages($scope, $panelId),
            'widgets' => $this->getWidgets($scope, $panelId),
            'plugins' => $this->getPlugins($scope, $panelId),
            'clusters' => $this->getClusters($scope, $panelId),
        ];
    }
}