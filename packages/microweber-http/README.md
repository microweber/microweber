# Microweber HTTP

A standalone Laravel HTTP client package with Guzzle/cURL adapters, SSL enforcement, SSRF guard, and URL fetch guard.

## Installation

```bash
composer require microweber-packages/http
```

## Usage

### Via Service Container

```php
// Resolve via the container
$http = app('http');
$body = $http->url('https://example.com/api')->get();
```

### HttpClientFactory

```php
use MicroweberPackages\Http\HttpClientFactory;

// Create a Guzzle client with SSL verification
$client = HttpClientFactory::guzzle(['timeout' => 30]);

// Create a secure cURL handle
$ch = HttpClientFactory::curl('https://example.com');

// Fetch content
$content = HttpClientFactory::fetchContent('https://example.com/file.zip');
```

### SSRF Guard

```php
use MicroweberPackages\Http\Ssrf\SsrfGuard;

if (SsrfGuard::isExternallyReachable($url)) {
    // Safe to fetch
}
```

### URL Fetch Guard

```php
use MicroweberPackages\Http\UrlFetchGuard;

UrlFetchGuard::assertSafe($userSuppliedUrl);
```

## License

MIT