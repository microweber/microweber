# CustomFields

Custom field builder for adding extra form fields to content, products, and other entities.

## Structure

- Filament admin
- Eloquent models
- Service classes
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="custom_fields" />
```

### Run migrations

```sh
php artisan module:migrate CustomFields
```

### Publish assets

```sh
php artisan module:publish CustomFields
```

### Views

```php
view('modules.custom_fields::index')
```

