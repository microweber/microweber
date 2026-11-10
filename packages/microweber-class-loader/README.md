# Microweber Class Loader

Standalone **instance-based** class loader for Laravel applications.

Replaces the CMS-entangled static `ClassLoader` / `SplClassLoader` with a reusable service that:

- Avoids static state (no memory leaks between container cycles / tests)
- Registers a **single** `spl_autoload` callback
- Deduplicates paths that differ only by separator (`/` vs `\`) or trailing slash
- Supports flat directory lookup **and** PSR-4 namespace prefixes

## Installation

```bash
composer require microweber-packages/microweber-class-loader
```

The service provider is registered via `spatie/laravel-package-tools` (in Microweber it is loaded first, by `CoreServiceProvider`). To register it manually:

```php
// bootstrap/providers.php or AppServiceProvider::register()
$app->register(\MicroweberPackages\ClassLoader\ClassLoaderServiceProvider::class);
```

The provider registers the autoloader during the **register** phase (not boot) — other providers may resolve autoloaded classes while still registering, and `boot()` would be too late. On terminate it frees the lookup caches so long-running workers / test suites don't retain unbounded class-path maps.

## Usage

### The service

```php
use MicroweberPackages\ClassLoader\ClassLoaderService;

$loader = app(ClassLoaderService::class);

$loader->addDirectories([
    base_path('modules'),
    __DIR__,
]);

$loader->addNamespace('Acme\\Plugin', base_path('plugins/acme/src'));
$loader->register();

// Path dedup: these all count as one directory after normalization
$loader->addDirectories('/var/www/app/');
$loader->addDirectories('/var/www/app');
$loader->addDirectories('\\var\\www\\app\\');

$loader->resolve('Acme\\Plugin\\Hello'); // absolute path or null (no include)
$loader->load('Acme\\Plugin\\Hello');    // resolve + require_once; bool
$loader->getStatistics();                // registered? dir/namespace counts, cache sizes
$loader->clearCache();                   // free found/not-found lookup maps
```

### The facade

```php
use MicroweberPackages\ClassLoader\Facades\ClassLoader;

ClassLoader::addNamespace('Acme\\Plugin', base_path('plugins/acme/src'));
ClassLoader::addDirectories(base_path('modules'));
ClassLoader::register();

$path = ClassLoader::resolve('Acme\\Plugin\\Hello');
```

### Global helpers (`src/helpers.php`, auto-loaded)

```php
mw_class_loader();                                       // ClassLoaderService
class_loader_add_directories([$path]);                   // ClassLoaderService
class_loader_add_namespace('Foo\\Bar', $path);           // ClassLoaderService
class_loader_resolve('Foo\\Bar\\Baz');                   // ?string
class_loader_stats();                                    // array
```

## Configuration

Publishable config `config/class-loader.php`:

```bash
php artisan vendor:publish --tag=class-loader-config
```

| Key | Env | Default | Purpose |
| --- | --- | --- | --- |
| `enabled` | `MW_CLASS_LOADER_ENABLED` | `true` | Register the autoloader on boot |
| `directories` | — | `[]` | Absolute paths preloaded for flat lookup |
| `namespaces` | — | `[]` | `"Namespace\\" => "path/to/src"` map preloaded on boot |
| `cache_lookups` | `MW_CLASS_LOADER_CACHE` | `true` | Remember found/not-found class paths |

HTTP inspection routes (`class-loader/*`) are registered only in the `testing` environment.

## Key classes

- Service (container singleton): `MicroweberPackages\ClassLoader\ClassLoaderService`
- Facade: `MicroweberPackages\ClassLoader\Facades\ClassLoader`
- Path normalizer: `MicroweberPackages\ClassLoader\PathNormalizer`
- Provider: `MicroweberPackages\ClassLoader\ClassLoaderServiceProvider`

## Testing

```bash
composer test
composer analyse
```

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
