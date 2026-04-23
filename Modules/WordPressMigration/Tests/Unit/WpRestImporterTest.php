<?php

namespace Modules\WordPressMigration\Tests\Unit;

use Modules\WordPressMigration\DTOs\WpRestImportResult;
use Modules\WordPressMigration\Services\Http\WpAppPasswordCredential;
use Modules\WordPressMigration\Services\Importers\WpRestImporter;
use Modules\WordPressMigration\Tests\Support\FakeHttpProbeFetcher;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Unit coverage for the Phase 3 WordPress REST importer.
 *
 * Scenarios map to the enumerated endpoints the importer consumes
 * (`/posts`, `/pages`, `/media`, `/categories`, `/tags`, `/users`,
 * `/comments`, `/menus`). Each test pins exactly the responses the
 * code under test requests, so accidental URL drift surfaces as a
 * transport failure rather than a silent behavior change.
 */
class WpRestImporterTest extends TestCase
{
    #[Test]
    public function happy_path_collects_posts_pages_media_comments_menus_and_enriches_via_term_user_lookups(): void
    {
        $base = 'https://wp.example';
        $table = [
            "{$base}/wp-json" => self::okJson(['name' => 'Test', 'namespaces' => ['wp/v2']]),

            // Enrichers first (importer fetches them before posts+pages)
            "{$base}/wp-json/wp/v2/categories?per_page=100&page=1" => self::okJson([
                ['id' => 11, 'name' => 'News', 'slug' => 'news'],
                ['id' => 12, 'name' => 'Updates', 'slug' => 'updates'],
            ]),
            "{$base}/wp-json/wp/v2/tags?per_page=100&page=1" => self::okJson([
                ['id' => 31, 'name' => 'launch', 'slug' => 'launch'],
                ['id' => 32, 'name' => 'featured', 'slug' => 'featured'],
            ]),
            "{$base}/wp-json/wp/v2/users?per_page=100&page=1" => self::okJson([
                ['id' => 5, 'name' => 'Jane Doe', 'slug' => 'jane'],
                ['id' => 7, 'name' => 'John Smith', 'slug' => 'john'],
            ]),
            "{$base}/wp-json/wp/v2/media?per_page=100&page=1" => self::okJson([
                ['id' => 101, 'source_url' => 'https://wp.example/wp-content/uploads/hero.jpg'],
            ]),
            "{$base}/wp-json/wp/v2/comments?per_page=100&page=1" => self::okJson([
                ['id' => 201, 'post' => 1001, 'content' => ['rendered' => '<p>Nice!</p>']],
            ]),
            "{$base}/wp-json/wp/v2/menus?per_page=100" => self::okJson([
                ['id' => 7, 'name' => 'Primary', 'slug' => 'primary'],
            ]),

            // Posts + pages
            "{$base}/wp-json/wp/v2/posts?per_page=100&page=1" => self::okJson([
                [
                    'id' => 1001,
                    'date_gmt' => '2026-04-10T12:00:00',
                    'title' => ['rendered' => 'Post One'],
                    'content' => ['rendered' => '<p>Body of post one.</p>'],
                    'excerpt' => ['rendered' => '<p>Short.</p>'],
                    'link' => 'https://wp.example/2026/04/post-one/',
                    'author' => 5,
                    'categories' => [11, 12],
                    'tags' => [31],
                ],
                [
                    'id' => 1002,
                    'date_gmt' => '2026-04-09T09:00:00',
                    'title' => ['rendered' => 'Post Two'],
                    'content' => ['rendered' => '<p>Body of post two.</p>'],
                    'excerpt' => ['rendered' => ''],
                    'link' => 'https://wp.example/2026/04/post-two/',
                    'author' => 7,
                    'categories' => [12],
                    'tags' => [32],
                ],
            ]),
            "{$base}/wp-json/wp/v2/pages?per_page=100&page=1" => self::okJson([
                [
                    'id' => 2001,
                    'date_gmt' => '2026-01-01T00:00:00',
                    'title' => ['rendered' => 'About'],
                    'content' => ['rendered' => '<p>About us.</p>'],
                    'excerpt' => ['rendered' => ''],
                    'link' => 'https://wp.example/about/',
                    'author' => 5,
                    'categories' => [],
                    'tags' => [],
                ],
            ]),
        ];

        $fetcher = new FakeHttpProbeFetcher($table);
        $result = (new WpRestImporter($fetcher))->walk($base);

        $this->assertSame(WpRestImportResult::STOP_COMPLETE, $result->stopReason);
        $this->assertCount(3, $result->items, 'two posts + one page');

        [$p1, $p2, $page] = $result->items;
        $this->assertSame('wp:1001', $p1->guid);
        $this->assertSame('Post One', $p1->title);
        $this->assertSame('Jane Doe', $p1->author);
        $this->assertSame(['News', 'Updates'], $p1->categories);
        $this->assertSame(['launch'], $p1->tags);
        $this->assertSame('https://wp.example/2026/04/post-one/', $p1->canonicalUrl);
        $this->assertSame('wp.example', $p1->sourceHost);
        $this->assertSame('rest', $p1->source);
        $this->assertNotNull($p1->publishedAt);
        $this->assertSame('2026-04-10T12:00:00+00:00', $p1->publishedAt->format(DATE_ATOM));

        $this->assertSame('wp:1002', $p2->guid);
        $this->assertSame('John Smith', $p2->author);
        $this->assertSame(['Updates'], $p2->categories);
        $this->assertNull($p2->excerpt, 'Empty rendered excerpt should normalize to null');

        $this->assertSame('wp:2001', $page->guid);
        $this->assertSame('About', $page->title);
        $this->assertSame([], $page->categories);

        $this->assertCount(1, $result->media);
        $this->assertCount(1, $result->comments);
        $this->assertIsArray($result->menus);
        $this->assertCount(1, $result->menus);
    }

