<?php

declare(strict_types=1);

namespace Tests\Feature;

use MicroweberPackages\Filament\Support\AdminFixtureGuard;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-f15cce / AI-860 — PUBLIC fixture-slug guard.
 * Jira: https://microweber.atlassian.net/browse/AI-860
 *
 * Designer's Round 22 commerce-conversion audit caught PHPUnit fixture
 * product "CheckoutResourceTest Product" leaking to public /shop AND
 * resolving on its own auto-generated URL /CheckoutResourceTest-Product.
 * Same family signature as the admin-side AdminFixtureGuard (AI-776 /
 * AI-781 / AI-844 + Faker-data-leak family is cross-surface, codified
 * post-Round 14 RSS audit). Public blast radius is the actionable
 * concern here: SEO crawl, social-share preview, first-visit trust.
 *
 * Fix shape (Slice A):
 *   - AdminFixtureGuard::isFixtureSlug(?string $slug): bool — public
 *     helper + FIXTURE_SLUG_LIKE_PATTERNS / FIXTURE_SLUG_REGEX_PATTERNS
 *     constants for the 4 fixture-shape families.
 *   - Modules/Shop/Livewire/ShopComponent.php — LIKE filter on both
 *     $productsQuery + $productsQueryAll across title + url columns.
 *   - src/MicroweberPackages/App/Http/Controllers/FrontendController.php
 *     — abort(404) short-circuit immediately after $page_url is
 *     finalized, BEFORE content_manager->get_by_url() lookup.
 *
 * Acceptance gate (verified at HEAD via curl):
 *   - curl /shop | grep -c "CheckoutResourceTest" = 0
 *   - curl /CheckoutResourceTest-Product = 404
 *   - curl /CheckoutResourceTest-Product-1 = 404
 *   - real merchant products still render (additive filter)
 *   - admin AdminFixtureGuard unchanged (shouldRenderItem / looksLikeFakerLorem
 *     / filterByTitle all still exist + carry pre-AI-860 behaviour)
 *
 * 4-group structure: A = isFixtureSlug() unit via DataProvider over
 * fixture + legitimate slug shapes; B = ShopComponent.php source-presence
 * pin (LIKE filter on both surfaces); C = FrontendController.php
 * source-presence pin (abort(404) + isFixtureSlug call after $page_url
 * finalisation); D = pattern-set integrity (LIKE/regex arrays parity +
 * constant shapes).
 */
class PublicF15cceAI860FixtureSlugLeakContractTest extends TestCase
{
    /**
     * Pre-strip language comments (LESSONS selector-self-match UNIFORMITY
     * RULE, 18+ session-recurrences): every negative source-assertion
     * must pre-strip language comments so docblock prose doesn't
     * self-match the legacy pattern being absence-asserted.
     */
    private function stripPhpComments(string $source): string
    {
        $source = preg_replace('~/\*.*?\*/~s', '', $source);
        $source = preg_replace('~//[^\n]*~', '', (string) $source);
        return (string) $source;
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — AdminFixtureGuard::isFixtureSlug() unit (DataProvider)
    // ─────────────────────────────────────────────────────────────────────

    public static function fixtureSlugCases(): array
    {
        return [
            'CheckoutResourceTest exact'            => ['CheckoutResourceTest-Product', true],
            'CheckoutResourceTest with suffix'      => ['CheckoutResourceTest-Product-1', true],
            'CheckoutResourceTest lowercase'        => ['checkoutresourcetest-product', true],
            'test-product generic pattern'          => ['test-product-foo', true],
            'OrderTest Product variant'             => ['OrderTest-Product', true],
            'faker substring'                       => ['faker-seeded-page', true],
            'seeder substring'                      => ['my-seeder-output', true],
            'uppercase FAKER'                       => ['FAKER-fixture', true],
            'mixed-case Seeder'                     => ['SeederFoo', true],
            // Legitimate slugs must NOT be classified as fixture
            'about-us page'                         => ['about-us', false],
            'contact page'                          => ['contact', false],
            'my-product-listing'                    => ['my-product-listing', false],
            'best-selling-products'                 => ['best-selling-products', false],
            'product without test'                  => ['premium-coffee-beans', false],
            'blog'                                  => ['blog', false],
            'empty string'                          => ['', false],
            'whitespace only'                       => ['   ', false],
            'null'                                  => [null, false],
        ];
    }

    #[Test]
    #[DataProvider('fixtureSlugCases')]
    public function is_fixture_slug_classifies_correctly(?string $slug, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AdminFixtureGuard::isFixtureSlug($slug),
            sprintf('isFixtureSlug(%s) must return %s', json_encode($slug), $expected ? 'true' : 'false')
        );
    }

