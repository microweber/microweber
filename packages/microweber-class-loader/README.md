# microweber-packages/class-loader

Standalone **instance-based** class loader for Laravel applications.

Replaces the CMS-entangled static `ClassLoader` / `SplClassLoader` with a reusable service that:

- Avoids static state (no memory leaks between container cycles / tests)
- Registers a **single** `spl_autoload` callback
- Deduplicates paths that differ only by separator (`/` vs `\`) or trailing slash
- Supports flat directory lookup **and** PSR-4 namespace prefixes

## Installation

```bash
composer require microweber-packages/class-loader
```

Register the provider (Microweber CMS does this via `CoreServiceProvider`):

```php
// bootstrap/providers.php or AppServiceProvider::register()
$app->register(\MicroweberPackages\ClassLoader\ClassLoaderServiceProvider::class);
```

## Usage

```php
use MicroweberPackages\ClassLoader\ClassLoaderService;

$loader = app(ClassLoader::class);

$loader->addDirectories([
    base_path('modules'),
    __DIR__,
]);

$loader->addNamespace('Acme\\Plugin', base_path('plugins/acme/src'));
$loader->register();

// Path dedup: these count as one directory
$loader->addDirectories('/var/www/app/');
$loader->addDirectories('/var/www/app');
$loader->addDirectories('\\var\\www\\app\\'); // same after normalize

$loader->resolve('Acme\\Plugin\\Hello'); // path or null
$loader->getStatistics();
$loader->clearCache(); // free lookup maps
```

Helpers:

```php
mw_class_loader();
class_loader_add_directories([$path]);
class_loader_add_namespace('Foo\\Bar', $path);
class_loader_resolve('Foo\\Bar\\Baz');
class_loader_stats();
```

## Config

Publishable config: `config/class-loader.php`

## Testing

```bash
composer test
composer analyse
```

HTTP inspection routes (`class-loader/*`) are registered only in the testing environment.
