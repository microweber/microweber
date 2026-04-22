<?php

namespace Modules\WordPressMigration\Services\Http;

use MicroweberPackages\Utils\Http\HttpClientFactory;

/**
 * Default HttpProbeFetcher — delegates to HttpClientFactory::executeCurl
 * so it inherits Microweber's SSL verification (covered by
 * SslVerificationTest), protocol restrictions (HTTP/HTTPS only), and
 * the bundled CA bundle.
 *
 * Probe-specific tweaks applied here:
 *  - CURLOPT_FOLLOWLOCATION = true: many WP sites 301 /wp-json → /wp-json/
 *  - CURLOPT_USERAGENT: the Microweber-WP-Migration UA from the ADR
 *  - CURLOPT_CONNECTTIMEOUT = min(10, $timeout): don't spend the
 *    full 60s on DNS/connect when the probe only gets 10s per URL
 */
class CurlHttpProbeFetcher implements HttpProbeFetcher
{
    private const USER_AGENT = 'Microweber-WP-Migration/1.0 (+https://microweber.com)';

    public function fetch(string $url, int $timeout): array
    {
        $ch = HttpClientFactory::curl($url, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, min(10, $timeout));
        curl_setopt($ch, CURLOPT_ACCEPT_ENCODING, '');

        $result = HttpClientFactory::executeCurl($ch);

        return [
            'body' => is_string($result['body']) ? $result['body'] : '',
            'http_code' => (int)$result['http_code'],
            'error' => (string)$result['error'],
        ];
    }
}
