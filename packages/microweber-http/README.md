# Microweber HTTP

A standalone Laravel HTTP client package with Guzzle/cURL adapters, SSL enforcement, an SSRF guard,
and a URL fetch guard. Reusable in Microweber CMS and any Laravel app.

## Features

- **`HttpService`** — a small fluent client (`url()->get()`, `->post()`, `->download()`) with caching.
- **`HttpClientFactory`** — pre-hardened Guzzle clients and cURL handles with bundled CA bundle + SSL options.
- **`SsrfGuard`** — blocks requests to private/loopback/link-local IPv4 & IPv6 ranges (SSRF protection).
- **`UrlFetchGuard`** — one-call assertion that a user-supplied URL is safe to fetch.

## Installation

```bash
composer require microweber-packages/microweber-http
```

The `HttpServiceProvider` (built on `spatie/laravel-package-tools`) binds `HttpService` into the
container. The `Http` facade resolves to the same service.

## Usage

### Fluent HTTP service

```php
use MicroweberPackages\Http\HttpService;

$http = app(HttpService::class);

// GET with a 30s cache
$body = $http->url('https://example.com/api')
    ->set_timeout(20)
    ->set_cache(1800)
    ->get();

// POST
$response = $http->url('https://example.com/api')->post(['key' => 'value']);

// Download to a file
$http->url('https://example.com/file.zip')->download('/tmp/file.zip');
```

Or via the facade:

```php
use MicroweberPackages\Http\Facades\Http;

$body = Http::url('https://example.com/api')->get();
```

### HttpClientFactory

```php
use MicroweberPackages\Http\HttpClientFactory;

// Guzzle client with SSL verification and the bundled CA bundle
$client = HttpClientFactory::guzzle(['timeout' => 30]);

// Hardened cURL handle
$ch = HttpClientFactory::curl('https://example.com', 30);
[$status, $body] = HttpClientFactory::executeCurl($ch);     // returns [httpCode, responseBody]
$json = HttpClientFactory::executeCurlJson($ch, 'MyAPI');   // decoded JSON (throws on error)

// One-shot fetch
$content = HttpClientFactory::fetchContent('https://example.com/file.zip');

// Path to the bundled cacert.pem
$caPath = HttpClientFactory::caCertPath();
```

### SSRF guard

```php
use MicroweberPackages\Http\Ssrf\SsrfGuard;

if (SsrfGuard::isExternallyReachable($url)) {
    // Host resolves only to public IPs — safe to fetch
}

SsrfGuard::isPrivateIpv4('10.0.0.1');   // true
SsrfGuard::isPrivateIpv6('::1');        // true
SsrfGuard::resolveHost('example.com');  // resolved IP list
```

### URL fetch guard

```php
use MicroweberPackages\Http\UrlFetchGuard;

// Throws if the URL is unsafe (bad scheme, private host, etc.)
UrlFetchGuard::assertSafe($userSuppliedUrl);
```

## Key classes

- `MicroweberPackages\Http\Facades\Http` — facade → `HttpService`.
- `MicroweberPackages\Http\HttpService` — fluent client (`url`, `set_url`, `set_timeout`, `set_cache`, `get`, `post`, `download`).
- `MicroweberPackages\Http\HttpClientFactory` — hardened Guzzle/cURL factory and fetch helpers.
- `MicroweberPackages\Http\Ssrf\SsrfGuard` — private-range / SSRF detection.
- `MicroweberPackages\Http\UrlFetchGuard` — `assertSafe()` convenience guard.
- `MicroweberPackages\Http\Adapters\{Guzzle, Curl}` — underlying transport adapters.
- `MicroweberPackages\Http\HttpServiceProvider` — service-container wiring.

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
