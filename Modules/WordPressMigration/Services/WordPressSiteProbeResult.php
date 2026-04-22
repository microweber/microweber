<?php

namespace Modules\WordPressMigration\Services;

/**
 * Immutable snapshot of a WordPressSiteProbe run.
 *
 * The Filament migration form renders these fields verbatim — so
 * `$capabilities` is already in operator-friendly order (the
 * preferred mode is first), and `$estimatedPosts` / `$estimatedPages`
 * are the numbers shown in the "we can pull N posts via REST" line.
 *
 * `$mode` is the single recommended importer mode for the Create
 * form's default ("REST" if available, else "RSS", else "Sitemap",
 * else "Scrape"). Operators can still override to any capability the
 * source exposes.
 */
final class WordPressSiteProbeResult
{
    public const MODE_REST = 'rest';
    public const MODE_RSS = 'rss';
    public const MODE_SITEMAP = 'sitemap';
    public const MODE_SCRAPE = 'scrape';
    public const MODE_UNREACHABLE = 'unreachable';

    /**
     * @param string $sourceUrl Normalized URL the probe was run against (with scheme + no trailing slash)
     * @param string $sourceHost Hostname extracted from the URL (idempotency-key component)
     * @param string $mode Recommended mode — one of MODE_* above
     * @param list<string> $capabilities Every mode the probe could reach, ordered by preference (rest > rss > sitemap > scrape)
     * @param bool $restEnabled True iff /wp-json resolved as a WordPress REST root
     * @param string|null $restNamespace The `namespaces[0]` or `name` reported by /wp-json (e.g. "WordPress", "wp/v2")
     * @param bool $rssReachable True iff /feed returned a non-empty RSS/Atom payload
     * @param bool $sitemapReachable True iff /sitemap.xml or /sitemap_index.xml returned XML
     * @param string|null $sitemapIndexUrl Which sitemap URL answered (sitemap.xml vs sitemap_index.xml)
     * @param int|null $estimatedPosts Best-effort post count (from /wp-json/wp/v2/posts?per_page=1 X-WP-Total, or RSS item count, or null)
     * @param int|null $estimatedPages Best-effort page count (REST only; null otherwise)
     * @param list<string> $disallowedPaths Paths from robots.txt we've been told not to crawl
     * @param list<string> $warnings Operator-facing notices (e.g. "REST present but app-password not supplied")
     * @param list<string> $errors Operator-facing errors (non-fatal; a mode may still be usable)
     */
    public function __construct(
        public readonly string $sourceUrl,
        public readonly string $sourceHost,
        public readonly string $mode,
        public readonly array $capabilities,
        public readonly bool $restEnabled,
        public readonly ?string $restNamespace,
        public readonly bool $rssReachable,
        public readonly bool $sitemapReachable,
        public readonly ?string $sitemapIndexUrl,
        public readonly ?int $estimatedPosts,
        public readonly ?int $estimatedPages,
        public readonly array $disallowedPaths,
        public readonly array $warnings,
        public readonly array $errors,
    ) {}

    public function isUsable(): bool
    {
        return $this->mode !== self::MODE_UNREACHABLE;
    }

    /**
     * Shape suitable for persistence on wp_migration_jobs (Phase 2)
     * or for JSON rendering back to a Filament form.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'source_url' => $this->sourceUrl,
            'source_host' => $this->sourceHost,
            'mode' => $this->mode,
            'capabilities' => $this->capabilities,
            'rest_enabled' => $this->restEnabled,
            'rest_namespace' => $this->restNamespace,
            'rss_reachable' => $this->rssReachable,
            'sitemap_reachable' => $this->sitemapReachable,
            'sitemap_index_url' => $this->sitemapIndexUrl,
            'estimated_posts' => $this->estimatedPosts,
            'estimated_pages' => $this->estimatedPages,
            'disallowed_paths' => $this->disallowedPaths,
            'warnings' => $this->warnings,
            'errors' => $this->errors,
        ];
    }
}
