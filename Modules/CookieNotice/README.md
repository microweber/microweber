# CookieNotice

Cookie consent banner for GDPR compliance. Displays a customizable notice about cookie usage.

## Structure

- Filament admin
- HTTP controllers
- Route definitions
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="cookie_notice" />
```

### Publish assets

```sh
php artisan module:publish CookieNotice
```

### Configuration

```php
config('modules.cookie_notice.name')
```

### Views

```php
view('modules.cookie_notice::index')
```