    #[Test]
    public function unreachable_wp_json_returns_empty_result_with_unreachable_stop(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://offline.example/wp-json' => self::err('connection refused'),
        ]);

        $result = (new WpRestImporter($fetcher))->walk('https://offline.example');

        $this->assertSame(WpRestImportResult::STOP_UNREACHABLE, $result->stopReason);
        $this->assertSame([], $result->items);
    }

    #[Test]
    public function users_endpoint_401_falls_back_to_unknown_author_and_records_warning(): void
    {
        // Hardened WP sites return 401 on the users index — all other
        // endpoints still work, the post just loses its author name.
        $base = 'https://wp.example';
        $table = [
            "{$base}/wp-json" => self::okJson(['name' => 'Test']),
            "{$base}/wp-json/wp/v2/categories?per_page=100&page=1" => self::okJson([]),
            "{$base}/wp-json/wp/v2/tags?per_page=100&page=1" => self::okJson([]),
            "{$base}/wp-json/wp/v2/users?per_page=100&page=1" => ['body' => '', 'http_code' => 401, 'error' => ''],
            "{$base}/wp-json/wp/v2/media?per_page=100&page=1" => self::okJson([]),
            "{$base}/wp-json/wp/v2/comments?per_page=100&page=1" => self::okJson([]),
            "{$base}/wp-json/wp/v2/menus?per_page=100" => self::okJson([]),
            "{$base}/wp-json/wp/v2/posts?per_page=100&page=1" => self::okJson([
                [
                    'id' => 1,
                    'date_gmt' => '2026-04-01T00:00:00',
                    'title' => ['rendered' => 'Orphan'],
                    'content' => ['rendered' => '<p>body</p>'],
                    'excerpt' => ['rendered' => ''],
                    'link' => 'https://wp.example/orphan/',
                    'author' => 99,
                    'categories' => [],
                    'tags' => [],
                ],
            ]),
            "{$base}/wp-json/wp/v2/pages?per_page=100&page=1" => self::okJson([]),
        ];

        $fetcher = new FakeHttpProbeFetcher($table);
        $result = (new WpRestImporter($fetcher))->walk($base);

        $this->assertSame(WpRestImportResult::STOP_COMPLETE, $result->stopReason);
        $this->assertCount(1, $result->items);
        $this->assertNull($result->items[0]->author, 'Missing users index removes the author enrichment');
        $this->assertNotEmpty($result->warnings);
        $this->assertTrue((bool)array_filter($result->warnings, fn ($w) => str_contains($w, 'users')),
            'Warning must mention the users endpoint was unavailable'
        );
    }

    #[Test]
    public function menus_endpoint_missing_is_null_not_empty_array(): void
    {
        // `null` vs `[]` matters: the Filament UI shows "Menus not
        // exposed" for null, and "0 menus" for []. Don't flatten.
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];

        $result = (new WpRestImporter(new FakeHttpProbeFetcher($table)))->walk($base);

        $this->assertNull($result->menus);
    }

    #[Test]
    public function menus_endpoint_empty_array_is_exposed_but_empty(): void
    {
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => self::okJson([]),
        ];

        $result = (new WpRestImporter(new FakeHttpProbeFetcher($table)))->walk($base);

        $this->assertSame([], $result->menus);
    }

    #[Test]
    public function seen_guids_skip_previously_imported_posts_without_aborting_walk(): void
    {
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        // Override posts with two entries; caller has one already.
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"] = self::okJson([
            [
                'id' => 1,
                'date_gmt' => '2026-04-01T00:00:00',
                'title' => ['rendered' => 'Already imported'],
                'content' => ['rendered' => '<p>a</p>'],
                'excerpt' => ['rendered' => ''],
                'link' => 'https://wp.example/a/',
                'author' => 0, 'categories' => [], 'tags' => [],
            ],
            [
                'id' => 2,
                'date_gmt' => '2026-04-02T00:00:00',
                'title' => ['rendered' => 'Fresh'],
                'content' => ['rendered' => '<p>b</p>'],
                'excerpt' => ['rendered' => ''],
                'link' => 'https://wp.example/b/',
                'author' => 0, 'categories' => [], 'tags' => [],
            ],
        ]);

        $fetcher = new FakeHttpProbeFetcher($table);
        $result = (new WpRestImporter($fetcher))->walk($base, seenGuids: ['wp:1']);

        $this->assertCount(1, $result->items);
        $this->assertSame('wp:2', $result->items[0]->guid);
    }

    #[Test]
    public function max_items_cap_stops_mid_collection_with_max_items_reason(): void
    {
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"] = self::okJson([
            ['id' => 1, 'title' => ['rendered' => 'a'], 'content' => ['rendered' => 'a'], 'excerpt' => ['rendered' => ''], 'date_gmt' => '', 'link' => 'https://wp.example/a/', 'author' => 0, 'categories' => [], 'tags' => []],
            ['id' => 2, 'title' => ['rendered' => 'b'], 'content' => ['rendered' => 'b'], 'excerpt' => ['rendered' => ''], 'date_gmt' => '', 'link' => 'https://wp.example/b/', 'author' => 0, 'categories' => [], 'tags' => []],
            ['id' => 3, 'title' => ['rendered' => 'c'], 'content' => ['rendered' => 'c'], 'excerpt' => ['rendered' => ''], 'date_gmt' => '', 'link' => 'https://wp.example/c/', 'author' => 0, 'categories' => [], 'tags' => []],
        ]);

        $result = (new WpRestImporter(new FakeHttpProbeFetcher($table)))->walk($base, maxItems: 2);

        $this->assertSame(WpRestImportResult::STOP_MAX_ITEMS, $result->stopReason);
        $this->assertCount(2, $result->items);
    }

    #[Test]
    public function posts_pagination_walks_through_full_pages_then_stops_on_short_page(): void
    {
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];

        // Build 100 items for page 1 (full), 3 items for page 2 (short → stop).
        $page1 = [];
        for ($i = 1; $i <= 100; $i++) {
            $page1[] = self::minimalPostRow($i);
        }
        $page2 = [
            self::minimalPostRow(101),
            self::minimalPostRow(102),
            self::minimalPostRow(103),
        ];
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"] = self::okJson($page1);
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=2"] = self::okJson($page2);

        $result = (new WpRestImporter(new FakeHttpProbeFetcher($table)))->walk($base);

        $this->assertSame(WpRestImportResult::STOP_COMPLETE, $result->stopReason);
        $this->assertCount(103, $result->items);
    }

    #[Test]
    public function featured_media_resolves_to_source_url_via_media_index(): void
    {
        // A post carrying `featured_media: 101` must surface the
        // matching media row's `source_url` on the DTO — this is the
        // input the mapper needs to attach a Microweber picture row.
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        $table["{$base}/wp-json/wp/v2/media?per_page=100&page=1"] = self::okJson([
            ['id' => 101, 'source_url' => 'https://wp.example/wp-content/uploads/hero.jpg'],
            ['id' => 202, 'source_url' => 'https://wp.example/wp-content/uploads/other.png'],
        ]);
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"] = self::okJson([
            [
                'id' => 7,
                'date_gmt' => '2026-04-01T00:00:00',
                'title' => ['rendered' => 'Featured'],
                'content' => ['rendered' => '<p>body</p>'],
                'excerpt' => ['rendered' => ''],
                'link' => 'https://wp.example/featured/',
                'author' => 0,
                'categories' => [],
                'tags' => [],
                'featured_media' => 101,
            ],
        ]);

        $result = (new WpRestImporter(new FakeHttpProbeFetcher($table)))->walk($base);

        $this->assertCount(1, $result->items);
        $this->assertSame(
            'https://wp.example/wp-content/uploads/hero.jpg',
            $result->items[0]->featuredImageUrl
        );
    }

    #[Test]
    public function featured_media_zero_sentinel_yields_null_featured_image(): void
    {
        // WP encodes "no featured image" as `featured_media: 0`. The
        // DTO must treat that as null rather than "look up media 0".
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"] = self::okJson([
            [
                'id' => 8,
                'date_gmt' => '2026-04-01T00:00:00',
                'title' => ['rendered' => 'No image'],
                'content' => ['rendered' => '<p>body</p>'],
                'excerpt' => ['rendered' => ''],
                'link' => 'https://wp.example/no-image/',
                'author' => 0,
                'categories' => [],
                'tags' => [],
                'featured_media' => 0,
            ],
        ]);

        $result = (new WpRestImporter(new FakeHttpProbeFetcher($table)))->walk($base);

        $this->assertNull($result->items[0]->featuredImageUrl);
    }

    #[Test]
    public function featured_media_with_unknown_id_leaves_featured_image_null(): void
    {
        // A hardened `/media` endpoint returning 401 (or a post
        // referencing a media row WP later deleted) means the
        // lookup misses — the DTO must degrade gracefully to null
        // instead of carrying a stale or fabricated URL.
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        $table["{$base}/wp-json/wp/v2/media?per_page=100&page=1"] = ['body' => '', 'http_code' => 401, 'error' => ''];
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"] = self::okJson([
            [
                'id' => 9,
                'date_gmt' => '2026-04-01T00:00:00',
                'title' => ['rendered' => 'Orphan featured'],
                'content' => ['rendered' => '<p>body</p>'],
                'excerpt' => ['rendered' => ''],
                'link' => 'https://wp.example/orphan-featured/',
                'author' => 0,
                'categories' => [],
                'tags' => [],
                'featured_media' => 99999,
            ],
        ]);

        $result = (new WpRestImporter(new FakeHttpProbeFetcher($table)))->walk($base);

        $this->assertNull($result->items[0]->featuredImageUrl);
    }

    #[Test]
    public function rendered_content_html_is_preserved_verbatim_including_gutenberg_markup(): void
    {
        // The Gutenberg renderer is authoritative — the importer
        // must not strip block comments, normalize whitespace,
        // re-encode entities, or filter tags. This test pins the
        // passthrough by feeding a payload carrying the three
        // most commonly-eaten constructs (block comments,
        // <pre> whitespace, HTML entities) and asserting byte
        // equality after a trim of leading/trailing whitespace
        // only (the WP renderer itself trims edges).
        $base = 'https://wp.example';
        $gutenberg = <<<'HTML'
<!-- wp:paragraph -->
<p class="has-text-align-center">Hello &mdash; world &amp; friends.</p>
<!-- /wp:paragraph -->

<!-- wp:preformatted -->
<pre class="wp-block-preformatted">line1
    indented
        deeper</pre>
<!-- /wp:preformatted -->

<!-- wp:html -->
<script>/* do not strip me */</script>
<!-- /wp:html -->
HTML;
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"] = self::okJson([
            [
                'id' => 42,
                'date_gmt' => '2026-04-01T00:00:00',
                'title' => ['rendered' => 'Gutenberg post'],
                'content' => ['rendered' => $gutenberg],
                'excerpt' => ['rendered' => '<p>summary with <em>emphasis</em> &amp; symbols</p>'],
                'link' => 'https://wp.example/gutenberg/',
                'author' => 0,
                'categories' => [],
                'tags' => [],
                'featured_media' => 0,
            ],
        ]);

        $result = (new WpRestImporter(new FakeHttpProbeFetcher($table)))->walk($base);

        $this->assertCount(1, $result->items);
        $this->assertSame(trim($gutenberg), $result->items[0]->html);
        $this->assertStringContainsString('<!-- wp:paragraph -->', $result->items[0]->html);
        $this->assertStringContainsString('<!-- /wp:html -->', $result->items[0]->html);
        $this->assertStringContainsString("line1\n    indented\n        deeper", $result->items[0]->html);
        $this->assertStringContainsString('<script>', $result->items[0]->html);
        $this->assertSame(
            '<p>summary with <em>emphasis</em> &amp; symbols</p>',
            $result->items[0]->excerpt
        );
    }

    #[Test]
    public function anonymous_importer_sends_no_authorization_header_on_any_request(): void
    {
        // Public-only WP sites don't need credentials — the importer
        // must remain usable without any auth and MUST NOT synthesize
        // a header on its own.
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];

        $fetcher = new FakeHttpProbeFetcher($table);
        (new WpRestImporter($fetcher))->walk($base);

        $this->assertNotEmpty($fetcher->fetched, 'sanity: the walk actually hit endpoints');
        $this->assertSame(
            array_fill(0, count($fetcher->fetched), null),
            $fetcher->fetchedAuth,
            'every fetch must be unauthenticated when no credential is supplied'
        );
    }

    #[Test]
    public function authenticated_importer_attaches_basic_authorization_header_to_every_request(): void
    {
        // WP 5.6+ app-password path: every request (enrichers + paginated
        // posts/pages) must carry the Basic header. Attaching it only to
        // the root probe would silently fail against sites that protect
        // /wp-json/wp/v2/* behind auth while leaving /wp-json public.
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];

        $credential = WpAppPasswordCredential::of('admin', 'abcd efgh ijkl mnop qrst uvwx');
        $fetcher = new FakeHttpProbeFetcher($table);
        (new WpRestImporter($fetcher, $credential))->walk($base);

        $this->assertNotEmpty($fetcher->fetched);
        $expected = 'Basic ' . base64_encode('admin:abcdefghijklmnopqrstuvwx');
        foreach ($fetcher->fetchedAuth as $authHeader) {
            $this->assertSame($expected, $authHeader, 'every request must be authenticated');
        }
    }

    #[Test]
    public function x_wp_totalpages_header_stops_walk_without_fetching_past_the_reported_end(): void
    {
        // Server says "1 total page" even though page 1 returned 100
        // rows (perfectly filling per_page). Without the header, the
        // short-page heuristic would force a page-2 request to notice
        // the end. With the header, we must stop immediately and the
        // unscripted page-2 URL must never be called.
        $base = 'https://wp.example';

        $page1 = [];
        for ($i = 1; $i <= 100; $i++) {
            $page1[] = self::minimalPostRow($i);
        }

        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"] = [
            'body' => json_encode($page1, JSON_UNESCAPED_SLASHES),
            'http_code' => 200,
            'error' => '',
            'headers' => ['x-wp-totalpages' => '1'],
        ];
        // Deliberately no page=2 entry — requesting it would fail
        // the "no scripted response" guard and flunk the test.

        $fetcher = new FakeHttpProbeFetcher($table);
        $result = (new WpRestImporter($fetcher))->walk($base);

        $this->assertSame(WpRestImportResult::STOP_COMPLETE, $result->stopReason);
        $this->assertCount(100, $result->items);
        foreach ($fetcher->fetched as $url) {
            $this->assertStringNotContainsString('posts?per_page=100&page=2', $url);
        }
    }

    #[Test]
    public function x_wp_totalpages_header_walks_through_all_reported_pages(): void
    {
        // Two pages reported, both full — importer must fetch both
        // and NOT peek at page 3. Proves totalPages is used as a
        // stop condition rather than ignored once we've seen page 1.
        $base = 'https://wp.example';
        $page1 = [];
        for ($i = 1; $i <= 100; $i++) {
            $page1[] = self::minimalPostRow($i);
        }
        $page2 = [];
        for ($i = 101; $i <= 150; $i++) {
            $page2[] = self::minimalPostRow($i);
        }

        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"] = [
            'body' => json_encode($page1, JSON_UNESCAPED_SLASHES),
            'http_code' => 200,
            'error' => '',
            'headers' => ['x-wp-totalpages' => '2'],
        ];
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=2"] = [
            'body' => json_encode($page2, JSON_UNESCAPED_SLASHES),
            'http_code' => 200,
            'error' => '',
            'headers' => ['x-wp-totalpages' => '2'],
        ];

        $fetcher = new FakeHttpProbeFetcher($table);
        $result = (new WpRestImporter($fetcher))->walk($base);

        $this->assertSame(WpRestImportResult::STOP_COMPLETE, $result->stopReason);
        $this->assertCount(150, $result->items);
        foreach ($fetcher->fetched as $url) {
            $this->assertStringNotContainsString('posts?per_page=100&page=3', $url);
        }
    }

    #[Test]
    public function retries_transient_5xx_on_posts_page_and_succeeds_after_backoff(): void
    {
        // Two 503s in a row should not abort the walk — the third
        // attempt succeeds and items land. Backoff is captured via
        // the sleeper so we can also pin the schedule (500 → 1000ms).
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        unset($table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"]);

        $fetcher = new FakeHttpProbeFetcher($table);
        $fetcher->queue("{$base}/wp-json/wp/v2/posts?per_page=100&page=1", [
            ['body' => 'upstream 503', 'http_code' => 503, 'error' => ''],
            ['body' => 'upstream 503', 'http_code' => 503, 'error' => ''],
            self::okJson([self::minimalPostRow(1)]),
        ]);

        $sleeps = [];
        $sleeper = function (int $ms) use (&$sleeps): void {
            $sleeps[] = $ms;
        };

        $result = (new WpRestImporter($fetcher, null, $sleeper))->walk($base);

        $this->assertSame(WpRestImportResult::STOP_COMPLETE, $result->stopReason);
        $this->assertCount(1, $result->items);
        $this->assertSame([500, 1000], $sleeps, 'exponential backoff: 500ms then 1000ms');
    }

    #[Test]
    public function retries_429_honors_retry_after_header_for_delay(): void
    {
        // WP rate limiters emit Retry-After in seconds; the importer
        // must use that value (in ms) rather than the exponential
        // default, because the server's pacing is authoritative.
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        unset($table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"]);

        $fetcher = new FakeHttpProbeFetcher($table);
        $fetcher->queue("{$base}/wp-json/wp/v2/posts?per_page=100&page=1", [
            ['body' => '', 'http_code' => 429, 'error' => '', 'headers' => ['retry-after' => '2']],
            self::okJson([self::minimalPostRow(1)]),
        ]);

        $sleeps = [];
        $sleeper = function (int $ms) use (&$sleeps): void {
            $sleeps[] = $ms;
        };

        $result = (new WpRestImporter($fetcher, null, $sleeper))->walk($base);

        $this->assertSame(WpRestImportResult::STOP_COMPLETE, $result->stopReason);
        $this->assertCount(1, $result->items);
        $this->assertSame([2000], $sleeps, 'Retry-After: 2 seconds → 2000ms sleep');
    }

    #[Test]
    public function retry_exhausts_after_max_attempts_and_gives_up(): void
    {
        // Three 500s in a row = MAX_RETRIES reached. The walk must
        // not hang; the endpoint is treated as unavailable and the
        // walk continues to pages/menus.
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        unset($table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"]);

        $fetcher = new FakeHttpProbeFetcher($table);
        $fetcher->queue("{$base}/wp-json/wp/v2/posts?per_page=100&page=1", [
            ['body' => '', 'http_code' => 500, 'error' => ''],
            ['body' => '', 'http_code' => 500, 'error' => ''],
            ['body' => '', 'http_code' => 500, 'error' => ''],
        ]);

        $sleeps = [];
        $result = (new WpRestImporter($fetcher, null, function (int $ms) use (&$sleeps): void {
            $sleeps[] = $ms;
        }))->walk($base);

        $this->assertSame(WpRestImportResult::STOP_COMPLETE, $result->stopReason);
        $this->assertSame([], $result->items, 'posts unreachable → nothing collected');
        $this->assertCount(2, $sleeps, 'two sleeps between three attempts');
    }

    #[Test]
    public function permanent_4xx_does_not_retry(): void
    {
        // 401 (auth denied) and 404 (endpoint missing) are permanent
        // for the duration of a walk. Retrying them burns budget on
        // a response that will never change. Only one fetch attempt
        // should be recorded for the posts URL.
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"] = [
            'body' => '', 'http_code' => 401, 'error' => '',
        ];

        $sleeps = [];
        $fetcher = new FakeHttpProbeFetcher($table);
        (new WpRestImporter($fetcher, null, function (int $ms) use (&$sleeps): void {
            $sleeps[] = $ms;
        }))->walk($base);

        $this->assertSame([], $sleeps, '401 must not trigger a retry');
        $postsHits = array_filter(
            $fetcher->fetched,
            fn (string $u) => str_contains($u, 'wp/v2/posts?per_page=100&page=1')
        );
        $this->assertCount(1, $postsHits, 'exactly one attempt for a permanent 4xx');
    }

    #[Test]
    public function transport_failure_is_retried_then_gives_up(): void
    {
        // DNS / TLS / connection-reset errors surface as http_code=0
        // with a non-empty `error`. Those are usually transient so
        // they get the retry treatment, but the budget still caps.
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        unset($table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"]);

        $fetcher = new FakeHttpProbeFetcher($table);
        $fetcher->queue("{$base}/wp-json/wp/v2/posts?per_page=100&page=1", [
            ['body' => '', 'http_code' => 0, 'error' => 'connection reset'],
            ['body' => '', 'http_code' => 0, 'error' => 'connection reset'],
            self::okJson([self::minimalPostRow(7)]),
        ]);

        $sleeps = [];
        $result = (new WpRestImporter($fetcher, null, function (int $ms) use (&$sleeps): void {
            $sleeps[] = $ms;
        }))->walk($base);

        $this->assertCount(1, $result->items);
        $this->assertSame([500, 1000], $sleeps);
    }

    #[Test]
    public function rendered_title_handles_both_wrapped_and_raw_string_shapes(): void
    {
        // Some fixtures (older WP, custom endpoints) emit raw strings
        // instead of `{"rendered": "..."}`. The importer tolerates
        // both shapes so it doesn't crash on minor emission variance.
        $base = 'https://wp.example';
        $table = self::minimalTable($base) + [
            "{$base}/wp-json/wp/v2/menus?per_page=100" => ['body' => '', 'http_code' => 404, 'error' => ''],
        ];
        $table["{$base}/wp-json/wp/v2/posts?per_page=100&page=1"] = self::okJson([
            [
                'id' => 42,
                'date_gmt' => '2026-04-01T00:00:00',
                'title' => 'Plain string title',
                'content' => 'Plain string body',
                'excerpt' => '',
                'link' => 'https://wp.example/p/',
                'author' => 0, 'categories' => [], 'tags' => [],
            ],
        ]);

        $result = (new WpRestImporter(new FakeHttpProbeFetcher($table)))->walk($base);

        $this->assertCount(1, $result->items);
        $this->assertSame('Plain string title', $result->items[0]->title);
        $this->assertSame('Plain string body', $result->items[0]->html);
    }

    /**
     * Minimal scripted table for an importer walk that only exercises
     * posts/pages routing — every enricher resolves to an empty list.
     * Tests that care about a specific enricher override the relevant
     * entry. Menus is deliberately NOT included here so callers pick
     * between the "endpoint missing" (404) and "exposed but empty"
     * ([]) shapes explicitly.
     */
    private static function minimalTable(string $base): array
    {
        return [
            "{$base}/wp-json" => self::okJson(['name' => 'Test']),
            "{$base}/wp-json/wp/v2/categories?per_page=100&page=1" => self::okJson([]),
            "{$base}/wp-json/wp/v2/tags?per_page=100&page=1" => self::okJson([]),
            "{$base}/wp-json/wp/v2/users?per_page=100&page=1" => self::okJson([]),
            "{$base}/wp-json/wp/v2/media?per_page=100&page=1" => self::okJson([]),
            "{$base}/wp-json/wp/v2/comments?per_page=100&page=1" => self::okJson([]),
            "{$base}/wp-json/wp/v2/posts?per_page=100&page=1" => self::okJson([]),
            "{$base}/wp-json/wp/v2/pages?per_page=100&page=1" => self::okJson([]),
        ];
    }

    private static function minimalPostRow(int $id): array
    {
        return [
            'id' => $id,
            'date_gmt' => '2026-04-01T00:00:00',
            'title' => ['rendered' => 'Post ' . $id],
            'content' => ['rendered' => '<p>body ' . $id . '</p>'],
            'excerpt' => ['rendered' => ''],
            'link' => 'https://wp.example/p-' . $id . '/',
            'author' => 0,
            'categories' => [],
            'tags' => [],
        ];
    }

    private static function okJson(array $payload): array
    {
        return [
            'body' => json_encode($payload, JSON_UNESCAPED_SLASHES),
            'http_code' => 200,
            'error' => '',
        ];
    }

    private static function err(string $msg): array
    {
        return ['body' => '', 'http_code' => 0, 'error' => $msg];
    }
}
