<?php

namespace Modules\WordPressMigration\Services\Importers;

use Modules\WordPressMigration\DTOs\ExtractedPageDTO;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\DTOs\SitemapCrawlResult;
use Modules\WordPressMigration\DTOs\SitemapImportResult;
use Modules\WordPressMigration\DTOs\SitemapUrlEntry;
use Modules\WordPressMigration\Services\Extractors\SitemapPageExtractor;
use Modules\WordPressMigration\Services\Http\CurlHttpProbeFetcher;
use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;

/**
 * Phase 4 page importer — the "for each sitemap URL, fetch and
 * extract" driver.
 *
 * Chains {@see SitemapImporter} (URL discovery) with
 * {@see SitemapPageExtractor} (readability + meta extraction) and
 * produces a stream of {@see MigrationItemDTO} the existing
 * WordPressContentMapper already knows how to persist. Keeping the
 * two halves separate means the crawl matrix and the extraction
 * matrix stay independently testable; this class is only the glue.
 *
 * GUID strategy
 * -------------
 * Sitemaps don't carry stable identity like RSS `<guid>` or WP
 * post IDs. The best candidate for "same URL twice means same
 * content" is the canonical URL itself — which is also what the
 * mapper uses as the `original_link`. We use the URL string as the
 * GUID so re-runs short-circuit at the `$seenGuids` check in the
 * same way the RSS walker does.
 *
 * Skipping vs. aborting
 * ---------------------
 * Individual page failures (4xx, transport error, extractor
 * couldn't find a usable body) are counted in `pagesSkipped` but
 * do NOT abort the walk. A partially-readable site still produces
 * something useful; a hard stop on the first bad URL would be
 * hostile to the operator. The sitemap-level
 * {@see SitemapCrawlResult::STOP_UNREACHABLE} however does abort
 * — if we can't even load the root sitemap there's nothing to
 * stream.
 */
class SitemapPageImporter
{
    private const FETCH_TIMEOUT_SECONDS = 30;
    private const SOURCE = 'sitemap';

    private SitemapImporter $crawler;
    private SitemapPageExtractor $extractor;
    private HttpProbeFetcher $fetcher;

    public function __construct(
        ?SitemapImporter $crawler = null,
        ?SitemapPageExtractor $extractor = null,
        ?HttpProbeFetcher $fetcher = null,
    ) {
        $this->fetcher = $fetcher ?? new CurlHttpProbeFetcher();
        $this->crawler = $crawler ?? new SitemapImporter($this->fetcher);
        $this->extractor = $extractor ?? new SitemapPageExtractor();
    }

    /**
     * @param list<string> $seenGuids URLs already imported in previous runs
     * @param list<MigrationItemDTO> $rssFallback Optional RSS/Atom items for the same site, used to fill in
     *     taxonomy + author metadata the sitemap page HTML doesn't expose.
     *     Match is by canonical URL (trailing-slash / host-case tolerant).
     *     Pages that weren't in the RSS feed are mapped unchanged.
     */
    public function walk(
        string $sitemapUrl,
        array $seenGuids = [],
        int $maxItems = 1000,
        array $rssFallback = [],
    ): SitemapImportResult {
        $crawl = $this->crawler->crawl($sitemapUrl, $maxItems);
        if ($crawl->urls === []) {
            return new SitemapImportResult(
                items: [],
                stopReason: $crawl->stopReason,
                pagesFetched: 0,
            );
        }

        $seen = [];
        foreach ($seenGuids as $g) {
            if (is_string($g) && $g !== '') {
                $seen[$g] = true;
            }
        }

        $rssByUrl = $this->indexRssByCanonicalUrl($rssFallback);

        $items = [];
        $pagesFetched = 0;
        $pagesSkipped = 0;
        $skipped = [];

        foreach ($crawl->urls as $entry) {
            if (count($items) >= $maxItems) {
                break;
            }
            if (isset($seen[$entry->loc])) {
                continue;
            }

            $resp = $this->fetcher->fetch($entry->loc, self::FETCH_TIMEOUT_SECONDS);
            if (!$this->isSuccess($resp)) {
                $pagesSkipped++;
                $skipped[] = $entry->loc;
                continue;
            }
            $pagesFetched++;

            $extracted = $this->extractor->extract($resp['body'], $entry->loc);
            if (!$extracted->isUsable()) {
                $pagesSkipped++;
                $skipped[] = $entry->loc;
                continue;
            }

            $fallback = $rssByUrl[$this->normalizeUrl($entry->loc) ?? ''] ?? null;
            $items[] = $this->toMigrationItem($entry, $extracted, $fallback);
            $seen[$entry->loc] = true;
        }

        return new SitemapImportResult(
            items: $items,
            stopReason: $crawl->stopReason,
            pagesFetched: $pagesFetched,
            pagesSkipped: $pagesSkipped,
            skippedUrls: $skipped,
        );
    }

