<?php

namespace Tests\Feature\Filament\GlobalSearch;

use MicroweberPackages\FilamentRegistry\FilamentRegistryManager;
use MicroweberPackages\FilamentRegistry\GlobalSearch\MicroweberGlobalSearchProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tests\TestCase;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;

/**
 * Tests for the global search registry — verifies that module service providers
 * register all expected settings pages, shop settings, and admin pages as
 * global search entries in the FilamentRegistryManager, and that keyword
 * matching works correctly across panel scopes.
 */
#[RunTestsInSeparateProcesses]
class GlobalSearchRegistryTest extends TestCase
{
    use InteractsWithFilamentPanel;

    private FilamentRegistryManager $registry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();

        // Module service providers have already registered their entries
        // during boot, so we use the app-level singleton.
        $this->registry = app(FilamentRegistryManager::class);
    }

    /**
     * Simulate the provider's keyword matching for registry entries
     * (without needing a full Filament panel context for resource search).
     */
    private function assertRegistrySearchFinds(string $query, string $expectedKeyword, string $panelId = 'admin'): void
    {
        $entries = $this->registry->getGlobalSearchEntries($panelId);
        $searchLower = mb_strtolower($query);
        $searchWords = array_filter(preg_split('/\s+/', $searchLower));

        $matched = [];
        foreach ($entries as $entry) {
            $haystack = mb_strtolower($entry['title']);
            foreach ($entry['keywords'] as $kw) {
                $haystack .= ' ' . $kw;
            }
            foreach ($entry['details'] as $v) {
                $haystack .= ' ' . mb_strtolower((string) $v);
            }

            $allMatch = true;
            foreach ($searchWords as $word) {
                if (mb_strpos($haystack, $word) === false) {
                    $allMatch = false;
                    break;
                }
            }
            if ($allMatch) {
                $matched[] = $entry['title'];
            }
        }

        $found = false;
        foreach ($matched as $title) {
            if (mb_stripos($title, $expectedKeyword) !== false) {
                $found = true;
                break;
            }
        }

        $this->assertTrue(
            $found,
            "Search for '{$query}' in panel '{$panelId}' should find a result containing '{$expectedKeyword}'. Got: " . implode(', ', $matched)
        );
    }

    // ── Website Settings ────────────────────────────────────────────

    #[Test]
    public function general_settings_found_by_keyword_website_name(): void
    {
        $this->assertRegistrySearchFinds('website name', 'General');
    }

    #[Test]
    public function seo_settings_found_by_keyword_meta_tags(): void
    {
        $this->assertRegistrySearchFinds('meta tags', 'SEO');
    }

    #[Test]
    public function seo_settings_found_by_keyword_google_analytics(): void
    {
        $this->assertRegistrySearchFinds('google analytics', 'Analytics');
    }

    #[Test]
    public function email_settings_found_by_keyword_smtp(): void
    {
        $this->assertRegistrySearchFinds('smtp', 'Email');
    }

    #[Test]
    public function language_settings_found_by_keyword_translation(): void
    {
        $this->assertRegistrySearchFinds('translation', 'Language');
    }

    #[Test]
    public function login_register_settings_found_by_keyword_registration(): void
    {
        $this->assertRegistrySearchFinds('registration', 'Login');
    }

    #[Test]
    public function privacy_policy_found_by_keyword_gdpr(): void
    {
        $this->assertRegistrySearchFinds('gdpr', 'Privacy');
    }

    #[Test]
    public function advanced_settings_found_by_keyword_cache(): void
    {
        $this->assertRegistrySearchFinds('cache', 'Advanced');
    }

    #[Test]
    public function custom_tags_found_by_keyword_html_head(): void
    {
        $this->assertRegistrySearchFinds('html head', 'Custom Tags');
    }

    #[Test]
    public function maintenance_mode_found_by_keyword(): void
    {
        $this->assertRegistrySearchFinds('under construction', 'System');
    }

    // ── Shop Settings ───────────────────────────────────────────────

    #[Test]
    public function shop_settings_found_by_keyword_payment_options(): void
    {
        $this->assertRegistrySearchFinds('payment options', 'Shop');
    }

    #[Test]
    public function shop_settings_found_by_keyword_currency(): void
    {
        $this->assertRegistrySearchFinds('currency', 'Currency');
    }

    #[Test]
    public function shipping_settings_found_by_keyword(): void
    {
        $this->assertRegistrySearchFinds('shipping method', 'Shipping');
    }

    #[Test]
    public function coupon_settings_found_by_keyword_promo_code(): void
    {
        $this->assertRegistrySearchFinds('promo code', 'Coupon');
    }

    #[Test]
    public function tax_settings_found_by_keyword_vat(): void
    {
        $this->assertRegistrySearchFinds('vat', 'Tax');
    }

    #[Test]
    public function payment_gateway_found_by_keyword_stripe(): void
    {
        $this->assertRegistrySearchFinds('stripe', 'Payment');
    }

    #[Test]
    public function payment_gateway_found_by_keyword_paypal(): void
    {
        $this->assertRegistrySearchFinds('paypal', 'Payment');
    }

    // ── Admin Pages ─────────────────────────────────────────────────

    #[Test]
    public function backup_page_found_by_keyword(): void
    {
        $this->assertRegistrySearchFinds('backup', 'Backup');
    }

    #[Test]
    public function media_library_found_by_keyword_images(): void
    {
        $this->assertRegistrySearchFinds('images', 'Media');
    }

    #[Test]
    public function email_templates_found_by_keyword(): void
    {
        $this->assertRegistrySearchFinds('email template', 'Email Template');
    }

    #[Test]
    public function newsletter_found_by_keyword_mailing_list(): void
    {
        $this->assertRegistrySearchFinds('mailing list', 'Newsletter');
    }

    #[Test]
    public function faq_found_by_keyword(): void
    {
        $this->assertRegistrySearchFinds('frequently asked', 'FAQ');
    }

    #[Test]
    public function custom_fields_found_by_keyword(): void
    {
        $this->assertRegistrySearchFinds('custom field', 'Custom Fields');
    }

    #[Test]
    public function menu_management_found_by_keyword(): void
    {
        $this->assertRegistrySearchFinds('navigation', 'Menu');
    }

    #[Test]
    public function ai_settings_found_by_keyword(): void
    {
        $this->assertRegistrySearchFinds('openai', 'AI');
    }

    #[Test]
    public function white_label_found_by_keyword(): void
    {
        $this->assertRegistrySearchFinds('rebrand', 'White Label');
    }

    #[Test]
    public function orders_found_by_keyword(): void
    {
        $this->assertRegistrySearchFinds('purchase', 'Order');
    }

    // ── Multi-panel scoping ─────────────────────────────────────────

    #[Test]
    public function newsletter_panel_has_own_entries(): void
    {
        $entries = $this->registry->getGlobalSearchEntries('admin-newsletter');
        $this->assertNotEmpty($entries, 'Newsletter panel should have its own global search entries.');

        $titles = array_column($entries, 'title');
        $this->assertContains('Campaigns', $titles);
        $this->assertContains('Subscribers', $titles);
        $this->assertContains('Templates', $titles);
    }

    #[Test]
    public function newsletter_panel_search_finds_campaigns(): void
    {
        $this->assertRegistrySearchFinds('campaign', 'Campaign', 'admin-newsletter');
    }

    #[Test]
    public function newsletter_panel_search_finds_subscribers(): void
    {
        $this->assertRegistrySearchFinds('email list', 'Subscriber', 'admin-newsletter');
    }

    #[Test]
    public function admin_entries_not_in_newsletter_panel(): void
    {
        $entries = $this->registry->getGlobalSearchEntries('admin-newsletter');
        $titles = array_column($entries, 'title');
        $this->assertNotContains('General Settings', $titles, 'Admin-only entries should not leak into the newsletter panel.');
    }

    // ── Registry completeness ───────────────────────────────────────

    #[Test]
    public function admin_panel_has_at_least_thirty_entries(): void
    {
        $entries = $this->registry->getGlobalSearchEntries('admin');

        $this->assertGreaterThanOrEqual(
            30,
            count($entries),
            'Module service providers should register at least 30 admin search entries. Got: ' . count($entries)
        );
    }

    #[Test]
    public function every_entry_has_required_fields(): void
    {
        $entries = $this->registry->getGlobalSearchEntries('admin');

        foreach ($entries as $i => $entry) {
            $this->assertArrayHasKey('title', $entry, "Entry #{$i} missing 'title'.");
            $this->assertArrayHasKey('url', $entry, "Entry #{$i} missing 'url'.");
            $this->assertArrayHasKey('keywords', $entry, "Entry #{$i} missing 'keywords'.");
            $this->assertNotEmpty($entry['title'], "Entry #{$i} has empty title.");
            $this->assertNotEmpty($entry['url'], "Entry #{$i} has empty URL.");
            $this->assertNotEmpty($entry['keywords'], "Entry #{$i} has no keywords.");
        }
    }

    #[Test]
    public function all_entry_keywords_are_lowercase(): void
    {
        $entries = $this->registry->getGlobalSearchEntries('admin');

        foreach ($entries as $entry) {
            foreach ($entry['keywords'] as $keyword) {
                $this->assertEquals(
                    mb_strtolower($keyword),
                    $keyword,
                    "Keyword '{$keyword}' in entry '{$entry['title']}' should be lowercase."
                );
            }
        }
    }

    #[Test]
    public function panelId_scoping_isolates_entries(): void
    {
        $manager = new FilamentRegistryManager();

        $manager->registerGlobalSearchEntry(
            'Admin Entry', '/admin/test',
            ['admin test'], 'Settings', [],
            panelId: 'admin',
        );
        $manager->registerGlobalSearchEntry(
            'Other Panel Entry', '/other/test',
            ['other test'], 'Other', [],
            panelId: 'other-panel',
        );

        $this->assertCount(1, $manager->getGlobalSearchEntries('admin'));
        $this->assertCount(1, $manager->getGlobalSearchEntries('other-panel'));
        $this->assertEmpty($manager->getGlobalSearchEntries('nonexistent'));
        $this->assertEquals('Admin Entry', $manager->getGlobalSearchEntries('admin')[0]['title']);
        $this->assertEquals('Other Panel Entry', $manager->getGlobalSearchEntries('other-panel')[0]['title']);
    }

    #[Test]
    public function flush_clears_all_panel_entries(): void
    {
        $manager = new FilamentRegistryManager();
        $manager->registerGlobalSearchEntry('Test', '/test', ['test'], panelId: 'admin');
        $manager->registerGlobalSearchEntry('Test2', '/test2', ['test2'], panelId: 'other');

        $manager->flush();

        $this->assertEmpty($manager->getGlobalSearchEntries('admin'));
        $this->assertEmpty($manager->getGlobalSearchEntries('other'));
    }
}