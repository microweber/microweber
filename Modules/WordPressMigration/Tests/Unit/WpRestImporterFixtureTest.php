<?php

namespace Modules\WordPressMigration\Tests\Unit;

use Modules\WordPressMigration\DTOs\WpRestImportResult;
use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;
use Modules\WordPressMigration\Services\Http\WpAppPasswordCredential;
use Modules\WordPressMigration\Services\Importers\WpRestImporter;
use Modules\WordPressMigration\Tests\Support\FakeHttpProbeFetcher;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Fixture-driven coverage for {@see WpRestImporter}.
 *
 * The companion {@see WpRestImporterTest} exercises individual parser
 * and retry branches with inline JSON so failures localize to a named
 * case. This suite runs the same importer against recorded
 * `tests/fixtures/wp/wp-json/*.json` bodies — the exact JSON a real
 * WordPress install emits, including `_links`, `guid`, `meta` and
 * other side fields we deliberately ignore. Keeping the fixtures on
 * disk means:
 *
 *   - A future Dusk harness can mount the same files behind the WP
 *     fixture router (see `tests/fixtures/wp/router.php`) without
 *     re-shaping them, so the unit-level bytes match the live probe.
 *   - Schema drift in a WP emission (e.g. new keys) surfaces here
 *     rather than producing a mystery diff against a hand-crafted
 *     inline payload.
 *
 * Two modes are exercised:
 *   - **Public**: anon walk, all endpoints exposed.
 *   - **Authed (app-password)**: same fixtures with a credential;
 *     plus a hardened-site scenario where `/users` is 401 for anon
 *     but unlocks with the Basic header.
 */
class WpRestImporterFixtureTest extends TestCase
{
    private const FIXTURE_DIR = __DIR__ . '/../../../../tests/fixtures/wp/wp-json';
    private const BASE = 'https://wp.example';

    private static function fixture(string $name): string
    {
        $path = self::FIXTURE_DIR . '/' . $name;
        $body = @file_get_contents($path);
        if ($body === false) {
            throw new \RuntimeException("Fixture missing: {$path}");
        }
        return $body;
    }

    /**
     * Build the scripted response table for a fully-public walk —
     * every endpoint resolves to the recorded fixture on disk.
     *
     * @return array<string, array{body: string, http_code: int, error: string, headers?: array<string, string>}>
     */
    private static function publicTable(): array
    {
        $base = self::BASE;
        return [
            "{$base}/wp-json" => self::ok(self::fixture('root.json')),
            "{$base}/wp-json/wp/v2/categories?per_page=100&page=1" => self::ok(self::fixture('categories.json')),
            "{$base}/wp-json/wp/v2/tags?per_page=100&page=1" => self::ok(self::fixture('tags.json')),
            "{$base}/wp-json/wp/v2/users?per_page=100&page=1" => self::ok(self::fixture('users.json')),
            "{$base}/wp-json/wp/v2/media?per_page=100&page=1" => self::ok(self::fixture('media.json')),
            "{$base}/wp-json/wp/v2/comments?per_page=100&page=1" => self::ok(self::fixture('comments.json')),
            "{$base}/wp-json/wp/v2/menus?per_page=100" => self::ok(self::fixture('menus.json')),
            "{$base}/wp-json/wp/v2/posts?per_page=100&page=1" => self::ok(self::fixture('posts-page-1.json')),
            "{$base}/wp-json/wp/v2/pages?per_page=100&page=1" => self::ok(self::fixture('pages.json')),
        ];
    }

