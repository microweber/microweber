# WhiteLabel

White label branding settings. Customize the admin panel branding, logo, and appearance for resellers.

## Structure

- Filament admin
- Service classes
- Blade views

## Usage

### Module tag

```html
<module type="white_label" />
```

### Publish assets

```sh
php artisan module:publish WhiteLabel
```

### Configuration

```php
config('modules.white_label.name')
```

### Views

```php
view('modules.white_label::index')
```

