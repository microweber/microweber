<?php

namespace Modules\WordPressMigration\DTOs;

/**
 * Outcome of a paginated feed walk — returned by
 * {@see \Modules\WordPressMigration\Services\Importers\RssFeedImporter::walk()}.
 *
 * The walker needs to tell the caller not just *what* it fetched
 * but *why it stopped*, because each stop condition has a distinct
 * operator-facing meaning:
 *
 *  - `empty_page`   The feed ran out — we fetched a page with zero
 *                   items. This is the normal completion path for
 *                   small sites and is not a failure.
 *  - `seen_guid`    A guid we were told to treat as already-imported
 *                   showed up, so everything older is also already
 *                   imported (WP feeds are newest-first). Normal
 *                   incremental-re-run completion.
 *  - `max_items`    Hit the job-configured items cap. The operator
 *                   may want to re-run with a larger cap.
 *  - `max_pages`    Hit the safety cap on page count — shouldn't
 *                   happen against a real WP site, but prevents a
 *                   runaway walk against a broken source.
 *  - `unreachable`  Page 1 itself didn't yield a parseable feed.
 */
final class FeedWalkResult
{
    public const STOP_EMPTY_PAGE = 'empty_page';
    public const STOP_SEEN_GUID = 'seen_guid';
    public const STOP_MAX_ITEMS = 'max_items';
    public const STOP_MAX_PAGES = 'max_pages';
    public const STOP_UNREACHABLE = 'unreachable';

    /**
     * @param list<MigrationItemDTO> $items In feed order (newest-first)
     * @param int $pagesFetched Number of HTTP pages whose body was parsed
     * @param string $stopReason One of the STOP_* constants above
     * @param list<string> $fetchedUrls URLs actually requested, in order — useful for logging/diagnostics
     */
    public function __construct(
        public readonly array $items,
        public readonly int $pagesFetched,
        public readonly string $stopReason,
        public readonly array $fetchedUrls = [],
    ) {}
}
