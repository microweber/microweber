<?php

namespace Modules\WordPressMigration\DTOs;

/**
 * Outcome of a WordPress REST API import run — returned by
 * {@see \Modules\WordPressMigration\Services\Importers\WpRestImporter::walk()}.
 *
 * The REST pipeline is richer than the RSS/sitemap pipelines because
 * `/wp-json/wp/v2/*` splits into orthogonal collections (posts,
 * pages, media, categories, tags, users, comments, menus) that all
 * need to land somewhere downstream:
 *
 *   - `items` — post + page rows normalized into MigrationItemDTOs,
 *     enriched with author names + category/tag term labels resolved
 *     via the collected lookup tables. These are what the
 *     WordPressContentMapper consumes.
 *   - `media` — raw WP media rows. The Phase 7 MediaRehoster can
 *     pre-warm its queue from this list rather than waiting to
 *     discover assets in post HTML.
 *   - `comments` — raw comment rows keyed by `post`. Phase 3 doesn't
 *     ingest them yet, but surfacing them here means the Filament
 *     preview UI can show a "N comments will be imported" count
 *     without a second REST pass.
 *   - `menus` — either the rows returned by the WP Menus endpoint
 *     (WP 5.9+ / `wp/v2/menus`), or null when the endpoint isn't
 *     exposed. Null lets callers distinguish "endpoint missing"
 *     from "endpoint returned zero menus".
 *
 * Ancillary stop_reasons mirror FeedWalkResult's vocabulary so the
 * Filament progress bar doesn't have to branch on the importer.
 */
final class WpRestImportResult
{
    public const STOP_COMPLETE = 'complete';
    public const STOP_MAX_ITEMS = 'max_items';
    public const STOP_MAX_PAGES = 'max_pages';
    public const STOP_UNREACHABLE = 'unreachable';

    /**
     * @param list<MigrationItemDTO> $items Posts + pages ready for the mapper (in newest-first order)
     * @param int $pagesFetched Total REST pages (across posts + pages endpoints) whose body was parsed
     * @param string $stopReason One of the STOP_* constants above
     * @param list<array<string, mixed>> $media Raw media rows as returned by `/wp-json/wp/v2/media`
     * @param list<array<string, mixed>> $comments Raw comment rows as returned by `/wp-json/wp/v2/comments`
     * @param list<array<string, mixed>>|null $menus Raw menu rows, or null if the endpoint isn't exposed
     * @param list<string> $fetchedUrls URLs actually requested, in order — useful for logging
     * @param list<string> $warnings Operator-facing notices (e.g. "users endpoint 401 — falling back to post.author.slug")
     */
    public function __construct(
        public readonly array $items,
        public readonly int $pagesFetched,
        public readonly string $stopReason,
        public readonly array $media = [],
        public readonly array $comments = [],
        public readonly ?array $menus = null,
        public readonly array $fetchedUrls = [],
        public readonly array $warnings = [],
    ) {}
}
