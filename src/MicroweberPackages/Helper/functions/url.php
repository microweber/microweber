<?php

if (! function_exists('mw_is_safe_remote_url')) {
    /**
     * AI-59 / TICKET-VV (cycle-66 2026-05-08): allowlist check for the
     * file-picker URL tab.
     *
     * Mirrors `mw.tools.isAllowedFileUrl()` (admin-tools.service.js) so
     * the client-side UX gate and the server-side acceptance gate
     * agree byte-for-byte on what counts as a safe remote URL.
     *
     * Returns true iff:
     *   - $url is a non-empty string
     *   - the scheme is `http` or `https` (case-insensitive); protocol-
     *     relative `//host/path` URLs are also accepted because they
     *     resolve to the page's own scheme — never to javascript:/data:
     *   - parse_url accepts the input AND returns a non-empty `host`
     *     (relative paths like `/foo/bar.jpg` are NOT remote URLs and
     *     should be handled by the local-asset code path, not this one)
     *
     * Used at the boundary where a user-typed URL is about to be
     * persisted or rendered. Rejecting `javascript:`, `data:`, `file:`,
     * `vbscript:` etc. closes the local-XSS surface where someone
     * pastes one of those into the picker and the saved value gets
     * wired into an `<img src>` / `<a href>` attribute.
     *
     * @param  mixed  $url
     * @return bool
     */
    function mw_is_safe_remote_url($url): bool
    {
        if (! is_string($url)) {
            return false;
        }
        $trimmed = trim($url);
        if ($trimmed === '') {
            return false;
        }

        // Protocol-relative `//host/path` is OK — browser resolves to
        // the current page's scheme (always http/https in any
        // page-load context).
        if (str_starts_with($trimmed, '//')) {
            $parts = parse_url('http:' . $trimmed);
            return is_array($parts) && ! empty($parts['host']);
        }

        $parts = parse_url($trimmed);
        if ($parts === false || empty($parts['host'])) {
            return false;
        }
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        return in_array($scheme, ['http', 'https'], true);
    }
}

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
