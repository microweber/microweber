<?php

namespace MicroweberPackages\Url;

/**
 * URL security utilities - safe URL validation and CSS URL sanitization.
 */
class UrlSecurity
{
    /**
     * Check if a URL is a safe remote URL (http/https scheme, has a host).
     *
     * Returns true iff:
     *   - $url is a non-empty string
     *   - the scheme is `http` or `https` (case-insensitive); protocol-
     *     relative `//host/path` URLs are also accepted
     *   - parse_url accepts the input AND returns a non-empty `host`
     *
     * @param  mixed  $url
     * @return bool
     */
    public static function isSafeRemoteUrl($url): bool
    {
        if (! is_string($url)) {
            return false;
        }
        $trimmed = trim($url);
        if ($trimmed === '') {
            return false;
        }

        // Protocol-relative `//host/path` is OK
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

    /**
     * Sanitize a URL for safe interpolation inside an inline CSS `url('...')` value.
     *
     * Rejects javascript:/data:/vbscript: schemes outright.
     * Backslash-escapes characters that can terminate the CSS string.
     *
     * @param  string|null  $url
     * @return string
     */
    public static function safeCssUrl($url): string
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