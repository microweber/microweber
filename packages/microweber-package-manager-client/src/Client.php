<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient;

use MicroweberPackages\PackageManagerClient\Support\FileDownloader;

/**
 * HTTP client for Composer/Satis package repositories.
 *
 * Moved from microweber-packages/composer-client into this package so the
 * package manager is self-contained (no vendor dependency on the old client).
 *
 * @phpstan-type PackageMeta array<string, mixed>
 * @phpstan-type PackageVersions array<string, PackageMeta>
 * @phpstan-type PackageMap array<string, PackageVersions>
 * @phpstan-type LicenseRow array<string, mixed>
 */
class Client
{
    use FileDownloader;

    /** @var list<LicenseRow> */
    public array $licenses = [];

    /** @var list<string> */
    public array $packageServers = [
        'https://modules.microweberapi.com/packages.json',
    ];

    /** @var array{timeout?: int, connect_timeout?: int, verify_ssl?: bool, user_agent?: string} */
    protected array $httpOptions = [];

    /**
     * Optional extra request headers (e.g. x-mw-version).
     *
     * @var list<string>
     */
    protected array $extraHeaders = [];

    /**
     * @param list<string>|null $packageServers
     * @param array{timeout?: int, connect_timeout?: int, verify_ssl?: bool, user_agent?: string} $httpOptions
     */
    public function __construct(?array $packageServers = null, array $httpOptions = [])
    {
        if ($packageServers !== null && $packageServers !== []) {
            $this->packageServers = array_values($packageServers);
        }
        $this->httpOptions = $httpOptions;
    }

    /**
     * @param list<string> $servers
     */
    public function setPackageServers(array $servers): static
    {
        $this->packageServers = array_values(array_filter($servers, static fn ($s): bool => is_string($s) && $s !== ''));

        return $this;
    }

    /**
     * @param list<LicenseRow> $licenses
     */
    public function setLicenses(array $licenses): static
    {
        $this->licenses = $licenses;

        return $this;
    }

    /**
     * @param LicenseRow|string $license
     */
    public function addLicense(array|string $license): static
    {
        if (is_string($license)) {
            $this->licenses[] = ['local_key' => $license];
        } else {
            $this->licenses[] = $license;
        }

        return $this;
    }

    /**
     * @param list<string> $headers
     */
    public function setExtraHeaders(array $headers): static
    {
        $this->extraHeaders = $headers;

        return $this;
    }

    /**
     * @return array{valid: bool, status: string, servers: array<string, mixed>}
     */
    public function consumeLicense(string $license): array
    {
        $status = 'invalid';
        $servers = [];
        $valid = false;

        foreach ($this->packageServers as $package) {
            $parsed = parse_url($package);
            if (!is_array($parsed) || !isset($parsed['scheme'], $parsed['host'])) {
                continue;
            }
            $host = $parsed['host'];
            $licenseCheckUrl = $parsed['scheme'] . '://' . $host . '/licenses/check?key=' . rawurlencode($license);

            $response = $this->httpGet($licenseCheckUrl);
            if (isset($response['error'])) {
                $servers[$host] = $response;
                continue;
            }

            $body = $response['body'] ?? '';
            $jsonResponse = is_string($body) ? json_decode($body, true) : null;
            if (is_array($jsonResponse)) {
                $servers[$host] = $jsonResponse;
                $details = isset($jsonResponse['details']) && is_array($jsonResponse['details'])
                    ? $jsonResponse['details']
                    : [];
                if (isset($details['status']) && is_string($details['status'])) {
                    $status = $details['status'];
                    if ($status === 'Active') {
                        $valid = true;
                    }
                }
            }
        }

        return ['valid' => $valid, 'status' => $status, 'servers' => $servers];
    }

    /**
     * @return PackageMeta|array{}
     */
    public function getPackageByName(string $packageName, string|false $packageVersion = false): array
    {
        $foundedPackage = [];

        foreach ($this->packageServers as $package) {
            $parsed = parse_url($package);
            if ($parsed === false || !isset($parsed['scheme'], $parsed['host'])) {
                continue;
            }
            $singlePackageUrl = $parsed['scheme'] . '://' . $parsed['host'] . '/packages/' . $packageName . '.json';

            $packageFile = $this->getPackageFile($singlePackageUrl);
            if ($packageFile === [] || isset($packageFile['error'])) {
                // Fallback: search the full packages.json
                $packageFile = $this->getPackageFile($package);
            }

            if ($packageFile === [] || isset($packageFile['error'])) {
                continue;
            }

            foreach ($packageFile as $name => $versions) {
                if (!is_array($versions) || !is_string($name)) {
                    continue;
                }
                if ($packageName !== $name) {
                    continue;
                }

                /** @var PackageVersions $versions */
                if ($packageVersion !== false && $packageVersion !== '' && $packageVersion !== 'latest') {
                    foreach ($versions as $version => $versionData) {
                        if (is_string($version) && $packageVersion === $version && is_array($versionData)) {
                            return $versionData;
                        }
                    }
                }

                $last = end($versions);
                if (is_array($last)) {
                    $foundedPackage = $last;
                }
            }
        }

        return $foundedPackage;
    }

