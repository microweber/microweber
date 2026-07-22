<?php

namespace Tests\Feature\Filament\GlobalSearch;

use MicroweberPackages\FilamentRegistry\FilamentRegistryManager;
use MicroweberPackages\Filament\GlobalSearch\GlobalSearchRegistrar;
use MicroweberPackages\Filament\GlobalSearch\MicroweberGlobalSearchProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tests\TestCase;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;

/**
 * Tests for the GlobalSearchRegistrar — verifies that all expected
 * settings pages, shop settings, and admin pages are registered
 * and discoverable through keyword searches.
 */
#[RunTestsInSeparateProcesses]
class GlobalSearchRegistryTest extends TestCase
{
    use InteractsWithFilamentPanel;

    private FilamentRegistryManager $registry;
    private MicroweberGlobalSearchProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();

        $this->registry = new FilamentRegistryManager();
        $registrar = new GlobalSearchRegistrar($this->registry);
        $registrar->register();

        // Bind the fresh registry so the provider uses it
        app()->instance(FilamentRegistryManager::class, $this->registry);

        $this->provider = new MicroweberGlobalSearchProvider();
    }

    private function assertSearchFinds(string $query, string $expectedKeyword): void
    {
        $results = $this->provider->getResults($query);
        $this->assertNotNull($results, "Search for '{$query}' should not return null.");

        $allTitles = [];
        foreach ($results->getCategories() as $cat => $items) {
            foreach ($items as $item) {
                $allTitles[] = (string) $item->title;
            }
        }

        $found = false;
        foreach ($allTitles as $title) {
            if (mb_stripos($title, $expectedKeyword) !== false) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, "Search for '{$query}' should find a result containing '{$expectedKeyword}'. Got: " . implode(', ', $allTitles));
    }

    // ── Website Settings ────────────────────────────────────────────

    #[Test]
    public function general_settings_found_by_keyword_website_name(): void
    {
        $this->assertSearchFinds('website name', 'General');
    }

    #[Test]
    public function seo_settings_found_by_keyword_meta_tags(): void
    {
        $this->assertSearchFinds('meta tags', 'SEO');
    }

    #[Test]
    public function seo_settings_found_by_keyword_google_analytics(): void
    {
        $this->assertSearchFinds('google analytics', 'Analytics');
    }

    #[Test]
    public function email_settings_found_by_keyword_smtp(): void
    {
        $this->assertSearchFinds('smtp', 'Email');
    }

    #[Test]
    public function language_settings_found_by_keyword_translation(): void
    {
        $this->assertSearchFinds('translation', 'Language');
    }

    #[Test]
    public function login_register_settings_found_by_keyword_registration(): void
    {
        $this->assertSearchFinds('registration', 'Login');
    }

    #[Test]
    public function privacy_policy_found_by_keyword_gdpr(): void
    {
        $this->assertSearchFinds('gdpr', 'Privacy');
    }

    #[Test]
    public function advanced_settings_found_by_keyword_cache(): void
    {
        $this->assertSearchFinds('cache', 'Advanced');
    }

    #[Test]
    public function custom_tags_found_by_keyword_html_head(): void
    {
        $this->assertSearchFinds('html head', 'Custom Tags');
    }

    #[Test]
    public function maintenance_mode_found_by_keyword(): void
    {
        $this->assertSearchFinds('under construction', 'System');
    }

    // ── Shop Settings ───────────────────────────────────────────────

    #[Test]
    public function shop_settings_found_by_keyword_payment_options(): void
    {
        $this->assertSearchFinds('payment options', 'Shop');
    }

    #[Test]
    public function shop_settings_found_by_keyword_currency(): void
    {
        $this->assertSearchFinds('currency', 'Shop');
    }

    #[Test]
    public function shipping_settings_found_by_keyword(): void
    {
        $this->assertSearchFinds('shipping method', 'Shipping');
    }

    #[Test]
    public function coupon_settings_found_by_keyword_promo_code(): void
    {
        $this->assertSearchFinds('promo code', 'Coupon');
    }

    #[Test]
    public function tax_settings_found_by_keyword_vat(): void
    {
        $this->assertSearchFinds('vat', 'Tax');
    }

    #[Test]
    public function payment_gateway_found_by_keyword_stripe(): void
    {
        $this->assertSearchFinds('stripe', 'Payment');
    }

    #[Test]
    public function payment_gateway_found_by_keyword_paypal(): void
    {
        $this->assertSearchFinds('paypal', 'Payment');
    }

    // ── Admin Pages ─────────────────────────────────────────────────

    #[Test]
    public function backup_page_found_by_keyword(): void
    {
        $this->assertSearchFinds('backup', 'Backup');
    }

    #[Test]
    public function media_library_found_by_keyword_images(): void
    {
        $this->assertSearchFinds('images', 'Media');
    }

    #[Test]
    public function email_templates_found_by_keyword(): void
    {
        $this->assertSearchFinds('email template', 'Email Template');
    }

    #[Test]
    public function newsletter_found_by_keyword_mailing_list(): void
    {
        $this->assertSearchFinds('mailing list', 'Newsletter');
    }

    // ── Registrar completeness ──────────────────────────────────────

    #[Test]
    public function registrar_creates_at_least_twenty_entries(): void
    {
        $entries = $this->registry->getGlobalSearchEntries();

        $this->assertGreaterThanOrEqual(
            20,
            count($entries),
            'The registrar should register at least 20 discoverable entries.'
        );
    }

    #[Test]
    public function every_entry_has_required_fields(): void
    {
        $entries = $this->registry->getGlobalSearchEntries();

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
        $entries = $this->registry->getGlobalSearchEntries();

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
}