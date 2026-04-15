# Payment

Payment gateway integration. Process payments via Stripe, PayPal, and other configured providers.

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
<module type="payment" />
```

### Run migrations

```sh
php artisan module:migrate Payment
```

### Publish assets

```sh
php artisan module:publish Payment
```

### Configuration

```php
config('modules.payment.name')
```

### Views

```php
view('modules.payment::index')
```

