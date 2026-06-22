# Blade Cache

Cache expensive Blade fragments with a simple `@cache` / `@endcache` directive.

## Installation

```bash
composer require microweber-packages/blade-cache
```

The service provider is auto-discovered.

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

$service->put('key', '<html>', ['tag'], 600);
$service->get('key', ['tag']);
$service->forget('key', ['tag']);
$service->flush(['tag']);
```

## License

MIT