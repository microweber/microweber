<?php

declare(strict_types=1);

namespace MicroweberPackages\Filament\Support;

/**
 * task-2026-05-17-378d85 / AI-784 — systemic admin-form fixture-leak guard.
 * Jira: https://microweber.atlassian.net/browse/AI-784
 *
 * Designer's Round-10 audit identified a recurring defect family:
 * PHPUnit test fixtures + lorem-ipsum cruft + before/after scenario
 * labels surface in production admin form dropdowns/lists because no
 * filter exists between `get_*()` helper output and the admin UI
 * rendering layer. AI-776 shipped a per-resource name-pattern filter
 * on the Posts admin Menus rail; AI-781 needs the same filter for
 * the Products form; future resources will keep hitting the same
 * defect class.
 *
 * This guard centralises the name-pattern blocklist + the
 * `shouldRender()` decision so every admin form that surfaces
 * fixture-prone data (menus, categories, content, tags, ...) can
 * filter via one canonical helper instead of re-inventing the
 * blocklist per resource.
 *
 * **What it filters:**
 *   - empty / whitespace-only titles (PHPUnit anonymous fixtures)
 *   - `menu test <hex>` (PHPUnit unique-name generator pattern)
 *   - `test menu` / `test page` / generic `<word> test` scenario labels
 *   - `created via module api menu` (module-API integration tests)
 *   - lorem-ipsum prefixed cruft
 *   - `after` / `before` / pure-numeric test-step labels
 *
 * **What it deliberately does NOT do:**
 *   - It is label-side only — no DB writes, no schema changes.
 *     A row hidden by this guard remains fully functional via
 *     direct API access; only the admin form UI excludes it.
 *   - It does NOT introduce an `is_visible_in_admin` column. That
 *     was one of the options designer offered in the AI-784
 *     dispatch; the chosen path is name-pattern blocking because
 *     it covers existing test data without a migration. A column
 *     remains a future option if blocklist patterns become
 *     unmanageable.
 *   - It does NOT touch seeders. The blocklist intercepts at
 *     render time, which is robust against past + future seeder
 *     leakage. Seeder audit is a separate concern (deferred per
 *     AI-784 scope).
 *
 * **How to consume from a resource:**
 *
 *     $menus = get_menus();
 *     foreach ($menus as $menu) {
 *         if (! AdminFixtureGuard::shouldRenderItem($menu['title'] ?? '')) {
 *             continue;
 *         }
 *         // ... use $menu
 *     }
 *
 * Or in array form via array_filter:
 *
 *     $menus = array_filter(get_menus(), fn ($m) =>
 *         AdminFixtureGuard::shouldRenderItem($m['title'] ?? '')
 *     );
 *
 * Reference call site: Modules/Content/Filament/Admin/ContentResource.php
 * `menusSection()` options closure (Posts admin Menus rail). When
 * adding a new caller, also add a regression-guard contract test
 * row to AdminFixtureGuard784ContractTest::fixtureLeakStrings or the
 * resource's own contract test.
 */
final class AdminFixtureGuard
{
    /**
     * Name-pattern blocklist. Regexes are case-insensitive where the
     * pattern flag does the lifting (anchored variants are explicit).
     * Add a new pattern HERE rather than inline in a resource — single
     * source of truth.
     */
    public const FIXTURE_LEAK_PATTERNS = [
        '/^menu test [0-9a-f]+$/i',              // PHPUnit unique-name menu fixture
        '/^test menu$/i',                         // generic scenario label
        '/^test page$/i',                         // page-side scenario label
        '/^test category$/i',                     // category-side scenario label
        '/^created via module api menu$/i',       // module-API integration test fixture
        '/^lorem\b/i',                            // lorem-ipsum cruft prefix
        '/^after$/i',                             // before/after test-step labels
        '/^before$/i',
        '/^\d+$/',                                // pure-numeric "titles" (123, 456) from quick-test setup
    ];

