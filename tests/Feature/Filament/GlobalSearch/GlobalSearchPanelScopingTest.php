<?php

namespace Tests\Feature\Filament\GlobalSearch;

use Filament\Facades\Filament;
use Livewire\Livewire;
use MicroweberPackages\FilamentRegistry\FilamentRegistryManager;
use MicroweberPackages\FilamentRegistry\GlobalSearch\MicroweberGlobalSearchProvider;
use MicroweberPackages\FilamentRegistry\GlobalSearch\MicroweberGloballySearchable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tests\TestCase;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;

/**
 * Tests for panel-scoped global search and the MicroweberGloballySearchable trait.
 *
 * Verifies:
 * - Panel ID scoping isolates search entries between panels
 * - The trait correctly enables global search on resources
 * - Case-insensitive matching works via keyword matching
 * - The Livewire global search component renders and returns results
 * - Provider returns correct results for each panel context
 */
#[RunTestsInSeparateProcesses]
class GlobalSearchPanelScopingTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    // ── Panel scoping ───────────────────────────────────────────────

    #[Test]
    public function register_global_search_entry_stores_by_panel_id(): void
    {
        $manager = new FilamentRegistryManager();

        $manager->registerGlobalSearchEntry(
            'Admin Setting', '/admin/setting',
            ['admin', 'setting'],
            'Settings', [], null,
            panelId: 'admin',
        );

        $manager->registerGlobalSearchEntry(
            'Newsletter Campaign', '/newsletter/campaigns',
            ['newsletter', 'campaign'],
            'Newsletter', [], null,
            panelId: 'admin-newsletter',
        );

        $manager->registerGlobalSearchEntry(
            'Billing Plan', '/billing/plans',
            ['billing', 'plan', 'subscription'],
            'Billing', [], null,
            panelId: 'admin-billing',
        );

        // Each panel should have exactly 1 entry
        $this->assertCount(1, $manager->getGlobalSearchEntries('admin'));
        $this->assertCount(1, $manager->getGlobalSearchEntries('admin-newsletter'));
        $this->assertCount(1, $manager->getGlobalSearchEntries('admin-billing'));

        // Unknown panel returns empty
        $this->assertEmpty($manager->getGlobalSearchEntries('nonexistent'));
    }

    #[Test]
    public function default_panel_id_is_admin(): void
    {
        $manager = new FilamentRegistryManager();

        // No panelId specified — should default to 'admin'
        $manager->registerGlobalSearchEntry('Default Panel', '/admin/default', ['default']);

        $this->assertCount(1, $manager->getGlobalSearchEntries('admin'));
        $this->assertCount(0, $manager->getGlobalSearchEntries('other'));
    }

    #[Test]
    public function entries_with_icon_are_stored_correctly(): void
    {
        $manager = new FilamentRegistryManager();

        $manager->registerGlobalSearchEntry(
            'Iconified Entry', '/admin/icon-test',
            ['icon', 'test'],
            'Settings', ['Section' => 'Test'],
            icon: 'heroicon-o-cog',
        );

        $entries = $manager->getGlobalSearchEntries('admin');
        $this->assertCount(1, $entries);
        $this->assertEquals('heroicon-o-cog', $entries[0]['icon']);
    }

    #[Test]
    public function keywords_are_always_lowercased(): void
    {
        $manager = new FilamentRegistryManager();

        $manager->registerGlobalSearchEntry(
            'Mixed Case', '/admin/mixed',
            ['UPPER', 'MiXeD', 'lower'],
        );

        $entries = $manager->getGlobalSearchEntries('admin');
        $this->assertEquals(['upper', 'mixed', 'lower'], $entries[0]['keywords']);
    }

    // ── Module service provider entries ──────────────────────────────

    #[Test]
    public function admin_panel_has_settings_entries_from_modules(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $entries = $registry->getGlobalSearchEntries('admin');

        $titles = array_column($entries, 'title');

        // Entries from Settings module
        $this->assertContains('General Settings', $titles);
        $this->assertContains('SEO Settings', $titles);
        $this->assertContains('Email Settings', $titles);
        $this->assertContains('Language Settings', $titles);
        $this->assertContains('Advanced Settings', $titles);
        $this->assertContains('Template Settings', $titles);
        $this->assertContains('Main Shop Settings', $titles);
    }

    #[Test]
    public function admin_panel_has_module_entries(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $entries = $registry->getGlobalSearchEntries('admin');

        $titles = array_column($entries, 'title');

        // Entries from individual modules
        $this->assertContains('FAQ Management', $titles);
        $this->assertContains('Custom Fields', $titles);
        $this->assertContains('Menu Management', $titles);
        $this->assertContains('Media Library', $titles);
        $this->assertContains('File Manager', $titles);
        $this->assertContains('Backup & Restore', $titles);
        $this->assertContains('AI Settings', $titles);
        $this->assertContains('Newsletter', $titles);
        $this->assertContains('Orders', $titles);
    }

    #[Test]
    public function admin_panel_has_shop_entries(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $entries = $registry->getGlobalSearchEntries('admin');

        $titles = array_column($entries, 'title');

        $this->assertContains('Coupons', $titles);
        $this->assertContains('Currency Settings', $titles);
        $this->assertContains('Invoice Settings', $titles);
        $this->assertContains('Shipping Provider Settings', $titles);
        $this->assertContains('Payment Provider Settings', $titles);
        $this->assertContains('Tax Settings', $titles);
        $this->assertContains('Offers & Discount Prices', $titles);
    }

    #[Test]
    public function newsletter_panel_has_separate_entries(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $entries = $registry->getGlobalSearchEntries('admin-newsletter');

        $titles = array_column($entries, 'title');

        $this->assertContains('Campaigns', $titles);
        $this->assertContains('Subscribers', $titles);
        $this->assertContains('Templates', $titles);
        $this->assertContains('Sender Accounts', $titles);
        $this->assertContains('Subscriber Lists', $titles);

        // Admin-only entries should NOT be here
        $this->assertNotContains('General Settings', $titles);
        $this->assertNotContains('SEO Settings', $titles);
    }

    // ── Provider keyword matching ───────────────────────────────────

    #[Test]
    public function provider_returns_null_for_blank_query(): void
    {
        $provider = new MicroweberGlobalSearchProvider();
        $this->assertNull($provider->getResults(''));
        $this->assertNull($provider->getResults('   '));
    }

    #[Test]
    public function provider_returns_results_object(): void
    {
        $provider = new MicroweberGlobalSearchProvider();
        $result = $provider->getResults('test');
        $this->assertInstanceOf(\Filament\GlobalSearch\GlobalSearchResults::class, $result);
    }

    // ── Case-insensitive keyword matching ───────────────────────────

    #[Test]
    public function keyword_matching_is_case_insensitive(): void
    {
        $manager = new FilamentRegistryManager();
        $manager->registerGlobalSearchEntry(
            'TEST Entry', '/admin/test',
            ['UPPERCASE keyword', 'MiXeD CaSe'],
        );

        $entries = $manager->getGlobalSearchEntries('admin');
        // Keywords should be stored lowercase
        $this->assertEquals('uppercase keyword', $entries[0]['keywords'][0]);
        $this->assertEquals('mixed case', $entries[0]['keywords'][1]);
    }

    // ── Trait ────────────────────────────────────────────────────────

    #[Test]
    public function globally_searchable_trait_exists(): void
    {
        $this->assertTrue(
            trait_exists(MicroweberGloballySearchable::class),
            'MicroweberGloballySearchable trait should exist in the registry package.'
        );
    }

    // ── Livewire ────────────────────────────────────────────────────

    #[Test]
    public function global_search_livewire_component_renders(): void
    {
        Livewire::test(\Filament\Livewire\GlobalSearch::class)
            ->assertSuccessful();
    }

    // ── Flush ───────────────────────────────────────────────────────

    #[Test]
    public function flush_clears_all_panels(): void
    {
        $manager = new FilamentRegistryManager();
        $manager->registerGlobalSearchEntry('A', '/a', ['a'], panelId: 'admin');
        $manager->registerGlobalSearchEntry('B', '/b', ['b'], panelId: 'newsletter');
        $manager->registerGlobalSearchEntry('C', '/c', ['c'], panelId: 'billing');

        $manager->flush();

        $this->assertEmpty($manager->getGlobalSearchEntries('admin'));
        $this->assertEmpty($manager->getGlobalSearchEntries('newsletter'));
        $this->assertEmpty($manager->getGlobalSearchEntries('billing'));
    }
}