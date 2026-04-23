<?php

namespace Modules\WordPressMigration\DTOs;

use DateTimeImmutable;

/**
 * One `<url>` entry from a sitemap, normalized for the Phase 4
 * sitemap crawler.
 *
 * The sitemap protocol (sitemaps.org) gives us four fields per URL —
 * `loc`, `lastmod`, `changefreq`, `priority` — and Yoast/RankMath/AIOSEO
 * add no per-URL metadata on top of that. What they DO add is the
 * concept of "this URL came from `post-sitemap.xml` vs `page-sitemap.xml`
 * vs `category-sitemap.xml`", which is the only signal the crawler has
 * for downstream mappers to pick the right Microweber content_type /
 * subtype. That inference is parked on `$contentType` so the
 * {@see \Modules\WordPressMigration\Services\WordPressContentMapper}
 * (or a later sitemap-specific mapper) can branch on it without
 * re-parsing URLs.
 *
 * All fields except `loc` may be null. Sitemap entries that omit
 * `loc` are dropped at parse time — they have no meaning — so the
 * constructor enforces a non-empty string.
 */
final class SitemapUrlEntry
{
    /**
     * @param string $loc Absolute URL of the content page (required by spec)
     * @param DateTimeImmutable|null $lastmod `<lastmod>` parsed as W3C Datetime, null on missing/unparseable
     * @param string|null $changefreq `<changefreq>` (always|hourly|daily|weekly|monthly|yearly|never), or null if not set
     * @param float|null $priority `<priority>` in [0.0, 1.0], or null if not set / non-numeric
     * @param string|null $contentType Heuristic type inferred from the parent sub-sitemap filename (post|page|category|...), null if the URL came from a flat urlset with no naming signal
     */
    public function __construct(
        public readonly string $loc,
        public readonly ?DateTimeImmutable $lastmod = null,
        public readonly ?string $changefreq = null,
        public readonly ?float $priority = null,
        public readonly ?string $contentType = null,
    ) {
        if ($loc === '') {
            throw new \InvalidArgumentException('SitemapUrlEntry requires a non-empty loc');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'loc' => $this->loc,
            'lastmod' => $this->lastmod?->format(DATE_ATOM),
            'changefreq' => $this->changefreq,
            'priority' => $this->priority,
            'content_type' => $this->contentType,
        ];
    }
}
