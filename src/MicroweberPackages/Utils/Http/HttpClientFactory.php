<?php

declare(strict_types=1);

namespace MicroweberPackages\Utils\Http;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Centralized factory for creating HTTP clients with consistent SSL configuration.
 *
 * All outgoing HTTP requests should use this factory to ensure:
 * - SSL peer/host verification is always enabled
 * - A known CA certificate bundle is used
 * - Only HTTP/HTTPS protocols are allowed
 * - Timeouts are explicitly set
 */
class HttpClientFactory
{
    private const CA_CERT_FILENAME = 'cacert.pem.txt';

    private const DEFAULT_TIMEOUT = 60;

    /**
     * Resolve the absolute path to the bundled CA certificate file.
     */
    public static function caCertPath(): string
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR
            . 'Http' . DIRECTORY_SEPARATOR
            . 'Adapters' . DIRECTORY_SEPARATOR
            . self::CA_CERT_FILENAME;
    }

    /**
     * Create a GuzzleHttp\Client pre-configured with SSL verification.
     *
     * @param array $options Additional Guzzle client options (merged, not overwritten)
     */
    public static function guzzle(array $options = []): GuzzleClient
    {
        $defaults = [
            'verify' => true,
            'timeout' => self::DEFAULT_TIMEOUT,
        ];

        return new GuzzleClient(array_merge($defaults, $options));
    }

    /**
     * Create and configure a cURL handle with SSL verification and protocol restrictions.
     *
     * @param string $url     The request URL
     * @param int    $timeout Timeout in seconds
     * @return \CurlHandle     The configured cURL handle
     */
    public static function curl(string $url, int $timeout = self::DEFAULT_TIMEOUT): \CurlHandle
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        // SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, self::caCertPath());

        // Protocol restrictions — only HTTP and HTTPS
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS | CURLPROTO_HTTP);
        curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS | CURLPROTO_HTTP);

        return $ch;
    }

    /**
     * Apply standard SSL options to an existing cURL handle.
     *
     * Useful when the handle is already initialised elsewhere (e.g. file downloads).
     *
     * @param \CurlHandle $ch The existing cURL handle
     */
    public static function applySslOptions(\CurlHandle $ch): void
    {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, self::caCertPath());
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS | CURLPROTO_HTTP);
        curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS | CURLPROTO_HTTP);
    }

    /**
     * Execute a cURL handle, close it, and return the result with error info.
     *
     * @param \CurlHandle $ch The cURL handle to execute
     * @return array{body: string|false, http_code: int, error: string}
     */
    public static function executeCurl(\CurlHandle $ch): array
    {
        $body = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        return [
            'body' => $body,
            'http_code' => $httpCode,
            'error' => $error,
        ];
    }

    /**
     * Execute a cURL handle and decode the JSON response.
     *
     * @param \CurlHandle $ch          The cURL handle to execute
     * @param string      $driverName  Human-readable driver name for error messages
     * @return array The decoded JSON response
     * @throws \Exception On cURL errors, HTTP errors, or JSON decode failures
     */
    public static function executeCurlJson(\CurlHandle $ch, string $driverName = 'API'): array
    {
        $result = self::executeCurl($ch);

        if ($result['error']) {
            throw new \Exception("cURL Error: {$result['error']}");
        }

        if ($result['http_code'] >= 400) {
            throw new \Exception("{$driverName} returned error code: {$result['http_code']}, Response: {$result['body']}");
        }

        $decoded = json_decode($result['body'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Failed to decode JSON response: " . json_last_error_msg());
        }

        return $decoded;
    }

    /**
     * Download binary content from a URL using a secure cURL handle.
     *
     * @param string $url     The URL to download from
     * @param int    $timeout Timeout in seconds
     * @return string The downloaded content
     * @throws \Exception On cURL or HTTP errors
     */
    public static function fetchContent(string $url, int $timeout = self::DEFAULT_TIMEOUT): string
    {
        $ch = self::curl($url, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $result = self::executeCurl($ch);

        if ($result['error']) {
            throw new \Exception("cURL Error when downloading content: {$result['error']}");
        }

        if ($result['http_code'] >= 400) {
            throw new \Exception("Error downloading content, HTTP code: {$result['http_code']}");
        }

        return $result['body'];
    }
}
