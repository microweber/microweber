<?php

namespace MicroweberPackages\Utils\Http;

/**
 * Guard against Server-Side Request Forgery (SSRF) when the application
 * fetches a URL on behalf of an end-user (image grab, RSS pull, OEmbed
 * lookup, etc.).
 *
 * Closes the OOYES_AUDITS/01_SECURITY_AUDITOR.md A10 backlog gap:
 *   - Block private-IP ranges (RFC1918 10/8, 172.16/12, 192.168/16).
 *   - Block loopback (127/8, ::1).
 *   - Block link-local (169.254/16) — including the AWS metadata
 *     endpoint 169.254.169.254 the audit explicitly called out.
 *   - Block multicast / reserved ranges.
 *   - Require http(s) scheme — refuse file://, gopher://, ftp:// etc.
 *
 * Intended call site for any future URL-fetch helper (image-URL tab in
 * the Media picker, Marketplace updater, etc.):
 *
 *     UrlFetchGuard::assertSafe($userSuppliedUrl);
 *     $body = Http::timeout(10)->get($userSuppliedUrl)->body();
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

        // Resolve the host to every A/AAAA record and check each. A single
        // attacker-controlled DNS that returns 169.254.169.254 must not
        // pass even if the literal string is "evil.example.com".
        $ips = self::resolveAll($host);
        if (empty($ips)) {
            throw new \InvalidArgumentException('Host could not be resolved.');
        }

        foreach ($ips as $ip) {
            if (! self::isPublicIp($ip)) {
                throw new \InvalidArgumentException(
                    'URL resolves to a non-public IP range.'
                );
            }
        }
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