    private function toMigrationItem(
        SitemapUrlEntry $entry,
        ExtractedPageDTO $page,
        ?MigrationItemDTO $rssFallback = null,
    ): MigrationItemDTO {
        $host = (string)parse_url($entry->loc, PHP_URL_HOST) ?: null;

        // Prefer the extractor's published_at (article meta is more
        // accurate than <lastmod>) but fall back to lastmod so the
        // mapper always has *some* time signal for sorting.
        $publishedAt = $page->publishedAt ?? $entry->lastmod;

        // Attach the og:image at the top of the body when present
        // and not already embedded. Mapper-facing contract: HTML is
        // "body as published", including any lead media. The
        // extractor's $ogImage is a convenience for callers that
        // want the hero separately, but the body-render must still
        // show it — otherwise imported posts render imageless.
        $html = $page->html;
        if ($page->ogImage !== null && !$this->htmlMentionsImage($html, $page->ogImage)) {
            $html = '<img src="' . htmlspecialchars($page->ogImage, ENT_QUOTES | ENT_HTML5) . '" alt="" />' . $html;
        }

        // When an RSS DTO exists for the same canonical URL, fill in
        // fields the page extractor can't surface reliably (taxonomy
        // terms aren't usually rendered on the article page; author
        // is missing more often than not). Page-derived values always
        // win when present — the RSS data is a fallback, not an
        // override — because page meta reflects what the publisher
        // showed their readers.
        $author = $page->author ?? $rssFallback?->author;
        $categories = ($rssFallback !== null && $rssFallback->categories !== [])
            ? $rssFallback->categories
            : [];
        $tags = ($rssFallback !== null && $rssFallback->tags !== [])
            ? $rssFallback->tags
            : [];

        return new MigrationItemDTO(
            guid: $entry->loc,
            title: $page->title,
            html: $html,
            excerpt: $page->excerpt,
            author: $author,
            categories: $categories,
            tags: $tags,
            publishedAt: $publishedAt,
            canonicalUrl: $entry->loc,
            source: self::SOURCE,
            sourceHost: $host,
        );
    }

    /**
     * Build a URL-keyed index of the RSS fallback items so each
     * sitemap URL can look up its match in O(1). The key is a
     * normalized form (lowercased scheme+host, trailing slash
     * stripped) so RSS feeds that emit `https://Wp.Example/post/`
     * still match a sitemap entry of `https://wp.example/post`.
     *
     * @param list<MigrationItemDTO> $rss
     * @return array<string, MigrationItemDTO>
     */
    private function indexRssByCanonicalUrl(array $rss): array
    {
        $index = [];
        foreach ($rss as $dto) {
            if (!$dto instanceof MigrationItemDTO) {
                continue;
            }
            $url = $dto->canonicalUrl;
            if (!is_string($url) || $url === '') {
                continue;
            }
            $key = $this->normalizeUrl($url);
            if ($key === null) {
                continue;
            }
            // First-wins so a duplicated RSS row (e.g. cross-posted
            // category + post feed merge) doesn't silently swap its
            // metadata mid-run.
            $index[$key] ??= $dto;
        }
        return $index;
    }

    private function normalizeUrl(string $url): ?string
    {
        $parsed = parse_url(trim($url));
        if (!is_array($parsed) || empty($parsed['host'])) {
            return null;
        }
        $scheme = strtolower((string)($parsed['scheme'] ?? 'https'));
        $host = strtolower((string)$parsed['host']);
        $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $path = rtrim((string)($parsed['path'] ?? ''), '/');
        return "{$scheme}://{$host}{$port}{$path}";
    }

    private function htmlMentionsImage(string $html, string $src): bool
    {
        if ($html === '' || $src === '') {
            return false;
        }
        return str_contains($html, $src);
    }

    /**
     * @param array{body: string, http_code: int, headers?: array<string, string>, error: string} $resp
     */
    private function isSuccess(array $resp): bool
    {
        return $resp['http_code'] >= 200 && $resp['http_code'] < 400 && $resp['body'] !== '';
    }
}
