<?php

namespace Tests\Feature\Filament\Registry;

use Filament\Facades\Filament;
use Livewire\Livewire;
use MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\FilamentRegistry\FilamentRegistryManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * Integration tests for the standalone FilamentRegistry package
 * running within the Microweber application context.
 *
 * Uses Livewire::test() to verify that registered resources, pages,
 * and widgets actually render in the Filament admin panel.
 */
class FilamentRegistryIntegrationTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    // ── Container Integration ────────────────────────────────────

    #[Test]
    public function registry_manager_resolves_from_container(): void
    {
        $manager = app(FilamentRegistryManager::class);
        $this->assertInstanceOf(FilamentRegistryManager::class, $manager);
    }

    #[Test]
    public function registry_is_a_singleton(): void
    {
        $a = app(FilamentRegistryManager::class);
        $b = app(FilamentRegistryManager::class);
        $this->assertSame($a, $b);
    }

    #[Test]
    public function facade_resolves_to_manager(): void
    {
        $root = FilamentRegistry::getFacadeRoot();
        $this->assertInstanceOf(FilamentRegistryManager::class, $root);
    }

    // ── Registration During Boot ─────────────────────────────────

    #[Test]
    public function modules_register_resources_during_boot(): void
    {
        $resources = FilamentRegistry::getResources(
            FilamentAdminPanelProvider::class,
            'admin'
        );
        $this->assertNotEmpty($resources, 'Expected modules to register resources during boot.');
    }

    #[Test]
    public function modules_register_pages_during_boot(): void
    {
        $pages = FilamentRegistry::getPages(
            FilamentAdminPanelProvider::class,
            'admin'
        );
        $this->assertNotEmpty($pages, 'Expected modules to register pages during boot.');
    }

    #[Test]
    public function known_resources_are_registered(): void
    {
        $resources = FilamentRegistry::getResources(
            FilamentAdminPanelProvider::class,
            'admin'
        );

        $expected = [
            \Modules\Content\Filament\Admin\ContentResource::class,
            \Modules\Category\Filament\Admin\Resources\CategoryResource::class,
        ];

        foreach ($expected as $class) {
            $this->assertContains($class, $resources, "Expected {$class} to be registered.");
        }
    }

    // ── Livewire Resource Rendering ──────────────────────────────

    #[Test]
    public function registered_resource_list_page_renders_via_livewire(): void
    {
        $resources = FilamentRegistry::getResources(
            FilamentAdminPanelProvider::class,
            'admin'
        );

        $this->assertNotEmpty($resources);

        // Pick the Backup resource which uses Sushi (no real DB needed)
        if (in_array(\Modules\Backup\Filament\Resources\BackupResource::class, $resources)) {
            $listPage = \Modules\Backup\Filament\Resources\BackupResource\Pages\ListBackups::class;

            // Clean Sushi cache
            $sushiCachePath = storage_path('framework/cache/sushi-modules-backup-models-backup.sqlite');
            if (file_exists($sushiCachePath)) {
                @unlink($sushiCachePath);
            }

            Livewire::test($listPage)->assertSuccessful();
        }
    }

    #[Test]
    public function panel_has_resources_from_registry(): void
    {
        $registeredResources = FilamentRegistry::getResources(
            FilamentAdminPanelProvider::class,
            'admin'
        );

        $panel = Filament::getPanel('admin');
        $panelResources = $panel->getResources();

        // The panel should contain at least a subset of the registry resources.
        // Some resources may be filtered by the panel provider (e.g. authorization).
        $foundCount = 0;
        foreach ($registeredResources as $resource) {
            if (in_array($resource, $panelResources)) {
                $foundCount++;
            }
        }

        $this->assertGreaterThan(
            0,
            $foundCount,
            'Expected at least some registry resources to appear in the panel.'
        );
    }

    // ── Scope Isolation ──────────────────────────────────────────

    #[Test]
    public function scope_isolation_works_in_app_context(): void
    {
        // Register a test resource with a custom scope
        FilamentRegistry::registerResource(
            'Tests\\Fake\\CustomScopeResource',
            'CustomScope',
            'admin'
        );

        $customResults = FilamentRegistry::getResources('CustomScope', 'admin');
        $adminResults = FilamentRegistry::getResources(FilamentAdminPanelProvider::class, 'admin');

        $this->assertContains('Tests\\Fake\\CustomScopeResource', $customResults);
        $this->assertNotContains('Tests\\Fake\\CustomScopeResource', $adminResults);
    }

    // ── Panel ID Isolation ───────────────────────────────────────

    #[Test]
    public function panel_id_isolation_works_in_app_context(): void
    {
        FilamentRegistry::registerResource(
            'Tests\\Fake\\FrontendResource',
            FilamentAdminPanelProvider::class,
            'frontend-test'
        );

        $frontendResults = FilamentRegistry::getResources(
            FilamentAdminPanelProvider::class,
            'frontend-test'
        );
        $adminResults = FilamentRegistry::getResources(
            FilamentAdminPanelProvider::class,
            'admin'
        );

        $this->assertContains('Tests\\Fake\\FrontendResource', $frontendResults);
        $this->assertNotContains('Tests\\Fake\\FrontendResource', $adminResults);
    }

    // ── All Registry Types ───────────────────────────────────────

    #[Test]
    public function all_method_returns_complete_registry(): void
    {
        $all = FilamentRegistry::all(FilamentAdminPanelProvider::class, 'admin');

        $this->assertArrayHasKey('resources', $all);
        $this->assertArrayHasKey('pages', $all);
        $this->assertArrayHasKey('widgets', $all);
        $this->assertArrayHasKey('plugins', $all);
        $this->assertArrayHasKey('clusters', $all);
    }

    // ── Flush ────────────────────────────────────────────────────

    #[Test]
    public function flush_and_re_register_works(): void
    {
        // Capture current count
        $beforeResources = FilamentRegistry::getResources(
            FilamentAdminPanelProvider::class,
            'admin'
        );
        $beforeCount = count($beforeResources);

        // Register an extra resource
        FilamentRegistry::registerResource(
            'Tests\\Fake\\FlushTestResource',
            FilamentAdminPanelProvider::class,
            'admin'
        );

        $afterResources = FilamentRegistry::getResources(
            FilamentAdminPanelProvider::class,
            'admin'
        );
        $this->assertCount($beforeCount + 1, $afterResources);

        // Flush clears everything
        FilamentRegistry::flush();
        $this->assertEmpty(FilamentRegistry::getResources(
            FilamentAdminPanelProvider::class,
            'admin'
        ));
    }

}
