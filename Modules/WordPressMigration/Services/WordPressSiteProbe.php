<?php

namespace Modules\WordPressMigration\Services;

use Modules\WordPressMigration\Services\Http\CurlHttpProbeFetcher;
use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;

/**
 * Classify a WordPress source by probing its well-known endpoints
 * before any bulk import runs.
 *
 * Probe targets (see TODO Phase 2 + docs/adr/wordpress-migration.md):
 *   - <url>/wp-json              → REST API root + version sniff
 *   - <url>/wp-json/wp/v2/posts  → estimated post count via X-WP-Total
 *   - <url>/wp-json/wp/v2/pages  → estimated page count via X-WP-Total
 *   - <url>/feed                 → RSS 2.0 reachability + item count
 *   - <url>/sitemap.xml          → flat or index sitemap
 *   - <url>/sitemap_index.xml    → Yoast/RankMath/AIOSEO index variant
 *   - <url>/robots.txt           → disallow hints for the sitemap mode
 *
 * The probe is deliberately cheap: one GET per URL, no pagination,
 * 10s per request. Everything runs through an injectable
 * HttpProbeFetcher so tests script per-URL responses without real
 * network (see WordPressSiteProbeTest).
 *
 * Honouring the rate-limit posture in the ADR isn't this class's
 * concern — the full import-job runner is. The probe is infrequent
 * enough (one operator click) that we don't also pace 7 small
 * requests here.
 */
class WordPressSiteProbe
{
    private const PROBE_TIMEOUT_SECONDS = 10;

    private HttpProbeFetcher $fetcher;

    public function __construct(?HttpProbeFetcher $fetcher = null)
    {
        $this->fetcher = $fetcher ?? new CurlHttpProbeFetcher();
    }

