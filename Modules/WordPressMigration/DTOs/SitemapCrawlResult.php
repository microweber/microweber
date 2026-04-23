<?php

namespace Modules\WordPressMigration\DTOs;

/**
 * Outcome of a sitemap crawl — returned by
 * {@see \Modules\WordPressMigration\Services\Importers\SitemapImporter::crawl()}.
 *
 * The crawler starts at a single sitemap URL (flat `<urlset>` or an
 * index pointing at sub-sitemaps), recursively resolves any nested
 * index entries, and flattens everything into a deduped list of URL
 * entries. As with the RSS walker, the operator-facing question "why
 * did the crawl stop?" is answered by a STOP_* constant:
 *
 *  - `complete`    The crawl walked every reachable sub-sitemap and
 *                  exhausted the discovered URL set without tripping
 *                  any cap. The normal happy-path termination.
 *  - `max_urls`    Collected `max_urls` entries and stopped mid-walk.
 *                  The operator may want to re-run with a larger cap
 *                  (or accept the truncation if the site really is
 *                  that big).
 *  - `unreachable` The initial sitemap URL itself didn't return a
 *                  parseable payload. The crawl produced no data.
 *                  Sub-sitemap 404s AFTER a valid root are tolerated
 *                  and do not trigger this stop — they just don't
 *                  contribute URLs.
 *  - `max_depth`   Reached the recursion cap on nested indexes. Real
 *                  sitemaps rarely go past two levels; this exists
 *                  to keep a hostile/circular index from running
 *                  forever rather than to model a real completion.
 */
final class SitemapCrawlResult
{
    public const STOP_COMPLETE = 'complete';
    public const STOP_MAX_URLS = 'max_urls';
    public const STOP_UNREACHABLE = 'unreachable';
    public const STOP_MAX_DEPTH = 'max_depth';

    /**
     * @param list<SitemapUrlEntry> $urls Deduped URL entries, in discovery order
     * @param int $sitemapsFetched Total number of sitemap XML documents whose body was parsed (root + sub-sitemaps)
     * @param string $stopReason One of the STOP_* constants above
     * @param list<string> $fetchedUrls Sitemap URLs actually requested, in order — useful for diagnostics
     */
    public function __construct(
        public readonly array $urls,
        public readonly int $sitemapsFetched,
        public readonly string $stopReason,
        public readonly array $fetchedUrls = [],
    ) {}
}
