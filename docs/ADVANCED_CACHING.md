# Advanced Caching System

## Overview

The Microweber Advanced Caching System provides two levels of caching:

1. **Full Page Cache** - Caches entire HTML pages for lightning-fast response times
2. **Fragment Cache** - Caches specific sections of views (menus, product lists, etc.)

## Features

- **Tag-based Invalidation** - Cache groups can be cleared by tags
- **Mobile/Desktop Separation** - Separate caches for mobile and desktop
- **User Role Support** - Optional caching for authenticated users
- **Smart Exclusions** - Configurable URL patterns that bypass caching
- **Cache Warming** - Pre-populate cache with important pages
- **Fragment Directives** - Blade directives for easy fragment caching
- **Statistics Tracking** - Monitor cache hit rates and performance

## Configuration

Add the following to your `.env` file:

```env
# Page Cache Configuration
PAGE_CACHE_ENABLED=true
PAGE_CACHE_TTL=3600
PAGE_CACHE_DRIVER=redis
PAGE_CACHE_LOGGED_IN=false
PAGE_CACHE_QUERY_PARAMS=false
PAGE_CACHE_MOBILE=true

# Fragment Cache Configuration
FRAGMENT_CACHE_ENABLED=true
FRAGMENT_CACHE_TTL=3600
FRAGMENT_CACHE_DRIVER=redis
```

### Configuration Options

| Option | Description | Default |
|--------|-------------|---------|
| `PAGE_CACHE_ENABLED` | Enable full page caching | `false` |
| `PAGE_CACHE_TTL` | Cache lifetime in seconds | `3600` |
| `PAGE_CACHE_DRIVER` | Cache driver (redis/memcached/array) | `config('cache.default')` |
| `PAGE_CACHE_LOGGED_IN` | Cache for authenticated users | `false` |
| `PAGE_CACHE_QUERY_PARAMS` | Cache URLs with query strings | `false` |
| `PAGE_CACHE_MOBILE` | Separate caches for mobile/desktop | `true` |
| `FRAGMENT_CACHE_ENABLED` | Enable fragment caching | `true` |
| `FRAGMENT_CACHE_TTL` | Default fragment cache TTL | `3600` |

## Requirements

The advanced caching system requires a cache driver that supports tagging:

- **Redis** (recommended for production)
- **Memcached**
- **Array** (for testing only)

File and database drivers do not support tagging and will not work with this system.

## Full Page Cache

### How It Works

The page cache middleware intercepts HTTP GET requests and:

1. Checks if the request should be cached
2. Returns cached content if available
3. Generates and caches the response if not

### URL Exclusions

By default, the following URLs are excluded from caching:

- `/admin/*` - Admin panel
- `/api/*` - API endpoints
- `/login`, `/logout`, `/register` - Authentication
- `/profile`, `/account` - User areas
- `/checkout`, `/cart` - E-commerce
- URLs with `?editmode` or `?preview`
- AJAX requests

You can configure exclusions in `config/page-cache.php`.

### Cache Key Components

The cache key includes:
- Request URL
- HTTP method
- Locale
- Mobile/desktop flag
- Authentication status (if enabled)

## Fragment Cache

### Blade Directives

Use these directives in your Blade templates:

```blade
{{-- Cache a menu --}}
@fragment('main-menu', ['menu'], 7200)
    {{-- Menu rendering logic --}}
    @include('menu.main')
@endfragment

{{-- Cache a product list --}}
@fragment('featured-products', ['products'], 1800)
    @foreach($products as $product)
        @include('product.card', ['product' => $product])
    @endforeach
@endfragment

{{-- Cache with default TTL --}}
@cache('sidebar-content', ['sidebar'])
    {{-- Sidebar content --}}
@endcache
```

### Helper Functions

```php
// Cache a fragment
$content = cache_fragment('unique-key', ['tags'], 3600, function() {
    return view('expensive-view')->render();
});

// Cache a menu
$menuHtml = cache_menu('main-menu', function() {
    return render_menu('main');
}, 7200);

// Cache a module
$moduleHtml = cache_module('featured-products', function() {
    return render_module('shop/products', ['limit' => 6]);
});

// Cache category tree
$categoryTree = cache_category_tree(0, function() {
    return build_category_tree();
});

// Cache product list
$products = cache_product_list('featured', ['limit' => 10], function() {
    return get_products(['featured' => true, 'limit' => 10]);
});
```

## Console Commands

### Cache Warming

Pre-populate the cache with important pages:

```bash
# Warm specific URLs
php artisan cache:warm --urls=/,/about,/products

# Warm from sitemap
php artisan cache:warm --sitemap

# Warm all public pages
php artisan cache:warm --all

# Warm with concurrent requests
php artisan cache:warm --all --concurrent

# Custom chunk size and timeout
php artisan cache:warm --urls=/,/about --chunk=5 --timeout=60
```

### Clearing Cache

```bash
# Clear all page caches
php artisan cache:clear-page

# Clear by tag
php artisan cache:clear-page --tag=content

# Clear fragment caches
php artisan cache:clear-page --type=fragment

# Clear everything (including Laravel cache)
php artisan cache:clear-page --all
```

