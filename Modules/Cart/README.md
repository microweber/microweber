# Cart

Shopping cart for e-commerce. Manage cart items, quantities, pricing, and cart-to-checkout flow.

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
<module type="cart" />
```

### Run migrations

```sh
php artisan module:migrate Cart
```

### Publish assets

```sh
php artisan module:publish Cart
```

### Views

```php
view('modules.cart::index')
```