    /**
     * @param array{require_name?: string, require_version?: string}|array{} $filter
     * @return PackageMap|PackageMeta|array{error?: string}|array{}
     */
    public function search(array $filter = []): array
    {
        if ($filter !== [] && isset($filter['require_name']) && is_string($filter['require_name'])) {
            $packageName = $filter['require_name'];
            $packageVersion = false;
            if (isset($filter['require_version']) && is_string($filter['require_version'])) {
                $packageVersion = $filter['require_version'];
            }

            return $this->getPackageByName($packageName, $packageVersion);
        }

        /** @var PackageMap $packageFileMerged */
        $packageFileMerged = [];
        foreach ($this->packageServers as $package) {
            $packageFile = $this->getPackageFile($package);
            if ($packageFile !== [] && !isset($packageFile['error'])) {
                /** @var PackageMap $packageFile */
                $packageFileMerged = array_merge($packageFileMerged, $packageFile);
            }
        }

        return $packageFileMerged;
    }

    /**
     * @return list<string>
     */
    public function prepareHeaders(): array
    {
        $headers = $this->extraHeaders;

        if (defined('MW_VERSION')) {
            $headers[] = 'x-mw-version: ' . (string) constant('MW_VERSION');
        }

        if (function_exists('site_url')) {
            /** @var mixed $siteUrl */
            $siteUrl = call_user_func('site_url');
            if (is_string($siteUrl) && $siteUrl !== '') {
                $headers[] = 'x-mw-site-url: ' . base64_encode($siteUrl);
            }
        }

        if ($this->licenses !== []) {
            $base64EncodedPassword = base64_encode((string) json_encode($this->licenses));
            $base64Encoded = base64_encode('license:' . $base64EncodedPassword);
            $headers[] = 'Authorization: Basic ' . $base64Encoded;
        }

        if (function_exists('mw_root_path')) {
            /** @var mixed $root */
            $root = call_user_func('mw_root_path');
            if (is_string($root) && $root !== '') {
                $headers[] = 'x-mw-root-path: ' . base64_encode($root);
            }
        }

        return $headers;
    }

    /**
     * @return PackageMap|array{error: string}|array{}
     */
    public function getPackageFile(string $packageUrl): array
    {
        $response = $this->httpGet($packageUrl);
        if (isset($response['error'])) {
            return ['error' => (string) $response['error']];
        }

        $body = $response['body'] ?? '';
        if (!is_string($body) || $body === '') {
            return [];
        }

        $getPackages = json_decode($body, true);
        if (!is_array($getPackages)) {
            return [];
        }

        if (isset($getPackages['packages']) && is_array($getPackages['packages'])) {
            /** @var PackageMap $packages */
            $packages = $getPackages['packages'];

            return $packages;
        }

        return [];
    }

    /**
     * @param PackageMeta $package
     * @return array<string, mixed>|array{error: string}|null
     */
    public function notifyPackageInstall(array $package): ?array
    {
        $packageUrl = null;
        if (isset($package['notification-url']) && is_string($package['notification-url'])) {
            $packageUrl = $package['notification-url'];
        }
        if ($packageUrl === null || $packageUrl === '') {
            return null;
        }

        $response = $this->httpRequest($packageUrl, 'POST');
        if (isset($response['error'])) {
            return ['error' => (string) $response['error']];
        }

        $body = $response['body'] ?? '';
        if (!is_string($body) || $body === '') {
            return null;
        }

        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            return null;
        }

        /** @var array<string, mixed> $result */
        $result = [];
        foreach ($decoded as $key => $value) {
            if (is_string($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @return array{body?: string, error?: string, status?: int}
     */
    protected function httpGet(string $url): array
    {
        return $this->httpRequest($url, 'GET');
    }

    /**
     * @return array{body?: string, error?: string, status?: int}
     */
    protected function httpRequest(string $url, string $method = 'GET'): array
    {
        if ($url === '') {
            return ['error' => 'Empty URL'];
        }

        $userAgent = $this->httpUserAgent();
        if ($userAgent === '') {
            $userAgent = 'MicroweberPackageManagerClient/1.0';
        }

        $curl = curl_init();
        $headers = $this->prepareHeaders();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->httpTimeout());
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->httpConnectTimeout());
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method !== '' ? $method : 'GET');
        curl_setopt($curl, CURLOPT_POSTFIELDS, '');
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->httpVerifySsl());
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        if ($headers !== []) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($err !== '') {
            return ['error' => 'cURL Error #: ' . $err];
        }

        return [
            'body' => is_string($response) ? $response : '',
            'status' => $status,
        ];
    }

    protected function httpTimeout(): int
    {
        return (int) ($this->httpOptions['timeout'] ?? 30);
    }

    protected function httpConnectTimeout(): int
    {
        return (int) ($this->httpOptions['connect_timeout'] ?? 10);
    }

    protected function httpUserAgent(): string
    {
        return (string) ($this->httpOptions['user_agent'] ?? 'MicroweberPackageManagerClient/1.0');
    }

    protected function httpVerifySsl(): bool
    {
        return (bool) ($this->httpOptions['verify_ssl'] ?? true);
    }
}
