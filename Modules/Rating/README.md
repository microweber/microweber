# Rating

Star rating and review system. Let users rate content and products with configurable scales.

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
<module type="rating" />
```

### Run migrations

```sh
php artisan module:migrate Rating
```

### Publish assets

```sh
php artisan module:publish Rating
```

### Views

```php
view('modules.rating::index')
```

