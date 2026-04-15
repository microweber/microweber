# Product

Product management for e-commerce. Define products with pricing, images, variants, and inventory.

## Structure

- Filament admin
- Eloquent models
- HTTP controllers
- Service classes
- Route definitions
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="product" />
```

### Run migrations

```sh
php artisan module:migrate Product
```

### Publish assets

```sh
php artisan module:publish Product
```

### Configuration

```php
config('modules.product.name')
```

### Views

```php
view('modules.product::index')
```

