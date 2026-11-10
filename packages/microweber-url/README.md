# Microweber URL Package

A reusable Laravel package for URL management, slug generation, URL security validation, and transliteration.

## Installation

```bash
composer require microweber-packages/microweber-url
```

Inside the Microweber monorepo the provider is registered by the core
`MicroweberPackages\App\Providers\MicroweberServiceProvider`. In a standalone
Laravel app register it manually:

```php
MicroweberPackages\Url\Providers\UrlServiceProvider::class,
```

The provider binds `UrlManagerService::class` as a singleton, which the
`UrlManager` facade resolves. The `src/helpers.php` global helpers are loaded
via Composer's `files` autoload.

## Usage

### UrlManager (facade)

```php
use MicroweberPackages\Url\Facades\UrlManager;

// Call methods statically through the facade...
$url = UrlManager::site_url('path/to/page');

// ...or grab the underlying MicroweberPackages\Url\UrlManagerService instance
$urlManager = UrlManager::getFacadeRoot();

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

### Global helpers (`src/helpers.php`)

```php
// Resolve the UrlManagerService instance
$urlManager = mw_url_manager();

// Security helpers (thin wrappers over UrlSecurity)
$safe = mw_is_safe_remote_url('https://example.com/a.jpg'); // bool
$css  = safe_css_url($userUrl);                              // string
```

## Key classes

- `MicroweberPackages\Url\UrlManagerService` — the URL manager (site URL, slugs, segments, placeholder replacement).
- `MicroweberPackages\Url\Facades\UrlManager` — facade for `UrlManagerService`.
- `MicroweberPackages\Url\UrlSecurity` — remote-URL / CSS-URL safety checks.
- `MicroweberPackages\Url\URLify` — transliteration / slug filtering.
- `MicroweberPackages\Url\Providers\UrlServiceProvider` — package registration.

## Testing

```bash
cd packages/microweber-url
../../vendor/bin/phpunit
```

## License

MIT
## Standalone validation

A minimal Laravel (Orchestra Testbench) app lives in `standalone-validation/`:

```bash
cd standalone-validation
composer install
./vendor/bin/phpunit
```

## Static analysis

```bash
# From monorepo root (level 7 maximized)
composer analyse -- packages/microweber-url/src
# Or package config
../../vendor/bin/phpstan analyse -c phpstan.neon.dist
```

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