### Cache Statistics

```bash
# Show all cache stats
php artisan cache:stats

# Show page cache stats only
php artisan cache:stats --type=page

# Show fragment cache stats only
php artisan cache:stats --type=fragment

# Show detailed stats with Redis info
php artisan cache:stats --detailed
```

## Cache Invalidation

### Automatic Invalidation

The system automatically clears cache when:

- Content is saved or deleted (content cache)
- Menus are updated (menu cache)
- Categories change (category cache)
- Products are updated (product cache)

### Manual Invalidation

```php
// Clear all page cache
clear_page_cache();

// Clear page cache by tag
clear_page_cache('content');

// Clear all fragment cache
clear_fragment_cache();

// Clear fragment cache by tags
clear_fragment_cache(['menu', 'navigation']);

// Invalidate specific content
invalidate_content_cache(123, 'page');
```

## Performance Tips

1. **Use Redis** - Redis is the recommended cache driver for production
2. **Configure TTL** - Set appropriate cache lifetimes:
   - Static pages: 1-24 hours
   - Product listings: 30 minutes - 2 hours
   - Menus: 2-12 hours
   - User-specific content: Don't cache or use short TTL

3. **Smart Tagging** - Use descriptive tags for easy invalidation:
   ```php
   cache_fragment('featured-products', ['products', 'featured'], 1800, function() {
       // expensive query
   });
   ```

4. **Monitor Stats** - Regularly check cache hit rates:
   ```bash
   php artisan cache:stats --detailed
   ```

5. **Warm Important Pages** - Schedule cache warming:
   ```bash
   # Add to crontab
   0 */6 * * * cd /path/to/microweber && php artisan cache:warm --urls=/,/products,/blog
   ```

## Troubleshooting

### Cache Not Working

1. Check if the cache driver supports tagging:
   ```bash
   php artisan cache:stats
   ```

2. Verify the driver is configured correctly in `.env`:
   ```env
   CACHE_STORE=redis
   ```

3. Check if Redis is running:
   ```bash
   redis-cli ping
   ```

### Low Cache Hit Rate

1. Check excluded URL patterns in config
2. Verify cache TTL is appropriate
3. Monitor with debug logging enabled:
   ```env
   APP_DEBUG=true
   ```

### Memory Issues

For high-traffic sites, tune Redis:

```conf
# redis.conf
maxmemory 256mb
maxmemory-policy allkeys-lru
```

## API Reference

### PageCacheService Methods

- `isEnabled()` - Check if page caching is enabled
- `getCacheKey()` - Generate cache key for current request
- `shouldExclude()` - Check if current request should be excluded
- `get()` - Get cached page content
- `store($content, $ttl)` - Store page content
- `clear($tag)` - Clear cache by tag
- `invalidateContent($id, $type)` - Invalidate specific content
- `getStats()` - Get cache statistics
- `warmCache($urls)` - Pre-populate cache

### FragmentCacheService Methods

- `isEnabled()` - Check if fragment caching is enabled
- `get($key, $tags, $ttl)` - Get cached fragment
- `store($key, $content, $tags, $ttl, $metadata)` - Store fragment
- `remember($key, $tags, $ttl, $callback)` - Get or compute and cache
- `delete($key, $tags)` - Delete specific fragment
- `clear($tags)` - Clear fragments by tags
- `clearAll()` - Clear all fragment caches
- `has($key, $tags)` - Check if fragment exists
- `touch($key, $tags, $ttl)` - Extend fragment TTL
- `getCacheKey($type, $identifier, $params)` - Generate cache key
- `getTags($type, $identifier, $additionalTags)` - Generate tags
- `getStats()` - Get cache statistics
- `menu($id, $callback, $ttl)` - Cache menu fragment
- `module($id, $callback, $ttl)` - Cache module fragment
- `categoryTree($parentId, $callback, $ttl)` - Cache category tree
- `productList($listId, $params, $callback, $ttl)` - Cache product list

## Integration with Existing Cache

The advanced caching system integrates seamlessly with Laravel's cache system and Microweber's existing cache manager. It does not replace but rather extends the existing caching capabilities.

The existing `mw()->cache_manager` remains available for backward compatibility while providing enhanced features for new implementations.

## Security Considerations

1. **Never cache** user-specific data like:
   - Shopping carts
   - Account pages
   - Checkout flows
   - Admin panels

2. **CSRF tokens** are automatically excluded from caching

3. **Session data** is respected - logged-out users see cached pages, logged-in users see fresh content (unless configured otherwise)

4. **Preview/edit modes** automatically bypass cache

## Migration from Existing Full Page Cache

The new system is backward compatible with the existing full page cache in `FrontendController`. To migrate:

1. Disable the legacy cache in settings
2. Enable the new page cache in `.env`
3. Configure URL exclusions as needed
4. Test thoroughly before production deployment

The legacy cache will continue to work alongside the new system until explicitly disabled.
