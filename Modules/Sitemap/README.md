# Sitemap

XML sitemap generator for search engine indexing. Auto-generates sitemap.xml from site content.

## Structure

- HTTP controllers
- Route definitions
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="sitemap" />
```

### Publish assets

```sh
php artisan module:publish Sitemap
```

### Configuration

```php
config('modules.sitemap.name')
```

### Views

```php
view('modules.sitemap::index')
```

