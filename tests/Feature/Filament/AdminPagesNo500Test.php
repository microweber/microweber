<?php

namespace Tests\Feature\Filament;

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * Smoke test: every admin GET route must respond with a non-5xx status
 * for an authenticated admin user. Catches accidental 500s introduced by
 * resource/page edits without having to load each page in a browser.
 *
 * Routes are sourced from tests/fixtures/admin-pages.php which is generated
 * from `php artisan route:list --path=admin --json`, grouped by area.
 * Each area has its own data provider so failures point at the exact route.
 */
class AdminPagesNo500Test extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    /**
     * Resolve a URI that may contain {record} to a real URL.
     * Returns null if the record source table has no rows.
     */
    private function resolveUri(string $uri): ?string
    {
        if (!str_contains($uri, '{record}')) {
            return '/' . ltrim($uri, '/');
        }

        $fixture = require __DIR__ . '/../../fixtures/admin-pages.php';
        $sources = $fixture['record_sources'] ?? [];

        // Find the matching record source by checking route prefixes
        $prefix = preg_replace('#/\{record\}.*#', '', $uri);
        if (!isset($sources[$prefix])) {
            $this->markTestSkipped("No record source configured for {$prefix}");
            return null;
        }

        [$table, $where] = $sources[$prefix];

        try {
            $query = DB::table($table);
            if ($where) {
                $query->whereRaw($where);
            }
            $id = $query->value('id');
        } catch (\Throwable $e) {
            $this->markTestSkipped("Table {$table} not available: " . $e->getMessage());
            return null;
        }

        if (!$id) {
            $this->markTestSkipped("No records in {$table}" . ($where ? " WHERE {$where}" : ''));
            return null;
        }

        return '/' . ltrim(str_replace('{record}', $id, $uri), '/');
    }

    /**
     * Build data-provider cases from a specific area key in the fixture.
     */
    private static function casesForArea(string $area): array
    {
        $fixture = require __DIR__ . '/../../fixtures/admin-pages.php';
        $routes = $fixture[$area] ?? [];
        $cases = [];
        foreach ($routes as $uri) {
            $cases[$uri] = [$uri];
        }
        return $cases;
    }

    // =========================================================================
    // Per-area data providers
    // =========================================================================

    public static function dashboardProvider(): array { return self::casesForArea('dashboard'); }
    public static function contentPagesProvider(): array { return self::casesForArea('content_pages'); }
    public static function contentPostsProvider(): array { return self::casesForArea('content_posts'); }
    public static function contentCategoriesProvider(): array { return self::casesForArea('content_categories'); }
    public static function contentsProvider(): array { return self::casesForArea('contents'); }
    public static function commentsProvider(): array { return self::casesForArea('comments'); }
    public static function formEntriesProvider(): array { return self::casesForArea('form_entries'); }
    public static function translationsProvider(): array { return self::casesForArea('translations'); }
    public static function faqProvider(): array { return self::casesForArea('faq'); }
    public static function shopProductsProvider(): array { return self::casesForArea('shop_products'); }
    public static function shopOrdersProvider(): array { return self::casesForArea('shop_orders'); }
    public static function shopCategoriesProvider(): array { return self::casesForArea('shop_categories'); }
    public static function shopCustomersProvider(): array { return self::casesForArea('shop_customers'); }
    public static function shopCouponsProvider(): array { return self::casesForArea('shop_coupons'); }
    public static function shopInvoicesProvider(): array { return self::casesForArea('shop_invoices'); }
    public static function shopInventoryProvider(): array { return self::casesForArea('shop_inventory'); }
    public static function shopPaymentsProvider(): array { return self::casesForArea('shop_payments'); }
    public static function shopShippingProvider(): array { return self::casesForArea('shop_shipping'); }
    public static function shopTaxProvider(): array { return self::casesForArea('shop_tax'); }
    public static function shopVariantsProvider(): array { return self::casesForArea('shop_variants'); }
    public static function tagsProvider(): array { return self::casesForArea('tags'); }
    public static function usersProvider(): array { return self::casesForArea('users'); }
    public static function mailTemplatesProvider(): array { return self::casesForArea('mail_templates'); }
    public static function settingsProvider(): array { return self::casesForArea('settings'); }
    public static function marketplaceProvider(): array { return self::casesForArea('marketplace'); }
    public static function moduleSettingsProvider(): array { return self::casesForArea('module_settings'); }
    public static function backupsProvider(): array { return self::casesForArea('backups'); }
    public static function billingProvider(): array { return self::casesForArea('billing'); }
    public static function newsletterProvider(): array { return self::casesForArea('newsletter'); }
    public static function aiProvider(): array { return self::casesForArea('ai'); }
    public static function otherProvider(): array { return self::casesForArea('other'); }

    // =========================================================================
    // Per-area test methods
    // =========================================================================

    #[Test] #[DataProvider('dashboardProvider')]
    public function dashboard_does_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('contentPagesProvider')]
    public function content_pages_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('contentPostsProvider')]
    public function content_posts_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('contentCategoriesProvider')]
    public function content_categories_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('contentsProvider')]
    public function contents_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('commentsProvider')]
    public function comments_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('formEntriesProvider')]
    public function form_entries_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('translationsProvider')]
    public function translations_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('faqProvider')]
    public function faq_does_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('shopProductsProvider')]
    public function shop_products_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('shopOrdersProvider')]
    public function shop_orders_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('shopCategoriesProvider')]
    public function shop_categories_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('shopCustomersProvider')]
    public function shop_customers_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('shopCouponsProvider')]
    public function shop_coupons_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('shopInvoicesProvider')]
    public function shop_invoices_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('shopInventoryProvider')]
    public function shop_inventory_does_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('shopPaymentsProvider')]
    public function shop_payments_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('shopShippingProvider')]
    public function shop_shipping_does_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('shopTaxProvider')]
    public function shop_tax_does_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('shopVariantsProvider')]
    public function shop_variants_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('tagsProvider')]
    public function tags_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('usersProvider')]
    public function users_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('mailTemplatesProvider')]
    public function mail_templates_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('settingsProvider')]
    public function settings_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('marketplaceProvider')]
    public function marketplace_does_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('moduleSettingsProvider')]
    public function module_settings_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('backupsProvider')]
    public function backups_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('billingProvider')]
    public function billing_does_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('newsletterProvider')]
    public function newsletter_does_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('aiProvider')]
    public function ai_does_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    #[Test] #[DataProvider('otherProvider')]
    public function other_pages_do_not_500(string $uri): void { $this->assertRouteNo500($uri); }

    // =========================================================================
    // Auth routes (unauthenticated — should return 200 for login page)
    // =========================================================================

    #[Test]
    public function login_page_does_not_500(): void
    {
        $response = $this->get('/admin/login');
        $this->assertLessThan(500, $response->getStatusCode(), 'GET /admin/login returned 5xx');
    }

    // =========================================================================
    // Core assertion
    // =========================================================================

    private function assertRouteNo500(string $uri): void
    {
        $this->actingAsAdmin();

        $url = $this->resolveUri($uri);
        if ($url === null) {
            return; // already marked skipped
        }

        $response = $this->get($url);
        $status = $response->getStatusCode();

        $this->assertLessThan(
            500,
            $status,
            "GET {$url} returned {$status} (expected < 500)"
        );
    }
}
