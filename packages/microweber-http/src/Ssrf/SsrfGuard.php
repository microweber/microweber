<?php

declare(strict_types=1);

namespace MicroweberPackages\Http\Ssrf;

/**
 * SSRF guard — validates that a user-supplied URL points at an externally
 * reachable host.
 *
 * Rejects loopback, private IPv4 ranges, link-local addresses,
 * localhost / .localhost / .test / .invalid sandbox suffixes, and
 * non-http schemes.
 */
final class SsrfGuard
{
    /**
     * Standard private + reserved IPv4 ranges (CIDR).
     */
    private const PRIVATE_CIDRS = [
        '127.0.0.0/8',     // Loopback
        '10.0.0.0/8',      // RFC1918 private
        '172.16.0.0/12',   // RFC1918 private
        '192.168.0.0/16',  // RFC1918 private
        '169.254.0.0/16',  // Link-local
        '0.0.0.0/8',       // Current network / "this host"
        '224.0.0.0/4',     // Multicast
        '240.0.0.0/4',     // Reserved
        '100.64.0.0/10',   // Carrier-grade NAT (RFC6598)
    ];

    /**
     * IPv6 ranges that map to "private / loopback / link-local /
     * unique-local / IPv4-mapped" — same blocking semantics.
     */
    private const PRIVATE_IPV6_PREFIXES = [
        '::1',        // Loopback
        'fe80::',     // Link-local
        'fc00::',     // Unique local (fc00::/7)
        'fd00::',     // Unique local (fd00::/7)
    ];

    /**
     * Hostname suffixes that indicate "internal sandbox" — never
     * legitimately reachable on the public internet.
     */
    private const RESERVED_TLDS = [
        '.localhost',
        '.local',
        '.test',
        '.invalid',
        '.example',
        '.localdomain',
    ];

    /**
     * Is this URL safe to fetch from a server-side context?
     *
     * Returns false for any URL whose host:
     *   - parses to a literal private/loopback/link-local IP, OR
     *   - resolves (via DNS) to an IP in a private range, OR
     *   - has a reserved TLD suffix (`.localhost`, `.test`, etc.), OR
     *   - is the literal string "localhost".
     *
     * Returns false for a malformed URL (no scheme/host).
     */
    public static function isExternallyReachable(string $url): bool
    {
        $parts = parse_url($url);
        if (!$parts || empty($parts['host']) || empty($parts['scheme'])) {
            return false;
        }

        $scheme = strtolower((string) $parts['scheme']);
        if ($scheme !== 'http' && $scheme !== 'https') {
            return false;
        }

        $host = strtolower((string) $parts['host']);
        if ($host === '' || $host === 'localhost') {
            return false;
        }

        foreach (self::RESERVED_TLDS as $suffix) {
            if (str_ends_with($host, $suffix)) {
                return false;
            }
        }

        // If the host parses as a literal IP, check the ranges directly.
        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return !self::isPrivateIpv4($host);
        }
        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return !self::isPrivateIpv6($host);
        }

        // Resolve all A + AAAA records. If ANY resolved IP is
        // private, deny — this is the SSRF-rebinding mitigation.
        $ips = self::resolveHost($host);
        if (empty($ips)) {
            return false;
        }
        foreach ($ips as $ip) {
            if (str_contains($ip, ':')) {
                if (self::isPrivateIpv6($ip)) {
                    return false;
                }
            } else {
                if (self::isPrivateIpv4($ip)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * IPv4 range membership check (CIDR).
     */
    public static function isPrivateIpv4(string $ip): bool
    {
        $packed = inet_pton($ip);
        if ($packed === false || strlen($packed) !== 4) {
            return false;
        }
        $ipLong = ip2long($ip);
        if ($ipLong === false) {
            return false;
        }
        foreach (self::PRIVATE_CIDRS as $cidr) {
            [$net, $bits] = explode('/', $cidr);
            $netLong = ip2long($net);
            if ($netLong === false) {
                continue;
            }
            $mask = $bits === '0' ? 0 : (-1 << (32 - (int) $bits)) & 0xFFFFFFFF;
            if (($ipLong & $mask) === ($netLong & $mask)) {
                return true;
            }
        }
        return false;
    }

    /**
     * IPv6 prefix membership check.
     */
    public static function isPrivateIpv6(string $ip): bool
    {
        $packed = inet_pton($ip);
        if ($packed === false || strlen($packed) !== 16) {
            return false;
        }
        $expanded = strtolower(self::expandIpv6($packed));

        // Loopback ::1 — exact match.
        if ($expanded === '0000:0000:0000:0000:0000:0000:0000:0001') {
            return true;
        }
        // Link-local fe80::/10
        if (str_starts_with($expanded, 'fe8')
            || str_starts_with($expanded, 'fe9')
            || str_starts_with($expanded, 'fea')
            || str_starts_with($expanded, 'feb')) {
            return true;
        }
        // Unique-local fc00::/7 (matches both fc00::/8 and fd00::/8)
        if (str_starts_with($expanded, 'fc') || str_starts_with($expanded, 'fd')) {
            return true;
        }
        // IPv4-mapped ::ffff:0:0/96 — extract the embedded IPv4.
        if (str_starts_with($expanded, '0000:0000:0000:0000:0000:ffff:')) {
            $ipv4 = long2ip(hexdec(substr($expanded, 30, 4) . substr($expanded, 35, 4)));
            return self::isPrivateIpv4($ipv4);
        }
        return false;
    }

    /**
     * Resolve a hostname to its IPv4 + IPv6 addresses.
     */
    public static function resolveHost(string $host): array
    {
        $records = @dns_get_record($host, DNS_A | DNS_AAAA);
        if (!is_array($records)) {
            return [];
        }
        $ips = [];
        foreach ($records as $rec) {
            if (!empty($rec['ip'])) {
                $ips[] = (string) $rec['ip'];
            } elseif (!empty($rec['ipv6'])) {
                $ips[] = (string) $rec['ipv6'];
            }
        }
        return $ips;
    }

    /**
     * Expand a packed-binary IPv6 to its full hex form.
     */
    private static function expandIpv6(string $packed): string
    {
        $hex = bin2hex($packed);
        return implode(':', str_split($hex, 4));
    }
}