# Form

Form builder module. Create custom forms with various field types, validation rules, and submission handling.

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
<module type="form" />
```

### Run migrations

```sh
php artisan module:migrate Form
```

### Publish assets

```sh
php artisan module:publish Form
```

### Configuration

```php
config('modules.form.name')
```

### Views

```php
view('modules.form::index')
```