    #[Test]
    public function fixture_slug_like_patterns_constant_carries_four_patterns(): void
    {
        $patterns = AdminFixtureGuard::FIXTURE_SLUG_LIKE_PATTERNS;
        $this->assertIsArray($patterns);
        $this->assertCount(4, $patterns, 'AI-860 LIKE pattern set should carry exactly 4 fixture families (test-product, checkoutresourcetest, faker, seeder).');
        $this->assertContains('%test%product%', $patterns);
        $this->assertContains('%checkoutresourcetest%', $patterns);
        $this->assertContains('%faker%', $patterns);
        $this->assertContains('%seeder%', $patterns);
    }

    #[Test]
    public function fixture_slug_regex_patterns_constant_carries_four_patterns_in_parity_with_like(): void
    {
        $regexes = AdminFixtureGuard::FIXTURE_SLUG_REGEX_PATTERNS;
        $this->assertIsArray($regexes);
        $this->assertCount(4, $regexes, 'AI-860 regex pattern set must mirror the LIKE pattern set 1:1 (parity rule documented in AdminFixtureGuard docblock).');
        // Confirm each regex is a valid PCRE pattern with the /i flag.
        foreach ($regexes as $regex) {
            $this->assertSame(1, preg_match('~^/.*/[a-z]*$~', $regex), sprintf('Pattern %s should be a delimited regex.', $regex));
            $this->assertStringContainsString('i', substr($regex, strrpos($regex, '/') + 1), sprintf('Pattern %s should carry the case-insensitive flag.', $regex));
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — ShopComponent.php source-presence
    // ─────────────────────────────────────────────────────────────────────

    private function shopComponentSource(): string
    {
        return (string) file_get_contents(base_path('Modules/Shop/Livewire/ShopComponent.php'));
    }

    #[Test]
    public function shop_component_imports_admin_fixture_guard(): void
    {
        $source = $this->shopComponentSource();
        $this->assertStringContainsString(
            'use MicroweberPackages\Filament\Support\AdminFixtureGuard;',
            $source,
            'ShopComponent must import AdminFixtureGuard so the AI-860 LIKE filter can reference FIXTURE_SLUG_LIKE_PATTERNS.'
        );
    }

    #[Test]
    public function shop_component_applies_fixture_filter_to_products_query(): void
    {
        $source = $this->shopComponentSource();
        // Both surfaces: the paginated $productsQuery + the all-products
        // $productsQueryAll companion. The filter must run on BOTH so
        // derived counts (price range, tags, categories) don't include
        // the fixture rows either.
        $this->assertGreaterThanOrEqual(
            2,
            substr_count($source, 'AdminFixtureGuard::FIXTURE_SLUG_LIKE_PATTERNS'),
            'ShopComponent must reference FIXTURE_SLUG_LIKE_PATTERNS on BOTH product queries (paginated + all-products companion).'
        );
        // The filter shape — both title + url columns are filtered. Each
        // query's foreach body should carry NOT LIKE clauses for both.
        $this->assertGreaterThanOrEqual(
            2,
            substr_count($source, "->where('title', 'NOT LIKE'"),
            'ShopComponent must filter the title column on both product queries.'
        );
        $this->assertGreaterThanOrEqual(
            2,
            substr_count($source, "->where('url', 'NOT LIKE'"),
            'ShopComponent must filter the url column on both product queries.'
        );
    }

    #[Test]
    public function shop_component_carries_ai860_task_marker(): void
    {
        $source = $this->shopComponentSource();
        $this->assertStringContainsString('task-2026-05-17-f15cce', $source, 'ShopComponent must carry the AI-860 task-id marker for cross-surface grep.');
        $this->assertStringContainsString('AI-860', $source);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — FrontendController.php source-presence
    // ─────────────────────────────────────────────────────────────────────

    private function frontendControllerSource(): string
    {
        return (string) file_get_contents(base_path('src/MicroweberPackages/App/Http/Controllers/FrontendController.php'));
    }

    #[Test]
    public function frontend_controller_imports_admin_fixture_guard(): void
    {
        $source = $this->frontendControllerSource();
        $this->assertStringContainsString(
            'use MicroweberPackages\Filament\Support\AdminFixtureGuard;',
            $source,
            'FrontendController must import AdminFixtureGuard so the AI-860 short-circuit can reference isFixtureSlug().'
        );
    }

    #[Test]
    public function frontend_controller_short_circuits_fixture_slugs_with_abort_404(): void
    {
        $source = $this->frontendControllerSource();
        // Slice from $page_url_orig anchor (a line that's unique near
        // the AI-860 short-circuit) forward 600 chars.
        $anchor = strpos($source, '$page_url_orig = $page_url;');
        $this->assertNotFalse($anchor, 'Could not locate the $page_url_orig anchor that precedes the AI-860 short-circuit.');
        $slice = substr($source, $anchor, 1200);
        $stripped = $this->stripPhpComments($slice);
        $this->assertStringContainsString('AdminFixtureGuard::isFixtureSlug(', $stripped, 'FrontendController must call AdminFixtureGuard::isFixtureSlug() right after page_url finalisation.');
        $this->assertStringContainsString('abort(404)', $stripped, 'FrontendController must call abort(404) when isFixtureSlug returns true.');
        $this->assertMatchesRegularExpression(
            '/if\s*\([^)]*\$page_url[^)]*AdminFixtureGuard::isFixtureSlug\s*\(\s*\$page_url\s*\)[^)]*\)\s*\{\s*abort\(404\)\s*;\s*\}/s',
            $stripped,
            'FrontendController must short-circuit fixture slugs via the canonical `if ($page_url !== "" && AdminFixtureGuard::isFixtureSlug($page_url)) { abort(404); }` shape.'
        );
    }

    #[Test]
    public function frontend_controller_carries_ai860_task_marker(): void
    {
        $source = $this->frontendControllerSource();
        $this->assertStringContainsString('task-2026-05-17-f15cce', $source);
        $this->assertStringContainsString('AI-860', $source);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — back-compat regression guards
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function admin_fixture_guard_public_methods_remain_intact(): void
    {
        // Pre-AI-860 admin-side API must stay intact — AI-860 extends
        // the surface, does NOT replace it. AdminFixtureGuard784ContractTest
        // exercises the admin-side paths; this is a regression sentinel.
        $this->assertTrue(method_exists(AdminFixtureGuard::class, 'shouldRenderItem'));
        $this->assertTrue(method_exists(AdminFixtureGuard::class, 'looksLikeFakerLorem'));
        $this->assertTrue(method_exists(AdminFixtureGuard::class, 'filterByTitle'));
        $this->assertTrue(method_exists(AdminFixtureGuard::class, 'isFixtureSlug'));
        // Constant set preserved.
        $this->assertIsArray(AdminFixtureGuard::FIXTURE_LEAK_PATTERNS);
        $this->assertGreaterThanOrEqual(9, count(AdminFixtureGuard::FIXTURE_LEAK_PATTERNS));
        $this->assertIsArray(AdminFixtureGuard::FAKER_LOREM_WORDS);
        $this->assertGreaterThanOrEqual(100, count(AdminFixtureGuard::FAKER_LOREM_WORDS));
    }
}
