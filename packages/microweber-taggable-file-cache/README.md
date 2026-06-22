# Laravel Taggable File Cache

A custom file cache driver for Laravel that supports Cache Tags with a file-based cache store. Works standalone in any Laravel project.

## Installation

```
composer require microweber-packages/taggable-file-cache
```

The service provider is auto-discovered. If you need to register it manually:

```php
'providers' => [
    // ...
    MicroweberPackages\TaggableFileCache\TaggableFileCacheServiceProvider::class,
];
```

## Usage

The package overrides the default `file` cache driver to support tags:

```php
use Illuminate\Support\Facades\Cache;

// Put with tags
Cache::tags(['people', 'artists'])->put('firstName', 'Peter', now()->addMinutes(9));

// Get with tags
$name = Cache::tags('people')->get('firstName');

// Flush specific tags
Cache::tags(['people', 'artists'])->flush();

// Flush all
Cache::flush();
```

## Multi-Site Environment Support

Cache is automatically scoped per environment. To clear cache for a specific environment:

```bash
php artisan cache:clear-taggable-file --env=production
php artisan cache:clear-taggable-file --env=staging
```

## License

MIT