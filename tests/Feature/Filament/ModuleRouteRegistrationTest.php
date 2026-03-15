<?php

namespace Tests\Feature\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Filament\FilamentRegistryManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * Tests that module routes are properly registered and accessible
 * in the test environment via the FilamentRegistry and panel system.
 */
#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class ModuleRouteRegistrationTest extends TestCase
{
    use LazilyRefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    // ── FilamentRegistryManager ─────────────────────────────────────

    #[Test]
    public function it_registry_manager_is_bound_in_container(): void
    {
        $manager = app(FilamentRegistryManager::class);

        $this->assertInstanceOf(FilamentRegistryManager::class, $manager);
    }

    #[Test]
    public function it_facade_resolves_consistently(): void
    {
        // The facade root should return the same instance
        $first = FilamentRegistry::getFacadeRoot();
        $second = FilamentRegistry::getFacadeRoot();

        $this->assertSame($first, $second);
    }

    #[Test]
    public function it_registry_registers_and_retrieves_resources(): void
    {
        $manager = new FilamentRegistryManager();

        $manager->registerResource('App\\Fake\\FakeResource', FilamentAdminPanelProvider::class, 'admin');

        $resources = $manager->getResources(FilamentAdminPanelProvider::class, 'admin');

        $this->assertContains('App\\Fake\\FakeResource', $resources);
    }

    #[Test]
    public function it_registry_scopes_resources_by_panel_id(): void
    {
        $manager = new FilamentRegistryManager();

        $manager->registerResource('App\\Fake\\AdminResource', FilamentAdminPanelProvider::class, 'admin');
        $manager->registerResource('App\\Fake\\BillingResource', FilamentAdminPanelProvider::class, 'admin-billing');

        $adminResources = $manager->getResources(FilamentAdminPanelProvider::class, 'admin');
        $billingResources = $manager->getResources(FilamentAdminPanelProvider::class, 'admin-billing');

        $this->assertContains('App\\Fake\\AdminResource', $adminResources);
        $this->assertNotContains('App\\Fake\\BillingResource', $adminResources);
        $this->assertContains('App\\Fake\\BillingResource', $billingResources);
        $this->assertNotContains('App\\Fake\\AdminResource', $billingResources);
    }

    #[Test]
    public function it_registry_scopes_resources_by_provider_class(): void
    {
        $manager = new FilamentRegistryManager();

        $manager->registerResource('App\\Fake\\Resource1', FilamentAdminPanelProvider::class, 'admin');
        $manager->registerResource('App\\Fake\\Resource2', 'OtherProvider', 'admin');

        $scoped = $manager->getResources(FilamentAdminPanelProvider::class, 'admin');

        $this->assertContains('App\\Fake\\Resource1', $scoped);
        $this->assertNotContains('App\\Fake\\Resource2', $scoped);
    }

    #[Test]
    public function it_registry_returns_empty_array_for_unknown_panel(): void
    {
        $manager = new FilamentRegistryManager();

        $this->assertSame([], $manager->getResources(FilamentAdminPanelProvider::class, 'nonexistent'));
    }

    #[Test]
    public function it_registry_registers_pages_widgets_plugins_clusters(): void
    {
        $manager = new FilamentRegistryManager();

        $manager->registerPage('FakePage', FilamentAdminPanelProvider::class, 'admin');
        $manager->registerWidget('FakeWidget', FilamentAdminPanelProvider::class, 'admin');
        $manager->registerPlugin('FakePlugin', FilamentAdminPanelProvider::class, 'admin');
        $manager->registerCluster('FakeCluster', FilamentAdminPanelProvider::class, 'admin');

        $this->assertContains('FakePage', $manager->getPages(FilamentAdminPanelProvider::class, 'admin'));
        $this->assertContains('FakeWidget', $manager->getWidgets(FilamentAdminPanelProvider::class, 'admin'));
        $this->assertContains('FakePlugin', $manager->getPlugins(FilamentAdminPanelProvider::class, 'admin'));
        $this->assertContains('FakeCluster', $manager->getClusters(FilamentAdminPanelProvider::class, 'admin'));
    }

    // ── Facade ──────────────────────────────────────────────────────

    #[Test]
    public function it_facade_resolves_to_registry_manager(): void
    {
        $this->assertInstanceOf(
            FilamentRegistryManager::class,
            FilamentRegistry::getFacadeRoot()
        );
    }

    // ── Module resources registered at boot ─────────────────────────

    #[Test]
    public function it_modules_register_resources_during_boot(): void
    {
        $resources = FilamentRegistry::getResources(FilamentAdminPanelProvider::class, 'admin');

        $this->assertNotEmpty($resources, 'No resources were registered in the admin panel during boot.');
    }

    #[Test]
    public function it_known_module_resources_are_registered(): void
    {
        $resources = FilamentRegistry::getResources(FilamentAdminPanelProvider::class, 'admin');

        // These modules are known to register resources via their service providers
        $expectedResources = [
            \Modules\Backup\Filament\Resources\BackupResource::class,
            \Modules\Content\Filament\Admin\ContentResource::class,
            \Modules\Category\Filament\Admin\Resources\CategoryResource::class,
            \Modules\Order\Filament\Admin\Resources\OrderResource::class,
            \Modules\Page\Filament\Resources\PageResource::class,
        ];

        foreach ($expectedResources as $expected) {
            $this->assertContains(
                $expected,
                $resources,
                "Expected resource {$expected} was not registered in the admin panel."
            );
        }
    }

    // ── Admin panel availability ────────────────────────────────────

    #[Test]
    public function it_admin_panel_is_available(): void
    {
        $panel = Filament::getPanel('admin');

        $this->assertNotNull($panel);
        $this->assertSame('admin', $panel->getId());
    }

    #[Test]
    public function it_current_panel_is_set_after_setup(): void
    {
        $current = Filament::getCurrentPanel();

        $this->assertNotNull($current);
        $this->assertSame('admin', $current->getId());
    }

    #[Test]
    public function it_panel_has_resources_from_registry(): void
    {
        $panel = Filament::getPanel('admin');
        $panelResources = $panel->getResources();

        $this->assertNotEmpty($panelResources, 'Admin panel has no resources registered.');
    }

    // ── Route accessibility ─────────────────────────────────────────

    #[Test]
    public function it_admin_panel_login_route_is_accessible(): void
    {
        // Unauthenticated request to admin should redirect to login
        auth()->logout();

        $response = $this->get('/admin');

        $response->assertStatus(302);
    }

    #[Test]
    public function it_authenticated_admin_can_reach_admin_panel(): void
    {
        $response = $this->get('/admin');

        // Admin panel route resolves (not a 404) for an authenticated admin
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    #[Test]
    public function it_resource_urls_can_be_generated(): void
    {
        $resourceClass = \Modules\Backup\Filament\Resources\BackupResource::class;

        $url = $resourceClass::getUrl('index');

        $this->assertNotEmpty($url);
        $this->assertStringContainsString('admin', $url);
    }

    #[Test]
    public function it_resource_list_page_loads_via_livewire(): void
    {
        $listPage = \Modules\Backup\Filament\Resources\BackupResource\Pages\ListBackups::class;

        // Clean Sushi cache for Backup model
        $sushiCachePath = storage_path('framework/cache/sushi-modules-backup-models-backup.sqlite');
        if (file_exists($sushiCachePath)) {
            @unlink($sushiCachePath);
        }

        Livewire::test($listPage)->assertSuccessful();
    }

    // ── InteractsWithFilamentPanel trait ─────────────────────────────

    #[Test]
    public function it_trait_sets_up_admin_authentication(): void
    {
        $this->assertAuthenticated();
    }

    #[Test]
    public function it_acting_as_admin_sets_panel_context(): void
    {
        $user = $this->actingAsAdmin();

        $this->assertSame(1, (int) $user->is_admin);
        $this->assertSame('admin', Filament::getCurrentPanel()->getId());
    }

    #[Test]
    public function it_acting_as_user_preserves_panel_context(): void
    {
        $user = $this->actingAsUser();

        $this->assertSame(0, (int) $user->is_admin);
        $this->assertSame('admin', Filament::getCurrentPanel()->getId());
    }

    #[Test]
    public function it_get_filament_resource_url_returns_valid_url(): void
    {
        $url = $this->getFilamentResourceUrl(
            \Modules\Backup\Filament\Resources\BackupResource::class,
            'index'
        );

        $this->assertNotEmpty($url);
        $this->assertStringContainsString('backup', strtolower($url));
    }

    // ── Guest access denied ─────────────────────────────────────────

    #[Test]
    public function it_guest_cannot_access_resource_routes(): void
    {
        auth()->logout();

        $url = \Modules\Backup\Filament\Resources\BackupResource::getUrl('index');
        $response = $this->get($url);

        // Should redirect to login
        $response->assertRedirect();
    }

    // ── Multiple resource route generation ──────────────────────────

    #[Test]
    public function it_multiple_resources_generate_distinct_urls(): void
    {
        $backupUrl = \Modules\Backup\Filament\Resources\BackupResource::getUrl('index');
        $orderUrl = \Modules\Order\Filament\Admin\Resources\OrderResource::getUrl('index');

        $this->assertNotEquals($backupUrl, $orderUrl);
        $this->assertStringContainsString('backup', strtolower($backupUrl));
        $this->assertStringContainsString('order', strtolower($orderUrl));
    }
}