    public function probe(string $rawUrl): WordPressSiteProbeResult
    {
        $url = self::normalizeUrl($rawUrl);

        if ($url === null) {
            return $this->unreachable(
                $rawUrl,
                '',
                ["Could not parse URL: '{$rawUrl}' — expected http(s)://host[/path]"]
            );
        }

        $host = (string)parse_url($url, PHP_URL_HOST);

        $restEnabled = false;
        $restNamespace = null;
        $rssReachable = false;
        $sitemapReachable = false;
        $sitemapIndexUrl = null;
        $estimatedPosts = null;
        $estimatedPages = null;
        $warnings = [];
        $errors = [];
        $disallowedPaths = [];

        $wpJson = $this->fetcher->fetch("{$url}/wp-json", self::PROBE_TIMEOUT_SECONDS);
        if ($this->isSuccess($wpJson)) {
            $decoded = json_decode($wpJson['body'], true);
            if (is_array($decoded) && (isset($decoded['name']) || isset($decoded['namespaces']) || isset($decoded['routes']))) {
                $restEnabled = true;
                $restNamespace = self::extractRestNamespace($decoded);
            } else {
                $warnings[] = '/wp-json returned 2xx but payload is not a WordPress REST root';
            }
        } elseif ($wpJson['error'] !== '') {
            $errors[] = "wp-json fetch error: {$wpJson['error']}";
        }

        if ($restEnabled) {
            $postsCountResp = $this->fetcher->fetch(
                "{$url}/wp-json/wp/v2/posts?per_page=1",
                self::PROBE_TIMEOUT_SECONDS
            );
            $estimatedPosts = self::extractWpTotal($postsCountResp) ?? self::countJsonArray($postsCountResp);

            $pagesCountResp = $this->fetcher->fetch(
                "{$url}/wp-json/wp/v2/pages?per_page=1",
                self::PROBE_TIMEOUT_SECONDS
            );
            $estimatedPages = self::extractWpTotal($pagesCountResp) ?? self::countJsonArray($pagesCountResp);
        }

        $rss = $this->fetcher->fetch("{$url}/feed", self::PROBE_TIMEOUT_SECONDS);
        if ($this->isSuccess($rss) && self::looksLikeRss($rss['body'])) {
            $rssReachable = true;
            if ($estimatedPosts === null) {
                $estimatedPosts = self::countRssItems($rss['body']);
            }
        }

        $sitemap = $this->fetcher->fetch("{$url}/sitemap.xml", self::PROBE_TIMEOUT_SECONDS);
        if ($this->isSuccess($sitemap) && self::looksLikeSitemap($sitemap['body'])) {
            $sitemapReachable = true;
            $sitemapIndexUrl = "{$url}/sitemap.xml";
        } else {
            $sitemapIdx = $this->fetcher->fetch("{$url}/sitemap_index.xml", self::PROBE_TIMEOUT_SECONDS);
            if ($this->isSuccess($sitemapIdx) && self::looksLikeSitemap($sitemapIdx['body'])) {
                $sitemapReachable = true;
                $sitemapIndexUrl = "{$url}/sitemap_index.xml";
            }
        }

        $robots = $this->fetcher->fetch("{$url}/robots.txt", self::PROBE_TIMEOUT_SECONDS);
        if ($this->isSuccess($robots)) {
            $disallowedPaths = self::parseRobotsDisallow($robots['body']);
        }

        if (!$restEnabled && $rss['http_code'] === 0 && $sitemap['http_code'] === 0) {
            return $this->unreachable(
                $url,
                $host,
                array_merge(
                    ["Source at {$url} did not respond to any probe endpoint"],
                    $errors
                )
            );
        }

        $capabilities = [];
        if ($restEnabled) {
            $capabilities[] = WordPressSiteProbeResult::MODE_REST;
        }
        if ($rssReachable) {
            $capabilities[] = WordPressSiteProbeResult::MODE_RSS;
        }
        if ($sitemapReachable) {
            $capabilities[] = WordPressSiteProbeResult::MODE_SITEMAP;
        }

        if (empty($capabilities)) {
            $warnings[] = 'No REST/RSS/sitemap responded — scrape mode requires manual per-URL fetches';
            $mode = WordPressSiteProbeResult::MODE_SCRAPE;
            $capabilities[] = WordPressSiteProbeResult::MODE_SCRAPE;
        } else {
            $mode = $capabilities[0];
        }

        if ($restEnabled) {
            $warnings[] = 'REST mode available — supply an application password on the create form to include draft/private content';
        }

        return new WordPressSiteProbeResult(
            sourceUrl: $url,
            sourceHost: $host,
            mode: $mode,
            capabilities: $capabilities,
            restEnabled: $restEnabled,
            restNamespace: $restNamespace,
            rssReachable: $rssReachable,
            sitemapReachable: $sitemapReachable,
            sitemapIndexUrl: $sitemapIndexUrl,
            estimatedPosts: $estimatedPosts,
            estimatedPages: $estimatedPages,
            disallowedPaths: $disallowedPaths,
            warnings: $warnings,
            errors: $errors,
        );
    }

    /**
     * Strip trailing slash, enforce http(s), and reject anything that
     * isn't parseable. Returns null on un-fixable input so callers
     * can surface a clean error instead of probing garbage.
     */
    public static function normalizeUrl(string $raw): ?string
    {
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }

        if (preg_match('~^[a-z][a-z0-9+.\-]*://~i', $raw)) {
            if (!preg_match('~^https?://~i', $raw)) {
                return null;
            }
        } else {
            $raw = 'https://' . $raw;
        }

        $parsed = parse_url($raw);
        if (!is_array($parsed) || empty($parsed['host'])) {
            return null;
        }

        $scheme = strtolower($parsed['scheme'] ?? 'https');
        if ($scheme !== 'http' && $scheme !== 'https') {
            return null;
        }

        $host = strtolower($parsed['host']);
        $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $path = rtrim($parsed['path'] ?? '', '/');