    #[Test]
    public function public_mode_walks_recorded_fixtures_end_to_end(): void
    {
        $fetcher = new FakeHttpProbeFetcher(self::publicTable());
        $result = (new WpRestImporter($fetcher))->walk(self::BASE);

        $this->assertSame(WpRestImportResult::STOP_COMPLETE, $result->stopReason);
        $this->assertCount(3, $result->items, '2 posts + 1 page from the fixtures');

        [$post1, $post2, $page] = $result->items;

        // Post 1 — every enrichment resolves: categories by id→name,
        // tags by id→name, author by id→display name, featured image
        // resolved from /media id 101 to its source_url.
        $this->assertSame('wp:1001', $post1->guid);
        $this->assertSame('Launch day: what shipped', $post1->title);
        $this->assertStringContainsString('<!-- wp:paragraph -->', $post1->html, 'Gutenberg comments preserved verbatim');
        $this->assertStringContainsString('&mdash;', $post1->html, 'entities not double-decoded');
        $this->assertSame('<p>A rundown of everything that went live.</p>', $post1->excerpt);
        $this->assertSame('Jane Doe', $post1->author);
        $this->assertSame('jane', $post1->authorSlug);
        $this->assertSame(['News', 'Tech'], $post1->categories);
        $this->assertSame(['news', 'tech'], $post1->categorySlugs);
        $this->assertSame(['Launch', 'Featured'], $post1->tags);
        $this->assertSame(['launch', 'featured'], $post1->tagSlugs);
        $this->assertSame(
            'https://wp.example/wp-content/uploads/2026/04/hero.jpg',
            $post1->featuredImageUrl,
            'featured_media id → /media[id].source_url'
        );
        $this->assertSame('https://wp.example/2026/04/post-one/', $post1->canonicalUrl);
        $this->assertSame('wp.example', $post1->sourceHost);
        $this->assertSame('rest', $post1->source);
        $this->assertSame('2026-04-10T12:00:00+00:00', $post1->publishedAt?->format(DATE_ATOM));

        // Post 2 — empty excerpt normalizes to null; featured_media=0
        // means the DTO must carry null rather than the media[0] miss.
        $this->assertSame('wp:1002', $post2->guid);
        $this->assertSame('John Smith', $post2->author);
        $this->assertSame('john', $post2->authorSlug);
        $this->assertNull($post2->excerpt);
        $this->assertNull($post2->featuredImageUrl);
        $this->assertSame(['Tech'], $post2->categories);

        // Page — separate collection, same shape mapper-side.
        $this->assertSame('wp:2001', $page->guid);
        $this->assertSame('About', $page->title);
        $this->assertSame([], $page->categories);

        // Side-loaded collections land for the Phase 7 rehoster and
        // the Filament preview.
        $this->assertCount(2, $result->media);
        $this->assertCount(1, $result->comments);
        $this->assertIsArray($result->menus);
        $this->assertCount(1, $result->menus);
        $this->assertSame(3, $result->categories ? count($result->categories) : 0, 'raw categories surfaced for TaxonomyIndex::prime()');
        $this->assertCount(2, $result->tags);
        $this->assertCount(2, $result->users);
        $this->assertSame([], $result->warnings, 'no warnings when every endpoint is exposed');
    }

    #[Test]
    public function authed_mode_attaches_basic_header_to_every_fixture_request(): void
    {
        // App-password mode: the Authorization header must be attached
        // to every request — enrichers AND paginated posts/pages. A
        // regression that sends it on the root probe only would silently
        // fail on hardened sites that protect /wp/v2/* but expose /wp-json.
        $credential = WpAppPasswordCredential::of('editor', 'abcd efgh ijkl mnop qrst uvwx');
        $expected = 'Basic ' . base64_encode('editor:abcdefghijklmnopqrstuvwx');

        $fetcher = new FakeHttpProbeFetcher(self::publicTable());
        $result = (new WpRestImporter($fetcher, $credential))->walk(self::BASE);

        $this->assertSame(WpRestImportResult::STOP_COMPLETE, $result->stopReason);
        $this->assertCount(3, $result->items);

        $this->assertNotEmpty($fetcher->fetched, 'sanity: walk actually hit endpoints');
        foreach ($fetcher->fetchedAuth as $i => $authHeader) {
            $this->assertSame(
                $expected,
                $authHeader,
                'Every request must carry the Basic header — missing on: ' . $fetcher->fetched[$i]
            );
        }
    }

