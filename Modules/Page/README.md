# Page

Page content type management. Create and edit website pages with the live editor.

## Structure

- Filament admin
- Eloquent models
- HTTP controllers
- Route definitions
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="page" />
```

### Publish assets

```sh
php artisan module:publish Page
```

### Configuration

```php
config('modules.page.name')
```

### Views

```php
view('modules.page::index')
```

