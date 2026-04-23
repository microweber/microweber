<?php

namespace Modules\WordPressMigration\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Produce a Microweber `content.url` slug that preserves the
 * original path from a source URL.
 *
 * Microweber stores a single slug per content row (the last-segment
 * of the URL) and its permalink manager resolves requests by that
 * last segment. So when a WordPress post at
 * `https://wp.example/category/my-post/` gets imported, we want the
 * resulting row's `url` to be `my-post` — which means an internal
 * link like `<a href="/category/my-post/">` still lands on the
 * right Microweber row once the host is rewritten to the destination
 * site, without any body-HTML rewrite of the slug itself.
 *
 * This class lives outside {@see WordPressContentMapper} so it stays
 * independently testable against the `content` + `categories` tables
 * without dragging a full Content model through unit tests, and so
 * other Phase 3/4 importers (REST, WXR, sitemap) can reuse the same
 * resolver without re-implementing the collision loop.
 *
 * Collision strategy
 * ------------------
 * WordPress enforces per-site slug uniqueness, but a single
 * Microweber install may import from multiple WP sites whose slugs
 * conflict. We pick a free variant with a deterministic `-N` suffix
 * (2, 3, 4, …) rather than the `Y-m-d-His` timestamp the generic
 * {@see \MicroweberPackages\Database\Traits\HasSlugTrait} falls back
 * to — a human-readable `my-post-2` is kinder to both the operator
 * who notices the collision and to any external links that approximate
 * the slug.
 *
 * The collision loop is bounded (`MAX_COLLISION_ATTEMPTS`) so a
 * pathological table full of `my-post`/`my-post-2`/… doesn't cause an
 * unbounded walk; at the cap we fall through to the timestamp
 * disambiguator so at least the insert completes.
 */
class SourceSlugResolver
{
    private const MAX_COLLISION_ATTEMPTS = 100;

    /**
     * Resolve a preserved slug from a canonical URL.
     *
     * Returns null when the URL carries no usable path segment
     * (root-only, query-string-only, or unparseable). Callers then
     * fall back to the default title-based slug generation and do
     * not override the content's `url` field.
     *
     * @param string $canonicalUrl Origin permalink — anything `parse_url` can handle, including relative-looking inputs
     * @param int|null $excludeContentId Content id currently being upserted; its own url row is not a collision
     */
    public function resolve(string $canonicalUrl, ?int $excludeContentId = null): ?string
    {
        $base = $this->extractLastSegment($canonicalUrl);
        if ($base === null) {
            return null;
        }

        // A slug that doesn't collide on the first try is the happy
        // path — no disambiguator appended. The suffix-N loop only
        // runs when another row already claims the base slug.
        if (!$this->slugExists($base, $excludeContentId)) {
            return $base;
        }

        for ($i = 2; $i <= self::MAX_COLLISION_ATTEMPTS; $i++) {
            $candidate = $base . '-' . $i;
            if (!$this->slugExists($candidate, $excludeContentId)) {
                return $candidate;
            }
        }

        // Pathological fallback: 100+ collisions on the same base.
        // Use a timestamp suffix so the insert isn't blocked; the
        // operator will see a non-pretty slug, which is better than
        // a failed import. This mirrors HasSlugTrait's own last-ditch
        // strategy so nothing downstream gets surprised by the shape.
        return $base . '-' . date('YmdHis');
    }

    /**
     * Pull the last non-empty path segment out of a URL, url-decode
     * it, lowercase it, and keep only chars that are legal in a
     * Microweber slug (letters, digits, hyphen, underscore). This
     * matches what `HasSlugTrait` would normalize the value into
     * anyway, so we do the cleanup here to make the subsequent
     * collision check see the final shape.
     */
    private function extractLastSegment(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (!is_string($path) || $path === '') {
            return null;
        }

        // Strip query and fragment that a caller may have handed us
        // unparsed. Shouldn't happen once `parse_url` ran, but cheap.
        $path = strtok($path, '?#');
        if (!is_string($path)) {
            return null;
        }

        $segments = array_values(array_filter(
            explode('/', $path),
            static fn (string $s): bool => $s !== '',
        ));
        if ($segments === []) {
            return null;
        }

        $last = rawurldecode((string)end($segments));
        // Reject extension-only pseudo-pages that aren't real
        // content: `/index.html`, `/feed.xml`, etc.
        if (preg_match('/^\\.?$/', $last) || preg_match('/^index\\.(?:html?|php)$/i', $last)) {
            return null;
        }

        $slug = strtolower($last);
        // Mirror UrlManager::slug() exactly: collapse every run of
        // non-letter/non-digit chars into a single `-`, then trim
        // leading/trailing `-`. Using the exact same pattern means
        // whatever we produce here round-trips through HasSlugTrait's
        // creating hook unchanged, so our pre-save collision check
        // sees the same slug the DB ends up with.
        $slug = preg_replace('/[^\\p{L}\\d]+/u', '-', $slug) ?? '';
        $slug = trim($slug, '-');

        return $slug === '' ? null : $slug;
    }

    private function slugExists(string $slug, ?int $excludeContentId): bool
    {
        if ($slug === '') {
            return false;
        }

        $contentQuery = DB::table('content')->where('url', $slug);
        if ($excludeContentId !== null) {
            $contentQuery->where('id', '!=', $excludeContentId);
        }
        if ($contentQuery->exists()) {
            return true;
        }

        // Categories share the slug namespace with content
        // (Microweber's permalink resolver checks both), so a
        // category `news` blocks the post slug `news`.
        if (Schema::hasTable('categories')) {
            if (DB::table('categories')->where('url', $slug)->exists()) {
                return true;
            }
        }

        return false;
    }
}
