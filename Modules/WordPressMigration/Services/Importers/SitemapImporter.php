<?php

namespace Modules\WordPressMigration\Services\Importers;

use DateTimeImmutable;
use Modules\WordPressMigration\DTOs\SitemapCrawlResult;
use Modules\WordPressMigration\DTOs\SitemapUrlEntry;
use Modules\WordPressMigration\Services\Http\CurlHttpProbeFetcher;
use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;
use SimpleXMLElement;

/**
 * Phase 4 sitemap crawler.
 *
 * Resolves a WordPress site's sitemap — vanilla `sitemap.xml`,
 * `sitemap_index.xml`, or the Yoast/RankMath/AIOSEO flavor whose
 * index points at per-type sub-sitemaps (`post-sitemap.xml`,
 * `page-sitemap.xml`, `category-sitemap.xml`, …) — into a flat,
 * deduped list of {@see SitemapUrlEntry} records.
 *
 * This class is intentionally only a URL-list resolver. Fetching
 * each discovered URL, running readability extraction on the HTML,
 * and mapping the extracted body onto a Microweber content row are
 * separate Phase 4 tasks living in their own services. Keeping the
 * crawl step narrow means the unit matrix here (flat urlset, nested
 * index, circular index, bad XML, caps) stays pinnable without
 * dragging in a DOM extractor.
 *
 * Safety model
 * ------------
 * The sitemap spec doesn't forbid circular index entries and doesn't
 * cap nesting depth, so the crawler enforces both itself:
 *   - `$seenSitemaps` dedupes sub-sitemap fetches (same URL visited
 *      twice in the same crawl is skipped, which also breaks cycles).
 *   - `MAX_DEPTH` bounds recursive indexes to 3 levels; real
 *      Yoast/RankMath sites stop at 2 (index → type-sitemap →
 *      urlset is the deepest legitimate shape), so 3 leaves headroom
 *      for oddball nested AIOSEO configs without allowing runaways.
 *   - `$maxUrls` caps the collected items — when hit, the crawl
 *      stops mid-walk and reports `max_urls`.
 *
 * HTTP is injected via {@see HttpProbeFetcher} for the same reason
 * the RSS importer reuses it: the probe's response shape is exactly
 * what the crawler wants (no exceptions on 4xx/5xx, header access
 * for future conditional-GET support) and tests reuse
 * `FakeHttpProbeFetcher` for scripted responses.
 */
class SitemapImporter
{
    private const FETCH_TIMEOUT_SECONDS = 30;
    private const MAX_DEPTH = 3;

    private HttpProbeFetcher $fetcher;

    public function __construct(?HttpProbeFetcher $fetcher = null)
    {
        $this->fetcher = $fetcher ?? new CurlHttpProbeFetcher();
    }

