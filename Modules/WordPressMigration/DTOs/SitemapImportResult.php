<?php

namespace Modules\WordPressMigration\DTOs;

/**
 * Outcome of a sitemap-driven page import run — returned by
 * {@see \Modules\WordPressMigration\Services\Importers\SitemapPageImporter::walk()}.
 *
 * Pairs the collected MigrationItemDTOs with a `stop_reason` from
 * the underlying {@see SitemapCrawlResult} (so the sitemap-level
 * outcome, e.g. `max_urls` or `unreachable`, flows through unchanged)
 * plus per-URL accounting — how many pages we fetched and how many
 * we had to skip for extraction failures. Operators looking at a
 * finished run need both numbers to distinguish "feed is small"
 * from "most of the site looked unreadable".
 */
final class SitemapImportResult
{
    /**
     * @param list<MigrationItemDTO> $items Successfully extracted items in crawl order
     * @param string $stopReason Underlying SitemapCrawlResult::STOP_* value
     * @param int $pagesFetched Total HTML pages whose body we actually downloaded
     * @param int $pagesSkipped Pages that returned non-2xx or failed extraction
     * @param list<string> $skippedUrls The URLs we skipped (for logging / retry UX)
     */
    public function __construct(
        public readonly array $items,
        public readonly string $stopReason,
        public readonly int $pagesFetched,
        public readonly int $pagesSkipped = 0,
        public readonly array $skippedUrls = [],
    ) {}
}
