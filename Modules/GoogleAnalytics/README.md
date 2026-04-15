# GoogleAnalytics

Google Analytics integration. Add tracking code and configure analytics settings for visitor tracking.

## Structure

- Filament admin
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="google_analytics" />
```

### Publish assets

```sh
php artisan module:publish GoogleAnalytics
```

### Configuration

```php
config('modules.google_analytics.name')
```

### Views

```php
view('modules.google_analytics::index')
```

