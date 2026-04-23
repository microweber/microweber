<?php

namespace Modules\WordPressMigration\Tests\Unit;

use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;
use Modules\WordPressMigration\Services\WordPressSiteProbe;
use Modules\WordPressMigration\Services\WordPressSiteProbeResult;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class WordPressSiteProbeTest extends TestCase
{
    #[Test]
    public function rest_enabled_source_classifies_as_rest_mode(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::ok(json_encode([
                'name' => 'My WP Site',
                'namespaces' => ['wp/v2', 'oembed/1.0'],
            ])),
            'https://wp.example/wp-json/wp/v2/posts?per_page=1' => self::okWithWpTotal(42, '[]'),
            'https://wp.example/wp-json/wp/v2/pages?per_page=1' => self::okWithWpTotal(7, '[]'),
            'https://wp.example/feed' => self::ok(self::sampleRss(3)),
            'https://wp.example/sitemap.xml' => self::notFound(),
            'https://wp.example/sitemap_index.xml' => self::notFound(),
            'https://wp.example/robots.txt' => self::notFound(),
        ]);

        $probe = new WordPressSiteProbe($fetcher);
        $result = $probe->probe('https://wp.example');

        $this->assertSame(WordPressSiteProbeResult::MODE_REST, $result->mode);
        $this->assertTrue($result->restEnabled);
        $this->assertSame('My WP Site', $result->restNamespace);
        $this->assertSame('wp.example', $result->sourceHost);
        $this->assertSame('https://wp.example', $result->sourceUrl);
        $this->assertContains(WordPressSiteProbeResult::MODE_REST, $result->capabilities);
        $this->assertContains(WordPressSiteProbeResult::MODE_RSS, $result->capabilities);
        $this->assertSame(42, $result->estimatedPosts);
        $this->assertSame(7, $result->estimatedPages);
        $this->assertTrue($result->isUsable());
    }

    #[Test]
    public function feed_only_source_classifies_as_rss_mode(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::notFound(),
            'https://wp.example/feed' => self::ok(self::sampleRss(12)),
            'https://wp.example/sitemap.xml' => self::notFound(),
            'https://wp.example/sitemap_index.xml' => self::notFound(),
            'https://wp.example/robots.txt' => self::notFound(),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('https://wp.example');

        $this->assertSame(WordPressSiteProbeResult::MODE_RSS, $result->mode);
        $this->assertFalse($result->restEnabled);
        $this->assertTrue($result->rssReachable);
        $this->assertFalse($result->sitemapReachable);
        $this->assertSame([WordPressSiteProbeResult::MODE_RSS], $result->capabilities);
        $this->assertSame(12, $result->estimatedPosts);
        $this->assertNull($result->estimatedPages);
    }

    #[Test]
    public function sitemap_only_source_classifies_as_sitemap_mode(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::notFound(),
            'https://wp.example/feed' => self::notFound(),
            'https://wp.example/sitemap.xml' => self::notFound(),
            'https://wp.example/sitemap_index.xml' => self::ok(self::sampleSitemapIndex()),
            'https://wp.example/robots.txt' => self::ok("User-agent: *\nDisallow: /wp-admin/\nDisallow: /xmlrpc.php\n"),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('https://wp.example');

        $this->assertSame(WordPressSiteProbeResult::MODE_SITEMAP, $result->mode);
        $this->assertTrue($result->sitemapReachable);
        $this->assertSame('https://wp.example/sitemap_index.xml', $result->sitemapIndexUrl);
        $this->assertSame(['/wp-admin/', '/xmlrpc.php'], $result->disallowedPaths);
    }

    #[Test]
    public function source_that_fails_every_probe_is_unreachable(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::transportError('connection refused'),
            'https://wp.example/feed' => self::transportError('connection refused'),
            'https://wp.example/sitemap.xml' => self::transportError('connection refused'),
            'https://wp.example/sitemap_index.xml' => self::transportError('connection refused'),
            'https://wp.example/robots.txt' => self::transportError('connection refused'),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('https://wp.example');

        $this->assertSame(WordPressSiteProbeResult::MODE_UNREACHABLE, $result->mode);
        $this->assertFalse($result->isUsable());
        $this->assertSame([], $result->capabilities);
        $this->assertNotEmpty($result->errors);
    }

    #[Test]
    public function source_with_no_rest_rss_or_sitemap_falls_back_to_scrape(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::notFound(),
            'https://wp.example/feed' => self::notFound(),
            'https://wp.example/sitemap.xml' => self::notFound(),
            'https://wp.example/sitemap_index.xml' => self::notFound(),
            'https://wp.example/robots.txt' => self::ok("User-agent: *\nAllow: /\n"),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('https://wp.example');

        $this->assertSame(WordPressSiteProbeResult::MODE_SCRAPE, $result->mode);
        $this->assertContains(WordPressSiteProbeResult::MODE_SCRAPE, $result->capabilities);
        $this->assertTrue($result->isUsable());
        $this->assertSame([], $result->disallowedPaths);
    }

    #[Test]
    public function unparseable_url_returns_unreachable_without_any_fetch(): void
    {
        $fetcher = new FakeHttpProbeFetcher([]);

        $result = (new WordPressSiteProbe($fetcher))->probe('   ');

        $this->assertSame(WordPressSiteProbeResult::MODE_UNREACHABLE, $result->mode);
        $this->assertSame([], $fetcher->fetched);
    }

    #[Test]
    public function bare_hostname_without_scheme_is_upgraded_to_https(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::ok(json_encode(['name' => 'Site'])),
            'https://wp.example/wp-json/wp/v2/posts?per_page=1' => self::ok('[]'),
            'https://wp.example/wp-json/wp/v2/pages?per_page=1' => self::ok('[]'),
            'https://wp.example/feed' => self::notFound(),
            'https://wp.example/sitemap.xml' => self::notFound(),
            'https://wp.example/sitemap_index.xml' => self::notFound(),
            'https://wp.example/robots.txt' => self::notFound(),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('wp.example');

        $this->assertSame('https://wp.example', $result->sourceUrl);
        $this->assertSame('wp.example', $result->sourceHost);
        $this->assertTrue($result->restEnabled);
    }

    #[Test]
    public function trailing_slash_in_source_url_is_stripped(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::ok(json_encode(['name' => 'Site'])),
            'https://wp.example/wp-json/wp/v2/posts?per_page=1' => self::ok('[]'),
            'https://wp.example/wp-json/wp/v2/pages?per_page=1' => self::ok('[]'),
            'https://wp.example/feed' => self::notFound(),
            'https://wp.example/sitemap.xml' => self::notFound(),
            'https://wp.example/sitemap_index.xml' => self::notFound(),
            'https://wp.example/robots.txt' => self::notFound(),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('https://wp.example/');

        $this->assertSame('https://wp.example', $result->sourceUrl);
    }

    #[Test]
    public function subdirectory_install_is_preserved_in_all_probe_urls(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://host.example/blog/wp-json' => self::ok(json_encode(['name' => 'Blog'])),
            'https://host.example/blog/wp-json/wp/v2/posts?per_page=1' => self::okWithWpTotal(5, '[]'),
            'https://host.example/blog/wp-json/wp/v2/pages?per_page=1' => self::okWithWpTotal(2, '[]'),
            'https://host.example/blog/feed' => self::notFound(),
            'https://host.example/blog/sitemap.xml' => self::notFound(),
            'https://host.example/blog/sitemap_index.xml' => self::notFound(),
            'https://host.example/blog/robots.txt' => self::notFound(),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('https://host.example/blog');

        $this->assertTrue($result->restEnabled);
        $this->assertSame(5, $result->estimatedPosts);
        $this->assertSame('https://host.example/blog', $result->sourceUrl);
    }

    #[Test]
    public function rest_mode_wins_when_source_exposes_every_capability(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::ok(json_encode(['name' => 'All Caps Site'])),
            'https://wp.example/wp-json/wp/v2/posts?per_page=1' => self::okWithWpTotal(1000, '[]'),
            'https://wp.example/wp-json/wp/v2/pages?per_page=1' => self::okWithWpTotal(10, '[]'),
            'https://wp.example/feed' => self::ok(self::sampleRss(10)),
            'https://wp.example/sitemap.xml' => self::ok(self::sampleUrlset()),
            'https://wp.example/sitemap_index.xml' => self::notFound(),
            'https://wp.example/robots.txt' => self::notFound(),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('https://wp.example');

        $this->assertSame(WordPressSiteProbeResult::MODE_REST, $result->mode);
        $this->assertSame(
            [
                WordPressSiteProbeResult::MODE_REST,
                WordPressSiteProbeResult::MODE_RSS,
                WordPressSiteProbeResult::MODE_SITEMAP,
            ],
            $result->capabilities
        );
        $this->assertSame(1000, $result->estimatedPosts);
    }

    #[Test]
    public function wp_json_returning_non_wordpress_json_does_not_enable_rest(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::ok(json_encode(['unrelated' => 'payload'])),
            'https://wp.example/feed' => self::ok(self::sampleRss(1)),
            'https://wp.example/sitemap.xml' => self::notFound(),
            'https://wp.example/sitemap_index.xml' => self::notFound(),
            'https://wp.example/robots.txt' => self::notFound(),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('https://wp.example');

        $this->assertFalse($result->restEnabled);
        $this->assertSame(WordPressSiteProbeResult::MODE_RSS, $result->mode);
        $this->assertContains(
            '/wp-json returned 2xx but payload is not a WordPress REST root',
            $result->warnings
        );
    }

    #[Test]
    public function atom_only_feed_is_detected_as_rss_capability(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::notFound(),
            'https://wp.example/feed' => self::ok(self::sampleAtom(4)),
            'https://wp.example/sitemap.xml' => self::notFound(),
            'https://wp.example/sitemap_index.xml' => self::notFound(),
            'https://wp.example/robots.txt' => self::notFound(),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('https://wp.example');

        $this->assertTrue($result->rssReachable);
        $this->assertSame(WordPressSiteProbeResult::MODE_RSS, $result->mode);
        $this->assertSame(4, $result->estimatedPosts);
    }

    #[Test]
    public function flat_sitemap_is_detected_and_indexed_url_recorded(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::notFound(),
            'https://wp.example/feed' => self::notFound(),
            'https://wp.example/sitemap.xml' => self::ok(self::sampleUrlset()),
            'https://wp.example/sitemap_index.xml' => self::notFound(),
            'https://wp.example/robots.txt' => self::notFound(),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('https://wp.example');

        $this->assertTrue($result->sitemapReachable);
        $this->assertSame('https://wp.example/sitemap.xml', $result->sitemapIndexUrl);
    }

    #[Test]
    public function robots_txt_non_wildcard_blocks_are_ignored(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::notFound(),
            'https://wp.example/feed' => self::ok(self::sampleRss(1)),
            'https://wp.example/sitemap.xml' => self::notFound(),
            'https://wp.example/sitemap_index.xml' => self::notFound(),
            'https://wp.example/robots.txt' => self::ok(
                "User-agent: Googlebot\nDisallow: /secret/\n\nUser-agent: *\nDisallow: /wp-admin/\n# a comment\nDisallow: /xmlrpc.php\n"
            ),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('https://wp.example');

        $this->assertSame(['/wp-admin/', '/xmlrpc.php'], $result->disallowedPaths);
    }

    #[Test]
    public function toArray_exposes_every_field_for_filament_rendering(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/wp-json' => self::ok(json_encode(['name' => 'Site'])),
            'https://wp.example/wp-json/wp/v2/posts?per_page=1' => self::okWithWpTotal(11, '[]'),
            'https://wp.example/wp-json/wp/v2/pages?per_page=1' => self::okWithWpTotal(3, '[]'),
            'https://wp.example/feed' => self::notFound(),
            'https://wp.example/sitemap.xml' => self::notFound(),
            'https://wp.example/sitemap_index.xml' => self::notFound(),
            'https://wp.example/robots.txt' => self::notFound(),
        ]);

        $result = (new WordPressSiteProbe($fetcher))->probe('https://wp.example');
        $array = $result->toArray();

        $this->assertSame('https://wp.example', $array['source_url']);
        $this->assertSame('wp.example', $array['source_host']);
        $this->assertSame('rest', $array['mode']);
        $this->assertSame(['rest'], $array['capabilities']);
        $this->assertTrue($array['rest_enabled']);
        $this->assertSame('Site', $array['rest_namespace']);
        $this->assertFalse($array['rss_reachable']);
        $this->assertFalse($array['sitemap_reachable']);
        $this->assertNull($array['sitemap_index_url']);
        $this->assertSame(11, $array['estimated_posts']);
        $this->assertSame(3, $array['estimated_pages']);
        $this->assertSame([], $array['disallowed_paths']);
        $this->assertIsArray($array['warnings']);
        $this->assertIsArray($array['errors']);
    }

    /**
     * @return array<string, array{input: string, expected: ?string}>
     */
    public static function urlNormalizationCases(): array
    {
        return [
            'bare host' => ['input' => 'example.com', 'expected' => 'https://example.com'],
            'http kept' => ['input' => 'http://example.com', 'expected' => 'http://example.com'],
            'https trailing slash stripped' => ['input' => 'https://example.com/', 'expected' => 'https://example.com'],
            'subpath preserved' => ['input' => 'https://example.com/blog', 'expected' => 'https://example.com/blog'],
            'subpath trailing slash stripped' => ['input' => 'https://example.com/blog/', 'expected' => 'https://example.com/blog'],
            'uppercase host lowercased' => ['input' => 'https://Example.COM/Blog', 'expected' => 'https://example.com/Blog'],
            'custom port retained' => ['input' => 'http://example.com:8080', 'expected' => 'http://example.com:8080'],
            'whitespace trimmed' => ['input' => '  https://example.com/  ', 'expected' => 'https://example.com'],
            'empty input rejected' => ['input' => '', 'expected' => null],
            'whitespace only rejected' => ['input' => '   ', 'expected' => null],
            'ftp rejected' => ['input' => 'ftp://example.com', 'expected' => null],
            'no host rejected' => ['input' => 'https://', 'expected' => null],
        ];
    }

    #[Test]
    #[DataProvider('urlNormalizationCases')]
    public function url_normalization(string $input, ?string $expected): void
    {
        $this->assertSame($expected, WordPressSiteProbe::normalizeUrl($input));
    }

    // --- test helpers ---

    private static function ok(string $body): array
    {
        return ['body' => $body, 'http_code' => 200, 'error' => ''];
    }

    /**
     * Mock response that includes an X-WP-Total header inline in the
     * body; the probe extracts the header via a body regex today
     * (see WordPressSiteProbe::extractWpTotal) since the fetcher
     * contract returns body only. When the fetcher grows a proper
     * headers field this helper stays valid — the regex will just
     * stop matching and the probe falls back to array count.
     */
    private static function okWithWpTotal(int $total, string $body): array
    {
        return [
            'body' => "HTTP/1.1 200 OK\r\nContent-Type: application/json\r\nX-WP-Total: {$total}\r\n\r\n{$body}",
            'http_code' => 200,
            'error' => '',
        ];
    }

    private static function notFound(): array
    {
        return ['body' => '', 'http_code' => 404, 'error' => ''];
    }

    private static function transportError(string $msg): array
    {
        return ['body' => '', 'http_code' => 0, 'error' => $msg];
    }

    private static function sampleRss(int $items): string
    {
        $itemsXml = str_repeat(
            '<item><title>Example</title><link>https://wp.example/a</link></item>',
            $items
        );
        return "<?xml version=\"1.0\"?><rss version=\"2.0\"><channel><title>Ex</title>{$itemsXml}</channel></rss>";
    }

    private static function sampleAtom(int $entries): string
    {
        $entriesXml = str_repeat(
            '<entry><title>Ex</title></entry>',
            $entries
        );
        return "<?xml version=\"1.0\"?><feed xmlns=\"http://www.w3.org/2005/Atom\"><title>Ex</title>{$entriesXml}</feed>";
    }

    private static function sampleUrlset(): string
    {
        return '<?xml version="1.0"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>https://wp.example/post-1</loc></url></urlset>';
    }

    private static function sampleSitemapIndex(): string
    {
        return '<?xml version="1.0"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><sitemap><loc>https://wp.example/post-sitemap.xml</loc></sitemap></sitemapindex>';
    }
}

/**
 * Scripted HttpProbeFetcher: responses are keyed by the exact URL
 * the probe requests. Any URL not in the table returns a transport
 * error, which makes accidental URL drift in the probe surface as a
 * test failure with an informative message rather than a silent
 * "looks like the source is down" classification.
 */
final class FakeHttpProbeFetcher implements HttpProbeFetcher
{
    /** @var list<string> */
    public array $fetched = [];

    /**
     * @param array<string, array{body: string, http_code: int, error: string}> $table
     */
    public function __construct(private array $table) {}

    public function fetch(string $url, int $timeout, ?string $authorization = null): array
    {
        $this->fetched[] = $url;
        if (!array_key_exists($url, $this->table)) {
            return [
                'body' => '',
                'http_code' => 0,
                'headers' => [],
                'error' => "FakeHttpProbeFetcher: no scripted response for URL '{$url}'",
            ];
        }
        $resp = $this->table[$url];
        $resp['headers'] = $resp['headers'] ?? [];
        return $resp;
    }
}
