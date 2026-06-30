<?php

namespace MicroweberPackages\Http;

/**
 * Guard against Server-Side Request Forgery (SSRF) when the application
 * fetches a URL on behalf of an end-user (image grab, RSS pull, OEmbed
 * lookup, etc.).
 *
 * Usage:
 *     UrlFetchGuard::assertSafe($userSuppliedUrl);
 *
 * Redirect contract — IMPORTANT
 * -----------------------------
 * `assertSafe()` validates ONE URL. A `301`/`302`/`307`/`308` response can
 * point at a metadata IP even after the first hop validated as public; this
 * is the classic SSRF bypass. Callers MUST disable HTTP-client auto-redirect
 * and re-call `assertSafe()` for every Location header before fetching the
 * next hop. Cap the redirect count (5) to prevent loops.
 */
class UrlFetchGuard
{
    /**
     * @throws \InvalidArgumentException when the URL is not safe to fetch.
     */
    public static function assertSafe(string $url): void
    {
        $parts = parse_url($url);

        if ($parts === false || empty($parts['host']) || empty($parts['scheme'])) {
            throw new \InvalidArgumentException('URL is malformed.');
        }

        $scheme = strtolower($parts['scheme']);
        if ($scheme !== 'http' && $scheme !== 'https') {
            throw new \InvalidArgumentException('Only http(s) URLs are allowed.');
        }

        $host = $parts['host'];

        // IPv6 literal in a URL is wrapped in brackets — strip them so
        // filter_var() and the IPv4-mapped check below can read it.
        if (str_starts_with($host, '[') && str_ends_with($host, ']')) {
            $host = substr($host, 1, -1);
        }

        // Resolve the host to every A/AAAA record and check each.
        $ips = self::resolveAll($host);
        if (empty($ips)) {
            throw new \InvalidArgumentException('Host could not be resolved.');
        }

        foreach ($ips as $ip) {
            if (! self::isPublicIp(self::normalizeIp($ip))) {
                throw new \InvalidArgumentException(
                    'URL resolves to a non-public IP range.'
                );
            }
        }
    }

    /**
     * Collapse IPv4-mapped IPv6 (e.g. ::ffff:169.254.169.254) down to its
     * IPv4 form so the public-IP filter cannot be bypassed by wrapping the
     * AWS metadata address in IPv6 syntax.
     */
    private static function normalizeIp(string $ip): string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            return $ip;
        }

        $packed = @inet_pton($ip);
        if ($packed === false) {
            return $ip;
        }

        // ::ffff:0:0/96 = IPv4-mapped IPv6 prefix (12 leading bytes:
        // 10 zero bytes + 0xff 0xff). Last 4 bytes are the embedded v4.
        if (strlen($packed) === 16
            && substr($packed, 0, 10) === str_repeat("\0", 10)
            && substr($packed, 10, 2) === "\xff\xff") {
            $v4 = @inet_ntop(substr($packed, 12, 4));
            if ($v4 !== false) {
                return $v4;
            }
        }

        return $ip;
    }

    /**
     * @return string[]
     */
    private static function resolveAll(string $host): array
    {
        // If host is already a literal IP, validate it directly.
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return [$host];
        }

        $ips = [];
        $records = @dns_get_record($host, DNS_A | DNS_AAAA);
        if (is_array($records)) {
            foreach ($records as $r) {
                if (! empty($r['ip'])) {
                    $ips[] = $r['ip'];
                }
                if (! empty($r['ipv6'])) {
                    $ips[] = $r['ipv6'];
                }
            }
        }

        if (empty($ips)) {
            $a = @gethostbyname($host);
            if ($a && $a !== $host) {
                $ips[] = $a;
            }
        }

        return $ips;
    }

    private static function isPublicIp(string $ip): bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6
            | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) !== false;
    }
}