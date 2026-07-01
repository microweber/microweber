<?php

namespace MicroweberPackages\FilamentRegistry\Tests;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\FilamentRegistry\FilamentRegistryManager;

class FilamentRegistryManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Ensure a clean state for each test
        FilamentRegistry::flush();
    }

    // ── Singleton / Container ─────────────────────────────────────

    public function test_manager_is_resolved_from_container(): void
    {
        $manager = app(FilamentRegistryManager::class);
        $this->assertInstanceOf(FilamentRegistryManager::class, $manager);
    }

    public function test_manager_is_a_singleton(): void
    {
        $first = app(FilamentRegistryManager::class);
        $second = app(FilamentRegistryManager::class);
        $this->assertSame($first, $second);
    }

    public function test_facade_resolves_to_manager(): void
    {
        $root = FilamentRegistry::getFacadeRoot();
        $this->assertInstanceOf(FilamentRegistryManager::class, $root);
    }

    public function test_facade_and_container_return_same_instance(): void
    {
        $fromContainer = app(FilamentRegistryManager::class);
        $fromFacade = FilamentRegistry::getFacadeRoot();
        $this->assertSame($fromContainer, $fromFacade);
    }

    // ── Resources ───────────────────────────────────────────────

    public function test_register_and_get_resource(): void
    {
        FilamentRegistry::registerResource('App\\Resource\\UserResource', 'MyPanel', 'admin');
        $resources = FilamentRegistry::getResources('MyPanel', 'admin');
        $this->assertContains('App\\Resource\\UserResource', $resources);
    }

    public function test_get_resources_returns_empty_when_none_registered(): void
    {
        $this->assertSame([], FilamentRegistry::getResources('SomeScope', 'admin'));
    }

    public function test_register_multiple_resources_same_scope(): void
    {
        FilamentRegistry::registerResource('Resource1', 'PanelA', 'admin');
        FilamentRegistry::registerResource('Resource2', 'PanelA', 'admin');
        FilamentRegistry::registerResource('Resource3', 'PanelB', 'admin');

        $results = FilamentRegistry::getResources('PanelA', 'admin');
        $this->assertCount(2, $results);
        $this->assertContains('Resource1', $results);
        $this->assertContains('Resource2', $results);

        $resultsB = FilamentRegistry::getResources('PanelB', 'admin');
        $this->assertCount(1, $resultsB);
    }

    public function test_resources_are_scoped_by_panel_id(): void
    {
        FilamentRegistry::registerResource('Resource1', 'Scope', 'panel-a');
        FilamentRegistry::registerResource('Resource2', 'Scope', 'panel-b');

        $this->assertCount(1, FilamentRegistry::getResources('Scope', 'panel-a'));
        $this->assertCount(1, FilamentRegistry::getResources('Scope', 'panel-b'));
        $this->assertContains('Resource1', FilamentRegistry::getResources('Scope', 'panel-a'));
        $this->assertContains('Resource2', FilamentRegistry::getResources('Scope', 'panel-b'));
    }

    // ── Pages ───────────────────────────────────────────────────

    public function test_register_and_get_page(): void
    {
        FilamentRegistry::registerPage('App\\Pages\\SettingsPage', 'MyPanel', 'admin');
        $pages = FilamentRegistry::getPages('MyPanel', 'admin');
        $this->assertContains('App\\Pages\\SettingsPage', $pages);
    }

    public function test_get_pages_returns_empty_when_none_registered(): void
    {
        $this->assertSame([], FilamentRegistry::getPages('SomeScope', 'admin'));
    }

    public function test_register_multiple_pages(): void
    {
        FilamentRegistry::registerPage('Page1', 'Scope', 'admin');
        FilamentRegistry::registerPage('Page2', 'Scope', 'admin');

        $pages = FilamentRegistry::getPages('Scope', 'admin');
        $this->assertCount(2, $pages);
    }

    public function test_pages_are_scoped_by_panel_id(): void
    {
        FilamentRegistry::registerPage('Page1', 'Scope', 'panel-a');
        FilamentRegistry::registerPage('Page2', 'Scope', 'panel-b');

        $this->assertCount(1, FilamentRegistry::getPages('Scope', 'panel-a'));
        $this->assertContains('Page1', FilamentRegistry::getPages('Scope', 'panel-a'));
    }

    // ── Widgets ──────────────────────────────────────────────────

    public function test_register_and_get_widget(): void
    {
        FilamentRegistry::registerWidget('App\\Widgets\\StatsWidget', 'MyPanel', 'admin');
        $widgets = FilamentRegistry::getWidgets('MyPanel', 'admin');
        $this->assertContains('App\\Widgets\\StatsWidget', $widgets);
    }

    public function test_get_widgets_returns_empty_when_none_registered(): void
    {
        $this->assertSame([], FilamentRegistry::getWidgets('SomeScope', 'admin'));
    }

    public function test_register_multiple_widgets(): void
    {
        FilamentRegistry::registerWidget('Widget1', 'Scope', 'admin');
        FilamentRegistry::registerWidget('Widget2', 'Scope', 'admin');
        FilamentRegistry::registerWidget('Widget3', 'OtherScope', 'admin');

        $widgets = FilamentRegistry::getWidgets('Scope', 'admin');
        $this->assertCount(2, $widgets);
    }

    // ── Plugins ──────────────────────────────────────────────────

    public function test_register_and_get_plugin(): void
    {
        FilamentRegistry::registerPlugin('App\\Plugins\\MyPlugin', 'MyPanel', 'admin');
        $plugins = FilamentRegistry::getPlugins('MyPanel', 'admin');
        $this->assertContains('App\\Plugins\\MyPlugin', $plugins);
    }

    public function test_get_plugins_returns_empty_when_none_registered(): void
    {
        $this->assertSame([], FilamentRegistry::getPlugins('SomeScope', 'admin'));
    }

    public function test_register_multiple_plugins(): void
    {
        FilamentRegistry::registerPlugin('Plugin1', 'Scope', 'admin');
        FilamentRegistry::registerPlugin('Plugin2', 'Scope', 'admin');

        $plugins = FilamentRegistry::getPlugins('Scope', 'admin');
        $this->assertCount(2, $plugins);
    }

    // ── Clusters ─────────────────────────────────────────────────

    public function test_register_and_get_cluster(): void
    {
        FilamentRegistry::registerCluster('App\\Clusters\\MyCluster', 'MyPanel', 'admin');
        $clusters = FilamentRegistry::getClusters('MyPanel', 'admin');
        $this->assertContains('App\\Clusters\\MyCluster', $clusters);
    }

    public function test_get_clusters_returns_empty_when_none_registered(): void
    {
        $this->assertSame([], FilamentRegistry::getClusters('SomeScope', 'admin'));
    }

    public function test_register_multiple_clusters(): void
    {
        FilamentRegistry::registerCluster('Cluster1', 'Scope', 'admin');
        FilamentRegistry::registerCluster('Cluster2', 'Scope', 'admin');

        $clusters = FilamentRegistry::getClusters('Scope', 'admin');
        $this->assertCount(2, $clusters);
    }

    // ── Default Scope ────────────────────────────────────────────

    public function test_default_scope_is_admin(): void
    {
        $manager = new FilamentRegistryManager();
        $this->assertEquals('admin', $manager->getDefaultScope());
    }

    public function test_set_default_scope(): void
    {
        $manager = new FilamentRegistryManager();
        $manager->setDefaultScope('CustomPanel');
        $this->assertEquals('CustomPanel', $manager->getDefaultScope());
    }

    public function test_register_uses_default_scope_when_null(): void
    {
        $manager = new FilamentRegistryManager();
        $manager->setDefaultScope('MyApp\\PanelProvider');

        $manager->registerResource('Res1');
        $resources = $manager->getResources('MyApp\\PanelProvider');
        $this->assertContains('Res1', $resources);
    }

    public function test_default_panel_id(): void
    {
        $manager = new FilamentRegistryManager();
        $this->assertEquals('admin', $manager->getDefaultPanelId());

        $manager->setDefaultPanelId('frontend');
        $this->assertEquals('frontend', $manager->getDefaultPanelId());
    }

    // ── Flush ────────────────────────────────────────────────────

    public function test_flush_clears_all_registries(): void
    {
        FilamentRegistry::registerResource('Res', 'S');
        FilamentRegistry::registerPage('Page', 'S');
        FilamentRegistry::registerWidget('Widget', 'S');
        FilamentRegistry::registerPlugin('Plugin', 'S');
        FilamentRegistry::registerCluster('Cluster', 'S');

        FilamentRegistry::flush();

        $this->assertSame([], FilamentRegistry::getResources('S'));
        $this->assertSame([], FilamentRegistry::getPages('S'));
        $this->assertSame([], FilamentRegistry::getWidgets('S'));
        $this->assertSame([], FilamentRegistry::getPlugins('S'));
        $this->assertSame([], FilamentRegistry::getClusters('S'));
    }

    // ── All ──────────────────────────────────────────────────────

    public function test_all_returns_combined_registry(): void
    {
        FilamentRegistry::registerResource('Res1', 'Scope');
        FilamentRegistry::registerPage('Page1', 'Scope');
        FilamentRegistry::registerWidget('Widget1', 'Scope');
        FilamentRegistry::registerPlugin('Plugin1', 'Scope');
        FilamentRegistry::registerCluster('Cluster1', 'Scope');

        $all = FilamentRegistry::all('Scope');

        $this->assertArrayHasKey('resources', $all);
        $this->assertArrayHasKey('pages', $all);
        $this->assertArrayHasKey('widgets', $all);
        $this->assertArrayHasKey('plugins', $all);
        $this->assertArrayHasKey('clusters', $all);
        $this->assertContains('Res1', $all['resources']);
        $this->assertContains('Page1', $all['pages']);
        $this->assertContains('Widget1', $all['widgets']);
        $this->assertContains('Plugin1', $all['plugins']);
        $this->assertContains('Cluster1', $all['clusters']);
    }

    // ── Scope Isolation ──────────────────────────────────────────

    public function test_different_scopes_are_isolated(): void
    {
        FilamentRegistry::registerResource('ResA', 'ScopeA');
        FilamentRegistry::registerResource('ResB', 'ScopeB');

        $this->assertContains('ResA', FilamentRegistry::getResources('ScopeA'));
        $this->assertNotContains('ResA', FilamentRegistry::getResources('ScopeB'));
        $this->assertContains('ResB', FilamentRegistry::getResources('ScopeB'));
        $this->assertNotContains('ResB', FilamentRegistry::getResources('ScopeA'));
    }

    public function test_different_panel_ids_are_isolated(): void
    {
        FilamentRegistry::registerPage('PageAdmin', 'Scope', 'admin');
        FilamentRegistry::registerPage('PageFrontend', 'Scope', 'frontend');

        $this->assertContains('PageAdmin', FilamentRegistry::getPages('Scope', 'admin'));
        $this->assertNotContains('PageFrontend', FilamentRegistry::getPages('Scope', 'admin'));
        $this->assertContains('PageFrontend', FilamentRegistry::getPages('Scope', 'frontend'));
    }

    // ── Direct Manager Instantiation ─────────────────────────────

    public function test_manager_works_standalone_without_container(): void
    {
        $manager = new FilamentRegistryManager();

        $manager->registerResource('StandaloneResource', 'TestScope');
        $manager->registerPage('StandalonePage', 'TestScope');
        $manager->registerWidget('StandaloneWidget', 'TestScope');
        $manager->registerPlugin('StandalonePlugin', 'TestScope');
        $manager->registerCluster('StandaloneCluster', 'TestScope');

        $this->assertContains('StandaloneResource', $manager->getResources('TestScope'));
        $this->assertContains('StandalonePage', $manager->getPages('TestScope'));
        $this->assertContains('StandaloneWidget', $manager->getWidgets('TestScope'));
        $this->assertContains('StandalonePlugin', $manager->getPlugins('TestScope'));
        $this->assertContains('StandaloneCluster', $manager->getClusters('TestScope'));
    }

    public function test_register_returns_registered_entry(): void
    {
        $entry = FilamentRegistry::registerResource('MyRes', 'Scope');
        $this->assertArrayHasKey('resource', $entry);
        $this->assertArrayHasKey('scope', $entry);
        $this->assertEquals('MyRes', $entry['resource']);
        $this->assertEquals('Scope', $entry['scope']);
    }

    public function test_register_page_returns_registered_entry(): void
    {
        $entry = FilamentRegistry::registerPage('MyPage', 'Scope');
        $this->assertArrayHasKey('page', $entry);
        $this->assertEquals('MyPage', $entry['page']);
    }

    public function test_register_widget_returns_registered_entry(): void
    {
        $entry = FilamentRegistry::registerWidget('MyWidget', 'Scope');
        $this->assertArrayHasKey('widget', $entry);
        $this->assertEquals('MyWidget', $entry['widget']);
    }

    public function test_register_plugin_returns_registered_entry(): void
    {
        $entry = FilamentRegistry::registerPlugin('MyPlugin', 'Scope');
        $this->assertArrayHasKey('plugin', $entry);
        $this->assertEquals('MyPlugin', $entry['plugin']);
    }

    public function test_register_cluster_returns_registered_entry(): void
    {
        $entry = FilamentRegistry::registerCluster('MyCluster', 'Scope');
        $this->assertArrayHasKey('cluster', $entry);
        $this->assertEquals('MyCluster', $entry['cluster']);
    }

    // ── Fluent API ───────────────────────────────────────────────

    public function test_set_default_scope_is_fluent(): void
    {
        $manager = new FilamentRegistryManager();
        $result = $manager->setDefaultScope('Test');
        $this->assertSame($manager, $result);
    }

    public function test_flush_is_fluent(): void
    {
        $manager = new FilamentRegistryManager();
        $result = $manager->flush();
        $this->assertSame($manager, $result);
    }

    // ── Multiple Panels ──────────────────────────────────────────

    public function test_multiple_panels_with_resources_pages_widgets(): void
    {
        FilamentRegistry::registerResource('AdminResource', 'AdminProvider', 'admin');
        FilamentRegistry::registerResource('FrontendResource', 'FrontendProvider', 'frontend');
        FilamentRegistry::registerPage('AdminPage', 'AdminProvider', 'admin');
        FilamentRegistry::registerPage('FrontendPage', 'FrontendProvider', 'frontend');
        FilamentRegistry::registerWidget('AdminWidget', 'AdminProvider', 'admin');
        FilamentRegistry::registerWidget('FrontendWidget', 'FrontendProvider', 'frontend');

        $adminAll = FilamentRegistry::all('AdminProvider', 'admin');
        $frontendAll = FilamentRegistry::all('FrontendProvider', 'frontend');

        $this->assertContains('AdminResource', $adminAll['resources']);
        $this->assertContains('AdminPage', $adminAll['pages']);
        $this->assertContains('AdminWidget', $adminAll['widgets']);

        $this->assertContains('FrontendResource', $frontendAll['resources']);
        $this->assertContains('FrontendPage', $frontendAll['pages']);
        $this->assertContains('FrontendWidget', $frontendAll['widgets']);

        // Cross-panel isolation
        $this->assertNotContains('FrontendResource', $adminAll['resources']);
        $this->assertNotContains('AdminResource', $frontendAll['resources']);
    }
}