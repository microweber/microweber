<?php

declare(strict_types=1);

namespace MicroweberPackages\Security;

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