# Microweber URL Package

A reusable Laravel package for URL management, slug generation, URL security validation, and transliteration.

## Installation

```bash
composer require microweber-packages/url
```

The package uses Laravel auto-discovery, so the service provider will be registered automatically.

## Usage

### UrlManager

```php
use MicroweberPackages\Url\UrlManager;

$urlManager = app('url_manager');

// Get site URL
$url = $urlManager->site_url('path/to/page');

// Get hostname
$hostname = $urlManager->hostname();

// Generate slug
$slug = $urlManager->slug('Hello World: My Page');

// Get current URL
$current = $urlManager->current();

// URL segments
$segments = $urlManager->segments();
$segment = $urlManager->segment(0);

// Replace/restore site URL placeholders
$replaced = $urlManager->replace_site_url($data);
$restored = $urlManager->replace_site_url_back($data);

// Clean dangerous URL wrappers
$clean = $urlManager->clean_url_wrappers($path);
```

### URLify (Transliteration)

```php
use MicroweberPackages\Url\URLify;

// Filter text to URL-safe slug
echo URLify::filter("J'étudie le français");
// Output: "jetudie-le-francais"

// Transliterate characters
echo URLify::transliterate('café');
// Output: "cafe"
```

### UrlSecurity

```php
use MicroweberPackages\Url\UrlSecurity;

// Check if URL is safe for remote fetching
$safe = UrlSecurity::isSafeRemoteUrl('https://example.com/image.jpg'); // true
$safe = UrlSecurity::isSafeRemoteUrl('javascript:alert(1)'); // false

// Sanitize URL for CSS url() context
$cssUrl = UrlSecurity::safeCssUrl($userUrl);
```

## Testing

```bash
cd packages/microweber-url
../../vendor/bin/phpunit
```

## License

MIT