<?php

namespace Modules\WordPressMigration\Services\Media;

/**
 * Walk an HTML string, find every `<img src>` and `<a href>`, and
 * hand each URL to a {@see MediaRehoster}. Any URL the rehoster
 * returns a non-null rewrite for is substituted in-place; null
 * means "leave this URL alone".
 *
 * Design choices
 * --------------
 * - **Regex, not DOMDocument.** DOMDocument re-serializes the
 *   entire document, normalizing whitespace, quote style,
 *   self-closing tags, and sometimes escaping. That kind of
 *   rewrite is destructive for imported WordPress HTML where
 *   shortcode-rendered markup, `data-*` attributes, and
 *   pre-existing encoding quirks need to survive intact. A
 *   surgical attribute substitution keeps the rest of the HTML
 *   byte-for-byte identical.
 * - **Only `<img src>` and `<a href>`.** That's what the task
 *   scopes. `<video src>`, `srcset`, and `<source>` are handled
 *   by a future Phase 7 pass when the generic MediaRehoster
 *   lands — we don't want to quietly expand the mapping here and
 *   leave those tags half-rewritten.
 * - **Same-URL caching within a rewrite() call.** Imported posts
 *   very commonly reference the same hero image from both an
 *   `<img>` inside the body and an `<a href>` wrapping it. We
 *   cache the rehost result per URL so both end up pointing at
 *   the same new media record without a second download.
 * - **Origin-scoped rewriting.** When `$context['host']` is
 *   supplied, the rewriter filters URLs to the WP uploads origin
 *   *before* calling the rehoster — anything off-site is left
 *   alone without even asking. This matches the Phase 7 brief
 *   ("replaces every `<img src>`, `<a href>` to the old
 *   `wp-content/uploads/` origin with the rehosted URL; leaves
 *   off-site links alone"). A URL is in-scope if:
 *     1. Its path contains `/wp-content/uploads/` — WP uploads
 *        regardless of host (covers Jetpack/CDN rewrites like
 *        `i0.wp.com/site.example/wp-content/uploads/...`)
 *     2. Its host equals `$context['host']` or is a subdomain of
 *        it — catches non-upload same-site assets (theme images,
 *        legacy `/files/` paths)
 *     3. It has no host at all — relative URL, defer to rehoster
 *   Omit the host and the rewriter stays permissive (delegates
 *   every URL), which is the old behavior.
 *
 * What this class does NOT do
 * ---------------------------
 * - Resolve relative URLs: WordPress feeds emit absolute URLs
 *   for imports, and invoking URL resolution here would require
 *   parsing the whole document's `<base>` which isn't worth it
 *   for this use case.
 * - Decide whether a URL is "an asset" — that's the rehoster's
 *   call. A link to `/category/news/` might legitimately return
 *   null (not an asset) or a rewritten in-Microweber URL, and
 *   the rewriter has no business hard-coding either answer.
 */
final class HtmlMediaRewriter
{
    private const ATTR_PATTERNS = [
        // <img ... src="..."> — non-greedy body capture so we match
        // the correct closing quote even when other attributes follow.
        '/(?P<prefix><img\b[^>]*?\bsrc=)(?P<quote>["\'])(?P<url>.*?)(?P=quote)/i',
        '/(?P<prefix><a\b[^>]*?\bhref=)(?P<quote>["\'])(?P<url>.*?)(?P=quote)/i',
    ];

    /**
     * Rewrite `$html` by asking `$rehoster` to resolve every
     * in-scope `<img src>` and `<a href>`. Returns the new HTML.
     *
     * @param array<string, mixed> $context forwarded to the rehoster on every call.
     *   Recognised keys:
     *     - `host` (string) — origin WP site host; enables scoping
     *     - `rel_type`, `rel_id` — forwarded verbatim (not read here)
     */
    public function rewrite(string $html, MediaRehoster $rehoster, array $context = []): string
    {
        if ($html === '' || !str_contains($html, '<')) {
            return $html;
        }

        $originHost = isset($context['host']) && is_string($context['host']) && $context['host'] !== ''
            ? strtolower($context['host'])
            : null;

        /** @var array<string, string|null> $cache */
        $cache = [];

        $resolve = function (string $url) use ($rehoster, $context, $originHost, &$cache): ?string {
            // Normalize whitespace to preserve leading/trailing
            // spaces intentionally used in some rare feeds, but
            // cache by the trimmed URL so semantically identical
            // callers share a result.
            $key = trim($url);
            if ($key === '') {
                return null;
            }
            if (array_key_exists($key, $cache)) {
                return $cache[$key];
            }
            if ($originHost !== null && !self::isInScope($key, $originHost)) {
                $cache[$key] = null;
                return null;
            }
            $result = $rehoster->rehost($key, $context);
            $cache[$key] = $result;
            return $result;
        };

        foreach (self::ATTR_PATTERNS as $pattern) {
            $html = (string)preg_replace_callback(
                $pattern,
                function (array $m) use ($resolve): string {
                    $new = $resolve($m['url']);
                    if ($new === null) {
                        return $m[0];
                    }
                    return $m['prefix'] . $m['quote'] . self::escapeAttr($new) . $m['quote'];
                },
                $html
            );
        }

        return $html;
    }

    /**
     * Decide whether a URL should be handed to the rehoster when
     * the caller has declared an origin host. The three gates:
     *   1. Path contains `/wp-content/uploads/` — always yes
     *   2. URL host == originHost or subdomain thereof — yes
     *   3. URL has no host (relative path, or non-http scheme like
     *      mailto:/tel:) — yes, let the rehoster decide
     * Anything else is off-site and we bail out without a rehost
     * call.
     */
    private static function isInScope(string $url, string $originHost): bool
    {
        if (stripos($url, '/wp-content/uploads/') !== false) {
            return true;
        }

        $parsed = parse_url($url);
        if (!is_array($parsed) || !isset($parsed['host'])) {
            // No host — relative URL or opaque scheme. Rehoster
            // will return null for opaque schemes anyway; the
            // rewriter's job here is just not to pre-filter them.
            return true;
        }

        $urlHost = strtolower((string)$parsed['host']);
        if ($urlHost === $originHost) {
            return true;
        }
        // Subdomain match: urlHost ends in ".originHost" — keeps
        // example.com from matching evil-example.com while still
        // accepting cdn.example.com.
        if (str_ends_with($urlHost, '.' . $originHost)) {
            return true;
        }

        return false;
    }

    /**
     * Minimal attribute-value escape: the rehoster is trusted to
     * return a URL (not arbitrary HTML), but the URL might contain
     * `"` or `&` characters that would break out of the quoted
     * attribute if pasted verbatim.
     *
     * We specifically do NOT encode `/` or `?` — encoding those
     * would break legitimate query-string URLs like
     * `/userfiles/media/x.jpg?v=2`.
     */
    private static function escapeAttr(string $value): string
    {
        return str_replace(
            ['&', '"', '<', '>'],
            ['&amp;', '&quot;', '&lt;', '&gt;'],
            $value
        );
    }
}
