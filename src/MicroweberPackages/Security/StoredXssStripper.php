<?php

declare(strict_types=1);

namespace MicroweberPackages\Security;

/**
 * AI-130 / SEC-05 (cycle-123 2026-05-09): stored-XSS rich-text
 * stripper.
 *
 * Brief: "Rich-text: strip <img onerror> and <svg onload> (stored
 * XSS)." Targets the OWASP A03 stored-XSS vector where an admin
 * (or compromised admin session) writes HTML into a content body
 * that another user later renders.
 *
 * The full HTMLPurifier-backed `MwHtmlSanitizer` already covers
 * most cases via its allowlist model — but it ships with a few
 * legacy carveouts for `onclick`-style attributes inside
 * trusted-author surfaces. This stripper is the explicit
 * defense-in-depth pass that runs at SAVE time on every content
 * body, no matter who the author is, regardless of any
 * MwHtmlSanitizer toggle.
 *
 * Strips:
 *
 *   1. Every `on*=` attribute on every tag (onerror, onload,
 *      onclick, onmouseover, etc.). The browser executes these
 *      regardless of where they're attached, so the only safe
 *      strategy is "remove them all".
 *
 *   2. `<script>...</script>` tags entirely (block-level so the
 *      whitespace + indentation collapses cleanly).
 *
 *   3. `<svg>` tags whose body contains `<script>` OR any `on*=`
 *      attribute. Plain decorative SVGs pass through unchanged
 *      (a strict <svg> blanket-strip would break legitimate icon
 *      libraries).
 *
 *   4. `javascript:` / `data:text/html` / `vbscript:` href values
 *      — collapses to `#` so the link still renders but does
 *      nothing.
 *
 * Pure-string regex implementation (no DOM parser) — runs in
 * O(n) on the input, no memory pressure on long bodies, no
 * dependencies.
 */
final class StoredXssStripper
{
    /**
     * Strip stored-XSS vectors from an HTML string.
     */
    public static function strip(string $html): string
    {
        if ($html === '') {
            return $html;
        }

        /*
         * IMPORTANT: pass order matters.
         *
         *   Pass 1 (SVG with handlers): runs FIRST so we can detect
         *     `<svg onload=...>` while the on*= attribute is still
         *     intact. Otherwise pass 2 would strip the on*= attribute
         *     first, leaving `<svg></svg>` which looks safe to pass 1.
         *
         *   Pass 2 (script blocks): can run before or after pass 1;
         *     but running here lets it strip ANY remaining script
         *     (e.g. inside other elements after pass 1 normalized
         *     the SVGs).
         *
         *   Pass 3 (event handlers): runs LAST since pass 1 already
         *     consumed the SVG-internal handlers.
         *
         *   Pass 4 (URL schemes): independent — runs at the end.
         */

        // 1. SVG with embedded scripts or event handlers — strip
        //    the whole element BEFORE the on*= sweep below blanks
        //    the attributes.
        $html = preg_replace_callback(
            '#<svg\\b[^>]*>([\\s\\S]*?)</svg\\s*>#i',
            function ($m) {
                $body = $m[0];
                if (
                    preg_match('/<script\\b/i', $body)
                    || preg_match('/\\son[a-z]+\\s*=/i', $body)
                ) {
                    return '';
                }
                return $body;
            },
            $html
        );

        // 2. <script> blocks (greedy match — multiple non-nested
        //    blocks handled by /U with the `?` quantifier wouldn't
        //    work because attributes can contain unescaped `>`;
        //    the safer pattern is `[\s\S]*?` ungreedy).
        $html = preg_replace(
            '#<script\\b[^>]*>[\\s\\S]*?</script\\s*>#i',
            '',
            $html
        );

        // 3. on* event-handler attributes. Match `on<name>=` with
        //    either single-quote, double-quote, or unquoted value.
        $html = preg_replace(
            '/\\s+on[a-z]+\\s*=\\s*(?:"[^"]*"|\'[^\']*\'|[^\\s>]+)/i',
            '',
            $html
        );

        // 4. Dangerous URL schemes inside href / src / action /
        //    formaction / xlink:href. Match the OPENING quote, then
        //    either `javascript:` / `vbscript:` (no slash) OR
        //    `data:text/html` (slash already in the keyword), then
        //    consume everything up to the CLOSING quote and replace
        //    with `"#"`.
        $html = preg_replace(
            '/((?:href|src|action|formaction|xlink:href)\\s*=\\s*)(["\'])\\s*(?:javascript:|vbscript:|data:text\\/html)[^"\']*\\2/i',
            '$1$2#$2',
            $html
        );

        return $html;
    }
}
