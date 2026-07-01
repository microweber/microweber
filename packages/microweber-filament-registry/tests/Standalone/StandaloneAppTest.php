<?php

namespace MicroweberPackages\FilamentRegistry\Tests\Standalone;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\FilamentRegistry\FilamentRegistryManager;
use MicroweberPackages\FilamentRegistry\Tests\Standalone\Fixtures\FakeCluster;
use MicroweberPackages\FilamentRegistry\Tests\Standalone\Fixtures\FakePage;
use MicroweberPackages\FilamentRegistry\Tests\Standalone\Fixtures\FakePanelProvider;
use MicroweberPackages\FilamentRegistry\Tests\Standalone\Fixtures\FakePlugin;
use MicroweberPackages\FilamentRegistry\Tests\Standalone\Fixtures\FakeResource;
use MicroweberPackages\FilamentRegistry\Tests\Standalone\Fixtures\FakeWidget;
use MicroweberPackages\FilamentRegistry\Tests\TestCase;

/**
 * Simulates a standalone Laravel app that uses the filament-registry package
 * independently of the Microweber CMS.
 *
 * This test verifies the package can be used in any Laravel/Filament app
 * by testing all registry operations with a fake panel provider that does
 * NOT depend on Microweber's FilamentAdminPanelProvider.
 */
class StandaloneAppTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        FilamentRegistry::flush();
    }

    // ── Standalone Panel Provider Usage ──────────────────────────

    public function test_register_resources_for_standalone_panel(): void
    {
        FilamentRegistry::registerResource(
            FakeResource::class,
            FakePanelProvider::class,
            'standalone'
        );

        $resources = FilamentRegistry::getResources(
            FakePanelProvider::class,
            'standalone'
        );

        $this->assertCount(1, $resources);
        $this->assertContains(FakeResource::class, $resources);
    }

    public function test_register_pages_for_standalone_panel(): void
    {
        FilamentRegistry::registerPage(
            FakePage::class,
            FakePanelProvider::class,
            'standalone'
        );

        $pages = FilamentRegistry::getPages(
            FakePanelProvider::class,
            'standalone'
        );

        $this->assertCount(1, $pages);
        $this->assertContains(FakePage::class, $pages);
    }

    public function test_register_widgets_for_standalone_panel(): void
    {
        FilamentRegistry::registerWidget(
            FakeWidget::class,
            FakePanelProvider::class,
            'standalone'
        );

        $widgets = FilamentRegistry::getWidgets(
            FakePanelProvider::class,
            'standalone'
        );

        $this->assertCount(1, $widgets);
        $this->assertContains(FakeWidget::class, $widgets);
    }

    public function test_register_plugins_for_standalone_panel(): void
    {
        FilamentRegistry::registerPlugin(
            FakePlugin::class,
            FakePanelProvider::class,
            'standalone'
        );

        $plugins = FilamentRegistry::getPlugins(
            FakePanelProvider::class,
            'standalone'
        );

        $this->assertCount(1, $plugins);
        $this->assertContains(FakePlugin::class, $plugins);
    }

    public function test_register_clusters_for_standalone_panel(): void
    {
        FilamentRegistry::registerCluster(
            FakeCluster::class,
            FakePanelProvider::class,
            'standalone'
        );

        $clusters = FilamentRegistry::getClusters(
            FakePanelProvider::class,
            'standalone'
        );

        $this->assertCount(1, $clusters);
        $this->assertContains(FakeCluster::class, $clusters);
    }

    // ── Full Standalone App Workflow ─────────────────────────────

    public function test_full_standalone_panel_setup(): void
    {
        // Simulate what a standalone Laravel app would do in its
        // service provider's register() method:
        $scope = FakePanelProvider::class;
        $panelId = 'standalone';

        // 1. Set default scope so callers don't have to repeat it
        FilamentRegistry::setDefaultScope($scope);
        FilamentRegistry::setDefaultPanelId($panelId);

        // 2. Register components (using defaults)
        FilamentRegistry::registerResource(FakeResource::class, null, $panelId);
        FilamentRegistry::registerPage(FakePage::class, null, $panelId);
        FilamentRegistry::registerWidget(FakeWidget::class, null, $panelId);
        FilamentRegistry::registerPlugin(FakePlugin::class, null, $panelId);
        FilamentRegistry::registerCluster(FakeCluster::class, null, $panelId);

        // 3. Retrieve via all()
        $all = FilamentRegistry::all($scope, $panelId);

        $this->assertContains(FakeResource::class, $all['resources']);
        $this->assertContains(FakePage::class, $all['pages']);
        $this->assertContains(FakeWidget::class, $all['widgets']);
        $this->assertContains(FakePlugin::class, $all['plugins']);
        $this->assertContains(FakeCluster::class, $all['clusters']);
    }

    // ── Multi-Panel Standalone App ──────────────────────────────

    public function test_multiple_panels_in_standalone_app(): void
    {
        $adminScope = 'App\\Panels\\AdminPanel';
        $customerScope = 'App\\Panels\\CustomerPanel';

        // Admin panel registrations
        FilamentRegistry::registerResource(FakeResource::class, $adminScope, 'admin');
        FilamentRegistry::registerPage(FakePage::class, $adminScope, 'admin');

        // Customer panel registrations
        FilamentRegistry::registerResource(FakeResource::class, $customerScope, 'customer');
        FilamentRegistry::registerWidget(FakeWidget::class, $customerScope, 'customer');

        // Verify isolation
        $adminAll = FilamentRegistry::all($adminScope, 'admin');
        $customerAll = FilamentRegistry::all($customerScope, 'customer');

        $this->assertCount(1, $adminAll['resources']);
        $this->assertCount(1, $adminAll['pages']);
        $this->assertEmpty($adminAll['widgets']);

        $this->assertCount(1, $customerAll['resources']);
        $this->assertEmpty($customerAll['pages']);
        $this->assertCount(1, $customerAll['widgets']);
    }

    // ── Direct Manager Usage (no facade) ────────────────────────

    public function test_manager_works_without_laravel_container(): void
    {
        // This simulates using the manager in a plain PHP context
        $manager = new FilamentRegistryManager();

        $manager->setDefaultScope('StandaloneScope');

        $manager->registerResource(FakeResource::class, 'StandaloneScope', 'my-panel');
        $manager->registerPage(FakePage::class, 'StandaloneScope', 'my-panel');
        $manager->registerWidget(FakeWidget::class, 'StandaloneScope', 'my-panel');

        $resources = $manager->getResources('StandaloneScope', 'my-panel');
        $pages = $manager->getPages('StandaloneScope', 'my-panel');
        $widgets = $manager->getWidgets('StandaloneScope', 'my-panel');

        $this->assertContains(FakeResource::class, $resources);
        $this->assertContains(FakePage::class, $pages);
        $this->assertContains(FakeWidget::class, $widgets);
    }

    // ── Default Scope Usage ─────────────────────────────────────

    public function test_default_scope_simplifies_registration(): void
    {
        $manager = new FilamentRegistryManager();
        $manager->setDefaultScope('MyApp\\AdminPanel');

        // Register without explicit scope
        $manager->registerResource(FakeResource::class);
        $manager->registerPage(FakePage::class);

        // Retrieve with explicit scope
        $resources = $manager->getResources('MyApp\\AdminPanel');
        $pages = $manager->getPages('MyApp\\AdminPanel');

        $this->assertContains(FakeResource::class, $resources);
        $this->assertContains(FakePage::class, $pages);
    }

    // ── Service Provider Auto-Discovery ─────────────────────────

    public function test_package_service_provider_is_auto_discovered(): void
    {
        $providers = $this->app->getLoadedProviders();
        $this->assertArrayHasKey(
            \MicroweberPackages\FilamentRegistry\FilamentRegistryServiceProvider::class,
            $providers
        );
    }

    // ── Flush & Re-register ─────────────────────────────────────

    public function test_flush_and_re_register_in_standalone_context(): void
    {
        FilamentRegistry::registerResource(FakeResource::class, 'Scope', 'panel');
        $this->assertCount(1, FilamentRegistry::getResources('Scope', 'panel'));

        FilamentRegistry::flush();
        $this->assertEmpty(FilamentRegistry::getResources('Scope', 'panel'));

        // Re-register after flush
        FilamentRegistry::registerResource(FakeResource::class, 'Scope', 'panel');
        $this->assertCount(1, FilamentRegistry::getResources('Scope', 'panel'));
    }

    // ── Registration Return Values ──────────────────────────────

    public function test_register_methods_return_entry_arrays(): void
    {
        $resEntry = FilamentRegistry::registerResource(FakeResource::class, 'S', 'p');
        $this->assertEquals(FakeResource::class, $resEntry['resource']);
        $this->assertEquals('S', $resEntry['scope']);

        $pageEntry = FilamentRegistry::registerPage(FakePage::class, 'S', 'p');
        $this->assertEquals(FakePage::class, $pageEntry['page']);

        $widgetEntry = FilamentRegistry::registerWidget(FakeWidget::class, 'S', 'p');
        $this->assertEquals(FakeWidget::class, $widgetEntry['widget']);

        $pluginEntry = FilamentRegistry::registerPlugin(FakePlugin::class, 'S', 'p');
        $this->assertEquals(FakePlugin::class, $pluginEntry['plugin']);

        $clusterEntry = FilamentRegistry::registerCluster(FakeCluster::class, 'S', 'p');
        $this->assertEquals(FakeCluster::class, $clusterEntry['cluster']);
    }
}