# Captcha

CAPTCHA verification for forms to prevent spam submissions. Supports reCAPTCHA and other providers.

## Structure

- Filament admin
- Livewire components
- Service classes
- Route definitions
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="captcha" />
```

### Publish assets

```sh
php artisan module:publish Captcha
```

### Configuration

```php
config('modules.captcha.name')
```

### Views

```php
view('modules.captcha::index')
```

