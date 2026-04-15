# GoogleMaps

Google Maps embed module. Display interactive maps with custom markers, zoom, and location settings.

## Structure

- Filament admin
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="google_maps" />
```

### Publish assets

```sh
php artisan module:publish GoogleMaps
```

### Configuration

```php
config('modules.google_maps.name')
```

### Views

```php
view('modules.google_maps::index')
```

