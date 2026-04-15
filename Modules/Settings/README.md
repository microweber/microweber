# Settings

Site settings management. Configure general, template, email, shop, and system settings.

## Structure

- Filament admin
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="settings" />
```

### Publish assets

```sh
php artisan module:publish Settings
```

### Configuration

```php
config('modules.settings.name')
```

### Views

```php
view('modules.settings::index')
```

