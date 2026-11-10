# Blade Cache

Cache expensive Blade fragments with a simple `@cache` / `@endcache` directive.

## Installation

```bash
composer require microweber-packages/microweber-blade-cache
```

`BladeCacheServiceProvider` is a Microweber package provider. In a full install it is registered by `microweber-core`'s `CoreServiceProvider`; in a standalone app register it manually (`config/app.php` or `bootstrap/providers.php`). On boot it merges the config, binds `BladeCacheService` as a singleton, and registers the `@cache` / `@endcache` directives via `CacheDirective::register()`.

## Usage

```blade
@cache('sidebar', ['navigation', 'menus'], 3600)
    {{-- expensive rendering --}}
@endcache
```

### Arguments

| # | Name | Type | Default | Description |
|---|------|------|---------|-------------|
| 1 | key | string | required | Unique cache key |
| 2 | tags | array | `[]` | Cache tags for invalidation |
| 3 | ttl | int\|null | config default | Seconds until expiry |

## Configuration

Publish the config:

```bash
php artisan vendor:publish --tag=blade-cache-config
```

| Key | Env | Default | Description |
|-----|-----|---------|-------------|
| `enabled` | `BLADE_CACHE_ENABLED` | `true` | Toggle caching |
| `ttl` | `BLADE_CACHE_TTL` | `3600` | Default TTL in seconds |
| `store` | `BLADE_CACHE_STORE` | `null` | Cache store (null = default) |

## Programmatic API

```php
$service = app(\MicroweberPackages\BladeCache\BladeCacheService::class);

$service->isEnabled();             // bool — respects config('blade-cache.enabled')
$service->getDefaultTtl();         // int  — config('blade-cache.ttl')
$service->put('key', '<html>', ['tag'], 600);
$service->get('key', ['tag']);     // ?string
$service->forget('key', ['tag']);
$service->flush(['tag']);          // flush by tag (or all)
```

## Key classes

- `MicroweberPackages\BladeCache\BladeCacheService` — the singleton that reads/writes the cache.
- `MicroweberPackages\BladeCache\CacheDirective` — registers the `@cache` / `@endcache` directives.
- `MicroweberPackages\BladeCache\Facades\BladeCache` — facade over the service.
- `MicroweberPackages\BladeCache\BladeCacheServiceProvider` — bindings, config, directive registration.

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
