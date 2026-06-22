# StaticPageCache Module

Full-page static cache for Microweber. Caches entire HTML responses for guest visitors to improve page load times.

## Features

- Full-page HTML caching via middleware
- Admin panel toggle in Settings > Page Cache
- Automatic cache invalidation on content save
- Excludes admin, API, cart, checkout, and other dynamic routes
- Separate cache for mobile/desktop

## Configuration

The module can be enabled from the admin panel under **Settings > Page Cache**, or via `.env`:

```
STATIC_PAGE_CACHE_ENABLED=true
STATIC_PAGE_CACHE_TTL=3600
```