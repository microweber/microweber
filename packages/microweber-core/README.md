# Microweber Core

Deterministic service-provider loader for Microweber CMS.

## Why this exists

Laravel's package auto-discovery reads `vendor/composer/installed.json` and
caches the result in `bootstrap/cache/packages.php`. Under **php-fpm** and
**php-cgi**, multiple concurrent requests can race while that cache file is
being written (truncated read, partial write, opcache serving a stale version).
This produces errors like:

```
Call to undefined function normalize_path()
  in src/MicroweberPackages/App/functions/paths.php (line 52)
```

This package replaces auto-discovery with a single `CoreServiceProvider` that
registers **every** internal and third-party package provider in a fixed,
deterministic order — no cache files, no race conditions.

## Installation

### In the Microweber monorepo

Already wired: `AppServiceProvider::register()` (the single provider in
`bootstrap/providers.php`) registers `MicroweberPackages\Core\CoreServiceProvider`
as its very first statement, so every package loads before the rest of the app.

### In a standalone Laravel app

```bash
composer require microweber-packages/core
```

Add to `bootstrap/providers.php` (Laravel 11+):

```php
return [
    \MicroweberPackages\Core\CoreServiceProvider::class,
    // ... your other providers
];
```

Or for Laravel 10 and below, add to `config/app.php`:

```php
'providers' => [
    \MicroweberPackages\Core\CoreServiceProvider::class,
    // ...
],
```

The provider guards every registration with `class_exists()`, so it's safe
to use even if not all sub-packages are installed.

## How it works

1. **`dont-discover: ["*"]`** in the root `composer.json` tells Laravel to
   skip auto-discovery entirely.
2. **`CoreServiceProvider`** is registered first — as the opening statement of
   `AppServiceProvider::register()`, the app's master provider.
3. It registers internal Microweber packages in **dependency order** (filesystem
   helpers first, then config, database, etc.).
4. It then registers third-party providers that were previously auto-discovered.
5. Each registration is guarded by `class_exists()` so missing optional
   packages don't cause errors.

## Testing

```bash
# Run core package tests only
php vendor/bin/phpunit packages/microweber-core/tests/

# Or via the root phpunit.xml suite
php vendor/bin/phpunit --testsuite MicroweberCore
```

## License

MIT