    /**
     * Walk the sitemap rooted at `$sitemapUrl` and return every
     * discovered URL entry.
     *
     * Walk order is breadth-first on sub-sitemaps (so the urlsets
     * listed first in a sitemapindex are resolved before deeper
     * ones). Within a single urlset the order matches the document
     * order — sitemaps typically list newest-first so this keeps
     * incremental re-runs predictable.
     *
     * `$maxUrls` is applied across the whole crawl. A crawl that
     * hits the cap mid-urlset returns the partial set with
     * {@see SitemapCrawlResult::STOP_MAX_URLS}.
     *
     * Incremental re-runs: pass `$modifiedSince` to drop URL entries
     * whose `<lastmod>` timestamp is at or before the cutoff. Entries
     * that have no parseable lastmod are kept — a missing signal
     * means "unknown, re-check" rather than "assume unchanged", so
     * the caller's downstream dedupe (by guid in the seenGuids pass)
     * still gets a chance to decide. This mirrors how Yoast and
     * RankMath populate lastmod for posts but leave it off for
     * taxonomy archives that rarely carry a meaningful timestamp.
     *
     * @param int $maxUrls Hard upper bound on URLs returned (0 → immediate unreachable stop)
     * @param DateTimeImmutable|null $modifiedSince If set, entries with lastmod <= this are filtered out before collection
     */
    public function crawl(
        string $sitemapUrl,
        int $maxUrls = 10000,
        ?DateTimeImmutable $modifiedSince = null,
    ): SitemapCrawlResult {
        $root = self::normalizeUrl($sitemapUrl);
        if ($root === null || $maxUrls <= 0) {
            return new SitemapCrawlResult([], 0, SitemapCrawlResult::STOP_UNREACHABLE);
        }

        /** @var list<array{url: string, depth: int, type: ?string}> $queue */
        $queue = [['url' => $root, 'depth' => 0, 'type' => null]];
        $seenSitemaps = [];

        /** @var list<SitemapUrlEntry> $collected */
        $collected = [];
        $seenLocs = [];
        $sitemapsFetched = 0;
        $fetched = [];
        $hitMaxDepth = false;

        while ($queue !== []) {
            $node = array_shift($queue);
            $currentUrl = $node['url'];
            $depth = $node['depth'];
            $parentType = $node['type'];

            if (isset($seenSitemaps[$currentUrl])) {
                continue;
            }
            $seenSitemaps[$currentUrl] = true;

            if ($depth > self::MAX_DEPTH) {
                $hitMaxDepth = true;
                continue;
            }

            $resp = $this->fetcher->fetch($currentUrl, self::FETCH_TIMEOUT_SECONDS);
            $fetched[] = $currentUrl;

            if (!$this->isSuccess($resp)) {
                if ($sitemapsFetched === 0) {
                    return new SitemapCrawlResult(
                        [],
                        0,
                        SitemapCrawlResult::STOP_UNREACHABLE,
                        $fetched,
                    );
                }
                continue;
            }

            $xml = self::safeLoadXml($resp['body']);
            if ($xml === null) {
                if ($sitemapsFetched === 0) {
                    return new SitemapCrawlResult(
                        [],
                        0,
                        SitemapCrawlResult::STOP_UNREACHABLE,
                        $fetched,
                    );
                }
                continue;
            }

            $sitemapsFetched++;
            $rootName = $xml->getName();

            if ($rootName === 'sitemapindex') {
                foreach ($xml->sitemap as $sub) {
                    $subLoc = isset($sub->loc) ? trim((string)$sub->loc) : '';
                    if ($subLoc === '') {
                        continue;
                    }
                    $inferred = self::inferContentType($subLoc) ?? $parentType;
                    $queue[] = [
                        'url' => $subLoc,
                        'depth' => $depth + 1,
                        'type' => $inferred,
                    ];
                }
                continue;
            }

            if ($rootName === 'urlset') {
                foreach ($xml->url as $u) {
                    $entry = self::parseUrlElement($u, $parentType);
                    if ($entry === null) {
                        continue;
                    }
                    if (isset($seenLocs[$entry->loc])) {
                        continue;
                    }
                    if ($modifiedSince !== null
                        && $entry->lastmod !== null
                        && $entry->lastmod <= $modifiedSince) {
                        // Entry has a lastmod that's not newer than
                        // the cutoff — skip without marking it seen
                        // (so a later re-crawl with a different cutoff
                        // can still pick it up).
                        continue;
                    }
                    $seenLocs[$entry->loc] = true;
                    $collected[] = $entry;
                    if (count($collected) >= $maxUrls) {
                        return new SitemapCrawlResult(
                            $collected,
                            $sitemapsFetched,
                            SitemapCrawlResult::STOP_MAX_URLS,
                            $fetched,
                        );
                    }
                }
                continue;
            }

            // Unknown root element — skip silently. Some older
            // plugins wrap urlsets in a <sitemap> root; we tolerate
            // the drop rather than guess.
        }

        $stop = $hitMaxDepth ? SitemapCrawlResult::STOP_MAX_DEPTH : SitemapCrawlResult::STOP_COMPLETE;

        return new SitemapCrawlResult($collected, $sitemapsFetched, $stop, $fetched);
    }

    /**
     * Parse a `<urlset>…</urlset>` document directly into URL
     * entries. Exposed (rather than kept private) so fixture-driven
     * tests and downstream utilities can reuse the parser without a
     * round-trip through the fetcher.
     *
     * @return list<SitemapUrlEntry>
     */
    public function parseUrlset(string $xml, ?string $contentType = null): array
    {
        $root = self::safeLoadXml($xml);
        if ($root === null || $root->getName() !== 'urlset') {
            return [];
        }

        $out = [];
        foreach ($root->url as $u) {
            $entry = self::parseUrlElement($u, $contentType);
            if ($entry !== null) {
                $out[] = $entry;
            }
        }
        return $out;
    }

    /**
     * Parse a `<sitemapindex>` document and return the list of
     * sub-sitemap URLs (with their inferred content types).
     *
     * @return list<array{loc: string, content_type: ?string}>
     */
    public function parseIndex(string $xml): array
    {
        $root = self::safeLoadXml($xml);
        if ($root === null || $root->getName() !== 'sitemapindex') {
            return [];
        }

        $out = [];
        foreach ($root->sitemap as $sub) {
            $loc = isset($sub->loc) ? trim((string)$sub->loc) : '';
            if ($loc === '') {
                continue;
            }
            $out[] = [
                'loc' => $loc,
                'content_type' => self::inferContentType($loc),
            ];
        }
        return $out;
    }