    /**
     * Faker-lorem word set (subset of the standard PHP Faker lorem
     * vocabulary). Used by looksLikeFakerLorem() to detect titles
     * where EVERY word appears in this set — strong signal that the
     * title is a Faker fixture ("Commodi Sunt", "Reprehenderit
     * Voluptate", "Asperiores Et" etc. surfaced in AI-781 audit).
     *
     * Deliberately excludes ambiguous 1-3-char Latin words that
     * overlap with English filler (`a`, `in`, `id`, `ut`, `et`, `sit`,
     * `est`) to avoid false positives on real English titles. Length
     * floor: 4 chars. Multi-word titles where every ≥4-char word is
     * in the set + no other 4+char word disqualifies the match get
     * filtered.
     */
    public const FAKER_LOREM_WORDS = [
        'accusamus', 'accusantium', 'adipisci', 'alias', 'aliquam', 'aliquid',
        'amet', 'animi', 'aperiam', 'architecto', 'asperiores', 'aspernatur',
        'assumenda', 'atque', 'autem', 'beatae', 'blanditiis', 'commodi',
        'consequatur', 'consectetur', 'consequuntur', 'corporis', 'corrupti',
        'culpa', 'cumque', 'cupiditate', 'debitis', 'deleniti', 'deserunt',
        'dicta', 'dignissimos', 'distinctio', 'dolor', 'dolore', 'dolorem',
        'doloremque', 'dolores', 'doloribus', 'dolorum', 'ducimus', 'eaque',
        'earum', 'eius', 'eligendi', 'enim', 'esse', 'eveniet', 'excepturi',
        'exercitationem', 'expedita', 'explicabo', 'facere', 'facilis',
        'fuga', 'fugiat', 'fugit', 'harum', 'illo', 'illum', 'impedit',
        'incidunt', 'inventore', 'ipsa', 'ipsam', 'ipsum', 'iste', 'itaque',
        'iure', 'iusto', 'labore', 'laboriosam', 'laborum', 'laudantium',
        'libero', 'magnam', 'magni', 'maiores', 'maxime', 'minima', 'minus',
        'mollitia', 'molestiae', 'natus', 'necessitatibus', 'nemo', 'neque',
        'nesciunt', 'nihil', 'nisi', 'nobis', 'nostrum', 'nulla', 'numquam',
        'obcaecati', 'odio', 'odit', 'officia', 'officiis', 'omnis', 'optio',
        'pariatur', 'perferendis', 'perspiciatis', 'placeat', 'porro',
        'possimus', 'praesentium', 'provident', 'quae', 'quam', 'quas',
        'quasi', 'quia', 'quibusdam', 'quidem', 'quisquam', 'quod',
        'quos', 'ratione', 'recusandae', 'reiciendis', 'repellat',
        'repellendus', 'reprehenderit', 'repudiandae', 'rerum', 'saepe',
        'sapiente', 'sequi', 'similique', 'sint', 'soluta', 'sunt',
        'suscipit', 'tempora', 'tempore', 'temporibus', 'tenetur', 'totam',
        'ullam', 'unde', 'velit', 'veniam', 'veritatis', 'vero', 'vitae',
        'voluptas', 'voluptate', 'voluptatem', 'voluptates', 'voluptatibus',
        'voluptatum',
    ];

    /**
     * Decide whether an admin form should render a row whose label
     * is `$title`. Returns true to keep the row, false to skip it.
     *
     * Pure function: no DB calls, no Laravel boot required. Safe to
     * call inside foreach loops.
     *
     * Filter order:
     *   1. empty/whitespace → false
     *   2. FIXTURE_LEAK_PATTERNS regex match → false
     *   3. looksLikeFakerLorem (every ≥4-char word in lorem set) → false
     *   4. otherwise → true
     */
    public static function shouldRenderItem(?string $title): bool
    {
        $trimmed = trim((string) $title);
        if ($trimmed === '') {
            return false;
        }
        foreach (self::FIXTURE_LEAK_PATTERNS as $pattern) {
            if (preg_match($pattern, $trimmed) === 1) {
                return false;
            }
        }
        if (self::looksLikeFakerLorem($trimmed)) {
            return false;
        }
        return true;
    }

