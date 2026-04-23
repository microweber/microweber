<?php

namespace Modules\WordPressMigration\DTOs;

use DateTimeImmutable;

/**
 * Output of {@see \Modules\WordPressMigration\Services\Extractors\SitemapPageExtractor}.
 *
 * Carries the fields the sitemap → content mapping needs: a title,
 * the extracted body HTML, the OpenGraph hero image, a publication
 * time, and an optional excerpt/author. Everything except the body
 * HTML is optional — the extractor returns null when it couldn't
 * confidently pin a value rather than guessing, and the mapper then
 * falls back to its defaults (e.g. deriving an excerpt from the
 * body) instead of propagating wrong data.
 *
 * The canonical URL is kept alongside the extraction output so the
 * mapper can stamp it onto the resulting MigrationItemDTO without
 * re-parsing the HTML.
 */
final class ExtractedPageDTO
{
    /**
     * @param string $title Page title — prefers og:title > twitter:title > <title> > first <h1>
     * @param string $html Body HTML, chrome-stripped. May be empty when extraction finds nothing useful.
     * @param string|null $excerpt Meta description / og:description, or null if neither was present
     * @param string|null $author Display name from og:article / meta[name=author] / JSON-LD, or null
     * @param string|null $ogImage Absolute URL of the hero image, or null when missing
     * @param DateTimeImmutable|null $publishedAt Article published time, or null when no date tag was found
     * @param string $canonicalUrl URL fetched to produce this extraction — used as the item's GUID/permalink
     * @param list<string> $warnings Non-fatal parse anomalies (e.g. "no main body found, used whole document")
     */
    public function __construct(
        public readonly string $title,
        public readonly string $html,
        public readonly ?string $excerpt = null,
        public readonly ?string $author = null,
        public readonly ?string $ogImage = null,
        public readonly ?DateTimeImmutable $publishedAt = null,
        public readonly string $canonicalUrl = '',
        public readonly array $warnings = [],
    ) {}

    /**
     * True when the extractor produced a body HTML string AND a
     * non-empty title. An extraction missing either of these is
     * unusable for Microweber content creation (the `content` table
     * requires title, and an empty body produces a visibly broken
     * page).
     */
    public function isUsable(): bool
    {
        return $this->title !== '' && $this->html !== '';
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'html' => $this->html,
            'excerpt' => $this->excerpt,
            'author' => $this->author,
            'og_image' => $this->ogImage,
            'published_at' => $this->publishedAt?->format(DATE_ATOM),
            'canonical_url' => $this->canonicalUrl,
            'warnings' => $this->warnings,
        ];
    }
}
