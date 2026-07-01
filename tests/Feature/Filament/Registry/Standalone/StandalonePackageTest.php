<?php

namespace Tests\Feature\Filament\Registry\Standalone;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\FilamentRegistry\FilamentRegistryManager;
use MicroweberPackages\FilamentRegistry\FilamentRegistryServiceProvider;
use Tests\TestCase;

/**
 * Tests that the filament-registry package works as a standalone reusable
 * package within a Laravel application (simulating standalone app usage).
 */
class StandalonePackageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Start each test with a clean registry
        $manager = app(FilamentRegistryManager::class);
        $manager->flush();
    }

    // ── Service Provider ─────────────────────────────────────────

    public function test_service_provider_is_loaded(): void
    {
        $providers = $this->app->getLoadedProviders();
        $this->assertArrayHasKey(
            FilamentRegistryServiceProvider::class,
            $providers
        );
    }

    public function test_manager_is_a_singleton(): void
    {
        $a = app(FilamentRegistryManager::class);
        $b = app(FilamentRegistryManager::class);
        $this->assertSame($a, $b);
    }

    public function test_facade_resolves_to_manager(): void
    {
        $root = FilamentRegistry::getFacadeRoot();
        $this->assertInstanceOf(FilamentRegistryManager::class, $root);
    }

    // ── Standalone Panel Registration ────────────────────────────

    public function test_register_resources_for_custom_panel(): void
    {
        FilamentRegistry::registerResource('App\\Resources\\UserResource', 'App\\Panels\\AdminPanel', 'admin');
        $resources = FilamentRegistry::getResources('App\\Panels\\AdminPanel', 'admin');
        $this->assertCount(1, $resources);
        $this->assertContains('App\\Resources\\UserResource', $resources);
    }

    public function test_register_pages_for_custom_panel(): void
    {
        FilamentRegistry::registerPage('App\\Pages\\SettingsPage', 'App\\Panels\\AdminPanel', 'admin');
        $pages = FilamentRegistry::getPages('App\\Panels\\AdminPanel', 'admin');
        $this->assertCount(1, $pages);
        $this->assertContains('App\\Pages\\SettingsPage', $pages);
    }

    public function test_register_widgets_for_custom_panel(): void
    {
        FilamentRegistry::registerWidget('App\\Widgets\\StatsWidget', 'App\\Panels\\AdminPanel', 'admin');
        $widgets = FilamentRegistry::getWidgets('App\\Panels\\AdminPanel', 'admin');
        $this->assertCount(1, $widgets);
    }

    public function test_register_plugins_for_custom_panel(): void
    {
        FilamentRegistry::registerPlugin('App\\Plugins\\MyPlugin', 'App\\Panels\\AdminPanel', 'admin');
        $plugins = FilamentRegistry::getPlugins('App\\Panels\\AdminPanel', 'admin');
        $this->assertCount(1, $plugins);
    }

    public function test_register_clusters_for_custom_panel(): void
    {
        FilamentRegistry::registerCluster('App\\Clusters\\MyCluster', 'App\\Panels\\AdminPanel', 'admin');
        $clusters = FilamentRegistry::getClusters('App\\Panels\\AdminPanel', 'admin');
        $this->assertCount(1, $clusters);
    }

    // ── Multi-Panel App ──────────────────────────────────────────

    public function test_multi_panel_isolation(): void
    {
        FilamentRegistry::registerResource('AdminRes', 'AdminPanel', 'admin');
        FilamentRegistry::registerResource('CustomerRes', 'CustomerPanel', 'customer');

        $admin = FilamentRegistry::getResources('AdminPanel', 'admin');
        $customer = FilamentRegistry::getResources('CustomerPanel', 'customer');

        $this->assertContains('AdminRes', $admin);
        $this->assertNotContains('CustomerRes', $admin);
        $this->assertContains('CustomerRes', $customer);
        $this->assertNotContains('AdminRes', $customer);
    }

    // ── Default Scope ────────────────────────────────────────────

    public function test_custom_default_scope(): void
    {
        $manager = new FilamentRegistryManager();
        $manager->setDefaultScope('App\\MyPanel');

        $manager->registerResource('Res1');
        $resources = $manager->getResources('App\\MyPanel');

        $this->assertContains('Res1', $resources);
        $this->assertEmpty($manager->getResources('other'));
    }

    // ── All Method ───────────────────────────────────────────────

    public function test_all_returns_all_types(): void
    {
        FilamentRegistry::registerResource('R', 'S', 'p');
        FilamentRegistry::registerPage('P', 'S', 'p');
        FilamentRegistry::registerWidget('W', 'S', 'p');
        FilamentRegistry::registerPlugin('Pl', 'S', 'p');
        FilamentRegistry::registerCluster('C', 'S', 'p');

        $all = FilamentRegistry::all('S', 'p');

        $this->assertCount(1, $all['resources']);
        $this->assertCount(1, $all['pages']);
        $this->assertCount(1, $all['widgets']);
        $this->assertCount(1, $all['plugins']);
        $this->assertCount(1, $all['clusters']);
    }

    // ── Flush ────────────────────────────────────────────────────

    public function test_flush_clears_everything(): void
    {
        FilamentRegistry::registerResource('R', 'S', 'p');
        FilamentRegistry::registerPage('P', 'S', 'p');

        FilamentRegistry::flush();

        $this->assertEmpty(FilamentRegistry::getResources('S', 'p'));
        $this->assertEmpty(FilamentRegistry::getPages('S', 'p'));
    }

    // ── Manager Without Container ────────────────────────────────

    public function test_manager_works_without_container(): void
    {
        $manager = new FilamentRegistryManager();
        $manager->setDefaultScope('StandaloneScope');

        $manager->registerResource('Res1', 'StandaloneScope', 'panel');
        $manager->registerPage('Page1', 'StandaloneScope', 'panel');
        $manager->registerWidget('Widget1', 'StandaloneScope', 'panel');
        $manager->registerPlugin('Plugin1', 'StandaloneScope', 'panel');
        $manager->registerCluster('Cluster1', 'StandaloneScope', 'panel');

        $all = $manager->all('StandaloneScope', 'panel');

        $this->assertContains('Res1', $all['resources']);
        $this->assertContains('Page1', $all['pages']);
        $this->assertContains('Widget1', $all['widgets']);
        $this->assertContains('Plugin1', $all['plugins']);
        $this->assertContains('Cluster1', $all['clusters']);
    }

    // ── Registration Return Values ──────────────────────────────

    public function test_register_returns_entry_data(): void
    {
        $entry = FilamentRegistry::registerResource('Res', 'Scope', 'panel');
        $this->assertEquals('Res', $entry['resource']);
        $this->assertEquals('Scope', $entry['scope']);
    }

    // ── Fluent Interface ─────────────────────────────────────────

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
}