    #[Test]
    public function authed_mode_unlocks_hardened_users_endpoint_while_anon_falls_back(): void
    {
        // Realistic hardened setup: the site exposes posts/pages
        // publicly but gates /wp/v2/users behind auth. An anonymous
        // walk should gracefully degrade (no author name) and record
        // a warning; an authed walk should get the full users.json
        // body back and keep the author enrichment.
        $credential = WpAppPasswordCredential::of('editor', 'abcd efgh ijkl mnop qrst uvwx');
        $expected = 'Basic ' . base64_encode('editor:abcdefghijklmnopqrstuvwx');

        // --- Anon walk: /users returns 401 body from fixture.
        $anonTable = self::publicTable();
        $anonTable[self::BASE . '/wp-json/wp/v2/users?per_page=100&page=1'] = [
            'body' => self::fixture('users-401.json'),
            'http_code' => 401,
            'error' => '',
        ];
        $anon = new WpRestImporter(new FakeHttpProbeFetcher($anonTable));
        $anonResult = $anon->walk(self::BASE);
        $this->assertSame(WpRestImportResult::STOP_COMPLETE, $anonResult->stopReason);
        $this->assertNotEmpty(
            array_filter($anonResult->warnings, fn ($w) => str_contains($w, 'users')),
            'Hardened users endpoint must surface an operator-facing warning'
        );
        $post1Anon = $anonResult->items[0];
        $this->assertNull($post1Anon->author, 'No users index → no author name');
        $this->assertNull($post1Anon->authorSlug);

        // --- Authed walk: fetcher is sensitive to the header, returns
        // users fixture only when Basic auth is attached.
        $authedFetcher = $this->authSensitiveFetcher($expected);
        $authed = new WpRestImporter($authedFetcher, $credential);
        $authedResult = $authed->walk(self::BASE);
        $this->assertSame(WpRestImportResult::STOP_COMPLETE, $authedResult->stopReason);
        $this->assertCount(3, $authedResult->items);
        $post1Authed = $authedResult->items[0];
        $this->assertSame('Jane Doe', $post1Authed->author);
        $this->assertSame('jane', $post1Authed->authorSlug);
        $this->assertSame([], $authedResult->warnings, 'Authed walk recovers enrichment — no warnings');

        // Defence-in-depth: the same auth-sensitive fetcher, used
        // without a credential, would hit the 401 branch. This proves
        // the credential itself is what flipped the outcome.
        $anonAgainstSameSite = new WpRestImporter($this->authSensitiveFetcher($expected));
        $this->assertNull(
            $anonAgainstSameSite->walk(self::BASE)->items[0]->author,
            'Without credential, the hardened /users endpoint stays locked.'
        );
    }

    #[Test]
    public function fixture_bytes_round_trip_through_json_decode_without_error(): void
    {
        // Pure file-health check so a malformed fixture is caught here
        // (named failure) rather than as a downstream "importer
        // returned empty" mystery. Runs on the same bytes the other
        // fixture tests consume.
        foreach ([
            'root.json',
            'categories.json',
            'tags.json',
            'users.json',
            'users-401.json',
            'media.json',
            'comments.json',
            'menus.json',
            'posts-page-1.json',
            'pages.json',
        ] as $name) {
            $decoded = json_decode(self::fixture($name), true);
            $this->assertIsArray($decoded, "Fixture must parse as JSON: {$name}");
        }
    }

    /**
     * Return an HttpProbeFetcher whose `/wp/v2/users` response is
     * 401 for anonymous requests and 200 (with the recorded fixture)
     * for requests carrying the expected Basic auth header. Every
     * other endpoint is served from the public fixture table.
     */
    private function authSensitiveFetcher(string $expectedAuthHeader): HttpProbeFetcher
    {
        $usersUrl = self::BASE . '/wp-json/wp/v2/users?per_page=100&page=1';
        $baseTable = self::publicTable();
        $usersFixtureBody = self::fixture('users.json');
        $users401Body = self::fixture('users-401.json');

        return new class($baseTable, $usersUrl, $usersFixtureBody, $users401Body, $expectedAuthHeader) implements HttpProbeFetcher {
            /** @var list<string> */
            public array $fetched = [];

            public function __construct(
                private readonly array $table,
                private readonly string $usersUrl,
                private readonly string $usersOkBody,
                private readonly string $users401Body,
                private readonly string $expectedAuthHeader,
            ) {}

            public function fetch(string $url, int $timeout, ?string $authorization = null): array
            {
                $this->fetched[] = $url;
                if ($url === $this->usersUrl) {
                    if ($authorization === $this->expectedAuthHeader) {
                        return ['body' => $this->usersOkBody, 'http_code' => 200, 'headers' => [], 'error' => ''];
                    }
                    return ['body' => $this->users401Body, 'http_code' => 401, 'headers' => [], 'error' => ''];
                }
                if (!array_key_exists($url, $this->table)) {
                    return ['body' => '', 'http_code' => 0, 'headers' => [], 'error' => "unexpected URL: {$url}"];
                }
                $resp = $this->table[$url];
                $resp['headers'] = $resp['headers'] ?? [];
                return $resp;
            }
        };
    }

    /**
     * @return array{body: string, http_code: int, error: string}
     */
    private static function ok(string $body): array
    {
        return ['body' => $body, 'http_code' => 200, 'error' => ''];
    }
}
