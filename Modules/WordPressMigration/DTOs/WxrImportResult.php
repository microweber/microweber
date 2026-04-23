<?php

namespace Modules\WordPressMigration\DTOs;

/**
 * Outcome of a WXR (WordPress eXtended RSS) file import — returned by
 * {@see \Modules\WordPressMigration\Services\Importers\WxrImporter::import()}.
 *
 * A WXR file is WordPress's self-contained export format, shipped as a
 * single `<rss>` XML document with `wp:` namespaced extensions that
 * carry everything a site's canonical REST response would carry:
 *
 *   - `<item>` rows where `<wp:post_type>post|page|attachment` marks
 *     the row's kind. Posts + pages normalize into MigrationItemDTOs
 *     the same way the REST importer does.
 *   - `<wp:category>` / `<wp:tag>` blocks at the channel level — the
 *     authoritative term index, carrying slug + description rather
 *     than just the names RSS `<category>` repeats per item.
 *   - `<wp:author>` blocks at the channel level — richer than RSS's
 *     `<dc:creator>` (which only has a display name) and enough for
 *     the TaxonomyIndex to probe local users by slug/email.
 *   - `<item wp:post_type="attachment">` rows — the WXR equivalent of
 *     REST's `/wp/v2/media` collection; the media rehoster picks them
 *     up by `<wp:attachment_url>`.
 *
 * The DTO mirrors {@see WpRestImportResult} field-for-field so the
 * Filament page can reuse the REST runner's taxonomy-first + mapper
 * plumbing unchanged — the only difference is the origin of the
 * bytes.
 *
 * `stopReason` vocabulary matches the REST walker so the progress
 * bar doesn't have to branch on the importer. The set is narrower
 * though: no pagination, so `max_pages` is unreachable, and there's
 * no HTTP so `unreachable` is repurposed to mean "WXR file was
 * missing / unreadable / not a WP export".
 */
final class WxrImportResult
{
    public const STOP_COMPLETE = 'complete';
    public const STOP_MAX_ITEMS = 'max_items';
    public const STOP_UNREACHABLE = 'unreachable';

    /**
     * @param list<MigrationItemDTO> $items Posts + pages ready for the mapper (document order)
     * @param string $stopReason One of the STOP_* constants above
     * @param list<array<string, mixed>> $media Attachment rows (post_type=attachment) with url + id
     * @param list<array<string, mixed>>|null $menus WXR doesn't export menus as a first-class collection; kept null for shape parity with WpRestImportResult
     * @param list<string> $warnings Operator-facing notices (e.g. "WXR file has no wp:category blocks — term slugs will be derived from item categories")
     * @param list<array<string, mixed>> $categories Channel-level `<wp:category>` rows (id/slug/name/parent/description)
     * @param list<array<string, mixed>> $tags Channel-level `<wp:tag>` rows
     * @param list<array<string, mixed>> $users Channel-level `<wp:author>` rows (login/email/display_name)
     * @param int $itemsSeen Total <item> elements encountered — includes skipped drafts/trash, so callers can distinguish "file was empty" from "file was big but everything was filtered"
     */
    public function __construct(
        public readonly array $items,
        public readonly string $stopReason,
        public readonly array $media = [],
        public readonly ?array $menus = null,
        public readonly array $warnings = [],
        public readonly array $categories = [],
        public readonly array $tags = [],
        public readonly array $users = [],
        public readonly int $itemsSeen = 0,
    ) {}
}
