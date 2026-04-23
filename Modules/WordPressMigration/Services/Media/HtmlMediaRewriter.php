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
     * `<img src>` and `<a href>`. Returns the new HTML.
     *
     * @param array<string, mixed> $context forwarded to the rehoster on every call
     */
    public function rewrite(string $html, MediaRehoster $rehoster, array $context = []): string
    {
        if ($html === '' || !str_contains($html, '<')) {
            return $html;
        }

        /** @var array<string, string|null> $cache */
        $cache = [];

        $resolve = function (string $url) use ($rehoster, $context, &$cache): ?string {
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
