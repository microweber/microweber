# Category

Content category management. Organize pages, posts, and products into hierarchical categories.

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
<module type="category" />
```

### Run migrations

```sh
php artisan module:migrate Category
```

### Publish assets

```sh
php artisan module:publish Category
```

### Configuration

```php
config('modules.category.name')
```

### Views

```php
view('modules.category::index')
```

