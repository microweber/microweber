# Order

Order management for e-commerce. Process, track, and manage customer orders and their statuses.

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
<module type="order" />
```

### Run migrations

```sh
php artisan module:migrate Order
```

### Publish assets

```sh
php artisan module:publish Order
```

### Configuration

```php
config('modules.order.name')
```

### Views

```php
view('modules.order::index')
```