    /**
     * Detect Faker lorem-ipsum-style 2+ word titles. Returns true when
     * the title contains at least 2 ≥4-char words AND every ≥4-char
     * word appears in FAKER_LOREM_WORDS (case-insensitive).
     *
     * Examples that match (filtered):
     *   - "Commodi Sunt"           (2 words, both ≥4 chars, both in set)
     *   - "Reprehenderit Voluptate" (2 words, both in set)
     *   - "Asperiores Et"          (et is <4 char so ignored; asperiores in set; <2 long words → does NOT match — passes)
     *   - "Aperiam placeat" lowercase (both in set)
     *
     * Examples that do NOT match (pass through):
     *   - "About Us"           (about/us not in set)
     *   - "Customer Support"   (neither in set)
     *   - "Main"               (single word — no multi-word fixture signature)
     */
    public static function looksLikeFakerLorem(string $title): bool
    {
        // Tokenize on whitespace + common separators
        $words = preg_split('/[\s\-_,.\/]+/', strtolower(trim($title))) ?: [];
        // Keep only ≥4-char tokens (filters out `et`, `a`, `in`, etc.)
        $significant = array_values(array_filter(
            $words,
            fn ($w) => mb_strlen($w) >= 4 && preg_match('/^[a-z]+$/u', $w) === 1
        ));
        if (count($significant) < 2) {
            return false;
        }
        $set = array_flip(self::FAKER_LOREM_WORDS);
        foreach ($significant as $word) {
            if (! isset($set[$word])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Convenience: filter an array of associative arrays by their
     * title field. Each element must carry a string-keyed `title`
     * key (the standard shape from `get_menus()`, `get_categories()`,
     * `get_content()` etc.). Returns a re-indexed array of survivors.
     */
    public static function filterByTitle(array $rows, string $titleKey = 'title'): array
    {
        return array_values(array_filter(
            $rows,
            fn ($row) => is_array($row) && self::shouldRenderItem($row[$titleKey] ?? null)
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // task-2026-05-17-f15cce / AI-860 — PUBLIC-side fixture-slug guard.
    // Family extension: AdminFixtureGuard was admin-only originally; the
    // AI-860 audit found PHPUnit fixture `CheckoutResourceTest Product`
    // leaking to PUBLIC /shop + product-detail. Same family signature as
    // AI-776 / AI-781 / AI-844 (Faker-data-leak family is cross-surface,
    // admin + public — codified post-Round 14 RSS audit). The 4 patterns
    // below cover the empirical fixture-slug shapes observed: PHPUnit
    // resource-test products, Faker-seeded slugs, raw seeder output.
    //
    // Consumed at:
    //   - Modules/Shop/Livewire/ShopComponent.php product query (LIKE)
    //   - src/MicroweberPackages/App/Http/Controllers/FrontendController.php
    //     content lookup short-circuit (regex via isFixtureSlug())
    //
    // Add a new pattern to BOTH arrays in lockstep — LIKE-form for
    // Eloquent NOT LIKE filters at the query layer; regex-form for the
    // pure-PHP isFixtureSlug() probe at the route-resolution layer.
    // ─────────────────────────────────────────────────────────────────

    /**
     * LIKE-pattern form for Eloquent NOT LIKE filters. Case-insensitive
     * via MySQL/SQLite collation defaults; `%` wildcards on both sides
     * so the pattern matches the substring anywhere in the column value.
     */
    public const FIXTURE_SLUG_LIKE_PATTERNS = [
        '%test%product%',          // CheckoutResourceTest Product, OrderTestProduct, etc.
        '%checkoutresourcetest%',  // CheckoutResourceTest fixture family
        '%faker%',                 // any Faker-seeded slug
        '%seeder%',                // raw Seeder leak
    ];

    /**
     * Regex form for the pure-PHP isFixtureSlug() check. Case-insensitive
     * via the `i` flag; no anchors so the pattern matches the substring
     * anywhere in the slug.
     */
    public const FIXTURE_SLUG_REGEX_PATTERNS = [
        '/test.*product/i',
        '/checkoutresourcetest/i',
        '/faker/i',
        '/seeder/i',
    ];

    /**
     * Decide whether a public URL slug looks like a PHPUnit / Faker /
     * Seeder fixture leak. Used by FrontendController to short-circuit
     * content resolution with abort(404) BEFORE the slug hits the
     * content_manager->get_by_url() lookup.
     *
     * Pure function: no DB calls, no Laravel boot required.
     *
     * Filter order:
     *   1. null / empty / whitespace-only → false (no slug to filter)
     *   2. FIXTURE_SLUG_REGEX_PATTERNS match → true (block)
     *   3. otherwise → false (legitimate slug)
     *
     * NOTE: returns true when the slug LOOKS like fixture leak (i.e.
     * SHOULD be blocked). Opposite polarity from shouldRenderItem() —
     * which returns true for "keep this item". Naming reflects the
     * call-site intent: `if (isFixtureSlug($slug)) { abort(404); }`.
     */
    public static function isFixtureSlug(?string $slug): bool
    {
        if ($slug === null) {
            return false;
        }
        $trimmed = trim($slug);
        if ($trimmed === '') {
            return false;
        }
        foreach (self::FIXTURE_SLUG_REGEX_PATTERNS as $pattern) {
            if (preg_match($pattern, $trimmed) === 1) {
                return true;
            }
        }
        return false;
    }
}
