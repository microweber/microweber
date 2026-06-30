<?php

namespace Modules\WordPressMigration\Services\Http;

use MicroweberPackages\Http\HttpClientFactory;

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

    public function fetch(string $url, int $timeout, ?string $authorization = null): array
    {
        $ch = HttpClientFactory::curl($url, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, min(10, $timeout));
        curl_setopt($ch, CURLOPT_ACCEPT_ENCODING, '');

        if ($authorization !== null && $authorization !== '') {
            // CURLOPT_USERPWD would also work but it forces cURL to
            // do its own auth negotiation dance. Sending the header
            // directly gives WP exactly what it asks for.
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: ' . $authorization]);
        }

        $headers = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($_ch, string $line) use (&$headers): int {
            $colon = strpos($line, ':');
            if ($colon !== false) {
                $name = strtolower(trim(substr($line, 0, $colon)));
                $value = trim(substr($line, $colon + 1));
                if ($name !== '') {
                    $headers[$name] = $value;
                }
            }
            return strlen($line);
        });

        $result = HttpClientFactory::executeCurl($ch);

        return [
            'body' => is_string($result['body']) ? $result['body'] : '',
            'http_code' => (int)$result['http_code'],
            'headers' => $headers,
            'error' => (string)$result['error'],
        ];
    }
}
