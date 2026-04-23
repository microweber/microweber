<?php

namespace Modules\WordPressMigration\DTOs;

use DateTimeImmutable;

/**
 * Canonical, importer-agnostic shape of one imported item.
 *
 * Every source (RSS/Atom, sitemap scrape, WP REST, WXR upload)
 * normalizes into this DTO before the item is staged or inserted
 * into Microweber's `content` table, so downstream consumers
 * (dedupe, HTML rewriter, media rehoster, Filament preview) can be
 * written exactly once and don't need to branch on the originating
 * importer.
 *
 * Design notes:
 *  - `guid` is the stable identity key for idempotent re-runs. For
 *    RSS that's the feed's `<guid>` (or `<id>` for Atom); for REST
 *    it's `wp:{post_id}`; for WXR the `<wp:post_id>`. Importers are
 *    responsible for producing a value that doesn't change between
 *    runs of the same source.
 *  - `html` is the body as published. Importers do NOT strip or
 *    rewrite — that's the HtmlMediaRewriter's job (Phase 7).
 *  - `excerpt` is optional; null means "downstream should derive
 *    from html if it wants one". We don't manufacture excerpts
 *    here because RSS, Atom, and REST all emit the excerpt field
 *    the publisher intended.
 *  - `publishedAt` uses DateTimeImmutable so serialization into a
 *    Microweber `content` row (which expects ISO strings) is a
 *    single ->format() call without timezone surprises.
 *  - `canonicalUrl` is the permalink on the origin site. Kept so
 *    the media rehoster can resolve relative asset URLs inside
 *    `html` and so the dedupe layer can fall back to URL identity
 *    when a feed omits `<guid>`.
 */
final class MigrationItemDTO
{
    /**
     * @param string $guid Stable identity key used for idempotent re-runs
     * @param string $title
     * @param string $html Body HTML, as published — no rewriting yet
     * @param string|null $excerpt Publisher-provided summary, or null if none
     * @param string|null $author Display name; importers map their source-specific IDs elsewhere
     * @param list<string> $categories
     * @param list<string> $tags
     * @param DateTimeImmutable|null $publishedAt Original publication time (timezone preserved)
     * @param string|null $canonicalUrl Permalink on the origin site
     * @param string $source The importer that produced this DTO ("rss", "rest", "wxr", "sitemap", "scrape")
     * @param string|null $sourceHost Hostname of the origin site — handy for logging/diagnostics
     * @param string|null $featuredImageUrl Origin URL of the featured/hero image, or null if the
     *        publisher never attached one. The REST importer populates this by resolving
     *        `featured_media` (post ID) against the `/wp-json/wp/v2/media` index; the RSS importer
     *        currently leaves it null because RSS has no equivalent first-class field. The mapper
     *        uses it to attach a Microweber `picture` media row to the content row so frontends
     *        that render via `content_picture($id)` display the WP featured image.
     */
    public function __construct(
        public readonly string $guid,
        public readonly string $title,
        public readonly string $html,
        public readonly ?string $excerpt = null,
        public readonly ?string $author = null,
        public readonly array $categories = [],
        public readonly array $tags = [],
        public readonly ?DateTimeImmutable $publishedAt = null,
        public readonly ?string $canonicalUrl = null,
        public readonly string $source = 'unknown',
        public readonly ?string $sourceHost = null,
        public readonly ?string $featuredImageUrl = null,
    ) {}

    /**
     * Serialize to the shape used by downstream dedupe/preview/insert
     * code. Dates go through ATOM so consumers can parse without
     * ambiguity; list fields are always present (never null) so
     * consumers don't have to `?? []`.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'guid' => $this->guid,
            'title' => $this->title,
            'html' => $this->html,
            'excerpt' => $this->excerpt,
            'author' => $this->author,
            'categories' => $this->categories,
            'tags' => $this->tags,
            'published_at' => $this->publishedAt?->format(DATE_ATOM),
            'canonical_url' => $this->canonicalUrl,
            'source' => $this->source,
            'source_host' => $this->sourceHost,
            'featured_image_url' => $this->featuredImageUrl,
        ];
    }
}