    /**
     * Infer a Microweber-shaped content type from a Yoast / RankMath
     * / AIOSEO sub-sitemap URL. All three plugins share the naming
     * convention `<type>-sitemap.xml` (or `<type>-sitemap1.xml` for
     * paginated variants), so a single regex covers every flavour
     * we care about:
     *
     *   https://example.com/post-sitemap.xml       → "post"
     *   https://example.com/page-sitemap.xml       → "page"
     *   https://example.com/category-sitemap.xml   → "category"
     *   https://example.com/product-sitemap1.xml   → "product"
     *   https://example.com/post_tag-sitemap.xml   → "post_tag"
     *
     * Returns null for the vanilla `/sitemap.xml`, nested index
     * files (`/sitemap_index.xml`), and anything that doesn't match
     * the pattern — callers then fall back to whatever type the
     * parent level declared (or null, meaning "no signal").
     */
    public static function inferContentType(string $url): ?string
    {
        $path = (string)parse_url($url, PHP_URL_PATH);
        if ($path === '') {
            return null;
        }
        if (preg_match('~/([a-z][a-z0-9_-]*?)-sitemap(?:\d+)?\.xml$~i', $path, $m)) {
            $type = strtolower($m[1]);
            // "sitemap-sitemap.xml" would match with $type === 'sitemap'
            // which is never a meaningful content type; reject it.
            if ($type === 'sitemap') {
                return null;
            }
            return $type;
        }
        return null;
    }

    /**
     * Map one `<url>` element to an entry. Returns null for entries
     * that lack a `<loc>` — we can't dedupe or fetch them.
     */
    private static function parseUrlElement(SimpleXMLElement $u, ?string $contentType): ?SitemapUrlEntry
    {
        $loc = isset($u->loc) ? trim((string)$u->loc) : '';
        if ($loc === '') {
            return null;
        }

        $lastmod = null;
        if (isset($u->lastmod)) {
            $raw = trim((string)$u->lastmod);
            if ($raw !== '') {
                try {
                    $lastmod = new DateTimeImmutable($raw);
                } catch (\Exception) {
                    // Unparseable lastmod is ignored rather than
                    // dropping the entry — the URL is still useful.
                    $lastmod = null;
                }
            }
        }

        $changefreq = null;
        if (isset($u->changefreq)) {
            $raw = strtolower(trim((string)$u->changefreq));
            if ($raw !== '') {
                $changefreq = $raw;
            }
        }

        $priority = null;
        if (isset($u->priority)) {
            $raw = trim((string)$u->priority);
            if ($raw !== '' && is_numeric($raw)) {
                $priority = (float)$raw;
            }
        }

        return new SitemapUrlEntry(
            loc: $loc,
            lastmod: $lastmod,
            changefreq: $changefreq,
            priority: $priority,
            contentType: $contentType,
        );
    }

    /**
     * @param array{body: string, http_code: int, headers?: array<string, string>, error: string} $resp
     */
    private function isSuccess(array $resp): bool
    {
        return $resp['http_code'] >= 200 && $resp['http_code'] < 400 && $resp['body'] !== '';
    }

    private static function safeLoadXml(string $xml): ?SimpleXMLElement
    {
        $xml = trim($xml);
        if ($xml === '') {
            return null;
        }
        $previous = libxml_use_internal_errors(true);
        try {
            $root = simplexml_load_string(
                $xml,
                SimpleXMLElement::class,
                LIBXML_NOCDATA | LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET
            );
            return $root === false ? null : $root;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }

    /**
     * Strip trailing whitespace and enforce http(s). Sitemap URLs
     * carry filenames so we do NOT trim paths — just canonicalize
     * the scheme/host and reject garbage.
     */
    private static function normalizeUrl(string $raw): ?string
    {
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }
        if (!preg_match('~^https?://~i', $raw)) {
            return null;
        }
        $parsed = parse_url($raw);
        if (!is_array($parsed) || empty($parsed['host'])) {
            return null;
        }
        $scheme = strtolower($parsed['scheme']);
        $host = strtolower($parsed['host']);
        $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $path = $parsed['path'] ?? '';
        $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
        return "{$scheme}://{$host}{$port}{$path}{$query}";
    }
}
