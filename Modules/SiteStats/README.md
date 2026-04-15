# SiteStats

Site statistics dashboard. Track visitors, page views, referrers, and other analytics data.

## Structure

- Filament admin
- Eloquent models
- HTTP controllers
- Route definitions
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="site_stats" />
```

### Run migrations

```sh
php artisan module:migrate SiteStats
```

### Publish assets

```sh
php artisan module:publish SiteStats
```

### Views

```php
view('modules.site_stats::index')
```

