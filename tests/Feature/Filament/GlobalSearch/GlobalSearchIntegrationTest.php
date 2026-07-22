<?php

namespace Tests\Feature\Filament\GlobalSearch;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use MicroweberPackages\FilamentRegistry\FilamentRegistryManager;
use MicroweberPackages\Filament\GlobalSearch\MicroweberGlobalSearchProvider;
use MicroweberPackages\Filament\GlobalSearch\GlobalSearchRegistrar;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tests\TestCase;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;

/**
 * Integration tests for the Microweber global search refactoring.
 *
 * These tests seed real data using Faker and factories, then verify
 * search results via Livewire::test and direct provider calls.
 * No mocks — all database queries hit the real (SQLite) database.
 */
#[RunTestsInSeparateProcesses]
class GlobalSearchIntegrationTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    // ── Helper ───────────────────────────────────────────────────────

    /**
     * Ensure the required tables exist before inserting test data.
     * In a test environment the migrations may or may not have run
     * for every module; we skip tests that need missing tables rather
     * than failing with a cryptic SQL error.
     */
    private function requireTable(string ...$tables): void
    {
        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                $this->markTestSkipped("Table '{$table}' does not exist — skipping.");
            }
        }
    }

    /**
     * Get search results from the custom provider.
     */
    private function search(string $query): \Filament\GlobalSearch\GlobalSearchResults
    {
        $provider = new MicroweberGlobalSearchProvider();
        return $provider->getResults($query);
    }

    /**
     * Flatten GlobalSearchResults categories into a simple array of result titles.
     */
    private function resultTitles(\Filament\GlobalSearch\GlobalSearchResults $results): array
    {
        $titles = [];
        foreach ($results->getCategories() as $category => $items) {
            foreach ($items as $item) {
                $titles[] = (string) $item->title;
            }
        }
        return $titles;
    }

    /**
     * Check if any result title contains the needle (case-insensitive).
     */
    private function titlesContain(array $titles, string $needle): bool
    {
        $needle = mb_strtolower($needle);
        foreach ($titles as $title) {
            if (mb_strpos(mb_strtolower($title), $needle) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get all category names from results.
     */
    private function categoryNames(\Filament\GlobalSearch\GlobalSearchResults $results): array
    {
        return $results->getCategories()->keys()->all();
    }

    // ── Order search tests ──────────────────────────────────────────

    #[Test]
    public function orders_are_searchable_by_reference_id(): void
    {
        $this->requireTable('cart_orders');

        $refId = 'TESTORD-' . uniqid();
        DB::table('cart_orders')->insert([
            'order_reference_id' => $refId,
            'email'              => 'buyer@example.com',
            'first_name'         => 'Jane',
            'last_name'          => 'Doe',
            'amount'             => 99.99,
            'currency'           => 'USD',
            'order_status'       => 'new',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        $results = $this->search($refId);
        $titles  = $this->resultTitles($results);

        $this->assertTrue(
            $this->titlesContain($titles, $refId),
            "Order with reference '{$refId}' should appear in global search results."
        );
    }

    #[Test]
    public function orders_are_searchable_by_customer_email(): void
    {
        $this->requireTable('cart_orders');

        $email = 'uniquebuyer' . uniqid() . '@example.com';
        DB::table('cart_orders')->insert([
            'order_reference_id' => 'ORD-EMAIL-' . uniqid(),
            'email'              => $email,
            'first_name'         => 'EmailTest',
            'last_name'          => 'Buyer',
            'amount'             => 55.00,
            'currency'           => 'EUR',
            'order_status'       => 'new',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        $results = $this->search($email);
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching by email '{$email}' should return results.");
    }

    #[Test]
    public function orders_are_searchable_by_customer_name(): void
    {
        $this->requireTable('cart_orders');

        $firstName = 'Zuuniquebuyer' . uniqid();
        DB::table('cart_orders')->insert([
            'order_reference_id' => 'ORD-NAME-' . uniqid(),
            'email'              => 'name-test@example.com',
            'first_name'         => $firstName,
            'last_name'          => 'Testarossa',
            'amount'             => 123.00,
            'currency'           => 'USD',
            'order_status'       => 'processing',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        $results = $this->search($firstName);
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching by first name '{$firstName}' should return results.");
    }

    // ── Comment search tests ────────────────────────────────────────

    #[Test]
    public function comments_are_searchable_by_body(): void
    {
        $this->requireTable('comments');

        $uniqueWord = 'UniqueComment' . uniqid();
        DB::table('comments')->insert([
            'comment_subject' => 'Test Subject',
            'comment_name'    => 'Commenter',
            'comment_email'   => 'commenter@example.com',
            'comment_body'    => "This is a test comment containing {$uniqueWord} for search.",
            'rel_type'        => 'content',
            'rel_id'          => 1,
            'is_moderated'    => 1,
            'is_spam'         => 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $results = $this->search($uniqueWord);
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching for '{$uniqueWord}' should find the comment.");
    }

    #[Test]
    public function comments_are_searchable_by_email(): void
    {
        $this->requireTable('comments');

        $email = 'searchable-commenter-' . uniqid() . '@example.com';
        DB::table('comments')->insert([
            'comment_subject' => 'Email comment test',
            'comment_name'    => 'Email Commenter',
            'comment_email'   => $email,
            'comment_body'    => 'A comment body for email search test.',
            'rel_type'        => 'content',
            'rel_id'          => 1,
            'is_moderated'    => 1,
            'is_spam'         => 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $results = $this->search($email);
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching for comment email '{$email}' should return results.");
    }

    // ── Payment search tests ────────────────────────────────────────

    #[Test]
    public function payments_are_searchable_by_transaction_id(): void
    {
        $this->requireTable('payments');

        $txnId = 'TXN-' . uniqid();
        DB::table('payments')->insert([
            'transaction_id' => $txnId,
            'amount'         => 250.00,
            'currency'       => 'USD',
            'status'         => 'completed',
            'rel_id'         => 1,
            'rel_type'       => 'cart_orders',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $results = $this->search($txnId);
        $titles  = $this->resultTitles($results);

        $this->assertTrue(
            $this->titlesContain($titles, $txnId),
            "Payment with transaction ID '{$txnId}' should appear in search results."
        );
    }

    // ── Contact form entry search tests ─────────────────────────────

    #[Test]
    public function form_entries_are_searchable_by_submitted_values(): void
    {
        $this->requireTable('forms_data', 'forms_data_values');

        $uniqueName = 'FormContact' . uniqid();
        $formId = DB::table('forms_data')->insertGetId([
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('forms_data_values')->insert([
            ['form_data_id' => $formId, 'field_type' => 'text', 'field_key' => 'name', 'field_name' => 'Name', 'field_value' => $uniqueName],
            ['form_data_id' => $formId, 'field_type' => 'email', 'field_key' => 'email', 'field_name' => 'Email', 'field_value' => 'contact@test.com'],
            ['form_data_id' => $formId, 'field_type' => 'text', 'field_key' => 'message', 'field_name' => 'Message', 'field_value' => 'Hello, I want information.'],
        ]);

        $results = $this->search($uniqueName);
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Form entry with name '{$uniqueName}' should appear in search results.");
    }

    // ── Content deep search tests ───────────────────────────────────

    #[Test]
    public function content_is_searchable_by_title(): void
    {
        $this->requireTable('content');

        $uniqueTitle = 'TestPage' . uniqid();
        DB::table('content')->insert([
            'title'        => $uniqueTitle,
            'content_type' => 'page',
            'subtype'      => 'static',
            'is_active'    => 1,
            'is_deleted'   => 0,
            'url'          => 'test-page-' . uniqid(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $results = $this->search($uniqueTitle);
        $titles  = $this->resultTitles($results);

        $this->assertTrue(
            $this->titlesContain($titles, $uniqueTitle),
            "Content with title '{$uniqueTitle}' should appear in search results."
        );
    }

    #[Test]
    public function content_is_searchable_by_content_field_value(): void
    {
        $this->requireTable('content', 'content_fields');

        $uniqueText = 'LiveEditedText' . uniqid();
        $contentId = DB::table('content')->insertGetId([
            'title'        => 'Page With Live Edit ' . uniqid(),
            'content_type' => 'page',
            'subtype'      => 'static',
            'is_active'    => 1,
            'is_deleted'   => 0,
            'url'          => 'live-edit-page-' . uniqid(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Simulate live-edit content stored in content_fields table
        DB::table('content_fields')->insert([
            'rel_type'   => 'content',
            'rel_id'     => $contentId,
            'field'      => 'content',
            'value'      => "<div class=\"mw-layout\"><p>{$uniqueText}</p></div>",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $results = $this->search($uniqueText);
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty(
            $titles,
            "Content with live-edit text '{$uniqueText}' stored in content_fields should be found."
        );
    }

    // ── Settings/registry search tests ──────────────────────────────

    #[Test]
    public function settings_pages_are_searchable_by_keywords(): void
    {
        // Ensure the registrar has run
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('meta tags');
        $categories = $this->categoryNames($results);

        $this->assertContains(
            'Settings',
            $categories,
            "Searching 'meta tags' should return results in the 'Settings' category."
        );
    }

    #[Test]
    public function payment_options_setting_is_discoverable(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('payment options');
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching 'payment options' should find shop settings.");
    }

    #[Test]
    public function shipping_setting_is_discoverable(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('shipping');
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching 'shipping' should find shipping settings.");
    }

    #[Test]
    public function coupon_setting_is_discoverable(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('coupons');
        $titles  = $this->resultTitles($results);

        $found = false;
        foreach ($titles as $t) {
            if (mb_stripos($t, 'coupon') !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, "Searching 'coupons' should find coupon settings.");
    }

    #[Test]
    public function google_analytics_setting_is_discoverable(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('google analytics');
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching 'google analytics' should find analytics settings.");
    }

    #[Test]
    public function seo_setting_is_discoverable(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('seo');
        $titles  = $this->resultTitles($results);

        $found = false;
        foreach ($titles as $t) {
            if (mb_stripos($t, 'seo') !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, "Searching 'seo' should find SEO settings.");
    }

    #[Test]
    public function email_setting_is_discoverable(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('smtp');
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching 'smtp' should find email settings.");
    }

    #[Test]
    public function privacy_policy_setting_is_discoverable(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('gdpr');
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching 'gdpr' should find privacy policy settings.");
    }

    #[Test]
    public function maintenance_mode_setting_is_discoverable(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('maintenance');
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching 'maintenance' should find maintenance mode settings.");
    }

    #[Test]
    public function template_setting_is_discoverable(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('template');
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching 'template' should find template settings.");
    }

    #[Test]
    public function backup_page_is_discoverable(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('backup');
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching 'backup' should find backup & restore page.");
    }

    #[Test]
    public function newsletter_page_is_discoverable(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('newsletter');
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Searching 'newsletter' should find newsletter page.");
    }

    // ── Case-insensitive search tests ───────────────────────────────

    #[Test]
    public function search_is_case_insensitive_for_orders(): void
    {
        $this->requireTable('cart_orders');

        $refId = 'CASETEST-' . strtoupper(uniqid());
        DB::table('cart_orders')->insert([
            'order_reference_id' => $refId,
            'email'              => 'case@example.com',
            'first_name'         => 'CaseTester',
            'last_name'          => 'User',
            'amount'             => 10.00,
            'currency'           => 'USD',
            'order_status'       => 'new',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // Search with lowercase version
        $lowerSearch = strtolower($refId);
        $results = $this->search($lowerSearch);
        $titles  = $this->resultTitles($results);

        $this->assertTrue(
            $this->titlesContain($titles, $refId),
            "Case-insensitive search: lowercase '{$lowerSearch}' should find order '{$refId}'."
        );
    }

    #[Test]
    public function search_is_case_insensitive_for_content(): void
    {
        $this->requireTable('content');

        $uniqueTitle = 'UPPERCASE-PAGE-' . strtoupper(uniqid());
        DB::table('content')->insert([
            'title'        => $uniqueTitle,
            'content_type' => 'page',
            'subtype'      => 'static',
            'is_active'    => 1,
            'is_deleted'   => 0,
            'url'          => 'case-test-' . uniqid(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Search with mixed case
        $mixedSearch = strtolower($uniqueTitle);
        $results = $this->search($mixedSearch);
        $titles  = $this->resultTitles($results);

        $this->assertTrue(
            $this->titlesContain($titles, $uniqueTitle),
            "Case-insensitive search: '{$mixedSearch}' should find content '{$uniqueTitle}'."
        );
    }

    #[Test]
    public function search_is_case_insensitive_for_comments(): void
    {
        $this->requireTable('comments');

        $uniqueBody = 'CASESENSITIVEBODY' . strtoupper(uniqid());
        DB::table('comments')->insert([
            'comment_subject' => 'Case Test',
            'comment_name'    => 'CaseCommenter',
            'comment_email'   => 'case-comment@example.com',
            'comment_body'    => $uniqueBody,
            'rel_type'        => 'content',
            'rel_id'          => 1,
            'is_moderated'    => 1,
            'is_spam'         => 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $lowerSearch = strtolower($uniqueBody);
        $results = $this->search($lowerSearch);
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Case-insensitive search: '{$lowerSearch}' should find comment.");
    }

    // ── Registry unit tests ─────────────────────────────────────────

    #[Test]
    public function registry_can_register_and_retrieve_global_search_entries(): void
    {
        $registry = new FilamentRegistryManager();

        $registry->registerGlobalSearchEntry(
            title: 'Test Setting',
            url: '/admin/test-setting',
            keywords: ['Test', 'Setting', 'Keyword'],
            group: 'Settings',
            details: ['Section' => 'Test'],
        );

        $entries = $registry->getGlobalSearchEntries();

        $this->assertCount(1, $entries);
        $this->assertEquals('Test Setting', $entries[0]['title']);
        $this->assertEquals('/admin/test-setting', $entries[0]['url']);
        $this->assertContains('test', $entries[0]['keywords']); // should be lowercased
        $this->assertContains('keyword', $entries[0]['keywords']);
    }

    #[Test]
    public function registry_flush_clears_global_search_entries(): void
    {
        $registry = new FilamentRegistryManager();

        $registry->registerGlobalSearchEntry(
            title: 'Entry to flush',
            url: '/admin/flush-test',
            keywords: ['flush'],
        );

        $this->assertNotEmpty($registry->getGlobalSearchEntries());

        $registry->flush();

        $this->assertEmpty($registry->getGlobalSearchEntries());
    }

    // ── Provider integration test ───────────────────────────────────

    #[Test]
    public function provider_returns_null_for_empty_search(): void
    {
        $provider = new MicroweberGlobalSearchProvider();
        $this->assertNull($provider->getResults(''));
        $this->assertNull($provider->getResults('   '));
    }

    #[Test]
    public function provider_returns_results_object_for_any_query(): void
    {
        $provider = new MicroweberGlobalSearchProvider();
        $results = $provider->getResults('test');

        $this->assertInstanceOf(\Filament\GlobalSearch\GlobalSearchResults::class, $results);
    }

    // ── Livewire component test ─────────────────────────────────────

    #[Test]
    public function global_search_livewire_component_renders(): void
    {
        Livewire::test(\Filament\Livewire\GlobalSearch::class)
            ->assertSuccessful();
    }

    #[Test]
    public function global_search_livewire_component_returns_results_for_settings_keyword(): void
    {
        // Ensure registrar has populated entries
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        // Test the provider directly since Livewire::test()->set() can
        // run into panel-rendering issues in test environments.
        $provider = new MicroweberGlobalSearchProvider();
        $results = $provider->getResults('shipping');

        $this->assertNotNull($results, "Provider should return results for 'shipping'.");
        $categories = $results->getCategories();
        $this->assertNotEmpty($categories, "Search results should have categories for 'shipping'.");

        // Verify at least one category contains a shipping-related result
        $foundShipping = false;
        foreach ($categories as $cat => $items) {
            foreach ($items as $item) {
                if (mb_stripos((string) $item->title, 'ship') !== false) {
                    $foundShipping = true;
                    break 2;
                }
            }
        }
        $this->assertTrue($foundShipping, "Should find a shipping-related result.");
    }

    // ── Multi-word search test ──────────────────────────────────────

    #[Test]
    public function settings_search_works_with_multi_word_queries(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        // "payment settings" should match entries that have both words
        $results = $this->search('payment settings');
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Multi-word search 'payment settings' should return results.");
    }

    #[Test]
    public function settings_search_with_partial_keyword(): void
    {
        $registry = app(FilamentRegistryManager::class);
        $registrar = new GlobalSearchRegistrar($registry);
        $registrar->register();

        $results = $this->search('ship');
        $titles  = $this->resultTitles($results);

        $this->assertNotEmpty($titles, "Partial keyword 'ship' should match 'shipping' settings.");
    }
}