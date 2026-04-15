# LayoutContent

Layout content wrapper for arranging modules and content blocks within structured page layouts.

## Structure

- Filament admin
- Eloquent models
- Route definitions
- Blade views
- Database migrations

## Usage

### Module tag

```html
<module type="layout_content" />
```

### Run migrations

```sh
php artisan module:migrate LayoutContent
```

### Publish assets

```sh
php artisan module:publish LayoutContent
```

### Views

```php
view('modules.layout_content::index')
```

