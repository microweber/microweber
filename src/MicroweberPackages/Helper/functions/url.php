<?php

if (! function_exists('safe_css_url')) {
    /**
     * Sanitize a URL for safe interpolation inside an inline CSS `url('...')` value.
     *
     * audit-test 2026-05-07 (TICKET-AV) — closes a project-wide CSS-injection
     * pattern: `style="background-image: url('{{ $picture }}')"` is HTML-escaped
     * by Blade but the browser HTML-decodes the attribute BEFORE handing it to
     * the CSS parser, so an admin URL containing `'` or `)` could break out of
     * the url(...) call and inject arbitrary CSS rules. This helper:
     *   1. Rejects javascript:/data:/vbscript: schemes outright.
     *   2. Backslash-escapes characters that can terminate the CSS string
     *      (`'`, `"`, `(`, `)`, `\`, NL/CR) per the CSS-string escape spec.
     * After escape, the CSS parser sees `url('http://example.com/x\'.jpg')`
     * — `\'` is a literal apostrophe inside the string, no string-termination,
     * no rule-injection. Used at the call site:
     *   <div style="background-image: url('{{ safe_css_url($picture) }}');">
     * Long-term, prefer migrating background-image patterns to real `<img>`
     * tags (better a11y + perf) — see TICKET-AV for the call-site sweep.
     *
     * @param  string|null  $url
     * @return string
     */
    function safe_css_url($url): string
    {
        if (! is_string($url) || $url === '') {
            return '';
        }

        $trimmed = ltrim($url);
        if (preg_match('#^(javascript|data|vbscript):#i', $trimmed)) {
            return '';
        }

        return addcslashes($url, "\\\"'()\n\r");
    }
}
