<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * AI-58 / TICKET-RR (cycle-65 2026-05-08): allowlist row for tracked
 * click-link redirects. The /click-link route consults this table
 * (in addition to the cycle-7 same-host fallback) when deciding
 * whether a redirect_to URL is trusted.
 *
 * Host pattern semantics:
 *   - exact match: `example.com`        → matches example.com only
 *   - wildcard:    `*.example.com`      → matches any subdomain
 *                                          (foo.example.com, a.b.example.com)
 *                                          but NOT example.com itself
 * Pattern matching is case-insensitive. Scheme is NOT part of the
 * pattern (the route already restricts to http/https).
 */
class NewsletterTrackedLinkAllowlist extends Model
{
    public $table = 'newsletter_tracked_link_allowlist';

    public $fillable = [
        'host_pattern',
        'note',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'created_by' => 'int',
        'updated_by' => 'int',
    ];

    /**
     * Check whether a given URL's host matches any active allowlist row.
     *
     * Pure function: takes a URL string, returns bool. Same-host
     * matches are NOT covered here — the route handles that as a
     * separate cycle-7 fallback.
     */
    public static function urlIsAllowed(string $url): bool
    {
        if ($url === '') {
            return false;
        }

        $parts = parse_url($url);
        if ($parts === false || empty($parts['host'])) {
            return false;
        }

        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        if (! in_array($scheme, ['http', 'https'], true)) {
            return false;
        }

        $host = strtolower((string) $parts['host']);

        $rows = static::query()
            ->where('is_active', true)
            ->get(['host_pattern']);

        foreach ($rows as $row) {
            if (static::hostMatchesPattern($host, (string) $row->host_pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Match a host against a single allowlist pattern.
     *
     * Public so tests can pin the pattern semantics without seeding
     * the table.
     */
    public static function hostMatchesPattern(string $host, string $pattern): bool
    {
        $host = strtolower(trim($host));
        $pattern = strtolower(trim($pattern));

        if ($host === '' || $pattern === '') {
            return false;
        }

        if (str_starts_with($pattern, '*.')) {
            $suffix = substr($pattern, 2);
            // Wildcard means "subdomain of suffix" — explicitly NOT
            // the bare suffix itself, otherwise `*.example.com` would
            // shadow the more-specific exact `example.com` row.
            return $suffix !== ''
                && str_ends_with($host, '.' . $suffix);
        }

        return $host === $pattern;
    }
}