        return "{$scheme}://{$host}{$port}{$path}";
    }

    /**
     * @param array{body: string, http_code: int, error: string} $resp
     */
    private function isSuccess(array $resp): bool
    {
        return $resp['http_code'] >= 200 && $resp['http_code'] < 400;
    }

    /**
     * Extract a REST namespace/name string for operator display. Tries
     * `name`, then `namespaces[0]`, then `routes` keys in that order
     * because WP 5.x's `/wp-json` root emits all three but which one
     * is most informative varies by version.
     *
     * @param array<string, mixed> $decoded
     */
    private static function extractRestNamespace(array $decoded): ?string
    {
        if (isset($decoded['name']) && is_string($decoded['name']) && $decoded['name'] !== '') {
            return $decoded['name'];
        }
        if (isset($decoded['namespaces']) && is_array($decoded['namespaces']) && count($decoded['namespaces']) > 0) {
            $first = reset($decoded['namespaces']);
            if (is_string($first)) {
                return $first;
            }
        }
        if (isset($decoded['routes']) && is_array($decoded['routes']) && count($decoded['routes']) > 0) {
            return (string)array_key_first($decoded['routes']);
        }
        return null;
    }

    /**
     * Read the `X-WP-Total` header the WP REST API sets on list
     * endpoints — it's the authoritative post/page count and is
     * preferred over counting the returned array (which is capped
     * at `per_page=1` for a cheap probe). Header names are
     * normalized lowercase by the fetcher.
     *
     * Back-compat: some older tests still bake the header into the
     * body as a pseudo-HTTP response. The regex fallback preserves
     * that behaviour so the existing unit tests keep passing
     * without modification.
     *
     * @param array{body: string, http_code: int, headers?: array<string, string>, error: string} $resp
     */
    private static function extractWpTotal(array $resp): ?int
    {
        $headers = $resp['headers'] ?? [];
        if (isset($headers['x-wp-total']) && ctype_digit((string)$headers['x-wp-total'])) {
            return (int)$headers['x-wp-total'];
        }

        if (!preg_match('/^\s*(?:HTTP\/[\d.]+\s+\d+[^\r\n]*\r?\n)?(?:[^\r\n]+\r?\n)*x-wp-total:\s*(\d+)\b/mi', $resp['body'], $m)) {
            return null;
        }
        return (int)$m[1];
    }

    /**
     * @param array{body: string, http_code: int, headers?: array<string, string>, error: string} $resp
     */
    private static function countJsonArray(array $resp): ?int
    {
        $decoded = json_decode($resp['body'], true);
        if (!is_array($decoded)) {
            return null;
        }
        return count($decoded);
    }

    private static function looksLikeRss(string $body): bool
    {
        $trim = ltrim($body);
        if ($trim === '') {
            return false;
        }
        if (stripos($trim, '<rss') !== false) {
            return true;
        }
        if (stripos($trim, '<feed') !== false && stripos($trim, 'http://www.w3.org/2005/Atom') !== false) {
            return true;
        }
        return false;
    }

    private static function countRssItems(string $body): ?int
    {
        $count = substr_count($body, '<item');
        if ($count > 0) {
            return $count;
        }
        $count = substr_count($body, '<entry');
        if ($count > 0) {
            return $count;
        }
        return null;
    }

    private static function looksLikeSitemap(string $body): bool
    {
        $trim = ltrim($body);
        if ($trim === '') {
            return false;
        }
        if (stripos($trim, '<urlset') !== false) {
            return true;
        }
        if (stripos($trim, '<sitemapindex') !== false) {
            return true;
        }
        return false;
    }

    /**
     * Pull User-agent: * Disallow: /path lines from robots.txt.
     * Wildcards and comments are respected; other User-agent blocks
     * are ignored (the ADR only honors * for the generic migrator).
     *
     * @return list<string>
     */
    private static function parseRobotsDisallow(string $body): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $body) ?: [];
        $paths = [];
        $inWildcardBlock = false;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (preg_match('/^user-agent:\s*(.+)$/i', $line, $m)) {
                $inWildcardBlock = trim($m[1]) === '*';
                continue;
            }
            if ($inWildcardBlock && preg_match('/^disallow:\s*(.*)$/i', $line, $m)) {
                $path = trim($m[1]);
                if ($path !== '') {
                    $paths[] = $path;
                }
            }
        }

        return array_values(array_unique($paths));
    }

    /**
     * @param list<string> $errors
     */
    private function unreachable(string $url, string $host, array $errors): WordPressSiteProbeResult
    {
        return new WordPressSiteProbeResult(
            sourceUrl: $url,
            sourceHost: $host,
            mode: WordPressSiteProbeResult::MODE_UNREACHABLE,
            capabilities: [],
            restEnabled: false,
            restNamespace: null,
            rssReachable: false,
            sitemapReachable: false,
            sitemapIndexUrl: null,
            estimatedPosts: null,
            estimatedPages: null,
            disallowedPaths: [],
            warnings: [],
            errors: $errors,
        );
    }
}
