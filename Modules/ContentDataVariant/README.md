# ContentDataVariant

Product variant data management. Store variant-specific data like SKU, price, and stock for product options.

## Structure

- Eloquent models
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="contentdatavariant" />
```

### Run migrations

```sh
php artisan module:migrate ContentDataVariant
```

### Publish assets

```sh
php artisan module:publish ContentDataVariant
```

### Configuration

```php
config('modules.contentdatavariant.name')
```

### Views

```php
view('modules.contentdatavariant::index')
```

